@extends('layouts.admin')

@section('title', 'Request #'.$req->id)

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold text-gray-800">Request #{{ $req->id }}</h1>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.requests.index') }}" class="px-3 py-2 border rounded-md text-gray-700 hover:bg-gray-50">Back to all</a>
            @if($req->status==='pending')
            <form method="post" action="{{ route('admin.requests.approve', $req) }}" onsubmit="return confirm('Approve this request?')">
                @csrf
                <button class="px-3 py-2 rounded-md bg-green-600 text-white hover:bg-green-700">Approve</button>
            </form>
            <form method="post" action="{{ route('admin.requests.reject', $req) }}" onsubmit="return confirm('Reject this request?')">
                @csrf
                <button class="px-3 py-2 rounded-md bg-red-600 text-white hover:bg-red-700">Reject</button>
            </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Summary -->
        <div class="bg-white border border-gray-200 shadow-sm sm:rounded-lg p-4 space-y-3">
            <div>
                <span class="text-sm text-gray-500">Status</span>
                <div class="mt-1">
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
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <div class="text-gray-500">Created</div>
                    <div class="font-medium text-gray-900">{{ $req->created_at?->format('Y-m-d H:i') }}</div>
                </div>
                <div>
                    <div class="text-gray-500">Updated</div>
                    <div class="font-medium text-gray-900">{{ $req->updated_at?->format('Y-m-d H:i') }}</div>
                </div>
            </div>
            @if($req->message)
            <div>
                <div class="text-sm text-gray-500">Message</div>
                <div class="mt-1 text-gray-800">{{ $req->message }}</div>
            </div>
            @endif
        </div>

        <!-- Routing / Who -->
        <div class="bg-white border border-gray-200 shadow-sm sm:rounded-lg p-4 space-y-4">
            <div>
                <div class="text-sm text-gray-500">Requested By</div>
                <div class="mt-1 font-medium text-gray-900">{{ $req->requester->name ?? '—' }} <span class="text-gray-500">(ID: {{ $req->requester_id }})</span></div>
                @if($req->requester)
                <dl class="mt-2 grid grid-cols-2 gap-x-4 gap-y-1 text-sm">
                    <dt class="text-gray-500">Phone</dt><dd class="text-gray-900">{{ $req->requester->phone }}</dd>
                    <dt class="text-gray-500">Email</dt><dd class="text-gray-900">{{ $req->requester->email }}</dd>
                    <dt class="text-gray-500">Region</dt><dd class="text-gray-900">{{ optional($req->requester->region)->name }}</dd>
                    <dt class="text-gray-500">District</dt><dd class="text-gray-900">{{ optional($req->requester->district)->name }}</dd>
                    <dt class="text-gray-500">Category</dt><dd class="text-gray-900">{{ optional($req->requester->category)->name }}</dd>
                    <dt class="text-gray-500">Station</dt><dd class="text-gray-900">{{ optional($req->requester->station)->name }}</dd>
                </dl>
                @endif
            </div>
            <div>
                <div class="text-sm text-gray-500">Goes To (Owner)</div>
                <div class="mt-1 font-medium text-gray-900">{{ $req->owner->name ?? '—' }} <span class="text-gray-500">(ID: {{ $req->owner_id }})</span></div>
            </div>
        </div>
    </div>

    <!-- Applications -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="bg-white border border-gray-200 shadow-sm sm:rounded-lg p-4">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-700">Target Application</h2>
                @if($req->application)
                    <button type="button" class="text-sm text-primary-700 hover:underline" data-peek-url="{{ route('applications.peek', $req->application) }}">Open</button>
                @endif
            </div>
            @if($req->application)
            <dl class="mt-2 text-sm grid grid-cols-2 gap-x-4 gap-y-2">
                <dt class="text-gray-500">Code</dt><dd class="text-gray-900">{{ $req->application->code ?? ('App '.$req->application->id) }}</dd>
                <dt class="text-gray-500">From</dt><dd class="text-gray-900">{{ optional($req->application->fromRegion)->name }}</dd>
                <dt class="text-gray-500">To</dt><dd class="text-gray-900">{{ optional($req->application->toRegion)->name }}</dd>
                <dt class="text-gray-500">User</dt><dd class="text-gray-900">{{ optional($req->application->user)->name }}</dd>
            </dl>
            @else
                <div class="mt-2 text-gray-500 text-sm">—</div>
            @endif
        </div>
        <div class="bg-white border border-gray-200 shadow-sm sm:rounded-lg p-4">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-700">Requester Application</h2>
                @if($req->requesterApplication)
                    <button type="button" class="text-sm text-primary-700 hover:underline" data-peek-url="{{ route('applications.peek', $req->requesterApplication) }}">Open</button>
                @endif
            </div>
            @if($req->requesterApplication)
            <dl class="mt-2 text-sm grid grid-cols-2 gap-x-4 gap-y-2">
                <dt class="text-gray-500">Code</dt><dd class="text-gray-900">{{ $req->requesterApplication->code ?? ('App '.$req->requesterApplication->id) }}</dd>
                <dt class="text-gray-500">From</dt><dd class="text-gray-900">{{ optional($req->requesterApplication->fromRegion)->name }}</dd>
                <dt class="text-gray-500">To</dt><dd class="text-gray-900">{{ optional($req->requesterApplication->toRegion)->name }}</dd>
                <dt class="text-gray-500">User</dt><dd class="text-gray-900">{{ optional($req->requesterApplication->user)->name }}</dd>
            </dl>
            @else
                <div class="mt-2 text-gray-500 text-sm">—</div>
            @endif
        </div>
    </div>

    <!-- Modal -->
    <div id="peek-modal" class="fixed inset-0 z-[70] hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="absolute inset-0 flex items-start sm:items-center justify-center p-4">
            <div class="bg-white w-full max-w-2xl rounded-lg shadow-lg overflow-hidden">
                <div class="flex items-center justify-between px-4 py-2 border-b">
                    <div class="text-sm font-medium text-gray-700">Application Preview</div>
                    <button type="button" id="peek-close" class="p-1 rounded hover:bg-gray-100">✕</button>
                </div>
                <div id="peek-body" class="max-h-[70vh] overflow-auto"></div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('peek-modal');
    const modalBody = document.getElementById('peek-body');
    const modalClose = document.getElementById('peek-close');
    const openModal = async (url) => {
        if (!url) return;
        modal.classList.remove('hidden');
        modalBody.innerHTML = '<div class="p-4 text-sm text-gray-500">Loading...</div>';
        try {
            const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const html = await res.text();
            modalBody.innerHTML = html;
        } catch (err) {
            modalBody.innerHTML = '<div class="p-4 text-sm text-red-600">Failed to load preview.</div>';
        }
    };
    const closeModal = () => { modal.classList.add('hidden'); modalBody.innerHTML = ''; };
    modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });
    modalClose.addEventListener('click', closeModal);

    document.querySelectorAll('[data-peek-url]').forEach(btn => {
        btn.addEventListener('click', () => openModal(btn.getAttribute('data-peek-url')));
    });
});
</script>
@endpush
@endsection
