@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Welcome -->
     <!-- User Profile Rating -->
<div class="mb-8 bg-white rounded-xl shadow-sm p-6 flex items-center justify-between">
    <div>
        <h2 class="text-lg font-semibold text-gray-800">
            User Trust Rating
        </h2>
        <p class="text-sm text-gray-600 mt-1">
            Based on your profile background and past activity
        </p>

        <div class="mt-3 flex items-center gap-4">
            <span class="text-3xl font-bold text-blue-600">
                {{ $rating['score'] }}/100
            </span>

            <span class="px-3 py-1 rounded-full text-sm font-medium
                {{ $rating['label'] === 'Trusted User' ? 'bg-green-100 text-green-800' :
                   ($rating['label'] === 'Normal User' ? 'bg-yellow-100 text-yellow-800' :
                   'bg-red-100 text-red-800') }}">
                {{ $rating['label'] }}
            </span>
        </div>
    </div>

    <div class="text-right text-sm text-gray-500">
        <p>Account Age: {{ Auth::user()->created_at->diffForHumans() }}</p>
        <p>Email Verified:
            {{ Auth::user()->email_verified_at ? 'Yes ✅' : 'No ❌' }}
        </p>
    </div>
</div>

    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
            Hello {{ Auth::user()->name }},
        </h1>
        <p class="text-gray-600 mt-2">
            Here's a quick overview of your fundraisers and contributions
        </p>
    </div>
               
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Campaigns</p>
                    <h3 class="text-2xl font-bold mt-1">{{ $stats['campaigns'] }}</h3>
                </div>
                <div class="bg-gray-100 p-3 rounded-full">
                    <i class="fas fa-list text-gray-600 text-xl"></i>
                </div>
            </div>
            <a href="{{ route('dashboard.campaigns') }}" class="inline-block text-blue-600 hover:text-blue-800 text-sm font-medium mt-4">
                View All <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Raised</p>
                    <h3 class="text-2xl font-bold mt-1">Rs.{{ number_format($stats['raised'], 0) }}</h3>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                </div>
            </div>
            <a href="{{ route('dashboard.campaigns') }}" class="inline-block text-green-600 hover:text-green-800 text-sm font-medium mt-4">
                View Campaigns <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Your Donations</p>
                    <h3 class="text-2xl font-bold mt-1">Rs.{{ number_format($stats['donated'], 0) }}</h3>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-donate text-purple-600 text-xl"></i>
                </div>
            </div>
            <a href="{{ route('dashboard.donations') }}" class="inline-block text-purple-600 hover:text-purple-800 text-sm font-medium mt-4">
                View All <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Active Campaigns</p>
                    <h3 class="text-2xl font-bold mt-1">{{ $campaigns->where('status', 'active')->count() }}</h3>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-chart-line text-yellow-600 text-xl"></i>
                </div>
            </div>
            <a href="{{ route('campaigns.create') }}" class="inline-block text-yellow-600 hover:text-yellow-800 text-sm font-medium mt-4">
                Start New <i class="fas fa-plus ml-1"></i>
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Campaigns -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Your Recent Campaigns</h3>
            </div>
            <div class="divide-y">
                @forelse($campaigns as $campaign)
                <div class="px-6 py-4 hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <a href="{{ route('campaigns.show', $campaign->slug) }}" 
                               class="font-medium text-gray-900 hover:text-blue-600">
                                {{ $campaign->title }}
                            </a>
                            <div class="flex items-center mt-1 text-sm text-gray-500">
                                <span class="px-2 py-1 rounded-full text-xs {{ 
                                    $campaign->status == 'active' ? 'bg-green-100 text-green-800' : 
                                    ($campaign->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                    'bg-gray-100 text-gray-800')
                                }}">
                                    {{ $campaign->status === 'active' ? 'Live' : ucfirst($campaign->status) }}
                                </span>
                                <span class="mx-2">•</span>
                                <span>Rs.{{ number_format($campaign->current_amount) }} raised</span>
                                <span class="mx-2">•</span>
                                <span>{{ $campaign->days_left }} days left</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-2xl font-bold text-blue-600">
                                {{ number_format($campaign->progress, 0) }}%
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="fas fa-inbox text-3xl mb-3"></i>
                    <p>No campaigns yet</p>
                    <a href="{{ route('campaigns.create') }}" class="inline-block mt-3 text-blue-600 hover:text-blue-800">
                        Start your first campaign
                    </a>
                </div>
                @endforelse
            </div>
            <div class="px-6 py-4 bg-gray-50">
                <a href="{{ route('dashboard.campaigns') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    View All Campaigns <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Recent Donations -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Your Recent Donations</h3>
            </div>
            <div class="divide-y">
                @forelse($donations as $donation)
                <div class="px-6 py-4 hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <a href="{{ route('campaigns.show', $donation->campaign->slug) }}" 
                               class="font-medium text-gray-900 hover:text-blue-600">
                                {{ $donation->campaign->title }}
                            </a>
                            <div class="text-sm text-gray-500 mt-1">
                                <span class="font-medium">Rs.{{ number_format($donation->amount, 2) }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ $donation->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                Thank you ❤️
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="fas fa-hand-holding-heart text-3xl mb-3"></i>
                    <p>No donations yet</p>
                    <a href="{{ route('campaigns.index') }}" class="inline-block mt-3 text-blue-600 hover:text-blue-800">
                        Explore campaigns to donate
                    </a>
                </div>
                @endforelse
            </div>
            <div class="px-6 py-4 bg-gray-50">
                <a href="{{ route('dashboard.donations') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    View All Donations <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('campaigns.create') }}" 
               class="bg-white rounded-xl shadow-sm p-6 text-center hover:shadow-md transition group border border-gray-200 hover:border-blue-300">
                <div class="text-3xl text-blue-600 mb-3 group-hover:scale-110 transition">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <h4 class="font-bold text-gray-800">Start New Campaign</h4>
                <p class="text-sm text-gray-600 mt-1">Share your story and start fundraising</p>
            </a>
            
            <a href="{{ route('campaigns.index') }}" 
               class="bg-white rounded-xl shadow-sm p-6 text-center hover:shadow-md transition group border border-gray-200 hover:border-green-300">
                <div class="text-3xl text-green-600 mb-3 group-hover:scale-110 transition">
                    <i class="fas fa-search"></i>
                </div>
                <h4 class="font-bold text-gray-800">Explore Campaigns</h4>
                <p class="text-sm text-gray-600 mt-1">Support other causes and make donations</p>
            </a>
            
            <a href="{{ route('dashboard.campaigns') }}" 
               class="bg-white rounded-xl shadow-sm p-6 text-center hover:shadow-md transition group border border-gray-200 hover:border-indigo-300">
                <div class="text-3xl text-indigo-600 mb-3 group-hover:scale-110 transition">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h4 class="font-bold text-gray-800">Campaign Analytics</h4>
                <p class="text-sm text-gray-600 mt-1">View detailed campaign reports</p>
            </a>
        </div>
    </div>
</div>
@endsection