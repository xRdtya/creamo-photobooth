<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;
use Midtrans\Config;
use Midtrans\Snap;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.serverKey');
        Config::$isProduction = config('midtrans.isProduction');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    public function checkout(Request $request)
    {
        $merchant = Auth::guard('merchant')->user();

        $plans = [
            'monthly' => ['label' => 'Bulanan', 'price' => 499000],
            'yearly'  => ['label' => 'Tahunan', 'price' => 899000],
        ];

        $plan     = $request->input('plan', 'monthly');
        $selected = $plans[$plan];
        $orderId  = 'SUB-' . $merchant->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $selected['price'],
            ],
            'item_details' => [[
                'id'       => 'SUBSCRIPTION-' . strtoupper($plan),
                'price'    => $selected['price'],
                'quantity' => 1,
                'name'     => 'Creamo Subscription ' . $selected['label'],
            ]],
            'customer_details' => [
                'first_name' => $merchant->name,
                'email'      => $merchant->email,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        Subscription::where('merchant_id', $merchant->id)
            ->whereNotIn('status', ['active'])
            ->delete();
        
        Subscription::create(
            [
                'merchant_id' => $merchant->id,
                'order_id'    => $orderId,
                'plan'        => $plan,
                'status'      => 'pending',
                'expires_at'  => null,
            ]
        );

        return response()->json(['snap_token' => $snapToken]);
    }

    public function webhook(Request $request)
    {
        $payload       = $request->all();
        $orderId       = $payload['order_id'];
        $transStatus   = $payload['transaction_status'];
        $fraudStatus   = $payload['fraud_status'] ?? null;

        $subscription = Subscription::where('order_id', $orderId)->first();
        if (!$subscription) return response('Not found', 404);

        if ($transStatus === 'capture' && $fraudStatus === 'accept') {
            $this->activateSubscription($subscription);
        } elseif ($transStatus === 'settlement') {
            $this->activateSubscription($subscription);
        } elseif (in_array($transStatus, ['cancel', 'deny', 'expire'])) {
            $subscription->update(['status' => 'failed']);
        }

        return response('OK', 200);
    }

    private function activateSubscription(Subscription $subscription)
    {
        $months     = $subscription->plan === 'yearly' ? 12 : 1;
        $expiryDate = now()->addMonths($months);

        $subscription->update([
            'status'     => 'active',
            'started_at' => now(),
            'expires_at' => $expiryDate,
        ]);

        \App\Models\Merchant::where('id', $subscription->merchant_id)->update([
            'subscription' => 'active',
            'expiry_date'  => $expiryDate,
        ]);
    }
}
