<!-- Fraud Detection Monitoring Dashboard -->
<div class="mb-8 bg-white rounded-xl shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-shield-alt text-red-600"></i> Fraud Detection & Monitoring
        </h2>
        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
            Last Checked: {{ $lastFraudCheck->format('M d, Y H:i') ?? 'Never' }}
        </span>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-gradient-to-br from-red-50 to-orange-50 rounded-lg p-4 border border-red-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-700 font-medium">Flagged Campaigns</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">{{ $flaggedCampaignsCount }}</p>
                </div>
                <div class="text-4xl text-red-200"><i class="fas fa-flag"></i></div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-lg p-4 border border-yellow-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-700 font-medium">Medium Risk</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $mediumRiskCount }}</p>
                </div>
                <div class="text-4xl text-yellow-200"><i class="fas fa-exclamation-circle"></i></div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-4 border border-green-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-700 font-medium">Safe Campaigns</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $safeCampaignsCount }}</p>
                </div>
                <div class="text-4xl text-green-200"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>
    </div>

    <!-- Fraud Risk Analysis -->
    <div class="space-y-3 mb-6">
        <h3 class="text-sm font-semibold text-gray-800">Fraud Risk Analysis</h3>
        
        @foreach($campaignFraudAnalysis as $analysis)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-start justify-between gap-4">
                    <!-- Campaign Info -->
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">{{ Str::limit($analysis['title'], 50) }}</h4>
                        <p class="text-sm text-gray-600 mt-1">Campaign by {{ $analysis['creator'] }}</p>
                    </div>

                    <!-- Risk Indicator -->
                    <div class="flex-shrink-0 text-right">
                        <div class="text-sm font-semibold mb-2">
                            @if($analysis['fraud_score'] >= 0.7)
                                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold">
                                    <i class="fas fa-times-circle mr-1"></i> HIGH RISK
                                </span>
                            @elseif($analysis['fraud_score'] >= 0.4)
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold">
                                    <i class="fas fa-exclamation-circle mr-1"></i> MEDIUM RISK
                                </span>
                            @else
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">
                                    <i class="fas fa-check-circle mr-1"></i> LOW RISK
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500">Score: {{ number_format($analysis['fraud_score'] * 100, 1) }}%</p>
                    </div>
                </div>

                <!-- Fraud Features -->
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <p class="text-xs font-medium text-gray-700 mb-2">Risk Factors:</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($analysis['risk_factors'] as $factor)
                            <span class="inline-block px-2 py-1 bg-red-50 text-red-700 rounded text-xs">
                                <i class="fas fa-triangle-exclamation mr-1"></i>{{ $factor }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-3 flex gap-2">
                    <a href="{{ route('campaigns.show', ['campaign' => $analysis['slug']]) }}" 
                       class="text-xs px-3 py-1 text-blue-600 hover:text-blue-800 font-medium">
                        View Details
                    </a>
                    @if(auth()->user()->isAdmin())
                        <button class="text-xs px-3 py-1 text-orange-600 hover:text-orange-800 font-medium"
                                onclick="reviewFraudCase('{{ $analysis['id'] }}')">
                            Review
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    @if($campaignFraudAnalysis->isEmpty())
        <div class="text-center py-8">
            <i class="fas fa-shield-check text-green-400 text-4xl mb-2"></i>
            <p class="text-gray-600">All campaigns look safe! No fraud detected.</p>
        </div>
    @endif

    <!-- Admin Actions -->
    @if(auth()->user()->isAdmin())
        <div class="mt-6 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.fraud') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition">
                <i class="fas fa-shield-alt"></i> Go to Fraud Dashboard
            </a>
        </div>
    @endif
</div>

<script>
function reviewFraudCase(campaignId) {
    window.location.href = `/admin/fraud/${campaignId}/review`;
}
</script>
