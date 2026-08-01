@extends('admin.layout')
@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h1>Categories</h1>
        <a href="{{ route('admin.categories.create') }}" class="admin-btn-primary">+ Add Category</a>
    </div>
    
    <table class="admin-card" style="width:100%; border-collapse:collapse; margin-top:20px; padding:0;">
        <thead>
            <tr style="background:#0F172A; color:#fff;">
                <th style="padding:15px; text-align:left;">ID</th>
                <th style="padding:15px; text-align:left;">Name</th>
                <th style="padding:15px; text-align:left;">Slug</th>
                <th style="padding:15px; text-align:left;">Status</th>
                <th style="padding:15px; text-align:center;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr style="border-bottom:1px solid #E2E8F0;">
                <td style="padding:15px;">{{ $category->id }}</td>
                <td style="padding:15px;">{{ $category->name }}</td>
                <td style="padding:15px;">{{ $category->slug }}</td>
                <td style="padding:15px;">{{ $category->status ? 'Active' : 'Inactive' }}</td>
                <td style="padding:15px; text-align:center;">
                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="admin-link" style="margin-right:10px;">Edit</a>
                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this category?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="color:#DC2626; text-decoration:none; font-weight:600; background:none; border:none; cursor:pointer; padding:0;">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection