@extends('layouts.app')

@section('title', 'Payment Successful | FundHive')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-50 py-12">
    <div class="max-w-2xl mx-auto px-4">
        
        <!-- Success Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            
            <!-- Success Icon -->
            <div class="bg-green-600 p-8 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full mb-4">
                    <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Payment Successful!</h1>
                <p class="text-green-100">Thank you for your generous donation</p>
            </div>

            <!-- Donation Details -->
            <div class="p-8">
                <div class="bg-slate-50 rounded-lg p-6 mb-6">
                    <h2 class="text-lg font-semibold text-slate-900 mb-4">Donation Details</h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-slate-600">Amount Donated:</span>
                            <span class="font-bold text-2xl text-green-600">Rs. {{ number_format($donation->amount) }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-slate-600">Campaign:</span>
                            <span class="font-medium text-slate-900">{{ $donation->campaign->title }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-slate-600">Transaction ID:</span>
                            <span class="font-mono text-sm text-slate-700">{{ $donation->transaction_id }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-slate-600">Payment Method:</span>
                            <span class="font-medium text-slate-700 capitalize">{{ $donation->payment_method }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-slate-600">Date:</span>
                            <span class="text-slate-700">{{ $donation->created_at->format('M d, Y h:i A') }}</span>
                        </div>

                        @if($donation->message)
                        <div class="pt-3 border-t border-slate-200">
                            <span class="text-slate-600 block mb-1">Your Message:</span>
                            <p class="text-slate-700 italic">{{ $donation->message }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Receipt Notice -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div class="text-sm text-blue-700">
                            <p class="font-medium">Receipt sent!</p>
                            <p class="mt-1">A confirmation email has been sent to your registered email address.</p>
                        </div>
                    </div>
                </div>

                <!-- Impact Message -->
                <div class="bg-gradient-to-r from-rose-50 to-pink-50 rounded-lg p-6 mb-6">
                    <h3 class="font-semibold text-slate-900 mb-2">Your Impact 💝</h3>
                    <p class="text-slate-700 text-sm leading-relaxed">
                        Your generous donation of <strong>Rs. {{ number_format($donation->amount) }}</strong> 
                        brings {{ $donation->campaign->user->name }}'s campaign closer to its goal. 
                        Every contribution makes a difference!
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('campaigns.show', $donation->campaign) }}" 
                       class="flex-1 bg-rose-600 hover:bg-rose-700 text-white py-3 px-4 rounded-lg font-semibold text-center transition">
                        View Campaign
                    </a>
                    <a href="{{ route('campaigns.index') }}" 
                       class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-700 py-3 px-4 rounded-lg font-semibold text-center transition">
                        Browse Campaigns
                    </a>
                </div>

                <!-- Social Share -->
                <div class="mt-6 pt-6 border-t border-slate-200 text-center">
                    <p class="text-sm text-slate-600 mb-3">Share this campaign with others:</p>
                    <div class="flex justify-center gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('campaigns.show', $donation->campaign)) }}" 
                           target="_blank"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                            Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('campaigns.show', $donation->campaign)) }}&text={{ urlencode($donation->campaign->title) }}" 
                           target="_blank"
                           class="bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                            Twitter
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
