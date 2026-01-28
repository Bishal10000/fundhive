@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Donations</h1>
        <p class="text-gray-600 mt-2">View all your contributions and support history</p>
    </div>

    @if($donations->isEmpty())
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm p-12 text-center">
            <div class="max-w-md mx-auto">
                <i class="fas fa-heart text-gray-300 text-6xl mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">No Donations Yet</h2>
                <p class="text-gray-600 mb-6">
                    Start making a difference by supporting campaigns that matter to you.
                </p>
                <a href="{{ route('campaigns.index') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                    <i class="fas fa-search"></i> Explore Campaigns
                </a>
            </div>
        </div>
    @else
        <!-- Donations Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-md p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Total Donated</p>
                        <h3 class="text-3xl font-bold mt-1">Rs.{{ number_format($donations->sum('amount'), 0) }}</h3>
                    </div>
                    <div class="bg-white/20 p-4 rounded-full">
                        <i class="fas fa-hand-holding-heart text-3xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-md p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Donations</p>
                        <h3 class="text-3xl font-bold mt-1">{{ $donations->total() }}</h3>
                    </div>
                    <div class="bg-white/20 p-4 rounded-full">
                        <i class="fas fa-list text-3xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-md p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Campaigns Supported</p>
                        <h3 class="text-3xl font-bold mt-1">{{ $donations->pluck('campaign_id')->unique()->count() }}</h3>
                    </div>
                    <div class="bg-white/20 p-4 rounded-full">
                        <i class="fas fa-bullhorn text-3xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Donations List -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-bold text-gray-900">Donation History</h2>
            </div>

            <div class="divide-y divide-gray-200">
                @foreach($donations as $donation)
                    <div class="p-6 hover:bg-gray-50 transition">
                        <div class="flex items-start justify-between gap-4">
                            <!-- Campaign Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-2">
                                    @if($donation->campaign->featured_image)
                                        <img src="{{ asset('storage/' . $donation->campaign->featured_image) }}" 
                                             alt="{{ $donation->campaign->title }}" 
                                             class="w-16 h-16 rounded-lg object-cover">
                                    @else
                                        <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-image text-white text-xl"></i>
                                        </div>
                                    @endif

                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('campaigns.show', $donation->campaign->slug) }}" 
                                           class="text-lg font-bold text-gray-900 hover:text-blue-600 block truncate">
                                            {{ $donation->campaign->title }}
                                        </a>
                                        <p class="text-sm text-gray-600">
                                            by {{ $donation->campaign->user->name }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Donation Details -->
                                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mt-3">
                                    <span class="flex items-center gap-1">
                                        <i class="fas fa-calendar text-gray-400"></i>
                                        {{ $donation->created_at->format('M d, Y') }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <i class="fas fa-clock text-gray-400"></i>
                                        {{ $donation->created_at->diffForHumans() }}
                                    </span>
                                    @if($donation->is_anonymous)
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">
                                            <i class="fas fa-user-secret"></i> Anonymous
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Amount -->
                            <div class="flex-shrink-0 text-right">
                                <div class="text-2xl font-bold text-green-600">
                                    Rs.{{ number_format($donation->amount, 0) }}
                                </div>
                                <span class="inline-block mt-2 px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                                    <i class="fas fa-check-circle mr-1"></i>Thank you!
                                </span>
                            </div>
                        </div>

                        <!-- Campaign Progress -->
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <div class="flex items-center justify-between text-sm mb-2">
                                <span class="text-gray-700 font-medium">Campaign Progress</span>
                                <span class="text-gray-600">
                                    Rs.{{ number_format($donation->campaign->current_amount, 0) }} 
                                    of Rs.{{ number_format($donation->campaign->goal_amount, 0) }}
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                <div class="bg-gradient-to-r from-green-500 to-green-600 h-2 rounded-full transition-all" 
                                     style="width: {{ min($donation->campaign->progress, 100) }}%"></div>
                            </div>
                            <div class="flex items-center justify-between mt-2 text-xs text-gray-500">
                                <span>{{ number_format($donation->campaign->progress, 1) }}% funded</span>
                                <span>{{ $donation->campaign->days_left }} days left</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($donations->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $donations->links() }}
                </div>
            @endif
        </div>
    @endif

    <!-- Back to Dashboard -->
    <div class="mt-8 text-center">
        <a href="{{ route('dashboard') }}" 
           class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 font-medium">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>
@endsection
