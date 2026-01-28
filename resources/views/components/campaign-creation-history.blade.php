<!-- Campaign Creation History Timeline -->
<div class="mb-8 bg-white rounded-xl shadow-sm p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i class="fas fa-timeline text-purple-600"></i> Campaign Creation History
    </h2>

    @if($campaignCreationHistory->isEmpty())
        <div class="text-center py-8">
            <i class="fas fa-calendar text-gray-300 text-4xl mb-2"></i>
            <p class="text-gray-600">No campaigns created yet. Start your first campaign!</p>
            <a href="{{ route('campaigns.create') }}" class="inline-block mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                Create Campaign
            </a>
        </div>
    @else
        <div class="relative">
            <!-- Timeline -->
            <div class="space-y-6">
                @foreach($campaignCreationHistory as $index => $campaign)
                    <div class="relative pl-8 pb-6 {{ $index !== $campaignCreationHistory->count() - 1 ? 'border-l-2 border-gray-300' : '' }}">
                        <!-- Timeline Dot -->
                        <div class="absolute left-0 top-0 w-4 h-4 bg-blue-600 rounded-full border-4 border-white transform -translate-x-1.5 shadow-md"></div>

                        <!-- Campaign Card -->
                        <div class="bg-gray-50 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-semibold text-gray-900">{{ $campaign->title }}</h3>
                                        <span class="inline-block px-2 py-0.5 rounded text-xs font-medium
                                            {{ $campaign->status === 'active' ? 'bg-green-100 text-green-800' :
                                               ($campaign->status === 'completed' ? 'bg-blue-100 text-blue-800' :
                                               ($campaign->status === 'rejected' ? 'bg-red-100 text-red-800' :
                                               'bg-gray-200 text-gray-800')) }}">
                                            {{ ucfirst($campaign->status) }}
                                        </span>
                                    </div>
                                    
                                    <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ Str::limit($campaign->description, 150) }}</p>

                                    <div class="flex flex-wrap items-center gap-4 mt-3 text-sm text-gray-700">
                                        <span>
                                            <i class="fas fa-calendar text-gray-400 mr-1"></i>
                                            Created {{ $campaign->created_at->diffForHumans() }}
                                        </span>
                                        <span>
                                            <i class="fas fa-tag text-gray-400 mr-1"></i>
                                            {{ $campaign->category->name ?? 'Uncategorized' }}
                                        </span>
                                        <span>
                                            <i class="fas fa-clock text-gray-400 mr-1"></i>
                                            {{ $campaign->days_left }} days left
                                        </span>
                                    </div>
                                </div>

                                <!-- Campaign Stats -->
                                <div class="flex-shrink-0 text-right">
                                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                                        <p class="text-sm font-semibold text-green-600">
                                            Rs.{{ number_format($campaign->current_amount, 0) }}
                                        </p>
                                        <p class="text-xs text-gray-600">
                                            of Rs.{{ number_format($campaign->goal_amount, 0) }}
                                        </p>
                                        <div class="mt-2 w-20 h-1 bg-gray-200 rounded-full overflow-hidden">
                                            <div class="h-full bg-green-500" 
                                                 style="width: {{ min($campaign->progress, 100) }}%"></div>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">{{ number_format($campaign->progress, 0) }}%</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-3 flex gap-2 pt-3 border-t border-gray-200">
                                <a href="{{ route('campaigns.show', $campaign->slug) }}" 
                                   class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                    <i class="fas fa-eye mr-1"></i>View
                                </a>
                                @if($campaign->status === 'active' && $campaign->user_id === auth()->id())
                                    <a href="{{ route('campaigns.edit', $campaign->slug) }}" 
                                       class="text-sm text-orange-600 hover:text-orange-800 font-medium">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </a>
                                @endif
                                <a href="{{ route('campaigns.show', $campaign->slug) }}#updates" 
                                   class="text-sm text-gray-600 hover:text-gray-800 font-medium">
                                    <i class="fas fa-list mr-1"></i>Updates
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @if($campaignCreationHistory->count() > 5)
            <div class="mt-6 text-center pt-6 border-t border-gray-200">
                <a href="{{ route('dashboard.campaigns') }}" 
                   class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                    View All Your Campaigns <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        @endif
    @endif
</div>
