<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\CoreApi;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.serverKey');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }
    /**
     * Display a listing of the resource.
     */
    public function createQris(Request $request)
    {
        $orderId = 'CRM-' . time();

        $params = [
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => 30000,
            ],
            'qris' => [
                'acquirer' => 'gopay',
            ]
        ];

        $response = CoreApi::charge($params);

        Transaction::create([
            'order_id' => $orderId,
            'merchant_id' => '1',
            'gross_amount' => $params['transaction_details']['gross_amount'],
            'payment_status' => 'pending'
        ]);

        $qr_url = $response->actions[0]->url;

        return view('Customer.payment', compact('qr_url', 'orderId'));
    }

    public function notificationHandler(Request $request)
    {
        $serverKey = config('midtrans.serverKey');

        $signature = hash(
            'sha512',
            $request->order_id .
                $request->status_code .
                $request->gross_amount .
                $serverKey
        );

        if ($signature !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $order = Transaction::where('order_id', $request->order_id)->first();

        if (!$order) return response()->json(['message' => 'Order not found'], 404);

        if ($request->transaction_status == 'settlement') {
            $order->update(['payment_status' => 'success']);
        } elseif ($request->transaction_status == 'pending') {
            $order->update(['payment_status' => 'pending']);
        } else {
            $order->update(['payment_status' => 'failed']);
        }

        Log::info($request->all());

        return response()->json(['message' => 'OK']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
