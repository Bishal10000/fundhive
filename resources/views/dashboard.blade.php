@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Welcome Header -->
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900">
            👋 Welcome, {{ Auth::user()->name }}!
        </h1>
        <p class="text-gray-600 mt-2 text-lg">
            Your dashboard with all the features you need to manage campaigns and track your impact
        </p>
    </div>

    <!-- User Trust Rating Section -->
    <div class="mb-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold">
                    <i class="fas fa-award mr-2"></i>Your Trust Rating
                </h2>
                <p class="text-sm text-blue-100 mt-1">
                    Based on your profile background and past activity
                </p>

                <div class="mt-4 flex items-center gap-6">
                    <div>
                        <span class="text-4xl font-bold">{{ number_format($rating['score'], 0) }}/100</span>
                    </div>
                    <div>
                        <span class="px-4 py-2 rounded-full text-sm font-bold
                            {{ $rating['label'] === 'Trusted User' ? 'bg-green-400 text-green-900' :
                               ($rating['label'] === 'Normal User' ? 'bg-yellow-400 text-yellow-900' :
                               'bg-red-400 text-red-900') }}">
                            {{ $rating['label'] }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="text-right text-sm text-blue-100 hidden md:block">
                <p class="font-medium text-white">Profile Stats:</p>
                <p>Account Age: {{ Auth::user()->created_at->diffForHumans() }}</p>
                <p>Email Verified: {{ Auth::user()->email_verified_at ? '✅ Yes' : '❌ No' }}</p>
                <p>Profile Status: {{ Auth::user()->is_verified ? '✅ Verified' : '⏳ Pending' }}</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Campaigns</p>
                    <h3 class="text-3xl font-bold mt-2 text-gray-900">{{ $stats['campaigns'] }}</h3>
                </div>
                <div class="bg-blue-100 p-4 rounded-full">
                    <i class="fas fa-list text-blue-600 text-2xl"></i>
                </div>
            </div>
            <a href="{{ route('dashboard.campaigns') }}" class="inline-block text-blue-600 hover:text-blue-800 text-sm font-medium mt-4">
                View All <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Raised</p>
                    <h3 class="text-3xl font-bold mt-2 text-green-600">Rs.{{ number_format($stats['raised'], 0) }}</h3>
                </div>
                <div class="bg-green-100 p-4 rounded-full">
                    <i class="fas fa-money-bill-wave text-green-600 text-2xl"></i>
                </div>
            </div>
            <a href="{{ route('dashboard.campaigns') }}" class="inline-block text-green-600 hover:text-green-800 text-sm font-medium mt-4">
                View Campaigns <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Your Donations</p>
                    <h3 class="text-3xl font-bold mt-2 text-purple-600">Rs.{{ number_format($stats['donated'], 0) }}</h3>
                </div>
                <div class="bg-purple-100 p-4 rounded-full">
                    <i class="fas fa-heart text-purple-600 text-2xl"></i>
                </div>
            </div>
            <a href="{{ route('dashboard.donations') }}" class="inline-block text-purple-600 hover:text-purple-800 text-sm font-medium mt-4">
                View All <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Active Campaigns</p>
                    <h3 class="text-3xl font-bold mt-2 text-yellow-600">{{ $campaigns->where('status', 'active')->count() }}</h3>
                </div>
                <div class="bg-yellow-100 p-4 rounded-full">
                    <i class="fas fa-fire text-yellow-600 text-2xl"></i>
                </div>
            </div>
            <a href="{{ route('campaigns.create') }}" class="inline-block text-yellow-600 hover:text-yellow-800 text-sm font-medium mt-4">
                Create New <i class="fas fa-plus ml-1"></i>
            </a>
        </div>
    </div>

    <!-- User Profile Background Card -->
    @include('components.user-background-card')

    <!-- Campaign Creation History Timeline -->
    @include('components.campaign-creation-history', ['campaignCreationHistory' => $campaignCreationHistory])

    <!-- User Activity History -->
    @include('components.user-activity-history', ['activityHistory' => $activityHistory])

    <!-- Fraud Detection & Monitoring Dashboard -->
    @include('components.fraud-detection-dashboard', [
        'flaggedCampaignsCount' => $flaggedCampaignsCount,
        'mediumRiskCount' => $mediumRiskCount,
        'safeCampaignsCount' => $safeCampaignsCount,
        'lastFraudCheck' => $lastFraudCheck,
        'campaignFraudAnalysis' => $campaignFraudAnalysis
    ])

    <!-- Success Stories Showcase -->
    @include('components.success-stories', ['successCampaigns' => $successCampaigns])

    <!-- Recent Campaigns and Donations Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Recent Campaigns -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-bullhorn text-blue-600"></i>Your Recent Campaigns
                </h3>
            </div>
            <div class="divide-y">
                @forelse($campaigns as $campaign)
                <div class="px-6 py-4 hover:bg-gray-50 transition">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('campaigns.show', $campaign->slug) }}" 
                               class="font-bold text-gray-900 hover:text-blue-600 block truncate">
                                {{ $campaign->title }}
                            </a>
                            <div class="flex items-center gap-2 mt-2 text-sm text-gray-500">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ 
                                    $campaign->status == 'active' ? 'bg-green-100 text-green-800' : 
                                    ($campaign->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                    'bg-gray-100 text-gray-800')
                                }}">
                                    {{ $campaign->status === 'active' ? '🔴 Live' : '⏳ ' . ucfirst($campaign->status) }}
                                </span>
                                <span>Rs.{{ number_format($campaign->current_amount) }}</span>
                                <span>{{ $campaign->days_left }} days left</span>
                            </div>
                        </div>
                        <div class="flex-shrink-0 text-right">
                            <div class="text-2xl font-bold text-blue-600">
                                {{ number_format($campaign->progress, 0) }}%
                            </div>
                            <div class="w-12 h-1 bg-gray-200 rounded-full mt-1 overflow-hidden">
                                <div class="h-full bg-blue-600" style="width: {{ min($campaign->progress, 100) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                    <p class="font-medium">No campaigns yet</p>
                    <a href="{{ route('campaigns.create') }}" class="inline-block mt-4 text-blue-600 hover:text-blue-800 font-medium">
                        <i class="fas fa-plus mr-1"></i>Start your first campaign
                    </a>
                </div>
                @endforelse
            </div>
            @if($campaigns->count() > 0)
            <div class="px-6 py-4 bg-gray-50 border-t">
                <a href="{{ route('dashboard.campaigns') }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                    View All Campaigns <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            @endif
        </div>

        <!-- Recent Donations -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-heart text-red-600"></i>Your Recent Donations
                </h3>
            </div>
            <div class="divide-y">
                @forelse($donations as $donation)
                <div class="px-6 py-4 hover:bg-gray-50 transition">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('campaigns.show', $donation->campaign->slug) }}" 
                               class="font-bold text-gray-900 hover:text-blue-600 block truncate">
                                {{ $donation->campaign->title }}
                            </a>
                            <div class="text-sm text-gray-500 mt-1">
                                <i class="fas fa-clock text-gray-400 mr-1"></i>
                                {{ $donation->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <div class="flex-shrink-0 text-right">
                            <span class="text-lg font-bold text-green-600">Rs.{{ number_format($donation->amount, 0) }}</span>
                            <div class="text-xs text-gray-500 mt-1">✅ Thank you</div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center text-gray-500">
                    <i class="fas fa-hand-holding-heart text-4xl mb-3 text-gray-300"></i>
                    <p class="font-medium">No donations yet</p>
                    <a href="{{ route('campaigns.index') }}" class="inline-block mt-4 text-blue-600 hover:text-blue-800 font-medium">
                        <i class="fas fa-search mr-1"></i>Explore campaigns to donate
                    </a>
                </div>
                @endforelse
            </div>
            @if($donations->count() > 0)
            <div class="px-6 py-4 bg-gray-50 border-t">
                <a href="{{ route('dashboard.donations') }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                    View All Donations <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mb-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('campaigns.create') }}" 
               class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-md p-6 text-center hover:shadow-lg hover:scale-105 transition text-white group">
                <div class="text-4xl mb-3 group-hover:scale-110 transition inline-block">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <h4 class="font-bold text-lg">Start New Campaign</h4>
                <p class="text-sm text-blue-100 mt-1">Share your story and start fundraising</p>
            </a>
            
            <a href="{{ route('campaigns.index') }}" 
               class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-md p-6 text-center hover:shadow-lg hover:scale-105 transition text-white group">
                <div class="text-4xl mb-3 group-hover:scale-110 transition inline-block">
                    <i class="fas fa-search"></i>
                </div>
                <h4 class="font-bold text-lg">Explore Campaigns</h4>
                <p class="text-sm text-green-100 mt-1">Support other causes and make donations</p>
            </a>
            
            <a href="{{ route('profile.edit') }}" 
               class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-md p-6 text-center hover:shadow-lg hover:scale-105 transition text-white group">
                <div class="text-4xl mb-3 group-hover:scale-110 transition inline-block">
                    <i class="fas fa-user-edit"></i>
                </div>
                <h4 class="font-bold text-lg">Update Profile</h4>
                <p class="text-sm text-purple-100 mt-1">Add background & verification info</p>
            </a>
        </div>
    </div>
</div>
@endsection
