<!-- Duplicate Campaign Detection Alert -->
@if($duplicateCampaignWarning)
    <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
        <div class="flex items-start gap-3">
            <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 flex-shrink-0"></i>
            <div class="flex-1">
                <h3 class="font-semibold text-yellow-900">Similar Campaign Detected</h3>
                <p class="text-sm text-yellow-700 mt-1">
                    We found a similar campaign you created recently. Please ensure this is a new campaign or update your existing one instead to avoid duplication.
                </p>
                @if($duplicateCampaignWarning['similar_campaign'])
                    <div class="mt-2 p-3 bg-white rounded border border-yellow-100">
                        <p class="text-sm font-medium text-gray-900">Similar to: {{ $duplicateCampaignWarning['similar_campaign']->title }}</p>
                        <a href="{{ route('campaigns.show', $duplicateCampaignWarning['similar_campaign']->slug) }}" 
                           class="text-xs text-blue-600 hover:text-blue-800 mt-2 inline-block">
                            View Campaign <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif
