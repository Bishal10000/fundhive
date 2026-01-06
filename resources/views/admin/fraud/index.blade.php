<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Fraud Review Dashboard</h2>
    </x-slot>

    <div class="p-6">
        @foreach ($campaigns as $campaign)
            <div class="border p-4 rounded mb-4">
                <h3 class="font-bold">{{ $campaign->title }}</h3>

                <p class="text-sm text-red-600">
                    Fraud Score: {{ $campaign->fraud_score }}
                </p>

                <pre class="bg-gray-100 p-2 text-xs">
{{ json_encode($campaign->fraud_features, JSON_PRETTY_PRINT) }}
                </pre>

                <div class="flex gap-2 mt-2">
                    <form method="POST" action="{{ route('admin.fraud.approve', $campaign) }}">
                        @csrf
                        <button class="bg-green-600 text-white px-3 py-1 rounded">
                            Approve
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.fraud.reject', $campaign) }}">
                        @csrf
                        <button class="bg-red-600 text-white px-3 py-1 rounded">
                            Reject
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
