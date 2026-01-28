<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

// Add these imports:
use App\Services\FraudDetectionService;
use App\Services\DuplicateCampaignService;
use App\Services\UserVerificationService;


class CampaignController extends Controller
{
    protected FraudDetectionService $fraudService;
    protected DuplicateCampaignService $dupService;

    protected int $storyMinLength = 100;
    protected int $titleMaxLength = 255;
    protected int $descriptionMaxLength = 500;
    protected int $goalMin = 100;
    protected int $goalMax = 1000000;
    protected int $imageMaxSize = 5120; // 5MB

    public function __construct(
        FraudDetectionService $fraudService,
        DuplicateCampaignService $dupService
    ) {
        $this->middleware(['auth'])->except(['index', 'show', 'byCategory']);
        $this->fraudService = $fraudService;
        $this->dupService   = $dupService;
    }

    /* -------------------------
     | Helpers
     --------------------------*/
    protected function generateUniqueSlug(string $title): string
    {
        do {
            $slug = Str::slug($title) . '-' . Str::random(8);
        } while (Campaign::where('slug', $slug)->exists());

        return $slug;
    }

    protected function rules(bool $isUpdate = false): array
    {
        return [
            'title' => "required|string|max:{$this->titleMaxLength}",
            'category_id' => 'required|exists:categories,id',
            'description' => "required|string|max:{$this->descriptionMaxLength}",
            'story' => "required|string|min:{$this->storyMinLength}",
            'goal_amount' => "required|numeric|min:{$this->goalMin}|max:{$this->goalMax}",
            'deadline' => 'required|date|after:today',
            'featured_image' => $isUpdate
                ? "nullable|image|max:{$this->imageMaxSize}"
                : "required|image|max:{$this->imageMaxSize}",
            'gallery_images.*' => "image|max:{$this->imageMaxSize}",
            'video_url' => 'nullable|url',
        ];
    }

    /* -------------------------
     | Public Pages
     --------------------------*/
    public function index(Request $request)
    {
        $query = Campaign::with(['user', 'category'])
            ->where('status', 'active')
            ->latest();

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) =>
                $q->where('slug', $request->category)
            );
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        return view('campaigns.index', [
            'campaigns'  => $query->paginate(12),
            'categories' => Category::where('is_active', true)->get(),
        ]);
    }

    public function show(Campaign $campaign)
    {
        $campaign->increment('views');
        $campaign->load(['user', 'category', 'comments.user', 'updates']);

        $donations = $campaign->donations()
            ->where('status', 'completed')
            ->latest()
            ->take(10)
            ->get();

        return view('campaigns.show', compact('campaign', 'donations'));
    }

    public function byCategory(Category $category)
    {
        $campaigns = $category->campaigns()
            ->where('status', 'active')
            ->latest()
            ->paginate(12);

        return view('campaigns.category', compact('campaigns', 'category'));
    }

    /* -------------------------
     | Create Campaign
     --------------------------*/
    public function create()
    {
        $categories = Category::all();
        
        return view('campaigns.create', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'story' => "required|string|min:{$this->storyMinLength}",
            'goal_amount' => "required|numeric|min:{$this->goalMin}|max:{$this->goalMax}",
            'deadline' => 'required|date|after:today',
            'featured_image' => "required|image|max:{$this->imageMaxSize}",
            'gallery_images.*' => "image|max:{$this->imageMaxSize}",
            'video_url' => 'nullable|url',
        ]);

        // Generate unique slug
        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;
        
        while (Campaign::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        $validated['slug'] = $slug;

        // Handle image upload
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('campaigns', 'public');
        }

        // Handle gallery images if present
        if ($request->hasFile('gallery_images')) {
            $paths = [];
            foreach ($request->file('gallery_images') as $image) {
                $paths[] = $image->store('campaigns/gallery', 'public');
            }
            $validated['gallery_images'] = $paths;
        }

        $campaign = Campaign::create([
            'user_id' => auth()->id(),
            'status' => 'active',
            ...$validated,
        ]);

        // Calculate fraud score for new campaign
        $fraudService = app(FraudDetectionService::class);
        $fraudScore = $fraudService->calculateFraudScore($campaign);
        
        // Update campaign with fraud score and flag if necessary
        $campaign->fraud_score = $fraudScore;
        $campaign->is_flagged = $fraudScore >= 0.70; // High risk threshold (0-1 scale)
        if ($campaign->is_flagged) {
            $campaign->flag_reason = $fraudService->getFlagReasons($campaign);
        }
        $campaign->save();

        // Check if user now qualifies for auto-verification
        $verificationService = app(UserVerificationService::class);
        $verificationService->attemptAutoVerification(auth()->user());

        return redirect()->route('campaigns.show', $campaign)->with('status', 'Campaign created successfully!');
    }

    /* -------------------------
     | Update Campaign
     --------------------------*/
    public function edit(Campaign $campaign)
    {
        $this->authorize('update', $campaign);

        return view('campaigns.edit', [
            'campaign'   => $campaign,
            'categories' => Category::where('is_active', true)->get(),
        ]);
    }

    public function update(Request $request, Campaign $campaign)
    {
        $this->authorize('update', $campaign);

        $validated = $request->validate($this->rules(true));
        if ($request->hasFile('featured_image')) {
            Storage::disk('public')->delete($campaign->featured_image);
            $validated['featured_image'] =
                $request->file('featured_image')->store('campaigns', 'public');
        }
        if ($request->hasFile('gallery_images')) {
            $paths = $campaign->gallery_images ?? [];
            foreach ($request->file('gallery_images') as $image) {
                $paths[] = $image->store('campaigns/gallery', 'public');
            }
            $validated['gallery_images'] = $paths;
        }
        $campaign->update($validated);

        return redirect()->route('campaigns.show', $campaign)->with('status', 'Campaign updated successfully!');
    }

    /* -------------------------
     | Delete Campaign
     --------------------------*/
    public function destroy(Campaign $campaign)
    {
        $this->authorize('delete', $campaign);
        $campaign->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Campaign deleted successfully!');
    }

    /* -------------------------
     | Donations
     --------------------------*/
    public function donate(Request $request, Campaign $campaign)
    {
        if ($campaign->status !== 'active') {
            return back()->withErrors([
                'donation' => 'Donations are disabled for this campaign.'
            ]);
        }
        
        $validated = $request->validate([
            'amount' => 'required|numeric|min:10',
            'donor_name' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:500',
            'is_anonymous' => 'nullable|boolean',
            'payment_method' => 'required|in:esewa,khalti,fonepay',
        ]);

        // Generate transaction ID
        $transactionId = strtoupper($validated['payment_method']) . '-' . date('YmdHis') . '-' . Str::random(6);

        // Create donation record
        $donation = Donation::create([
            'campaign_id' => $campaign->id,
            'user_id' => auth()->id(),
            'transaction_id' => $transactionId,
            'amount' => $validated['amount'],
            'currency' => 'NPR',
            'payment_method' => $validated['payment_method'],
            'donor_name' => $validated['donor_name'] ?? auth()->user()?->name ?? 'Anonymous',
            'donor_email' => auth()->user()?->email ?? 'anonymous@fundhive.com',
            'message' => $validated['message'],
            'is_anonymous' => $validated['is_anonymous'] ?? false,
            'status' => 'pending', // Will be updated after payment confirmation
        ]);

        // Redirect to payment gateway simulation
        return redirect()->route('payment.process', [
            'donation' => $donation->id,
            'gateway' => $validated['payment_method']
        ]);
    }
}
