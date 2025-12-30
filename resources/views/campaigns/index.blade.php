@extends('layouts.app')

@section('title', 'Explore Fundraising Campaigns - FundHive')

@section('content')
<div class="bg-slate-50 min-h-screen">

    <!-- Hero Section -->
    <div class="bg-rose-50 py-14">
        <div class="max-w-7xl mx-auto px-4">
            <h1 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">
                Fundraising campaigns in Nepal
            </h1>
            <p class="text-lg text-slate-600 mb-8 max-w-2xl">
                Discover genuine fundraising campaigns across Nepal.  
                Every campaign is reviewed to maintain trust and transparency.
            </p>

            <!-- Search -->
            <div class="max-w-3xl">
                <form action="{{ route('campaigns.index') }}" method="GET" class="relative">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search campaigns, causes, or names…"
                           class="w-full pl-12 pr-36 py-4 rounded-lg border border-slate-200 focus:ring-2 focus:ring-rose-500 focus:outline-none">

                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                    <button type="submit"
                            class="absolute right-2 top-1/2 -translate-y-1/2 bg-rose-600 text-white px-6 py-2 rounded-md hover:bg-rose-700 transition">
                        Search
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-10">
        <div class="flex flex-col lg:flex-row gap-8">

            <!-- Filters -->
            <aside class="lg:w-1/4">
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <h3 class="font-semibold text-lg mb-6">Refine results</h3>

                    <!-- Category -->
                    <div class="mb-8">
                        <h4 class="font-medium text-slate-700 mb-4">Category</h4>
                        <div class="space-y-2">
                            <a href="{{ route('campaigns.index') }}"
                               class="block px-4 py-2 rounded {{ !request('category') ? 'bg-rose-50 text-rose-600 font-medium' : 'text-slate-600 hover:bg-slate-50' }}">
                                All categories
                            </a>

                            @foreach($categories as $category)
                                <a href="{{ route('campaigns.index', ['category' => $category->slug]) }}"
                                   class="block px-4 py-2 rounded {{ request('category') == $category->slug ? 'bg-rose-50 text-rose-600 font-medium' : 'text-slate-600 hover:bg-slate-50' }}">
                                    <i class="{{ $category->icon }} mr-2"></i>
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Verification -->
                    <div class="mb-8">
                        <h4 class="font-medium text-slate-700 mb-4">Campaign type</h4>
                        <div class="space-y-3 text-sm text-slate-600">
                            <label class="flex items-center gap-3">
                                <input type="checkbox" class="rounded text-rose-600">
                                Verified campaigns only
                            </label>
                            <label class="flex items-center gap-3">
                                <input type="checkbox" class="rounded text-rose-600">
                                Emergency cases
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Trust box -->
                <div class="bg-white border border-slate-200 rounded-xl p-6">
                    <div class="flex items-center gap-4 mb-3">
                        <i class="fas fa-shield-alt text-2xl text-rose-600"></i>
                        <div>
                            <p class="font-semibold">Trust & verification</p>
                            <p class="text-sm text-slate-500">Applied to all campaigns</p>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600">
                        Each campaign is reviewed by our team to ensure authenticity.
                    </p>
                </div>
            </aside>

            <!-- Campaigns -->
            <section class="lg:w-3/4">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                    <h2 class="text-2xl font-bold text-slate-900">
                        {{ $campaigns->total() }} campaigns found
                    </h2>

                    <select class="mt-4 md:mt-0 border rounded-lg px-4 py-2 bg-white text-sm">
                        <option>Most relevant</option>
                        <option>Most recent</option>
                        <option>Almost funded</option>
                    </select>
                </div>

                @if($campaigns->count())
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($campaigns as $campaign)
                            <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition">

                                <a href="{{ route('campaigns.show', $campaign->slug) }}">
                                    <img src="{{ asset('storage/' . $campaign->featured_image) }}"
                                         class="w-full h-48 object-cover">
                                </a>

                                <div class="p-6">
                                    <h3 class="font-semibold text-lg mb-2">
                                        <a href="{{ route('campaigns.show', $campaign->slug) }}"
                                           class="hover:text-rose-600">
                                            {{ $campaign->title }}
                                        </a>
                                    </h3>

                                    <p class="text-sm text-slate-600 line-clamp-2 mb-4">
                                        {{ $campaign->description }}
                                    </p>

                                    <!-- Progress -->
                                    <div class="mb-5">
                                        <div class="flex justify-between text-sm font-medium mb-1">
                                            <span>Rs. {{ number_format($campaign->current_amount) }} raised</span>
                                            <span class="text-green-600">{{ number_format($campaign->progress) }}%</span>
                                        </div>

                                        <div class="w-full bg-slate-200 rounded-full h-2">
                                            <div class="bg-rose-600 h-2 rounded-full"
                                                 style="width: {{ min($campaign->progress, 100) }}%"></div>
                                        </div>

                                        <div class="flex justify-between text-xs text-slate-500 mt-2">
                                            <span>Goal: Rs. {{ number_format($campaign->goal_amount) }}</span>
                                            <span>{{ $campaign->days_left }} days left</span>
                                        </div>
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-slate-500">
                                            <i class="fas fa-users mr-1"></i>
                                            {{ $campaign->donors_count }} supporters
                                        </span>

                                        <a href="{{ route('campaigns.show', $campaign->slug) }}"
                                           class="bg-rose-600 text-white px-5 py-2 rounded-md text-sm font-medium hover:bg-rose-700 transition">
                                            Donate
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-10">
                        {{ $campaigns->links('vendor.pagination.tailwind') }}
                    </div>
                @else
                    <div class="bg-white rounded-xl p-12 text-center">
                        <h3 class="text-xl font-semibold mb-3">No campaigns found</h3>
                        <p class="text-slate-600 mb-6">
                            Try changing your search or explore other categories.
                        </p>
                        <a href="{{ route('campaigns.create') }}"
                           class="bg-rose-600 text-white px-6 py-3 rounded-md font-medium hover:bg-rose-700">
                            Start a fundraiser
                        </a>
                    </div>
                @endif
            </section>
        </div>
    </div>
</div>
@endsection
