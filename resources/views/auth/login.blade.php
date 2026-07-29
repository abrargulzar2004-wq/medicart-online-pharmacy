@extends('auth.layout')
@section('title', 'Login')
@section('content')
    <h2>Login to MediCart</h2>
    
    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif
    
    <form method="POST" action="{{ route('auth.login.submit') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
        </div>
        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;">Login</button>
    </form>
    <a href="{{ route('auth.register') }}" class="link">Don't have an account? Register</a>
@endsection