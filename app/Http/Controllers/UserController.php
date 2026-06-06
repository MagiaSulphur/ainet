<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request): View
    {
$users = User::query()

    ->when($request->filled('search'), function ($query) use ($request) {
        $query->where(function ($query) use ($request) {
            $query->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('email', 'like', '%'.$request->search.'%');
        });
    })

    ->when($request->filled('type'), function ($query) use ($request) {
        $query->where('user_type', $request->type);
    })

    ->when($request->filled('blocked'), function ($query) use ($request) {
        $query->where('blocked', $request->blocked);
    })

    ->orderBy('name')
    ->paginate(20)
    ->withQueryString();

        return view('users.index', [
            'users' => $users,
        ]);
    }

    public function toggleBlocked(User $user): RedirectResponse
{
    // Evitar bloquearse a sí mismo
    if ($user->id === auth()->id()) {
        return back()->with('status', 'You cannot block yourself.');
    }

    $user->update([ 'blocked' => !$user->blocked, ]);

    return back()->with('status', 'User updated successfully.');
}

public function create()
{
    return view('users.create');
}

public function store(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'unique:users,email'],
        'password' => ['required', 'min:8'],
        'gender' => ['required', 'in:M,F'],
        'user_type' => ['required', 'in:A,F'],
    ]);

    User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'gender' => $validated['gender'],
        'user_type' => $validated['user_type'],
        'blocked' => false,
        'email_verified_at' => now(),
    ]);

    return redirect()
        ->route('users.index')
        ->with('status', 'User created successfully.');
}

public function destroy(User $user)
{
    if ($user->id === auth()->id()) {
        return back()->with(
            'status',
            'You cannot delete yourself.'
        );
    }

    if ($user->isCustomer()) {

        $hasOrders = $user->customer
            && $user->customer->orders()->exists();

        $hasImages = $user->customer
            && $user->customer->tshirtImages()->exists();

        // Si tiene historial -> Soft Delete
        if ($hasOrders || $hasImages) {

            $user->delete();

            return back()->with(
                'status',
                'Customer soft deleted.'
            );
        }

        // Cliente sin historial -> borrar Customer primero
        $user->customer?->delete();
    }

    // Employee o Admin
    $user->forceDelete();

    return back()->with(
        'status',
        'User deleted successfully.'
    );
}

public function edit(User $user)
{
    return view('users.edit', [
        'user' => $user,
    ]);
}

public function update(
    Request $request,
    User $user
): RedirectResponse {

    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => [
            'required',
            'email',
            'unique:users,email,'.$user->id,
        ],
        'gender' => ['required', 'in:M,F'],
        'user_type' => ['required', 'in:A,F,C'],
        'blocked' => ['nullable'],
    ]);

    $user->update([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'gender' => $validated['gender'],
        'user_type' => $validated['user_type'],
        'blocked' => $request->boolean('blocked'),
    ]);

    return redirect()
        ->route('users.index')
        ->with(
            'status',
            'User updated successfully.'
        );
}
}