@extends('admin.layout')
@section('content')
    <h1>Add Category</h1>
    
    <form method="POST" action="{{ route('admin.categories.store') }}" class="admin-card" style="max-width:600px;">
        @csrf
        <div style="margin-bottom:15px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">Category Name</label>
            <input type="text" name="name" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;">
        </div>
        <div style="margin-bottom:15px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">Description</label>
            <textarea name="description" rows="4" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;"></textarea>
        </div>
        <div style="margin-bottom:20px;">
            <label style="display:flex; align-items:center; font-weight:bold;">
                <input type="checkbox" name="status" checked style="margin-right:10px;"> Active
            </label>
        </div>
        <button type="submit" class="admin-btn-primary" style="padding:10px 20px; font-size:16px;">Save Category</button>
    </form>
@endsection