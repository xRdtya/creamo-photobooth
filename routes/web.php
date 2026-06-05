<?php

use App\Http\Controllers\PhotoSessionController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\DevicePingController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Middleware\CheckMerchantSubscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function() {
    $merchant = Auth::guard('merchant')->user();
    $status = null;

    if ($merchant) {
        $status = \App\Models\Subscription::where('merchant_id', $merchant->id)->first();
    }

    return view("landing", compact('status'));
})->name('landing');

Route::middleware(['guest:merchant'])->group(function() {
    Route::get('/signin', function() {
        return view("auth.signin");
    })->name('login');
    
    Route::post('/signin', [\App\Http\Controllers\AuthController::class, 'signin']);

    Route::get('/signup', function() {
        return view("auth.signup");
    });

    Route::post('/signup', [\App\Http\Controllers\AuthController::class, 'signup']);
});

// Google OAuth Routes
Route::get('/auth/google/redirect', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);

// Apple OAuth Routes
Route::get('/auth/apple/redirect', [SocialAuthController::class, 'redirectToApple'])->name('auth.apple');
Route::get('/auth/apple/callback', [SocialAuthController::class, 'handleAppleCallback']);
Route::post('/auth/apple/callback', [SocialAuthController::class, 'handleAppleCallback']);

Route::post('/subscription/checkout', [SubscriptionController::class, 'checkout'])
    ->name('subscription.checkout');

// Webhook
Route::post('/subscription/webhook', [SubscriptionController::class, 'webhook'])
    ->name('subscription.webhook');
Route::post('/photo/webhook', [TransactionController::class, 'notificationHandler']);

// Admin Dashboard
Route::middleware(['auth:merchant', CheckMerchantSubscription::class])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/active-devices', [AdminDashboardController::class, 'activeDevices'])->name('dashboard.active-devices');

    Route::get('/photo', function() {
        return view("Customer.frontPage");
    })->name('photo');

    Route::post('/photo/payment', [TransactionController::class, 'createQris']);
    Route::get('/photo/status/{Order_id}', function ($order_id) {
        $order = \App\Models\Transaction::where('order_id', $order_id)->first();

        return response()->json([
            'payment_status' => $order ? $order->payment_status : 'not_found'
        ]);
    });
    Route::post('/photo/shoot/{Order_id}', function ($order_id) {
        $order = \App\Models\Transaction::where('order_id', $order_id)->firstOrFail();

        if ($order->payment_status !== 'success') {
            return redirect('/photo')->with('error', 'Silahkan bayar terlebih dahulu.');
        }
        return view("Customer.shoot", compact('order'));
    });

    Route::post('/photo/upload', [PhotoSessionController::class, 'upload']);

    Route::post('/photo/select-frame/{Order_id}', [PhotoSessionController::class, 'index']);
    Route::post('/photo/save-frame/{Order_id}', [PhotoSessionController::class, 'saveFrame'])->name('photo.save-frame');
});
Route::get('/photo/view/{Order_id}', [PhotoSessionController::class, 'viewPhoto'])->name('photo.view');
Route::get('/photo/download/{Order_id}', [PhotoSessionController::class, 'downloadPhoto'])->name('photo.download');

// ── Device Heartbeat (dipanggil oleh device photobooth) ──────────────────────
Route::post('/photo/device/ping',     [DevicePingController::class, 'ping']);
Route::post('/photo/device/ping-off', [DevicePingController::class, 'pingOff']);

// ── DEV ONLY: simulasi device aktif langsung dari browser ───────────────────
// Hapus route ini sebelum production!
Route::get('/photo/device/test-ping', [DevicePingController::class, 'testPing'])->name('device.test-ping');

Route::get('/logout', [SocialAuthController::class, 'logout']);

Route::get('/token', function() { 
    return array('csrf'=>csrf_token());
}); 