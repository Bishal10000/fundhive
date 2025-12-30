<!-- @extends('layouts.app')

@section('title', 'Explore Fundraising Campaigns - FundHive')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Hero Section -->
<div class="bg-[#fff5f5] py-14">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl md:text-4xl font-bold mb-4">Fundraising Campaigns in India</h1>
<p class="text-lg text-gray-600 mb-8 max-w-2xl">
Support genuine causes across India. Every fundraiser is reviewed to ensure trust and transparency.
</p>            
            <!-- Search -->
            <div class="max-w-3xl">
                <form action="{{ route('campaigns.index') }}" method="GET" class="relative">
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Search for campaigns, causes, or people..." 
                               class="w-full pl-12 pr-32 py-4 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <button type="submit" 
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-red-600 text-white px-6 py-2 rounded-md hover:bg-red-700">
                            Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Filters -->
            <div class="lg:w-1/4">
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
<h3 class="font-semibold text-lg mb-6">Refine results</h3>
                    
                    <!-- Category -->
                    <div class="mb-8">
                        <h4 class="font-medium text-gray-700 mb-4">Category</h4>
                        <div class="space-y-3">
                            <a href="{{ route('campaigns.index') }}" 
                               class="block py-2 px-4 rounded {{ !request('category') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                                All Categories
                            </a>
                            @foreach($categories as $category)
                            <a href="{{ route('campaigns.index', ['category' => $category->slug]) }}" 
                               class="block py-2 px-4 rounded {{ request('category') == $category->slug ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                                <i class="{{ $category->icon }} mr-3"></i>
                                {{ $category->name }}
                                <span class="float-right text-gray-500">{{ $category->active_campaigns_count ?? '12K+' }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Verification -->
                    <div class="mb-8">
                        <h4 class="font-medium text-gray-700 mb-4">Verification</h4>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded text-blue-600 mr-3">
                                <span>Verified campaigns only
</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded text-blue-600 mr-3">
                                <span>Emergency Campaigns</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded text-blue-600 mr-3">
                                <span>Featured Campaigns</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Progress -->
                    <div class="mb-8">
                        <h4 class="font-medium text-gray-700 mb-4">Progress</h4>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="radio" name="progress" class="text-blue-600 mr-3">
                                <span>Almost Funded (>80%)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="progress" class="text-blue-600 mr-3">
                                <span>Recently Started (<30%)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="progress" class="text-blue-600 mr-3">
                                <span>Urgent Needs (<7 days)</span>
                            </label>
                        </div>
                    </div>
                    
                    <button class="w-full btn-milaap py-3">
                        <i class="fas fa-filter mr-2"></i> Apply Filters
                    </button>
                </div>
                
                <!-- AI Protection -->
<div class="bg-white border border-gray-200 rounded-xl p-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-brain text-2xl mr-4"></i>
                        <div>
                            <div class="font-bold">AI Fraud Detection</div>
                            <div class="text-sm opacity-90">Active on all campaigns</div>
                        </div>
                    </div>
                    <p class="text-sm mb-4">
Each fundraiser is reviewed to ensure it meets our trust and safety standards.
                    </p>
                    <a href="#" class="text-sm underline">Learn more →</a>
                </div>
            </div>

            <!-- Campaigns -->
            <div class="lg:w-3/4">
                <!-- Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">
                            @if(request('category'))
                                {{ $categories->where('slug', request('category'))->first()->name ?? 'Campaigns' }}
                            @else
                                All Campaigns
                            @endif
                            <span class="text-gray-600">({{ $campaigns->total() }} results)</span>
                        </h2>
                        <p class="text-gray-600 text-sm mt-1">
<i class="fas fa-check-circle text-green-500 mr-1"></i>
All campaigns are reviewed for authenticity                        </p>
                    </div>
                    
                    <div class="mt-4 md:mt-0">
                        <select class="border rounded-lg px-4 py-2 bg-white">
                            <option>Sort by: Most Relevant</option>
                            <option>Most Recent</option>
                            <option>Almost Funded</option>
                            <option>Most Donated</option>
                        </select>
                    </div>
                </div>

                @if($campaigns->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($campaigns as $campaign)
                        <div class="campaign-card">
                            <!-- Image -->
                            <div class="relative">
                                <a href="{{ route('campaigns.show', $campaign->slug) }}">
                                    <img src="{{ asset('storage/' . $campaign->featured_image) }}" 
                                         alt="{{ $campaign->title }}" 
                                         class="w-full h-48 object-cover">
                                </a>
                                
                                @if($campaign->is_verified)
                                <div class="absolute top-4 left-4">
                                   <span class="bg-white text-green-600 px-3 py-1 rounded-full text-xs font-semibold shadow">
    <i class="fas fa-check-circle mr-1"></i> Verified
</span>

                                </div>
                                @endif
                                
                            
                                
                                <div class="absolute bottom-4 left-4">
                                    <span class="bg-white/90 text-gray-800 px-3 py-1 rounded-full text-sm font-medium">
                                        <i class="{{ $campaign->category->icon }} mr-1"></i> {{ $campaign->category->name }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Content -->
                            <div class="p-6">
                                <h3 class="font-bold text-lg mb-3">
                                    <a href="{{ route('campaigns.show', $campaign->slug) }}" class="text-gray-900 hover:text-blue-600">
                                        {{ $campaign->title }}
                                    </a>
                                </h3>
                                
                                <p class="text-gray-600 text-sm mb-6 line-clamp-2">{{ $campaign->description }}</p>
                                
                                <!-- Progress -->
                                <div class="mb-8">
                                    <div class="flex justify-between text-sm mb-2">
                                        <span class="font-bold">₹{{ number_format($campaign->current_amount, 0) }} raised</span>
                                        <span class="font-bold text-green-600">{{ number_format($campaign->progress, 0) }}%</span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: {{ min($campaign->progress, 100) }}%"></div>
                                    </div>
                                    <div class="flex justify-between text-sm mt-2">
                                        <span class="text-gray-600">Goal: ₹{{ number_format($campaign->goal_amount, 0) }}</span>
                                        <span class="text-gray-600">{{ $campaign->days_left }} days left</span>
                                    </div>
                                </div>
                                
                                <!-- Action -->
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-users mr-2"></i>
                                        <span>{{ $campaign->donors_count }} donors</span>
                                    </div>
                                    <a href="{{ route('campaigns.show', $campaign->slug) }}" class="btn-milaap px-6 py-3 font-semibold">
                                        Donate Now
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-12">
                        {{ $campaigns->links('vendor.pagination.tailwind') }}
                    </div>
                @else
                    <div class="bg-white rounded-xl p-12 text-center">
                        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-search text-red-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">No campaigns found</h3>
                        <p class="text-gray-600 mb-8 max-w-md mx-auto">
                            Try adjusting your filters or start a new campaign to help someone in need.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ route('campaigns.index') }}" class="btn-milaap-outline px-6 py-3">
                                Clear filters
                            </a>
                            <a href="{{ route('campaigns.create') }}" class="btn-milaap px-6 py-3 font-semibold">
                                Start a campaign
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection -->