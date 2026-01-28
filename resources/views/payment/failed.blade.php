@extends('layouts.app')

@section('title', 'Payment Failed | FundHive')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 to-orange-50 py-12">
    <div class="max-w-2xl mx-auto px-4">
        
        <!-- Failed Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            
            <!-- Error Icon -->
            <div class="bg-red-600 p-8 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full mb-4">
                    <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Payment Cancelled</h1>
                <p class="text-red-100">Your payment was not completed</p>
            </div>

            <!-- Donation Details -->
            <div class="p-8">
                <div class="bg-slate-50 rounded-lg p-6 mb-6">
                    <h2 class="text-lg font-semibold text-slate-900 mb-4">Transaction Details</h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-slate-600">Amount:</span>
                            <span class="font-bold text-xl text-slate-900">Rs. {{ number_format($donation->amount) }}</span>
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
                            <span class="text-slate-600">Status:</span>
                            <span class="text-red-600 font-semibold">Failed</span>
                        </div>
                    </div>
                </div>

                <!-- Reason -->
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-orange-600 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div class="text-sm text-orange-700">
                            <p class="font-medium">Payment Not Completed</p>
                            <p class="mt-1">You cancelled the payment or the transaction was interrupted. No charges have been made.</p>
                        </div>
                    </div>
                </div>

                <!-- Common Issues -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-slate-900 mb-3">Common reasons for payment failure:</h3>
                    <ul class="text-sm text-slate-700 space-y-2">
                        <li class="flex items-start">
                            <span class="mr-2">•</span>
                            <span>Payment was cancelled by user</span>
                        </li>
                        <li class="flex items-start">
                            <span class="mr-2">•</span>
                            <span>Insufficient balance in account</span>
                        </li>
                        <li class="flex items-start">
                            <span class="mr-2">•</span>
                            <span>Network connectivity issues</span>
                        </li>
                        <li class="flex items-start">
                            <span class="mr-2">•</span>
                            <span>Incorrect payment credentials</span>
                        </li>
                    </ul>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('campaigns.show', $donation->campaign) }}" 
                       class="flex-1 bg-rose-600 hover:bg-rose-700 text-white py-3 px-4 rounded-lg font-semibold text-center transition">
                        Try Again
                    </a>
                    <a href="{{ route('campaigns.index') }}" 
                       class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-700 py-3 px-4 rounded-lg font-semibold text-center transition">
                        Browse Campaigns
                    </a>
                </div>

                <!-- Support -->
                <div class="mt-6 pt-6 border-t border-slate-200 text-center">
                    <p class="text-sm text-slate-600 mb-2">Need help?</p>
                    <a href="mailto:support@fundhive.com" class="text-rose-600 hover:text-rose-700 font-medium text-sm">
                        Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
