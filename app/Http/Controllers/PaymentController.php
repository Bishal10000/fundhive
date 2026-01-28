<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Show payment gateway simulation page
     */
    public function process(Request $request)
    {
        $donationId = $request->get('donation');
        $gateway = $request->get('gateway');
        
        $donation = Donation::findOrFail($donationId);
        
        // Ensure donation belongs to current user or is being processed
        if ($donation->status !== 'pending') {
            return redirect()->route('campaigns.show', $donation->campaign)
                ->with('error', 'This donation has already been processed.');
        }

        return view('payment.gateway', compact('donation', 'gateway'));
    }

    /**
     * Handle payment confirmation (demo/sandbox)
     */
    public function confirm(Request $request, Donation $donation)
    {
        $action = $request->input('action'); // 'success' or 'cancel'
        
        if ($action === 'success') {
            // Update donation status
            $donation->update([
                'status' => 'completed',
                'payment_verified_at' => now(),
            ]);

            // Update campaign amount
            $donation->campaign->increment('current_amount', $donation->amount);

            return redirect()->route('payment.success', $donation);
        } else {
            // Cancel payment
            $donation->update([
                'status' => 'failed',
            ]);

            return redirect()->route('payment.failed', $donation);
        }
    }

    /**
     * Payment success page
     */
    public function success(Donation $donation)
    {
        return view('payment.success', compact('donation'));
    }

    /**
     * Payment failed page
     */
    public function failed(Donation $donation)
    {
        return view('payment.failed', compact('donation'));
    }
}
