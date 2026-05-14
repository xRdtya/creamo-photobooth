<?php

use App\Http\Controllers\PhotoSessionController;
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

// Apple OAuth Routes
Route::get('/auth/apple/redirect', [SocialAuthController::class, 'redirectToApple'])->name('auth.apple');
Route::get('/auth/apple/callback', [SocialAuthController::class, 'handleAppleCallback']);
Route::post('/auth/apple/callback', [SocialAuthController::class, 'handleAppleCallback']);

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
Route::post('/photo/shoot/{Order_id}', function($order_id) {
    $order = \App\Models\Transaction::where('order_id', $order_id)->firstOrFail();

    if ($order->payment_status !== 'success') {
        return redirect('/photo')->with('error', 'Silahkan bayar terlebih dahulu.');
    }
    return view("Customer.shoot", compact('order'));
});

Route::post('/photo/upload', [PhotoSessionController::class, 'upload']);

Route::post('/photo/select-frame/{Order_id}', [PhotoSessionController::class, 'index']);

Route::get('/logout', [SocialAuthController::class, 'logout']);

Route::get('/token', function() { 
    return array('csrf'=>csrf_token());
}); 