<?php

use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function() {
    return view("landing");
});

Route::get('/photo', function() {
    return view("Customer.frontPage");
});

Route::post('/photo/payment', [TransactionController::class, 'createQris']);
Route::post('/photo/webhook', [TransactionController::class, 'notificationHandler']);
Route::get('/photo/status/{Order_id}', function($order_id) {
    $order = \App\Models\Transaction::where('order_id', $order_id)->first();

    return response()->json([
        'payment_status' => $order ? $order->payment_status : 'not_found'
    ]);
});

Route::get('/token', function() { 
    return array('csrf'=>csrf_token());
}); 