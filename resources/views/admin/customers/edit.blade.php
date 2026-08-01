@extends('admin.layout')

@section('content')
<div class="flex justify-between items-center" style="margin-bottom: 20px;">
    <h1>Edit Customer</h1>
</div>

<form method="POST" action="{{ route('admin.customers.update', $user->id) }}" class="admin-card" style="max-width:600px;">
    @csrf
    @method('PUT')
    
    <div style="margin-bottom:15px;">
        <label style="display:block; margin-bottom:5px; font-weight:bold;">Name</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;">
        @error('name') <span style="color:red;">{{ $message }}</span> @enderror
    </div>
    
    <div style="margin-bottom:15px;">
        <label style="display:block; margin-bottom:5px; font-weight:bold;">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;">
        @error('email') <span style="color:red;">{{ $message }}</span> @enderror
    </div>
    
    <button type="submit" class="admin-btn-primary" style="padding:10px 20px; font-size:16px;">Update Customer</button>
</form>
@endsection
