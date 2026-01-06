<?php

namespace App\Http\Controllers;

use App\Models\SuccessStory;
use Illuminate\Http\Request;

class SuccessStoryController extends Controller
{
    public function index()
    {
        // fetch all stories with the related campaign
        $stories = SuccessStory::with('campaign')->latest()->get();
        
        // return to the view
        return view('success.index', compact('stories'));
    }

    // optionally, you can add show, create, store, etc.
}
