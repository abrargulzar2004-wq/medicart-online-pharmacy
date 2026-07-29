@extends('admin.layout')
@section('content')
    <h1>Edit Brand</h1>
    <form method="POST" action="{{ route('admin.brands.update', $brand) }}" class="admin-card">
        @csrf @method('PUT')
        <div style="margin-bottom:15px;"><label>Name</label><input type="text" name="name" value="{{ $brand->name }}" required style="width:100%; padding:10px;"></div>
        <div style="margin-bottom:20px;"><label><input type="checkbox" name="status" {{ $brand->status ? 'checked' : '' }}> Active</label></div>
        <button type="submit" class="admin-btn-primary" style="padding:10px 20px;">Update Brand</button>
    </form>
@endsection