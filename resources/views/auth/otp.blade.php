@extends('auth.layout')
@section('title', 'Verify OTP')
@section('content')
    <h2>Enter OTP</h2>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif
    
    <form method="POST" action="{{ route('auth.otp.submit') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">OTP Code</label>
            <input type="text" name="otp" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;">Verify</button>
    </form>
@endsection