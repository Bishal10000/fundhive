@extends('layouts.app')

@section('title', 'Pricing & Fees - FundHive')

@section('content')
<div class="bg-gray-50">

    <!-- Hero -->
    <section class="bg-white border-b">
        <div class="max-w-5xl mx-auto px-4 py-16 text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                Simple & Transparent Pricing
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                FundHive is built to help people in Nepal raise funds with confidence.
                We keep our pricing clear, fair, and easy to understand.
            </p>
        </div>
    </section>

    <!-- Pricing Card -->
    <section class="max-w-5xl mx-auto px-4 py-16">
        <div class="bg-white rounded-2xl shadow-sm border p-10 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                0% Platform Fee
            </h2>

            <p class="text-gray-600 text-lg mb-6 max-w-xl mx-auto">
                FundHive does not charge any fee to start or run a fundraiser.
                We believe help should reach people who need it most.
            </p>

            <div class="flex justify-center gap-6 flex-wrap text-sm text-gray-600 mb-8">
                <span class="flex items-center gap-2">
                    <i class="fas fa-check-circle text-green-600"></i>
                    No setup cost
                </span>
                <span class="flex items-center gap-2">
                    <i class="fas fa-check-circle text-green-600"></i>
                    No monthly charges
                </span>
                <span class="flex items-center gap-2">
                    <i class="fas fa-check-circle text-green-600"></i>
                    Unlimited donations
                </span>
            </div>

            <a href="{{ route('campaigns.create') }}"
               class="inline-block bg-red-600 text-white px-8 py-4 rounded-lg font-semibold hover:bg-red-700">
                Start a fundraiser
            </a>
        </div>
    </section>

    <!-- Payment Gateway Fees -->
    <section class="max-w-5xl mx-auto px-4 pb-16">
        <div class="bg-white rounded-xl border p-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-4">
                Payment Gateway Charges
            </h3>

            <p class="text-gray-600 mb-6 max-w-3xl">
                Payment gateway fees are charged by banks and payment providers.
                FundHive does not control or profit from these charges.
            </p>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="py-3 text-gray-700 font-semibold">Payment Method</th>
                            <th class="py-3 text-gray-700 font-semibold">Approx. Fee</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600">
                        <tr class="border-b">
                            <td class="py-3">eSewa / Khalti / IME Pay</td>
                            <td class="py-3">~2.5%</td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-3">Debit / Credit Cards</td>
                            <td class="py-3">~3–4%</td>
                        </tr>
                        <tr>
                            <td class="py-3">International Cards</td>
                            <td class="py-3">~4%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Example -->
    <section class="max-w-5xl mx-auto px-4 pb-16">
        <div class="bg-blue-50 border border-blue-100 rounded-xl p-8">
            <h3 class="text-xl font-bold text-gray-900 mb-4">
                Example Calculation
            </h3>

            <p class="text-gray-700 leading-relaxed">
                If someone donates <strong>NPR 10,000</strong>:<br>
                – Payment gateway fee (~3%): <strong>NPR 300</strong><br>
                – FundHive platform fee: <strong>NPR 0</strong><br><br>
                <strong>You receive: NPR 9,700</strong>
            </p>
        </div>
    </section>

    <!-- What's Included -->
    <section class="max-w-5xl mx-auto px-4 pb-20">
        <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">
            What’s Included With Every Fundraiser
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white border rounded-xl p-6">
                <i class="fas fa-brain text-red-600 text-2xl mb-3"></i>
                <h4 class="font-semibold mb-2">AI Fraud Detection</h4>
                <p class="text-gray-600 text-sm">
                    Every campaign is reviewed to ensure authenticity and trust.
                </p>
            </div>

            <div class="bg-white border rounded-xl p-6">
                <i class="fas fa-user-shield text-red-600 text-2xl mb-3"></i>
                <h4 class="font-semibold mb-2">Campaign Verification</h4>
                <p class="text-gray-600 text-sm">
                    We verify fundraisers to protect donors and beneficiaries.
                </p>
            </div>

            <div class="bg-white border rounded-xl p-6">
                <i class="fas fa-wallet text-red-600 text-2xl mb-3"></i>
                <h4 class="font-semibold mb-2">Fast Withdrawals</h4>
                <p class="text-gray-600 text-sm">
                    Withdraw funds easily to supported bank accounts in Nepal.
                </p>
            </div>

            <div class="bg-white border rounded-xl p-6">
                <i class="fas fa-headset text-red-600 text-2xl mb-3"></i>
                <h4 class="font-semibold mb-2">Fundraiser Support</h4>
                <p class="text-gray-600 text-sm">
                    Our support team helps you throughout your fundraising journey.
                </p>
            </div>
        </div>
    </section>

</div>
@endsection
