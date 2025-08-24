@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-gray-900">My Exchange Requests</h1>
        <div class="border-t border-dashed border-gray-300 mt-2"></div>
    </div>

    @if($hasApproved)
        <div class="mb-4 p-3 rounded bg-green-50 text-green-800 border border-green-200">
            Some of your exchange requests have been approved. Tap View to see details.
        </div>
    @endif
    @if($hasRejected)
        <div class="mb-4 p-3 rounded bg-red-50 text-red-800 border border-red-200">
            Some of your exchange requests were rejected.
        </div>
    @endif

    <div class="bg-white rounded-md border border-gray-200 shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">No</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">To Application</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Owner</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Route</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($requests as $i => $r)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 text-sm">{{ ($requests->currentPage()-1) * $requests->perPage() + $i + 1 }}</td>
                            <td class="px-4 py-2 text-sm">#{{ $r->application->tracking_code ?? $r->application_id }}</td>
                            <td class="px-4 py-2 text-sm">{{ $r->owner->name ?? '—' }}</td>
                            <td class="px-4 py-2 text-sm">{{ $r->application->fromRegion->name ?? '—' }} → {{ $r->application->toRegion->name ?? '—' }}</td>
                            <td class="px-4 py-2 text-sm">
                                @php
                                    $color = 'bg-gray-100 text-gray-800';
                                    if ($r->status === 'pending') $color = 'bg-yellow-100 text-yellow-800';
                                    if ($r->status === 'accepted') $color = 'bg-green-100 text-green-800';
                                    if ($r->status === 'rejected') $color = 'bg-red-100 text-red-800';
                                @endphp
                                <span class="px-2 py-1 rounded text-xs font-semibold {{ $color }}">{{ ucfirst($r->status) }}</span>
                            </td>
                            <td class="px-4 py-2 text-sm text-right">
                                <a href="{{ route('requests.show', $r) }}" class="inline-flex items-center px-3 py-1.5 border rounded hover:bg-gray-50">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">You have not sent any exchange requests yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t bg-gray-50">
            {{ $requests->links() }}
        </div>
    </div>
</div>
@endsection
