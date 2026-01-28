<!-- User Activity History -->
<div class="mb-8 bg-white rounded-xl shadow-sm p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i class="fas fa-history text-orange-500"></i> Your Campaign Activities
    </h2>

    <div class="space-y-4">
        @if($activityHistory->isEmpty())
            <div class="text-center py-8">
                <p class="text-gray-500">No activity yet. Start by creating or donating to campaigns!</p>
            </div>
        @else
            @foreach($activityHistory as $activity)
                <div class="flex items-start gap-4 pb-4 border-b border-gray-200 last:border-b-0">
                    <!-- Activity Icon -->
                    <div class="flex-shrink-0">
                        @switch($activity['type'])
                            @case('campaign_created')
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-bullhorn text-blue-600"></i>
                                </div>
                            @break
                            @case('donation_made')
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-heart text-green-600"></i>
                                </div>
                            @break
                            @case('campaign_successful')
                                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-trophy text-purple-600"></i>
                                </div>
                            @break
                            @case('comment_posted')
                                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-comment text-yellow-600"></i>
                                </div>
                            @break
                            @default
                                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-circle-dot text-gray-600"></i>
                                </div>
                        @endswitch
                    </div>

                    <!-- Activity Details -->
                    <div class="flex-1 min-w-0">
                        <p class="text-gray-900 font-medium">{{ $activity['title'] }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $activity['description'] }}</p>
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-clock mr-1"></i>{{ $activity['date']->diffForHumans() }}
                        </p>
                    </div>

                    <!-- Activity Meta -->
                    @if($activity['meta'])
                        <div class="text-right text-sm">
                            @if(isset($activity['meta']['amount']))
                                <p class="font-semibold text-green-600">Rs.{{ number_format($activity['meta']['amount'], 0) }}</p>
                            @endif
                            @if(isset($activity['meta']['status']))
                                <span class="inline-block px-2 py-1 rounded text-xs font-medium mt-1
                                    {{ $activity['meta']['status'] === 'active' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($activity['meta']['status']) }}
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        @endif
    </div>

    @if(!$activityHistory->isEmpty())
        <div class="mt-6 text-center">
            <a href="{{ route('profile.edit') }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                View Full History <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    @endif
</div>
