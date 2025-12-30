@extends('layouts.app')


@section('title', 'FundHive – Trusted Crowdfunding Platform in Nepal')

@section('content')


<!-- Hero Section -->
<section class="bg-[#f9fafb] py-20 md:py-28">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

            <!-- Left -->
            <div>
                <span class="inline-block bg-white px-4 py-2 rounded-full text-sm font-medium text-gray-700 mb-6 shadow">
                    Helping real people across Nepal
                </span>

                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 leading-tight mb-6">
                    Fundraising made
                    <span class="text-red-600">simple, honest, and transparent</span>
                </h1>

                <p class="text-lg text-gray-600 mb-10 max-w-xl">
                    FundHive is a crowdfunding platform built for Nepal.
                    Whether it’s medical emergencies, education fees, or urgent community needs,
                    we help you raise funds from people who care.
                </p>

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('campaigns.create') }}"
                       class="bg-red-600 text-white px-8 py-4 rounded-lg font-semibold hover:bg-red-700 text-lg">
                        Start a fundraiser
                    </a>

                    <a href="{{ route('campaigns.index') }}"
                       class="border border-gray-300 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 text-lg">
                        Explore campaigns
                    </a>
                </div>
            </div>

            <!-- Right -->
            <div class="flex justify-center">
                <img src="{{ asset('assets/img/fund.jpg') }}" class="img-fluid" alt="Team Member">

            </div>

        </div>
    </div>
</section>
<!-- Stats Section -->
<section class="bg-white py-16">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">

            <div>
                <div class="text-3xl font-bold text-gray-900">
                    NPR {{ number_format($totalRaised) }}+
                </div>
                <div class="text-gray-600 font-medium mt-1">Raised so far</div>
            </div>

            <div>
                <div class="text-3xl font-bold text-gray-900">
                    {{ number_format($totalCampaigns) }}+
                </div>
                <div class="text-gray-600 font-medium mt-1">Fundraisers created</div>
            </div>

            <div>
                <div class="text-3xl font-bold text-gray-900">
                    {{ number_format($totalDonors) }}+
                </div>
                <div class="text-gray-600 font-medium mt-1">People donated</div>
            </div>

            <div>
                <div class="text-3xl font-bold text-gray-900">
                    {{ $verifiedPercentage }}%
                </div>
                <div class="text-gray-600 font-medium mt-1">Verified campaigns</div>
            </div>

        </div>
    </div>
</section>

<!-- Featured Campaigns -->
<section class="bg-gray-50 py-20">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-12">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Featured Fundraisers</h2>
                <p class="text-gray-600">Campaigns actively helping people right now</p>
            </div>
            <a href="{{ route('campaigns.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                View all →
            </a>
        </div>

        <div class="relative">
            <!-- Carousel container -->
            <div id="featured-carousel" class="flex overflow-x-auto space-x-6 scroll-smooth py-4">
                @forelse($featuredCampaigns as $campaign)
                    <div class="bg-white rounded-xl shadow overflow-hidden flex-shrink-0 w-full sm:w-80">
                        <img src="{{ $campaign['image_url'] }}"
                             class="h-48 w-full object-cover"
                             alt="{{ $campaign['title'] }}">

                        <div class="p-6">
                            <h3 class="font-bold text-lg mb-3">
                                {{ $campaign['title'] }}
                            </h3>

                            <div class="mb-4">
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="font-medium">NPR {{ number_format($campaign['amount_raised']) }} raised</span>
                                    <span class="font-medium text-green-600">
                                        {{ $campaign['goal'] ? round(($campaign['amount_raised'] / $campaign['goal']) * 100) : 0 }}%
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" 
                                         style="width: {{ $campaign['goal'] ? ($campaign['amount_raised'] / $campaign['goal']) * 100 : 0 }}%">
                                    </div>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500 mt-2">
                                    <span>Goal: NPR {{ number_format($campaign['goal']) }}</span>
                                    <span>{{ $campaign['days_remaining'] }} days remaining</span>
                                </div>
                            </div>

                            <a href="{{ route('campaigns.show', $campaign['id']) }}"
                               class="block w-full bg-red-600 text-white py-3 rounded-lg text-center font-semibold hover:bg-red-700">
                                Donate
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 w-full">
                        No campaigns available at the moment.
                    </p>
                @endforelse
            </div>

            <!-- Carousel arrows -->
            <button id="prev-btn" class="absolute top-1/2 left-0 transform -translate-y-1/2 bg-white p-2 rounded-full shadow hover:bg-gray-100 z-10">
                &#10094;
            </button>
            <button id="next-btn" class="absolute top-1/2 right-0 transform -translate-y-1/2 bg-white p-2 rounded-full shadow hover:bg-gray-100 z-10">
                &#10095;
            </button>
        </div>
    </div>
</section>






<!-- How It Works -->
<section class="bg-white py-20">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center text-gray-900 mb-16">
            How FundHive works
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 text-center">

            <div>
                <div class="text-4xl font-bold text-red-600 mb-4">1</div>
                <h3 class="font-bold text-lg mb-2">Start your campaign</h3>
                <p class="text-gray-600">
                    Explain your situation, upload supporting documents, and set your target amount.
                </p>
            </div>

            <div>
                <div class="text-4xl font-bold text-red-600 mb-4">2</div>
                <h3 class="font-bold text-lg mb-2">Quick review & verification</h3>
                <p class="text-gray-600">
                    We review campaigns to reduce misuse and protect donors.
                </p>
            </div>

            <div>
                <div class="text-4xl font-bold text-red-600 mb-4">3</div>
                <h3 class="font-bold text-lg mb-2">Receive donations</h3>
                <p class="text-gray-600">
                    Funds are transferred securely, and you can keep supporters updated.
                </p>
            </div>

        </div>
    </div>
</section>

<!-- Final CTA -->
<!-- <section class="bg-gray-900 text-white py-20">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">
            Need help raising funds?
        </h2>

        <p class="text-lg opacity-90 mb-10 max-w-2xl mx-auto">
            Join thousands of people in Nepal who have already used FundHive
            to get support during difficult times.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('campaigns.create') }}"
               class="bg-white text-gray-900 font-bold px-10 py-4 rounded-lg hover:bg-gray-100">
                Start a fundraiser
            </a>

            <a href="{{ route('campaigns.index') }}"
               class="border-2 border-white px-10 py-4 rounded-lg hover:bg-white/10">
                Browse campaigns
            </a>
        </div>

        <p class="text-sm opacity-70 mt-8">
            Secure payments • Verified campaigns • Community-driven support
        </p>
    </div>
</section> -->

@endsection
