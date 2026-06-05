<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMerchantSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $merchant = Auth::guard('merchant')->user();

        if (!$merchant || !$merchant->hasActiveSubscription()) {
            return redirect()->route('landing');
        }

        return $next($request);
    }
}
