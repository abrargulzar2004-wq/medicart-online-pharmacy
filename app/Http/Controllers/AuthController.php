<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('home');
        }

        return back()
            ->withErrors([
                'email' => 'Invalid email or password.'
            ])
            ->withInput();
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);

        $otp = rand(100000, 999999);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer'
        ]);

        $user->update([
            'otp' => $otp
        ]);

        Log::info("OTP for {$user->email} is {$otp}");

        // Send OTP Email (Do NOT hide errors)
        Mail::to($user->email)->send(new OtpMail($otp));

        session([
            'otp_email' => $user->email
        ]);

        return redirect()
            ->route('auth.otp.show')
            ->with('success', 'Registration successful. Check your email for the OTP.');
    }

    public function showOtp()
    {
        if (!session('otp_email')) {
            return redirect()->route('auth.register');
        }

        return view('auth.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required'
        ]);

        $email = session('otp_email');

        $user = User::where('email', $email)->first();

        if ($user && $user->otp == $request->otp) {

            $user->update([
                'email_verified_at' => now(),
                'otp' => null
            ]);

            Auth::login($user);

            session()->forget('otp_email');

            return redirect()->route('home');
        }

        return back()->with('error', 'Invalid OTP.');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('home');
    }
}