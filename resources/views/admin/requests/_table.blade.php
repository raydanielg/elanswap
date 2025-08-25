<div class="relative overflow-x-auto bg-white border border-gray-200 shadow-sm sm:rounded-lg w-full">
    <table class="w-full text-sm text-left text-gray-600">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Created</th>
                <th class="px-4 py-3">Requester</th>
                <th class="px-4 py-3">Owner</th>
                <th class="px-4 py-3">Target App</th>
                <th class="px-4 py-3">Message</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($requests as $req)
            <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                <td class="px-4 py-3 whitespace-nowrap">#{{ $req->id }}</td>
                <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-600">{{ $req->created_at?->format('Y-m-d H:i') }}</td>
                <td class="px-4 py-3">
                    <div class="font-medium text-gray-900">{{ $req->requester->name ?? '—' }}</div>
                    <div class="text-xs text-gray-500">ID: {{ $req->requester_id }}</div>
                </td>
                <td class="px-4 py-3">
                    <div class="font-medium text-gray-900">{{ $req->owner->name ?? '—' }}</div>
                    <div class="text-xs text-gray-500">ID: {{ $req->owner_id }}</div>
                </td>
                <td class="px-4 py-3">
                    @php($app = $req->application)
                    <div class="font-medium text-gray-900">{{ $app->code ?? ('App '.($app->id ?? '')) }}</div>
                    <div class="text-xs text-gray-500">{{ optional($app->fromRegion)->name }} → {{ optional($app->toRegion)->name }}</div>
                    @if($app)
                        <div class="mt-1">
                            <button type="button" data-peek-url="{{ route('applications.peek', $app) }}" class="text-xs text-primary-700 hover:underline">Open</button>
                        </div>
                    @endif
                </td>
                <td class="px-4 py-3 max-w-[280px]"><div class="line-clamp-2">{{ $req->message }}</div></td>
                <td class="px-4 py-3 whitespace-nowrap">
                    @php($s = $req->status)
                    @if($s==='pending')
                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-yellow-100 text-yellow-800">Pending</span>
                    @elseif($s==='accepted')
                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-green-100 text-green-800">Approved</span>
                    @elseif($s==='rejected')
                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-red-100 text-red-800">Rejected</span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-gray-100 text-gray-800">{{ ucfirst($s) }}</span>
                    @endif
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-right" x-data="{open:false}">
                    <div class="relative inline-block text-left">
                        <button @click="open = !open" @click.outside="open=false" class="px-2 py-1 border rounded hover:bg-gray-50">Actions ▾</button>
                        <div x-show="open" x-transition class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded shadow-md z-10">
                            <a class="block px-3 py-2 hover:bg-gray-50 text-sm" href="{{ route('admin.requests.show', $req) }}">View details</a>
                            @if($req->status==='pending')
                            <form method="post" action="{{ route('admin.requests.approve', $req) }}" onsubmit="return confirm('Approve this request?')">
                                @csrf
                                <button class="w-full text-left px-3 py-2 hover:bg-gray-50 text-sm text-green-700">Approve</button>
                            </form>
                            <form method="post" action="{{ route('admin.requests.reject', $req) }}" onsubmit="return confirm('Reject this request?')">
                                @csrf
                                <button class="w-full text-left px-3 py-2 hover:bg-gray-50 text-sm text-red-700">Reject</button>
                            </form>
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="px-6 py-10 text-center text-gray-500">No requests found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">
    {{ $requests->links() }}
</div>
