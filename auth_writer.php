<?php

$controllersDir = __DIR__ . '/app/Http/Controllers';
$viewsDir = __DIR__ . '/resources/views/auth';
if (!is_dir($viewsDir)) mkdir($viewsDir, 0777, true);

// AuthController
$authController = <<<PHP
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request \$request) {
        \$request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt(\$request->only('email', 'password'))) {
            \$request->session()->regenerate();
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }
            return redirect()->intended('/customer/dashboard');
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
    }

    public function showRegister() {
        return view('auth.register');
    }

    public function register(Request \$request) {
        \$request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);

        \$otp = rand(100000, 999999);

        \$user = User::create([
            'name' => \$request->name,
            'email' => \$request->email,
            'password' => Hash::make(\$request->password),
            'role' => 'customer',
            'otp' => \$otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        // Simulate sending OTP
        Log::info("OTP for {\$user->email} is \$otp");

        \$request->session()->put('verify_email', \$user->email);
        return redirect()->route('auth.otp.show')->with('success', 'Registration successful! Please enter the OTP sent to your email.');
    }

    public function showOtp() {
        if (!session('verify_email')) {
            return redirect()->route('auth.login');
        }
        return view('auth.otp');
    }

    public function verifyOtp(Request \$request) {
        \$request->validate(['otp' => 'required|numeric']);
        \$email = session('verify_email');
        \$user = User::where('email', \$email)->first();

        if (\$user && \$user->otp == \$request->otp && \$user->otp_expires_at > now()) {
            \$user->update([
                'email_verified_at' => now(),
                'otp' => null,
                'otp_expires_at' => null
            ]);
            
            Auth::login(\$user);
            \$request->session()->forget('verify_email');
            return redirect()->route('customer.dashboard')->with('success', 'Email verified successfully!');
        }

        return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
    }

    public function logout(Request \$request) {
        Auth::logout();
        \$request->session()->invalidate();
        \$request->session()->regenerateToken();
        return redirect('/');
    }
}
PHP;

file_put_contents($controllersDir . '/AuthController.php', $authController);

// Views
$layout = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - MediCart</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .auth-container { background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        .auth-container h2 { margin-top: 0; margin-bottom: 20px; color: #333; text-align: center; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; color: #555; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn { width: 100%; padding: 10px; background: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; font-weight: bold; }
        .btn:hover { background: #0056b3; }
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .alert-danger { background: #f8d7da; color: #721c24; }
        .alert-success { background: #d4edda; color: #155724; }
        .link { display: block; text-align: center; margin-top: 15px; color: #007bff; text-decoration: none; }
        .link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="auth-container">
        @yield('content')
    </div>
</body>
</html>
HTML;
file_put_contents($viewsDir . '/layout.blade.php', $layout);

$login = <<<HTML
@extends('auth.layout')
@section('title', 'Login')
@section('content')
    <h2>Login to MediCart</h2>
    
    @if(\$errors->any())
        <div class="alert alert-danger">{{ \$errors->first() }}</div>
    @endif
    
    <form method="POST" action="{{ route('auth.login.submit') }}">
        @csrf
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" required value="{{ old('email') }}">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit" class="btn">Login</button>
    </form>
    <a href="{{ route('auth.register') }}" class="link">Don't have an account? Register</a>
@endsection
HTML;
file_put_contents($viewsDir . '/login.blade.php', $login);

$register = <<<HTML
@extends('auth.layout')
@section('title', 'Register')
@section('content')
    <h2>Create Account</h2>
    
    @if(\$errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach(\$errors->all() as \$error)
                    <li>{{ \$error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form method="POST" action="{{ route('auth.register.submit') }}">
        @csrf
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" required value="{{ old('name') }}">
        </div>
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" required value="{{ old('email') }}">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" required>
        </div>
        <button type="submit" class="btn">Register</button>
    </form>
    <a href="{{ route('login') }}" class="link">Already have an account? Login</a>
@endsection
HTML;
file_put_contents($viewsDir . '/register.blade.php', $register);

$otp = <<<HTML
@extends('auth.layout')
@section('title', 'Verify OTP')
@section('content')
    <h2>Enter OTP</h2>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(\$errors->any())
        <div class="alert alert-danger">{{ \$errors->first() }}</div>
    @endif
    
    <form method="POST" action="{{ route('auth.otp.submit') }}">
        @csrf
        <div class="form-group">
            <label>OTP Code</label>
            <input type="text" name="otp" required>
        </div>
        <button type="submit" class="btn">Verify</button>
    </form>
@endsection
HTML;
file_put_contents($viewsDir . '/otp.blade.php', $otp);

// Create empty Middlewares and update web.php next...
echo "Auth scaffolding generated.\n";
