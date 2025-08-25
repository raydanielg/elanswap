<div class="p-4">
    <div class="flex items-start justify-between">
        <h3 class="text-lg font-semibold text-gray-800">Application Preview</h3>
    </div>
    <div class="mt-3 grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
        <div class="text-gray-500">Code</div>
        <div class="text-gray-900">{{ $application->code ?? ('App '.$application->id) }}</div>
        <div class="text-gray-500">User</div>
        <div class="text-gray-900">{{ optional($application->user)->name }}</div>
        <div class="text-gray-500">From Region</div>
        <div class="text-gray-900">{{ optional($application->fromRegion)->name }}</div>
        <div class="text-gray-500">From District</div>
        <div class="text-gray-900">{{ optional($application->fromDistrict)->name }}</div>
        <div class="text-gray-500">From Station</div>
        <div class="text-gray-900">{{ optional($application->fromStation)->name }}</div>
        <div class="text-gray-500">To Region</div>
        <div class="text-gray-900">{{ optional($application->toRegion)->name }}</div>
        <div class="text-gray-500">To District</div>
        <div class="text-gray-900">{{ optional($application->toDistrict)->name }}</div>
        <div class="text-gray-500">Status</div>
        <div class="text-gray-900">{{ ucfirst($application->status) }}</div>
        @if($application->reason)
            <div class="text-gray-500">Reason</div>
            <div class="text-gray-900">{{ $application->reason }}</div>
        @endif
    </div>
    <div class="mt-4 text-right">
        <a href="{{ route('applications.show', $application) }}" class="inline-flex items-center px-3 py-2 text-sm rounded-md border border-gray-300 hover:bg-gray-50">Open full page</a>
    </div>
</div>
