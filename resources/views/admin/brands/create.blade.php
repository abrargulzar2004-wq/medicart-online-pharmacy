@extends('admin.layout')
@section('content')
    <h1>Add Brand</h1>
    <form method="POST" action="{{ route('admin.brands.store') }}" class="admin-card">
        @csrf
        <div style="margin-bottom:15px;"><label>Name</label><input type="text" name="name" required style="width:100%; padding:10px;"></div>
        <div style="margin-bottom:20px;"><label><input type="checkbox" name="status" checked> Active</label></div>
        <button type="submit" class="admin-btn-primary" style="padding:10px 20px;">Save Brand</button>
    </form>
@endsection