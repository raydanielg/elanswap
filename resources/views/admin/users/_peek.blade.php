<div class="p-4">
    <h3 class="text-lg font-semibold text-gray-800">User Preview</h3>
    <div class="mt-3 grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
        <div class="text-gray-500">Name</div>
        <div class="text-gray-900">{{ $user->name }}</div>

        <div class="text-gray-500">Phone</div>
        <div class="text-gray-900">{{ $user->phone }}</div>

        <div class="text-gray-500">Email</div>
        <div class="text-gray-900">{{ $user->email }}</div>

        <div class="text-gray-500">Role</div>
        <div class="text-gray-900">{{ ucfirst($user->role ?? 'user') }}</div>

        <div class="text-gray-500">Region</div>
        <div class="text-gray-900">{{ optional($user->region)->name }}</div>

        <div class="text-gray-500">Banned</div>
        <div class="text-gray-900">
            @if($user->is_banned)
                Yes @if($user->ban_reason) ({{ $user->ban_reason }}) @endif
            @else
                No
            @endif
        </div>
    </div>
</div>
