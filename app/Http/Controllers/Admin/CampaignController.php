<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;

class CampaignController extends Controller
{
    /**
     * List campaigns with admin controls.
     */
    public function index()
    {
        $campaigns = Campaign::with(['user', 'category'])
            ->latest()
            ->paginate(15);

        return view('admin.campaigns', compact('campaigns'));
    }

    /** Toggle campaign visibility between active and suspended. */
    public function toggleVisibility(Campaign $campaign)
    {
        if ($campaign->status === 'suspended') {
            $campaign->status = 'active';
        } else {
            $campaign->status = 'suspended';
        }
        $campaign->save();

        return back()->with('status', 'Campaign visibility updated.');
    }

    /** Soft-delete a suspicious campaign. */
    public function destroy(Campaign $campaign)
    {
        $campaign->delete();
        return back()->with('status', 'Campaign deleted.');
    }
}
