@extends('layouts.app')

@section('title', 'Start a Fundraiser | FundHive')

@section('content')
<div class="bg-slate-50 min-h-screen">

    <!-- Header -->
    <section class="bg-rose-50 py-12">
        <div class="max-w-4xl mx-auto px-4">
            <h1 class="text-3xl font-bold text-slate-900 mb-3">
                Start a fundraiser
            </h1>
            <p class="text-slate-600 max-w-2xl">
                Tell your story clearly and honestly. Campaigns with clear details
                and real photos raise more support.
            </p>
        </div>
    </section>
@if ($errors->any())
    <div class="bg-red-100 p-4 rounded mb-6">
        <ul class="list-disc pl-5 text-red-700">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <!-- Form -->
    <div class="max-w-4xl mx-auto px-4 py-10">
        <form
            action="{{ route('campaigns.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="bg-white rounded-xl shadow-sm p-8 space-y-8"
        >
            @csrf

            <!-- Title -->
            <div>
                <label class="block font-medium text-slate-700 mb-2">
                    Campaign title
                </label>
                <input
                    type="text"
                    name="title"
                    value="{{ old('title') }}"
                    placeholder="Example: Help Ram get urgent kidney treatment"
                    class="w-full rounded-lg border border-slate-300 px-4 py-3
                           focus:ring-2 focus:ring-rose-500 focus:outline-none"
                    required
                >
                <p class="text-sm text-slate-500 mt-1">
                    Be specific and clear. This is the first thing people see.
                </p>
            </div>

            <!-- Category -->
            <div>
                <label class="block font-medium text-slate-700 mb-2">
                    Category
                </label>
                <select
                    name="category_id"
                    class="w-full rounded-lg border border-slate-300 px-4 py-3 bg-white"
                    required
                >
                    <option value="">Select a category</option>
                    @foreach($categories as $category)
                        <option
                            value="{{ $category->id }}"
                            {{ old('category_id') == $category->id ? 'selected' : '' }}
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Goal Amount -->
            <div>
                <label class="block font-medium text-slate-700 mb-2">
                    Goal amount (NPR)
                </label>
                <input
                    type="number"
                    name="goal_amount"
                    value="{{ old('goal_amount') }}"
                    placeholder="e.g. 500000"
                    class="w-full rounded-lg border border-slate-300 px-4 py-3"
                    required
                >
            </div>

            <!-- Deadline -->
            <div>
                <label class="block font-medium text-slate-700 mb-2">
                    Fundraising deadline
                </label>
                <input
                    type="date"
                    name="deadline"
                    value="{{ old('deadline') }}"
                    class="w-full rounded-lg border border-slate-300 px-4 py-3"
                    required
                >
            </div>

            <!-- Short Description -->
            <div>
                <label class="block font-medium text-slate-700 mb-2">
                    Short description
                </label>
                <textarea
                    name="description"
                    rows="3"
                    placeholder="Briefly explain why you are raising funds"
                    class="w-full rounded-lg border border-slate-300 px-4 py-3"
                    required
                >{{ old('description') }}</textarea>
            </div>

            <!-- Full Story -->
            <div>
                <label class="block font-medium text-slate-700 mb-2">
                    Full story
                </label>
                <textarea
                    name="story"
                    rows="6"
                    placeholder="Share complete details, background, and how the funds will be used"
                    class="w-full rounded-lg border border-slate-300 px-4 py-3"
                    required
                >{{ old('story') }}</textarea>
            </div>

            <!-- Featured Image -->
            <div>
                <label class="block font-medium text-slate-700 mb-2">
                    Cover image
                </label>
                <input
                    type="file"
                    name="featured_image"
                    accept="image/*"
                    class="w-full text-sm"
                    required
                >
                <p class="text-sm text-slate-500 mt-1">
                    Use a real photo related to the campaign.
                </p>
            </div>

            <!-- Optional Video -->
            <div>
                <label class="block font-medium text-slate-700 mb-2">
                    Video link (optional)
                </label>
                <input
                    type="url"
                    name="video_url"
                    value="{{ old('video_url') }}"
                    placeholder="YouTube or Facebook video link"
                    class="w-full rounded-lg border border-slate-300 px-4 py-3"
                >
            </div>

            <!-- Submit -->
            <div class="pt-6 border-t border-slate-200 flex flex-col sm:flex-row gap-4">
                <button
                    type="submit"
                    class="bg-rose-600 text-white px-8 py-3 rounded-lg
                           font-semibold hover:bg-rose-700 transition"
                >
                    Create campaign
                </button>

                <a
                    href="/campaigns"
                    class="px-8 py-3 rounded-lg border border-slate-300
                           font-semibold text-slate-700 hover:bg-slate-50"
                >
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>

@endsection
