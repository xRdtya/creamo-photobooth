<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function signin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $merchant = Merchant::where('email', $credentials['email'])->first();

        if ($merchant) {
            // Jika password masih plain text (belum di-hash) atau kosong karena OAuth
            if (empty($merchant->password)) {
                return back()->withErrors([
                    'email' => 'Akun ini terdaftar melalui Google/Apple. Silakan gunakan tombol login sosial tersebut.',
                ])->onlyInput('email');
            } elseif (!str_starts_with($merchant->password, '$')) {
                // Jika password sama persis dengan plain text, otomatis kita hash
                if ($merchant->password === $credentials['password']) {
                    $merchant->password = Hash::make($credentials['password']);
                    $merchant->save();
                } else {
                    return back()->withErrors([
                        'email' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
                    ])->onlyInput('email');
                }
            }
        }

        if (Auth::guard('merchant')->attempt($credentials)) {
            $request->session()->regenerate();
            
            $merchant = Auth::guard('merchant')->user();
            return redirect()->intended('/dashboard')->with('success', 'Welcome back, ' . $merchant->business_name . '!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function signup(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:merchants',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $merchant = Merchant::create([
            'business_name' => $request->business_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'subscription' => 'inactive',
        ]);

        Auth::guard('merchant')->login($merchant);

        return redirect('/dashboard')->with('success', 'Registration successful! Welcome, ' . $merchant->business_name . '!');
    }
}
