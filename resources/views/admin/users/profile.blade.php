@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold">{{ request()->routeIs('admin.profile') ? 'My Profile' : 'Admin Profile' }}</h1>
        <a href="{{ url()->previous() }}" class="inline-flex items-center px-3 py-2 rounded-md bg-white text-gray-900 border border-gray-300 hover:bg-gray-50 text-sm">Back</a>
    </div>

    @if (session('status'))
        <div class="mb-4 p-3 rounded bg-green-50 text-green-800 border border-green-200">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-4 p-3 rounded bg-rose-50 text-rose-800 border border-rose-200">{{ $errors->first() }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow ring-1 ring-black/5 p-4">
            <h2 class="text-lg font-semibold mb-4">Profile Details</h2>
            <form method="POST" action="{{ request()->routeIs('admin.profile') ? route('admin.profile.update') : route('admin.users.profile.update', $user) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="flex items-center gap-4">
                    <div>
                        @if($user->avatar_path)
                            <img src="{{ asset('storage/'.$user->avatar_path) }}" alt="Avatar" class="h-20 w-20 rounded-full object-cover ring-1 ring-black/10">
                        @else
                            <div class="h-20 w-20 rounded-full bg-gray-100 ring-1 ring-black/10"></div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700">Upload Profile Image</label>
                        <input type="file" name="avatar" accept="image/*" class="mt-1 w-full text-sm" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input name="name" value="{{ old('name', $user->name) }}" required class="mt-1 w-full rounded-md border-gray-300 focus:ring-primary-500 focus:border-primary-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input name="phone" value="{{ old('phone', $user->phone) }}" required class="mt-1 w-full rounded-md border-gray-300 focus:ring-primary-500 focus:border-primary-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 w-full rounded-md border-gray-300 focus:ring-primary-500 focus:border-primary-500" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Role</label>
                        <input name="role" value="{{ old('role', $user->role) }}" class="mt-1 w-full rounded-md border-gray-300 focus:ring-primary-500 focus:border-primary-500" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Year Registered</label>
                        <input value="{{ optional($user->created_at)->format('Y') }}" disabled class="mt-1 w-full rounded-md border-gray-200 bg-gray-50 text-gray-500" />
                    </div>
                </div>

                <div class="pt-2">
                    <button class="inline-flex items-center px-4 py-2 rounded-md bg-primary-600 text-white text-sm hover:bg-primary-700">Save Changes</button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow ring-1 ring-black/5 p-4">
            <h2 class="text-lg font-semibold mb-4">Recent Logins</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-left text-gray-600">
                            <th class="px-3 py-2">When</th>
                            <th class="px-3 py-2">IP</th>
                            <th class="px-3 py-2">User Agent</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentLogins as $log)
                            <tr class="border-t">
                                <td class="px-3 py-2">{{ optional($log->created_at)->format('Y-m-d H:i') }}</td>
                                <td class="px-3 py-2">{{ $log->ip_address ?? '—' }}</td>
                                <td class="px-3 py-2 text-gray-600">{{ \Illuminate\Support\Str::limit($log->user_agent, 80) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-3 py-6 text-center text-gray-500">No login activity yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
