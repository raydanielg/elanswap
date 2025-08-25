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
        @isset($incoming)
        <div class="p-4">
            <h2 class="text-sm font-semibold text-gray-700 mb-3">Incoming Exchange Requests</h2>
            @if($incoming->isEmpty())
                <div class="text-sm text-gray-500">No incoming requests yet.</div>
            @else
                <div class="space-y-3">
                    @foreach($incoming as $req)
                        <div class="flex items-start justify-between gap-3 text-sm">
                            <div class="text-gray-800">
                                <div>
                                    <span class="text-gray-500">From:</span>
                                    <span class="font-medium">{{ $req->requester?->name ?? '—' }}</span>
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $req->status === 'accepted' ? 'bg-green-50 text-green-700' : ($req->status === 'rejected' ? 'bg-red-50 text-red-700' : 'bg-amber-50 text-amber-800') }}">{{ ucfirst($req->status) }}</span>
                                </div>
                                @if($req->status === 'accepted')
                                    <div class="text-gray-600 mt-1">
                                        {{ $req->requesterApplication?->fromRegion?->name }} → {{ $req->requesterApplication?->toRegion?->name }}
                                    </div>
                                @else
                                    <div class="text-gray-500 mt-1">Application details hidden until you accept.</div>
                                @endif
                                @if($req->message)
                                    <div class="text-gray-600 mt-1">“{{ $req->message }}”</div>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                @if($req->status === 'pending')
                                    <form id="approve-form-{{ $req->id }}" method="POST" action="{{ route('exchange-requests.accept', $req) }}">
                                        @csrf
                                        <button type="button" data-approve-form="approve-form-{{ $req->id }}" class="open-approve px-4 py-2 bg-blue-100 text-black border border-blue-700 rounded-md hover:bg-blue-200 font-semibold">Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('exchange-requests.reject', $req) }}" onsubmit="return confirm('Reject this exchange request?');">
                                        @csrf
                                        <button class="px-3 py-1.5 border rounded hover:bg-gray-50" type="submit">Reject</button>
                                    </form>
                                @endif
                            </div>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  let approveTargetFormId = null;
  const modal = document.getElementById('approve-modal');
  const overlay = document.getElementById('approve-overlay');
  const confirmBtn = document.getElementById('approve-confirm');
  const cancelBtns = document.querySelectorAll('[data-approve-cancel]');

  function openModal(formId){
    approveTargetFormId = formId;
    if (modal) modal.classList.remove('hidden');
  }
  function closeModal(){
    approveTargetFormId = null;
    if (modal) modal.classList.add('hidden');
  }

  document.querySelectorAll('.open-approve').forEach(btn => {
    btn.addEventListener('click', () => openModal(btn.getAttribute('data-approve-form')));
  });
  if (overlay) overlay.addEventListener('click', closeModal);
  cancelBtns.forEach(b => b.addEventListener('click', closeModal));
  if (confirmBtn) confirmBtn.addEventListener('click', function(){
    if (!approveTargetFormId) return;
    const form = document.getElementById(approveTargetFormId);
    if (form) form.submit();
  });
});
</script>

<div id="approve-modal" class="hidden fixed inset-0 z-50">
  <div id="approve-overlay" class="absolute inset-0 bg-black/40"></div>
  <div class="relative z-10 flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg border">
      <div class="px-4 py-3 border-b">
        <h3 class="text-base font-semibold text-gray-900">Approve Exchange Request</h3>
      </div>
      <div class="px-4 py-4 text-sm text-gray-800">
        Are you sure you want to approve this exchange request? The requester will be able to view your contact details.
      </div>
      <div class="px-4 py-3 border-t flex items-center justify-end gap-2">
        <button type="button" data-approve-cancel class="px-3 py-1.5 border rounded hover:bg-gray-50 text-sm">Cancel</button>
        <button id="approve-confirm" type="button" class="px-4 py-2 bg-blue-100 text-black border border-blue-700 rounded-md hover:bg-blue-200 font-semibold text-sm">Confirm Approve</button>
      </div>
    </div>
  </div>
</div>
@endpush
