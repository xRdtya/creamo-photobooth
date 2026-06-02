<?php

use App\Http\Controllers\PhotoSessionController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\DevicePingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function() {
    return view("landing");
});

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

// Admin Dashboard
Route::middleware('auth:merchant')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/active-devices', [AdminDashboardController::class, 'activeDevices'])->name('dashboard.active-devices');
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
Route::post('/photo/shoot/{Order_id}', function($order_id) {
    $order = \App\Models\Transaction::where('order_id', $order_id)->firstOrFail();

    if ($order->payment_status !== 'success') {
        return redirect('/photo')->with('error', 'Silahkan bayar terlebih dahulu.');
    }
    return view("Customer.shoot", compact('order'));
});

Route::post('/photo/upload', [PhotoSessionController::class, 'upload']);

Route::post('/photo/select-frame/{Order_id}', [PhotoSessionController::class, 'index']);
Route::post('/photo/save-frame/{Order_id}', [PhotoSessionController::class, 'saveFrame'])->name('photo.save-frame');
Route::get('/photo/success/{Order_id}', [PhotoSessionController::class, 'success'])->name('photo.success');

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