<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();

        $users = User::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'search' => $search,
        ]);
    }

    public function show(User $user): View
    {
        $user->load(['store.products', 'courses', 'posts', 'orders', 'ownedGroups', 'organizedEvents', 'ridesOffered', 'ads']);

        return view('admin.users.show', [
            'user' => $user,
        ]);
    }

    public function toggleAdmin(User $user): RedirectResponse
    {
        $user->update(['role' => $user->isAdmin() ? 'member' : 'admin']);

        return back()->with('status', 'role-updated');
    }

    public function suspend(User $user): RedirectResponse
    {
        $user->update(['is_suspended' => true]);

        return back()->with('status', 'user-suspended');
    }

    public function unsuspend(User $user): RedirectResponse
    {
        $user->update(['is_suspended' => false]);

        return back()->with('status', 'user-unsuspended');
    }

    public function updateSellerStatus(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'seller_status' => ['required', 'in:none,pending,approved,rejected'],
        ]);

        $user->update([
            'is_seller' => $data['seller_status'] !== 'none',
            'seller_status' => $data['seller_status'],
        ]);

        return back()->with('status', 'seller-status-updated');
    }

    public function updateTutorStatus(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'tutor_status' => ['required', 'in:none,pending,approved,rejected'],
        ]);

        $user->update([
            'is_tutor' => $data['tutor_status'] !== 'none',
            'tutor_status' => $data['tutor_status'],
        ]);

        return back()->with('status', 'tutor-status-updated');
    }
}
