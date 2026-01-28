@extends('layouts.app')

@section('title', 'Payment Gateway | FundHive')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-12">
    <div class="max-w-md mx-auto px-4">
        
        <!-- Payment Gateway Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            
            <!-- Gateway Header -->
            <div class="p-6 text-center border-b
                @if($gateway === 'esewa') bg-green-600
                @elseif($gateway === 'khalti') bg-purple-600
                @else bg-red-600
                @endif">
                
                @if($gateway === 'esewa')
                    <img src="{{ asset('assets/img/esewa.png') }}" alt="eSewa" class="h-16 mx-auto mb-3">
                    <h2 class="text-white text-2xl font-bold">eSewa Payment</h2>
                @elseif($gateway === 'khalti')
                    <img src="{{ asset('assets/img/khalti.png') }}" alt="Khalti" class="h-16 mx-auto mb-3">
                    <h2 class="text-white text-2xl font-bold">Khalti Payment</h2>
                @else
                    <img src="{{ asset('assets/img/fonepay.jpeg') }}" alt="FonePay" class="h-16 mx-auto mb-3">
                    <h2 class="text-white text-xl font-bold">FonePay Payment</h2>
                @endif
                
                <p class="text-white/90 text-sm mt-2">Demo/Sandbox Environment</p>
            </div>

            <!-- Payment Details -->
            <div class="p-6">
                <div class="bg-slate-50 rounded-lg p-4 mb-6">
                    <h3 class="text-sm font-medium text-slate-600 mb-3">Payment Summary</h3>
                    
                    <div class="flex justify-between mb-2">
                        <span class="text-slate-600">Campaign:</span>
                        <span class="font-medium text-slate-900">{{ Str::limit($donation->campaign->title, 30) }}</span>
                    </div>
                    
                    <div class="flex justify-between mb-2">
                        <span class="text-slate-600">Amount:</span>
                        <span class="font-bold text-xl text-slate-900">Rs. {{ number_format($donation->amount) }}</span>
                    </div>
                    
                    <div class="flex justify-between mb-2">
                        <span class="text-slate-600">Transaction ID:</span>
                        <span class="text-sm font-mono text-slate-700">{{ $donation->transaction_id }}</span>
                    </div>
                    
                    @if($donation->message)
                    <div class="mt-3 pt-3 border-t border-slate-200">
                        <span class="text-slate-600 text-sm">Message:</span>
                        <p class="text-slate-700 text-sm mt-1">{{ $donation->message }}</p>
                    </div>
                    @endif
                </div>

                <!-- Demo Login Section -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-blue-800">Demo Mode</p>
                            <p class="text-xs text-blue-600 mt-1">This is a simulated payment gateway for college project demonstration. No real money will be charged.</p>
                        </div>
                    </div>
                </div>

                <!-- Simulated Login Form -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        @if($gateway === 'esewa') eSewa ID / Mobile Number
                        @elseif($gateway === 'khalti') Khalti Mobile Number
                        @else FonePay Username
                        @endif
                    </label>
                    <input type="text" 
                           value="demo@user.com" 
                           readonly
                           class="w-full border border-slate-300 rounded-lg px-4 py-3 bg-slate-50 text-slate-600">
                    
                    <label class="block text-sm font-medium text-slate-700 mb-2 mt-4">
                        @if($gateway === 'esewa') Password / MPIN
                        @elseif($gateway === 'khalti') MPIN
                        @else Password
                        @endif
                    </label>
                    <input type="password" 
                           value="******" 
                           readonly
                           class="w-full border border-slate-300 rounded-lg px-4 py-3 bg-slate-50 text-slate-600">
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <form action="{{ route('payment.confirm', $donation) }}" method="POST">
                        @csrf
                        <input type="hidden" name="action" value="success">
                        <button type="submit" 
                                class="w-full
                                @if($gateway === 'esewa') bg-green-600 hover:bg-green-700 text-white
                                @elseif($gateway === 'khalti') bg-purple-600 hover:bg-purple-700 text-yellow-300
                                @else bg-red-600 hover:bg-red-700 text-white
                                @endif
                                py-3 px-4 rounded-lg font-semibold transition duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Confirm Payment (Demo)
                        </button>
                    </form>

                    <form action="{{ route('payment.confirm', $donation) }}" method="POST">
                        @csrf
                        <input type="hidden" name="action" value="cancel">
                        <button type="submit" 
                                class="w-full bg-slate-200 hover:bg-slate-300 text-slate-700 py-3 px-4 rounded-lg font-semibold transition duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Cancel Payment
                        </button>
                    </form>
                </div>

                <!-- Security Note -->
                <div class="mt-6 text-center">
                    <p class="text-xs text-slate-500">
                        🔒 Secured by {{ ucfirst($gateway) }} | Demo Environment
                    </p>
                </div>
            </div>
        </div>

        <!-- Info Box -->
        <div class="mt-6 bg-white rounded-lg shadow p-4">
            <h4 class="font-medium text-slate-900 mb-2">About this demo:</h4>
            <ul class="text-sm text-slate-600 space-y-1">
                <li>• This is a simulated payment gateway</li>
                <li>• No real payment integration (college project)</li>
                <li>• Click "Confirm Payment" to simulate successful payment</li>
                <li>• Click "Cancel" to simulate payment failure</li>
            </ul>
        </div>
    </div>
</div>
@endsection
