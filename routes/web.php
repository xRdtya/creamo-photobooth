<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function() {
    return view("landing");
});

Route::get('/token', function() { 
    return array('csrf'=>csrf_token());
}); 