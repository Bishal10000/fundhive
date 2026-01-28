@extends('layouts.app')

@section('title', $campaign->title . ' | FundHive')

@section('content')
<div class="bg-slate-50 min-h-screen py-10">

    <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- LEFT: Campaign content -->
        <div class="lg:col-span-2">

            <!-- Image -->
            <div class="bg-white rounded-xl overflow-hidden shadow-sm mb-6">
                <img src="{{ asset('storage/' . $campaign->featured_image) }}"
                     class="w-full h-80 object-cover">
            </div>

            <!-- Title & description -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h1 class="text-2xl md:text-3xl font-bold text-slate-900 mb-2">
                    {{ $campaign->title }}
                </h1>

                <p class="text-sm text-slate-600 mb-1">
                    By {{ $campaign->user->name }}
                </p>

                <!-- Trust Score -->
                <p class="text-sm text-slate-600 mb-4">
                    Trust Score: ⭐ {{ $campaign->user->trust_score ?? 75 }}/100
                </p>

                <p class="text-slate-600 mb-6">
                    {{ $campaign->description }}
                </p>

                <div class="prose max-w-none text-slate-700">
                    {!! nl2br(e($campaign->story)) !!}
                </div>
            </div>

            <!-- Recent donations -->
@if(isset($donations) && $donations->count())
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold mb-4">Recent supporters</h3>

                    <ul class="space-y-3">
                        @foreach($donations as $donation)
                            <li class="flex justify-between text-sm">
                                <span class="text-slate-600">
                                    {{ $donation->user->name ?? 'Anonymous' }}
                                </span>
                                <span class="font-medium">
                                    Rs. {{ number_format($donation->amount) }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

        </div>

        <!-- RIGHT: Donation box -->
        <div>

            <div class="bg-white rounded-xl shadow-sm p-6 sticky top-24">

                <p class="text-sm text-slate-500 mb-1">Raised</p>
                <p class="text-2xl font-bold mb-2">
                    Rs. {{ number_format($campaign->current_amount) }}
                </p>

                <div class="w-full bg-slate-200 rounded-full h-2 mb-3">
                    <div class="bg-rose-600 h-2 rounded-full"
                         style="width: {{ min($campaign->progress, 100) }}%">
                    </div>
                </div>

                <div class="flex justify-between text-xs text-slate-500 mb-6">
                    <span>Goal: Rs. {{ number_format($campaign->goal_amount) }}</span>
                    <span>{{ $campaign->days_left }} days left</span>
                </div>

                <form action="{{ route('campaigns.donate', $campaign) }}" method="POST" x-data="{ amount: 0, showPaymentOptions: false }">
                    @csrf

                    <!-- Donation Amount -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Enter Amount (NPR)
                        </label>
                        <input type="number"
                               name="amount"
                               x-model="amount"
                               placeholder="Enter amount"
                               required
                               min="10"
                               class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-rose-500 focus:outline-none">
                        
                        <!-- Quick amount buttons -->
                        <div class="grid grid-cols-3 gap-2 mt-2">
                            <button type="button" @click="amount = 100" 
                                    class="border border-slate-300 rounded-lg py-2 text-sm hover:bg-slate-50">
                                Rs. 100
                            </button>
                            <button type="button" @click="amount = 500" 
                                    class="border border-slate-300 rounded-lg py-2 text-sm hover:bg-slate-50">
                                Rs. 500
                            </button>
                            <button type="button" @click="amount = 1000" 
                                    class="border border-slate-300 rounded-lg py-2 text-sm hover:bg-slate-50">
                                Rs. 1000
                            </button>
                        </div>
                    </div>

                    <!-- Payment Method Selection -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Choose Payment Method
                        </label>
                        
                        <!-- eSewa -->
                        <label class="flex items-center p-4 border border-slate-300 rounded-lg mb-2 cursor-pointer hover:bg-slate-50">
                            <input type="radio" name="payment_method" value="esewa" required class="mr-3">
                            <img src="{{ asset('assets/img/esewa.png') }}" alt="eSewa" class="h-8 mr-3">
                            <span class="text-sm font-medium">eSewa</span>
                        </label>

                        <!-- Khalti -->
                        <label class="flex items-center p-4 border border-slate-300 rounded-lg mb-2 cursor-pointer hover:bg-slate-50">
                            <input type="radio" name="payment_method" value="khalti" required class="mr-3">
                            <img src="{{ asset('assets/img/khalti.png') }}" alt="Khalti" class="h-8 mr-3">
                            <span class="text-sm font-medium">Khalti</span>
                        </label>

                        <!-- FonePay -->
                        <label class="flex items-center p-4 border border-slate-300 rounded-lg mb-2 cursor-pointer hover:bg-slate-50">
                            <input type="radio" name="payment_method" value="fonepay" required class="mr-3">
                            <img src="{{ asset('assets/img/fonepay.jpeg') }}" alt="FonePay" class="h-8 mr-3">
                            <span class="text-sm font-medium">FonePay</span>
                        </label>
                    </div>

                    <!-- Optional: Donor Name (for anonymous donations) -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Your Name <span class="text-slate-400">(optional)</span>
                        </label>
                        <input type="text"
                               name="donor_name"
                               placeholder="Enter your name"
                               value="{{ auth()->user()->name ?? '' }}"
                               class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-rose-500 focus:outline-none">
                    </div>

                    <!-- Optional: Message -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Message <span class="text-slate-400">(optional)</span>
                        </label>
                        <textarea name="message"
                                  rows="2"
                                  placeholder="Leave a message of support"
                                  class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-rose-500 focus:outline-none"></textarea>
                    </div>

                    <!-- Anonymous option -->
                    <label class="flex items-center mb-4">
                        <input type="checkbox" name="is_anonymous" value="1" class="mr-2">
                        <span class="text-sm text-slate-600">Donate anonymously</span>
                    </label>

                    <button type="submit"
                            class="w-full bg-rose-600 text-white py-3 rounded-lg font-semibold hover:bg-rose-700 transition">
                        <span x-show="amount > 0" x-text="'Donate Rs. ' + amount"></span>
                        <span x-show="amount == 0">Donate now</span>
                    </button>
                </form>

                <p class="text-xs text-slate-500 text-center mt-4">
                    FundHive ensures verified campaigns across Nepal 🇳🇵
                </p>

            </div>

            <!-- Creator Info Box -->
            <div class="bg-white rounded-xl shadow-sm p-6 mt-6">

                <h3 class="text-sm font-semibold text-slate-700 mb-3">
                    Campaign Creator
                </h3>

                <p class="text-sm text-slate-600 mb-2">
                    {{ $campaign->user->name }}
                </p>

                <p class="text-xs text-slate-500 mb-3">
                    Member since {{ $campaign->user->created_at->format('Y') }}
                </p>

                <p class="text-sm text-slate-600 mb-2">
                    Trust Score:
                    <span class="font-semibold text-slate-800">
                        ⭐ {{ $campaign->user->trust_score ?? 75 }}/100
                    </span>
                </p>

                <p class="text-xs text-slate-500">
                    Trust score is calculated based on past campaigns, activity,
                    and platform behavior.
                </p>

            </div>

        </div>
    </div>
</div>
@endsection
