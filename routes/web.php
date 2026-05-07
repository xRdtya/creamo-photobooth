<?php

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SocialAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function() {
    return view("landing");
});

Route::middleware(['guest:merchant'])->group(function() {
    Route::get('/signin', function() {
        return view("auth.signin");
    })->name('login');
    
    Route::get('/signup', function() {
        return view("auth.signup");
    });
});

// Google OAuth Routes
Route::get('/auth/google/redirect', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);

// Temporary dashboard placeholder after login
Route::get('/dashboard', function() {
    return 'Login berhasil! Dashboard akan dibuat segera.';
})->middleware('auth:merchant');

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
Route::post('/photo/shoot', function() {
    return view("Customer.shoot");
});

Route::get('/logout', [SocialAuthController::class, 'logout']);

Route::get('/token', function() { 
    return array('csrf'=>csrf_token());
}); 