<!-- Success Stories Showcase -->
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i class="fas fa-star text-yellow-500"></i> Success Stories
    </h2>

    @if($successCampaigns->isEmpty())
        <div class="bg-white rounded-xl shadow-sm p-8 text-center">
            <i class="fas fa-heart text-gray-400 text-4xl mb-3"></i>
            <p class="text-gray-600 text-lg">No successful campaigns yet. Keep fundraising!</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($successCampaigns as $campaign)
                <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition overflow-hidden group">
                    <!-- Campaign Image -->
                    <div class="relative h-48 bg-gray-200 overflow-hidden">
                        @if($campaign->featured_image)
                            <img src="{{ asset('storage/' . $campaign->featured_image) }}" 
                                 alt="{{ $campaign->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-400 to-blue-600">
                                <i class="fas fa-image text-white text-3xl"></i>
                            </div>
                        @endif
                        <!-- Success Badge -->
                        <div class="absolute top-3 right-3 bg-green-500 text-white px-3 py-1 rounded-full text-sm font-semibold flex items-center gap-1">
                            <i class="fas fa-check-circle"></i> Successful
                        </div>
                    </div>

                    <!-- Campaign Content -->
                    <div class="p-5">
                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">{{ $campaign->title }}</h3>

                        <!-- Progress Info -->
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700">
                                    Rs.{{ number_format($campaign->current_amount, 0) }} raised
                                </span>
                                <span class="text-sm text-gray-600">
                                    of Rs.{{ number_format($campaign->goal_amount, 0) }}
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full transition-all duration-300"
                                     style="width: {{ min($campaign->progress, 100) }}%"></div>
                            </div>
                        </div>

                        <!-- Category & Donors -->
                        <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
                            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                {{ $campaign->category->name ?? 'Uncategorized' }}
                            </span>
                            <span><i class="fas fa-users mr-1"></i>{{ $campaign->donors_count }} donors</span>
                        </div>

                        <!-- Success Story -->
                        @if($campaign->successStory)
                            <div class="bg-gray-50 p-3 rounded-lg mb-4">
                                <p class="text-sm text-gray-700 line-clamp-3">
                                    <i class="fas fa-quote-left text-yellow-500 mr-2"></i>
                                    {{ $campaign->successStory->story }}
                                </p>
                            </div>
                        @endif

                        <!-- Action Button -->
                        <a href="{{ route('campaigns.show', $campaign->slug) }}" 
                           class="inline-flex items-center justify-center w-full gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                            <i class="fas fa-arrow-right"></i> View Campaign
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        @if($successCampaigns->count() > 3)
            <div class="mt-8 text-center">
                <a href="{{ route('campaigns.index', ['filter' => 'successful']) }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-lg font-medium transition">
                    View All Success Stories <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        @endif
    @endif
</div>
