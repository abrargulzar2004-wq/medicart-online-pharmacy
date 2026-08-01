<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Mail;
// use App\Mail\OtpMail;

class AuthController extends Controller
{
    // ==========================
    // Show Login
    // ==========================
    public function showLogin()
    {
        return view('auth.login');
    }

    // ==========================
    // Login
    // ==========================
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return back()
                ->withErrors([
                    'email' => 'Invalid email or password.',
                ])
                ->withInput();
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('customer.dashboard');
    }

    // ==========================
    // Show Register
    // ==========================
    public function showRegister()
    {
        return view('auth.register');
    }

    // ==========================
    // Register
    // ==========================
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        // $otp = rand(100000, 999999);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'otp' => null, // OTP explicitly bypassed
            'email_verified_at' => now(), // Bypass OTP verification check
        ]);

        // Log::info("OTP for {$user->email}: {$otp}");

        /*
        try {
            Mail::to($user->email)->send(new OtpMail($otp));
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        session(['otp_email' => $user->email]);

        return redirect()
            ->route('auth.otp.show')
            ->with('success', 'Registration successful. Check your email for the OTP.');
        */

        // Bypass: Log the user in immediately
        Auth::login($user);

        return redirect()
            ->route('customer.dashboard')
            ->with('success', 'Registration successful.');
    }

    // ==========================
    // Show OTP
    // ==========================
    public function showOtp()
    {
        if (!session()->has('otp_email')) {
            return redirect()->route('auth.register');
        }

        return view('auth.otp');
    }

    // ==========================
    // Verify OTP
    // ==========================
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required',
        ]);

        $user = User::where('email', session('otp_email'))->first();

        if (!$user || $user->otp != $request->otp) {
            return back()->with('error', 'Invalid OTP.');
        }

        $user->update([
            'otp' => null,
            'email_verified_at' => now(),
        ]);

        Auth::login($user);

        session()->forget('otp_email');

        return redirect()->route('customer.dashboard');
    }

    // ==========================
    // Logout
    // ==========================
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}