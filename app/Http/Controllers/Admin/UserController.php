<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $role = (string) $request->get('role', '');
        $banned = (string) $request->get('banned', ''); // '', 'yes', 'no'

        $users = User::query()
            ->with(['region','district','category','station'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%$q%")
                        ->orWhere('email', 'like', "%$q%")
                        ->orWhere('phone', 'like', "%$q%");
                });
            })
            ->when(in_array($role, ['user','admin','superadmin'], true), function ($query) use ($role) {
                $query->where('role', $role);
            })
            ->when(in_array($banned, ['yes','no'], true), function ($query) use ($banned) {
                if ($banned === 'yes') { $query->where('is_banned', true); }
                else { $query->where('is_banned', false); }
            })
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        $counts = [
            'total' => User::count(),
            'banned' => User::where('is_banned', true)->count(),
            'admins' => User::whereIn('role', ['admin','superadmin'])->count(),
        ];

        return view('admin.users.index', compact('users','q','role','banned','counts'));
    }

    public function peek(User $user)
    {
        $user->loadMissing(['region','district','category','station']);
        return view('admin.users._peek', compact('user'));
    }

    public function ban(Request $request, User $user)
    {
        $data = $request->validate([
            'reason' => ['nullable','string','max:255'],
        ]);
        if (in_array($user->role ?? 'user', ['superadmin'], true)) {
            return back()->with('error', 'You cannot ban a superadmin.');
        }
        DB::transaction(function () use ($user, $data) {
            $user->is_banned = true;
            $user->banned_at = now();
            $user->ban_reason = $data['reason'] ?? null;
            $user->save();
        });
        return back()->with('success', 'User banned successfully.');
    }

    public function unban(User $user)
    {
        DB::transaction(function () use ($user) {
            $user->is_banned = false;
            $user->banned_at = null;
            $user->ban_reason = null;
            $user->save();
        });
        return back()->with('success', 'User unbanned successfully.');
    }

    public function destroy(User $user)
    {
        if (in_array($user->role ?? 'user', ['superadmin'], true)) {
            return back()->with('error', 'You cannot delete a superadmin.');
        }
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }

    public function resetPassword(User $user)
    {
        if (!$user->email) {
            return back()->with('error', 'User has no email on file.');
        }
        $status = Password::sendResetLink(['email' => $user->email]);
        return back()->with(
            $status === Password::RESET_LINK_SENT ? 'success' : 'error',
            __($status)
        );
    }
}
