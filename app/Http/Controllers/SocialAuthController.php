<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;


class SocialAuthController extends Controller
{
    /**
     * Dapatkan base URL yang benar berdasarkan host request.
     * TrustProxies(at: '*') di bootstrap/app.php sudah memastikan
     * X-Forwarded-Host dan X-Forwarded-Proto dari ngrok terbaca dengan benar,
     * sehingga getSchemeAndHttpHost() mengembalikan URL ngrok yang tepat.
     */
    private function getBaseUrl(): string
    {
        return request()->getSchemeAndHttpHost();
    }

    /**
     * Redirect user to Google for authentication.
     */
    public function redirectToGoogle()
    {
        $callbackUrl = $this->getBaseUrl() . '/auth/google/callback';

        return Socialite::driver('google')
            ->redirectUrl($callbackUrl)
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    /**
     * Handle the callback from Google after authentication.
     */
    public function handleGoogleCallback()
    {
        try {
            $callbackUrl = $this->getBaseUrl() . '/auth/google/callback';

            $googleUser = Socialite::driver('google')
                ->redirectUrl($callbackUrl)
                ->stateless()
                ->user();

            // Find existing merchant by google_id or email
            $merchant = Merchant::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($merchant) {
                // Update google_id and avatar if not set yet
                $merchant->update([
                    'google_id' => $googleUser->getId(),
                    'avatar'    => $googleUser->getAvatar(),
                ]);
            } else {
                // Create new merchant account from Google data
                $merchant = Merchant::create([
                    'business_name' => $googleUser->getName(),
                    'email'         => $googleUser->getEmail(),
                    'google_id'     => $googleUser->getId(),
                    'avatar'        => $googleUser->getAvatar(),
                    'password'      => null,
                    'subscription'  => 'inactive',
                ]);
            }

            // Log in the merchant using the 'merchant' guard
            Auth::guard('merchant')->login($merchant, true);

            return redirect('/')->with('success', 'Selamat datang, ' . $merchant->business_name . '!');

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect('/signin')->with('error', 'Login dengan Google gagal: ' . $e->getMessage());
        }
    }

    /**
     * Redirect user to Apple for authentication.
     */
    public function redirectToApple()
    {
        $callbackUrl = $this->getBaseUrl() . '/auth/apple/callback';

        return Socialite::driver('apple')
            ->redirectUrl($callbackUrl)
            ->redirect();
    }

    /**
     * Handle the callback from Apple after authentication.
     */
    public function handleAppleCallback(Request $request)
    {
        try {
            $callbackUrl = $this->getBaseUrl() . '/auth/apple/callback';

            $appleUser = Socialite::driver('apple')
                ->redirectUrl($callbackUrl)
                ->stateless()
                ->user();


            // Find existing merchant by apple_id or email
            $merchant = Merchant::where('apple_id', $appleUser->getId())
                ->orWhere('email', $appleUser->getEmail())
                ->first();

            if ($merchant) {
                // Update apple_id if not set yet
                $merchant->update([
                    'apple_id' => $appleUser->getId(),
                ]);
            } else {
                // Create new merchant account from Apple data
                // Apple sometimes only returns the name on the very first login
                $name = $appleUser->getName();
                if (!$name || trim($name) === '') {
                    $name = 'Apple User';
                }

                $merchant = Merchant::create([
                    'business_name' => $name,
                    'email'         => $appleUser->getEmail(),
                    'apple_id'      => $appleUser->getId(),
                    'avatar'        => null,
                    'password'      => null,
                    'subscription'  => 'inactive',
                ]);
            }

            // Log in the merchant using the 'merchant' guard
            Auth::guard('merchant')->login($merchant, true);

            return redirect('/')->with('success', 'Selamat datang, ' . $merchant->business_name . '!');

        } catch (\Exception $e) {
            return redirect('/signin')->with('error', 'Login dengan Apple gagal. Silakan coba lagi.');
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('merchant')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
