@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-gray-900">Request Details</h1>
        <div class="border-t border-dashed border-gray-300 mt-2"></div>
    </div>

    @if($req->status === 'accepted')
        <div class="mb-4 p-3 rounded bg-green-50 text-green-800 border border-green-200">
            This exchange request has been approved.
        </div>
    @elseif($req->status === 'rejected')
        <div class="mb-4 p-3 rounded bg-red-50 text-red-800 border border-red-200">
            This exchange request was rejected.
        </div>
    @elseif($req->status === 'cancelled')
        <div class="mb-4 p-3 rounded bg-gray-50 text-gray-800 border border-gray-200">
            This exchange request was cancelled.
        </div>
    @endif

    <div class="bg-white rounded-md border border-gray-200 shadow-sm divide-y">
        <div class="p-4">
            <div class="flex items-center justify-between">
                <div>
                    @php
                        $color = 'bg-gray-100 text-gray-800';
                        if ($req->status === 'pending') $color = 'bg-yellow-100 text-yellow-800';
                        if ($req->status === 'accepted') $color = 'bg-green-100 text-green-800';
                        if ($req->status === 'rejected') $color = 'bg-red-100 text-red-800';
                    @endphp
                    <span class="px-2 py-1 rounded text-xs font-semibold {{ $color }}">{{ ucfirst($req->status) }}</span>
                </div>
                <div class="text-sm text-gray-500">Requested {{ $req->created_at->diffForHumans() }}</div>
            </div>
        </div>
        <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-1">Target Application</h3>
                <div class="text-sm text-gray-800">
                    <div>Ref: #{{ $req->application->tracking_code ?? $req->application_id }}</div>
                    <div>Owner: {{ $req->owner->name ?? '—' }}</div>
                    <div>Route: {{ $req->application->fromRegion->name ?? '—' }} → {{ $req->application->toRegion->name ?? '—' }}</div>
                    <div class="mt-2">
                        <a href="{{ route('applications.show', $req->application) }}" class="text-blue-600 hover:underline">Open application</a>
                    </div>
                </div>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-1">You (Requester)</h3>
                <div class="text-sm text-gray-800">
                    <div>Name: {{ $req->requester->name ?? '—' }}</div>
                    @if($req->requesterApplication)
                        <div class="mt-2">Your offered application:</div>
                        <div>Ref: #{{ $req->requesterApplication->tracking_code ?? $req->requester_application_id }}</div>
                        <div>Route: {{ $req->requesterApplication->fromRegion->name ?? '—' }} → {{ $req->requesterApplication->toRegion->name ?? '—' }}</div>
                        <div class="mt-2">
                            <a href="{{ route('applications.show', $req->requesterApplication) }}" class="text-blue-600 hover:underline">Open your application</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @if($req->message)
        <div class="p-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-1">Message</h3>
            <p class="text-sm text-gray-800 whitespace-pre-line">{{ $req->message }}</p>
        </div>
        @endif
        <div class="p-4 flex justify-between text-sm text-gray-600">
            <div>Created at: {{ $req->created_at->format('Y-m-d H:i') }}</div>
            <div>Last updated: {{ $req->updated_at->format('Y-m-d H:i') }}</div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('requests.index') }}" class="inline-flex items-center px-3 py-1.5 border rounded hover:bg-gray-50">Back to My Requests</a>
    </div>
</div>
@endsection
