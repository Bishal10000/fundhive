<?php

namespace App\Http\Controllers;
use App\Http\Controllers\FundHiveController;

use Illuminate\Http\Request;
    use App\Models\Category;

class FundHiveController extends Controller
{

public function create()
{
        $categories = Category::all(); // Make sure this line exists

    $categories = Category::where('is_active', true)->get();
    return view('campaigns.create', compact('categories'));
}

}
