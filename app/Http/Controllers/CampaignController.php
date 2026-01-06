<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\Donation;
use App\Services\FraudDetectionService;
use App\Services\DuplicateCampaignService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        return view('campaigns.create', [
            'categories' => Category::where('is_active', true)->get(),
        ]);
    }

    public function store(Request $request)
    {
        // Step 1: Validate
        $validated = $request->validate($this->rules());

        // Step 2: Duplicate check
        if ($this->dupService->isDuplicate($validated, auth()->id())) {
            return back()
                ->withErrors(['title' => 'Duplicate campaign detected.'])
                ->withInput();
        }

        // Step 3: Emergency restriction
        $category = Category::find($validated['category_id']);
        if ($category && $category->slug === 'emergency') {
            $recent = Campaign::where('user_id', auth()->id())
                ->where('category_id', $category->id)
                ->where('created_at', '>=', now()->subDays(30))
                ->exists();

            if ($recent) {
                return back()
                    ->withErrors(['category_id' => 'Emergency campaign limit reached.'])
                    ->withInput();
            }
        }

        // Step 4: Featured image
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] =
                $request->file('featured_image')->store('campaigns', 'public');
        }

        // Step 5: Gallery images
        if ($request->hasFile('gallery_images')) {
            $paths = [];
            foreach ($request->file('gallery_images') as $image) {
                $paths[] = $image->store('campaigns/gallery', 'public');
            }
            $validated['gallery_images'] = $paths;
        }

        $validated['user_id'] = auth()->id();
        $validated['status']  = 'pending';
        $validated['slug']    = $this->generateUniqueSlug($validated['title']);

        // Step 6: Create campaign
        $campaign = Campaign::create($validated);

        // Step 7: Fraud detection
        $fraud = $this->fraudService->analyze($campaign);

        // Step 8: Update fraud result
        $isFlagged = $fraud['fraud_probability'] >= 0.7;

        $campaign->update([
            'fraud_score'    => $fraud['fraud_probability'],
            'fraud_features' => json_encode($fraud['features']),
            'is_flagged'     => $isFlagged,
            'status'         => $isFlagged ? 'pending' : 'active',
        ]);

        // Step 8.5: Message
        $message = $isFlagged
            ? 'Campaign created but flagged for review.'
            : 'Campaign created successfully.';

        // Step 9: Redirect
        return redirect()
            ->route('campaigns.show', $campaign->slug)
            ->with('success', $message)
            ->with('fraud_score', $fraud['fraud_probability']);
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

        // Re-run fraud detection
        $fraud = $this->fraudService->analyze($campaign);
        $isFlagged = $fraud['fraud_probability'] >= 0.7;

        $campaign->update([
            'fraud_score' => $fraud['fraud_probability'],
            'is_flagged'  => $isFlagged,
            'status'      => $isFlagged ? 'pending' : 'active',
        ]);

        return redirect()
            ->route('campaigns.show', $campaign->slug)
            ->with('success', 'Campaign updated successfully.');
    }

    /* -------------------------
     | Delete
     --------------------------*/
    public function destroy(Campaign $campaign)
    {
        $this->authorize('delete', $campaign);
        $campaign->delete();

        return redirect()
            ->route('dashboard')
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
            'amount' => 'required|numeric|min:1',
            'donor_name' => 'required|string|max:255',
            'donor_email' => 'required|email',
            'message' => 'nullable|string|max:500',
            'is_anonymous' => 'boolean',
            'payment_method' => 'required|in:stripe,paypal,bank_transfer',
        ]);

        Donation::create([
            'campaign_id'   => $campaign->id,
            'user_id'       => auth()->id(),
            'transaction_id'=> 'DON-' . Str::random(16),
            'amount'        => $validated['amount'],
            'currency'      => 'USD',
            'payment_method'=> $validated['payment_method'],
            'status'        => 'completed',
            'donor_name'    => $validated['donor_name'],
            'donor_email'   => $validated['donor_email'],
            'message'       => $validated['message'] ?? null,
            'is_anonymous'  => $validated['is_anonymous'] ?? false,
        ]);

        $campaign->increment('amount_raised', $validated['amount']);

        return redirect()
            ->route('campaigns.show', $campaign->slug)
            ->with('success', 'Donation successful!');
    }
}
