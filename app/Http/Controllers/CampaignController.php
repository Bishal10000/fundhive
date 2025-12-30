<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\Donation;
use App\Models\Comment;
use App\Models\Update;
use App\Services\ML\FraudDetectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    protected $fraudService;

    public function __construct(FraudDetectionService $fraudService)
    {
        $this->middleware(['auth'])->except(['index', 'show', 'byCategory']);
        $this->fraudService = $fraudService;
    }

    public function index(Request $request)
    {
        $query = Campaign::with(['user', 'category'])
            ->where('status', 'active')
            ->latest();
        
        if ($request->has('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $campaigns = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();
        
        return view('campaigns.index', compact('campaigns', 'categories'));
    }

    public function show(Campaign $campaign)
    {
        $campaign->increment('views');
        $campaign->load(['user', 'category', 'updates', 'comments.user']);
        $donations = $campaign->donations()->where('status', 'completed')->latest()->take(10)->get();
        return view('campaigns.show', compact('campaign', 'donations'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('campaigns.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|max:500',
            'story' => 'required|string|min:100',
            'goal_amount' => 'required|numeric|min:100|max:1000000',
            'deadline' => 'required|date|after:today',
            'featured_image' => 'required|image|max:5120',
            'gallery_images.*' => 'image|max:5120',
            'video_url' => 'nullable|url',
        ]);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('campaigns', 'public');
        }

        if ($request->hasFile('gallery_images')) {
            $galleryPaths = [];
            foreach ($request->file('gallery_images') as $image) {
                $galleryPaths[] = $image->store('campaigns/gallery', 'public');
            }
            $validated['gallery_images'] = $galleryPaths;
        }

        $validated['user_id'] = auth()->id();
        $validated['slug'] = Str::slug($request->title) . '-' . Str::random(6);
        $validated['status'] = 'pending';

        // rename goal_amount to goal for consistency
        $validated['goal'] = $validated['goal_amount'];
        unset($validated['goal_amount']);

        $campaign = Campaign::create($validated);

        $fraudResult = $this->fraudService->checkCampaign($campaign);

        if ($fraudResult['fraud_probability'] < 0.3) {
            $campaign->update(['status' => 'active']);
            $message = 'Campaign created successfully!';
        } elseif ($fraudResult['fraud_probability'] < 0.7) {
            $message = 'Campaign created and pending review.';
        } else {
            $message = 'Campaign flagged for review due to fraud detection.';
        }

        return redirect()->route('campaigns.show', $campaign)
            ->with('success', $message)
            ->with('fraud_score', $fraudResult['fraud_probability']);
    }

    public function edit(Campaign $campaign)
    {
        $this->authorize('update', $campaign);
        $categories = Category::where('is_active', true)->get();
        return view('campaigns.edit', compact('campaign', 'categories'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $this->authorize('update', $campaign);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|max:500',
            'story' => 'required|string|min:100',
            'goal_amount' => 'required|numeric|min:100',
            'deadline' => 'required|date|after:today',
            'featured_image' => 'nullable|image|max:5120',
            'gallery_images.*' => 'image|max:5120',
            'video_url' => 'nullable|url',
        ]);

        if ($request->hasFile('featured_image')) {
            if ($campaign->featured_image) {
                Storage::disk('public')->delete($campaign->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('campaigns', 'public');
        }

        if ($request->hasFile('gallery_images')) {
            $galleryPaths = $campaign->gallery_images ?? [];
            foreach ($request->file('gallery_images') as $image) {
                $galleryPaths[] = $image->store('campaigns/gallery', 'public');
            }
            $validated['gallery_images'] = $galleryPaths;
        }

        // rename goal_amount to goal
        $validated['goal'] = $validated['goal_amount'] ?? $campaign->goal;
        unset($validated['goal_amount']);

        $campaign->update($validated);
        $this->fraudService->checkCampaign($campaign);

        return redirect()->route('campaigns.show', $campaign)
            ->with('success', 'Campaign updated successfully!');
    }

    public function destroy(Campaign $campaign)
    {
        $this->authorize('delete', $campaign);
        $campaign->delete();
        return redirect()->route('dashboard')
            ->with('success', 'Campaign deleted successfully!');
    }

    public function byCategory(Category $category)
    {
        $campaigns = $category->campaigns()
            ->where('status', 'active')
            ->latest()
            ->paginate(12);

        return view('campaigns.category', compact('campaigns', 'category'));
    }

    public function donate(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'donor_name' => 'required|string|max:255',
            'donor_email' => 'required|email',
            'message' => 'nullable|string|max:500',
            'is_anonymous' => 'boolean',
            'payment_method' => 'required|in:stripe,paypal,bank_transfer',
        ]);

        $donation = Donation::create([
            'campaign_id' => $campaign->id,
            'user_id' => auth()->id(),
            'transaction_id' => 'DON-' . Str::random(16),
            'amount' => $validated['amount'],
            'currency' => 'USD',
            'payment_method' => $validated['payment_method'],
            'status' => 'pending',
            'donor_name' => $validated['donor_name'],
            'donor_email' => $validated['donor_email'],
            'message' => $validated['message'],
            'is_anonymous' => $validated['is_anonymous'] ?? false,
        ]);

        $donation->update(['status' => 'completed']);
        $campaign->increment('amount_raised', $validated['amount']);
        $this->fraudService->checkCampaign($campaign);

        return redirect()->route('campaigns.show', $campaign)
            ->with('success', 'Donation successful! Thank you for your contribution.');
    }

    public function postComment(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $campaign->comments()->create([
            'user_id' => auth()->id(),
            'parent_id' => $validated['parent_id'] ?? null,
            'content' => $validated['content'],
        ]);

        return back()->with('success', 'Comment posted successfully!');
    }

    public function postUpdate(Request $request, Campaign $campaign)
    {
        $this->authorize('update', $campaign);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $campaign->updates()->create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        return back()->with('success', 'Update posted successfully!');
    }
}
