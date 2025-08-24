@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Application #{{ $application->id }}</h1>
            <div class="mt-1 flex items-center gap-3 text-sm text-gray-600">
                <span>Code: <span class="font-semibold text-gray-800">{{ $application->code }}</span></span>
                <span>•</span>
                <span>Submitted {{ optional($application->submitted_at ?? $application->created_at)->format('d M Y, H:i') }}</span>
            </div>
        </div>
        <a href="{{ route('applications.index') }}" class="px-3 py-2 border rounded text-gray-700 hover:bg-gray-50">Back</a>
    </div>

    <div class="bg-white border rounded-md divide-y">
        <div class="p-4">
            <h2 class="text-sm font-semibold text-gray-700 mb-3">Applicant</h2>
            <div class="grid sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <div class="text-gray-500">Name</div>
                    <div class="font-medium text-gray-900">{{ optional($application->user)->name }}</div>
                </div>
                <div>
                    <div class="text-gray-500">Phone</div>
                    <div class="font-medium text-gray-900">{{ optional($application->user)->phone }}</div>
                </div>
            </div>
        </div>
        <div class="p-4">
            <h2 class="text-sm font-semibold text-gray-700 mb-3">From (Current)</h2>
            <div class="grid sm:grid-cols-3 gap-4 text-sm">
                <div>
                    <div class="text-gray-500">Region</div>
                    <div class="font-medium text-gray-900">{{ optional($application->fromRegion)->name }}</div>
                </div>
                <div>
                    <div class="text-gray-500">District</div>
                    <div class="font-medium text-gray-900">{{ optional($application->fromDistrict)->name }}</div>
                </div>
                <div>
                    <div class="text-gray-500">Station</div>
                    <div class="font-medium text-gray-900">{{ optional($application->fromStation)->name }}</div>
                </div>
            </div>
        </div>
        <div class="p-4">
            <h2 class="text-sm font-semibold text-gray-700 mb-3">To (Requested)</h2>
            <div class="grid sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <div class="text-gray-500">Region</div>
                    <div class="font-medium text-gray-900">{{ optional($application->toRegion)->name }}</div>
                </div>
                <div>
                    <div class="text-gray-500">District</div>
                    <div class="font-medium text-gray-900">{{ optional($application->toDistrict)->name }}</div>
                </div>
            </div>
        </div>
        <div class="p-4">
            <h2 class="text-sm font-semibold text-gray-700 mb-3">Reason</h2>
            <div class="text-sm text-gray-900 whitespace-pre-line">{{ $application->reason ?: '—' }}</div>
        </div>
        @isset($matches)
        <div class="p-4">
            <h2 class="text-sm font-semibold text-gray-700 mb-3">Possible Exchange Matches</h2>
            @if($matches->isEmpty())
                <div class="text-sm text-gray-500">No matches found</div>
            @else
                <div class="space-y-2">
                    @foreach($matches as $m)
                        <div class="flex items-center justify-between gap-3 text-sm">
                            <div class="text-gray-800">#{{ $m->id }} — {{ optional($m->user)->name }}: {{ optional($m->fromRegion)->name }} → {{ optional($m->toRegion)->name }}</div>
                            @if(auth()->id() === $application->user_id && $application->status === 'pending')
                            <form action="{{ route('applications.approve.match', ['application' => $application->id, 'match' => $m->id]) }}" method="POST" onsubmit="return confirm('Approve with this match?');">
                                @csrf
                                <button class="px-3 py-1.5 bg-primary-600 text-white rounded hover:bg-primary-700" type="submit">Approve with this</button>
                            </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        @endisset
        <div class="p-4 flex items-center justify-between">
            <div>
                <span class="text-sm text-gray-500 mr-2">Status:</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">
                    {{ $application->status === 'pending' ? 'Received' : ucfirst($application->status) }}
                </span>
            </div>
            <div class="text-sm text-gray-500">Last updated {{ $application->updated_at->format('d M Y, H:i') }}</div>
        </div>
    </div>

    

    @if($application->status === 'approved' && $application->paired_application_id)
        <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded text-sm text-green-900">
            Paired with application #{{ $application->paired_application_id }}.
        </div>
    @endif

    @if(auth()->id() === $application->user_id && $application->status !== 'deletion_requested')
        <div class="mt-6 flex items-center justify-end">
            <form action="{{ route('applications.requestDeletion', $application) }}" method="POST" onsubmit="return confirm('Request deletion of this application?');">
                @csrf
                <button type="submit" class="px-3 py-2 border rounded hover:bg-gray-50 text-sm">Request deletion</button>
            </form>
        </div>
    @endif
</div>
@endsection
