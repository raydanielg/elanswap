<div class="p-4">
    <div class="flex items-start justify-between">
        <h3 class="text-lg font-semibold text-gray-800">Application Preview</h3>
    </div>

    <div class="mt-3 grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
        <div class="text-gray-500">Applicant</div>
        <div class="text-gray-900">{{ optional($application->user)->name }}</div>

        <div class="text-gray-500">From</div>
        <div class="text-gray-900">{{ optional($application->fromRegion)->name }} → {{ optional($application->toRegion)->name }}</div>

        <div class="text-gray-500">Status</div>
        <div class="text-gray-900">{{ ucfirst($application->status) }}</div>

        <div class="text-gray-500">Exchanged</div>
        <div class="text-gray-900">
            @if($application->paired_application_id)
                Yes with {{ optional(optional($application->pairedApplication)->user)->name }}
            @else
                No
            @endif
        </div>
    </div>
</div>
