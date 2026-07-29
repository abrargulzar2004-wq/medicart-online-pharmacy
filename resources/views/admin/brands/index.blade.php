@extends('admin.layout')
@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h1>Brands</h1>
        <a href="{{ route('admin.brands.create') }}" class="admin-btn-primary">+ Add Brand</a>
    </div>
    <table class="admin-card" style="width:100%; border-collapse:collapse; margin-top:20px; padding:0;">
        <thead>
            <tr style="background:#0F172A; color:#fff;">
                <th style="padding:15px; text-align:left;">ID</th>
                <th style="padding:15px; text-align:left;">Name</th>
                <th style="padding:15px; text-align:left;">Status</th>
                <th style="padding:15px; text-align:center;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($brands as $brand)
            <tr style="border-bottom:1px solid #E2E8F0;">
                <td style="padding:15px;">{{ $brand->id }}</td>
                <td style="padding:15px;">{{ $brand->name }}</td>
                <td style="padding:15px;">{{ $brand->status ? 'Active' : 'Inactive' }}</td>
                <td style="padding:15px; text-align:center;">
                    <a href="{{ route('admin.brands.edit', $brand) }}" class="admin-link">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection