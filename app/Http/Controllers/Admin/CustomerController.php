<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'customer')->latest()->paginate(10);
        return view('admin.customers.index', compact('customers'));
    }

    public function edit(User $user)
    {
        if ($user->role !== 'customer') {
            abort(403);
        }
        return view('admin.customers.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->role !== 'customer') {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only(['name', 'email']));

        return redirect()->route('admin.customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->role !== 'customer') {
            abort(403);
        }

        // Validate before deleting if orders exist
        if (\App\Models\Order::where('user_id', $user->id)->exists()) {
            return back()->with('error', 'Cannot delete customer because they have existing orders.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($user) {
            $user->delete();
        });

        return redirect()->route('admin.customers.index')->with('success', 'Customer deleted successfully.');
    }
}
