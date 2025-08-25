<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    public function show(User $user)
    {
        $recentLogins = Log::where('user_id', $user->id)
            ->where('log_type', 'login')
            ->orderByDesc('created_at')
            ->limit(15)
            ->get();
        return view('admin.users.profile', compact('user', 'recentLogins'));
    }

    // View own admin profile at /admin/profile
    public function profile()
    {
        $user = Auth::user();
        $recentLogins = Log::where('user_id', $user->id)
            ->where('log_type', 'login')
            ->orderByDesc('created_at')
            ->limit(15)
            ->get();
        return view('admin.users.profile', compact('user', 'recentLogins'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'role' => 'required|string|max:50',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $data['avatar_path'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return back()->with('status', 'Profile updated successfully');
    }

    // Update own admin profile at /admin/profile
    public function profileUpdate(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'role' => 'required|string|max:50',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar_path'] = $path;
        }

        $user->update($data);

        return back()->with('status', 'Profile updated successfully.');
    }

    // Show change password form for admin
    public function password()
    {
        $user = Auth::user();
        return view('admin.users.password', compact('user'));
    }

    // Handle password update for admin
    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (! Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        return back()->with('password_updated', true);
    }
}
