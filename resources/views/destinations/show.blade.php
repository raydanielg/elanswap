@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-gray-900">{{ $region->name }}</h1>
        <div class="border-t border-dashed border-gray-300 mt-2"></div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="p-5 flex items-center justify-between">
            <div class="text-sm md:text-base text-gray-700 font-medium">Applications to this region</div>
            <a href="{{ route('destinations.index') }}" class="text-sm md:text-base text-blue-600 hover:text-blue-700">Back to Destinations</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-gray-600 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-gray-600 uppercase">Applicant</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-gray-600 uppercase">From</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold tracking-wide text-gray-600 uppercase">To</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold tracking-wide text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($apps as $i => $app)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 text-sm md:text-base text-gray-800">{{ ($apps->currentPage() - 1) * $apps->perPage() + $i + 1 }}</td>
                            <td class="px-6 py-3 text-sm md:text-base text-gray-800 whitespace-nowrap">{{ $app->user->name ?? '—' }}</td>
                            <td class="px-6 py-3 text-sm md:text-base text-gray-800 whitespace-nowrap">{{ $app->fromRegion->name ?? '—' }}</td>
                            <td class="px-6 py-3 text-sm md:text-base text-gray-800 whitespace-nowrap">{{ $app->toRegion->name ?? '—' }}</td>
                            <td class="px-6 py-3 text-sm md:text-base min-w-[220px]">
                                <div class="flex items-center justify-end gap-3">
                                    @php $req = $requestedMap[$app->id] ?? null; @endphp
                                    @if(auth()->id() === ($app->user_id ?? null))
                                        <button type="button" class="inline-flex items-center px-3 py-1.5 bg-gray-200 text-gray-600 rounded cursor-not-allowed" disabled>
                                            Your application
                                        </button>
                                    @elseif($req)
                                        @php
                                            $label = match($req['status'] ?? 'pending') {
                                                'accepted' => 'Accepted exchange',
                                                'rejected' => 'Rejected exchange',
                                                default => 'Pending exchange',
                                            };
                                        @endphp
                                        <button type="button" class="inline-flex items-center px-3 py-1.5 bg-gray-200 text-gray-600 rounded cursor-not-allowed" disabled>
                                            {{ $label }}
                                        </button>
                                        <a href="{{ route('requests.show', $req['id']) }}" class="inline-flex items-center px-4 py-2 bg-blue-100 text-black border border-blue-700 rounded-md hover:bg-blue-200 font-semibold shadow-sm" data-requires-payment>
                                            View request
                                        </a>
                                    @else
                                        <button type="button" class="inline-flex items-center px-4 py-2 bg-blue-100 text-black border border-blue-700 rounded-md hover:bg-blue-200 font-semibold shadow-sm open-exchange" data-modal="modal-app-{{ $app->id }}" title="Request exchange" data-requires-payment>
                                            Exchange
                                        </button>
                                    @endif
                                </div>

                                @if(auth()->id() !== ($app->user_id ?? null))
                                <!-- Modal -->
                                <div id="modal-app-{{ $app->id }}" class="hidden fixed inset-0 z-50">
                                    <div class="absolute inset-0 bg-black/40" data-close="1"></div>
                                    <div class="relative z-10 flex items-center justify-center min-h-screen p-4">
                                        <div class="w-full max-w-lg bg-white rounded-lg shadow-lg border">
                                            <div class="flex items-center justify-between px-4 py-3 border-b">
                                                <h3 class="text-base font-semibold text-gray-800">Confirm Exchange Request</h3>
                                                <button type="button" class="p-1 rounded hover:bg-gray-100" data-close="1" aria-label="Close">✕</button>
                                            </div>
                                            <div class="px-4 py-3 space-y-3">
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                                                    <div class="p-3 rounded-md border bg-gray-50">
                                                        <div class="font-semibold text-gray-800 mb-1">Your Details</div>
                                                        <div>Name: {{ auth()->user()->name ?? '—' }}</div>
                                                        <div>Station: {{ auth()->user()->station->name ?? '—' }}</div>
                                                        <div>District: {{ auth()->user()->district->name ?? '—' }}</div>
                                                    </div>
                                                    <div class="p-3 rounded-md border bg-gray-50">
                                                        <div class="font-semibold text-gray-800 mb-1">Target Application</div>
                                                        <div>Owner: {{ $app->user->name ?? '—' }}</div>
                                                        <div>Station: {{ $app->user->station->name ?? '—' }}</div>
                                                        <div>District: {{ $app->user->district->name ?? '—' }}</div>
                                                        <div class="mt-1 text-gray-600">Route: {{ $app->fromRegion->name ?? '—' }} → {{ $app->toRegion->name ?? '—' }}</div>
                                                    </div>
                                                </div>
                                                <form method="POST" action="{{ route('exchange-requests.store') }}" class="space-y-2 exchange-form">
                                                    @csrf
                                                    <input type="hidden" name="application_id" value="{{ $app->id }}">
                                                    <div class="flex justify-end gap-2 pt-1">
                                                        <button type="button" class="px-3 py-1.5 border rounded text-sm hover:bg-gray-50" data-close="1">Cancel</button>
                                                        <button type="submit" class="send-btn inline-flex items-center px-4 py-2 bg-blue-100 text-black border border-blue-700 rounded-md text-sm hover:bg-blue-200 font-semibold" data-requires-payment>
                                                            <span class="btn-text">Confirm</span>
                                                            <span class="ml-2 hidden loader-ring" aria-hidden="true">
                                                                <span class="loader align-middle" style="width:16px;height:16px"></span>
                                                            </span>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">No applications found for this destination.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t bg-gray-50">
            {{ $apps->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  // Modal open/close
  document.querySelectorAll('.open-exchange').forEach(function(btn){
    btn.addEventListener('click', function(){
      const id = btn.getAttribute('data-modal');
      const m = document.getElementById(id);
      if (m) m.classList.remove('hidden');
    });
  });
  document.body.addEventListener('click', function(e){
    const close = e.target.closest('[data-close]');
    if (close) {
      const modal = close.closest('.fixed.inset-0.z-50');
      if (modal) modal.classList.add('hidden');
    }
  });

  document.querySelectorAll('form.exchange-form').forEach(function(form){
    form.addEventListener('submit', function(e){
      const btn = form.querySelector('.send-btn');
      if (!btn) return;
      // Prevent double submit
      if (btn.dataset.loading === '1') { e.preventDefault(); return false; }
      btn.dataset.loading = '1';
      btn.disabled = true;
      const text = btn.querySelector('.btn-text');
      const ring = btn.querySelector('.loader-ring');
      if (text) text.textContent = 'Sending...';
      if (ring) ring.classList.remove('hidden');
    }, { once: false });
  });
});
</script>
@endpush
