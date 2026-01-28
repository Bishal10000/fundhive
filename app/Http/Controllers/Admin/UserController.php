<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    /**
     * List all users for admin management.
     */
    public function index()
    {
        $users = User::latest()->paginate(15);

        return view('admin.users', compact('users'));
    }

    /**
     * Toggle block/unblock a user.
     */
    public function toggleBlock(User $user)
    {
        if (auth()->check() && $user->id === auth()->id()) {
            return back()->with('error', 'You cannot block your own account.');
        }

        $user->is_blocked = ! $user->is_blocked;
        $user->save();

        return back()->with('status', $user->is_blocked ? 'User blocked.' : 'User unblocked.');
    }

    /**
     * Verify a user's profile (admin action).
     */
    public function verifyUser(User $user)
    {
        $user->is_verified = true;
        $user->verified_at = now();
        $user->save();

        return back()->with('status', "User {$user->name} has been verified successfully.");
    }

    /**
     * Unverify a user's profile (admin action).
     */
    public function unverifyUser(User $user)
    {
        $user->is_verified = false;
        $user->verified_at = null;
        $user->save();

        return back()->with('status', "User {$user->name} verification has been removed.");
    }
}
