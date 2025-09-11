@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-gray-900">{{ $region->name }}</h1>
        <div class="border-t border-dashed border-gray-300 mt-2"></div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="px-5 py-4 flex items-center justify-between">
            <div class="text-base md:text-lg text-gray-900 font-semibold">Applications to this region</div>
            <a href="{{ route('destinations.index') }}" class="inline-flex items-center gap-2 text-sm md:text-base text-blue-700 hover:text-blue-800">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M10.828 11H20a1 1 0 110 2h-9.172l3.536 3.536a1 1 0 11-1.414 1.414l-5.243-5.243a1 1 0 010-1.414l5.243-5.243a1 1 0 111.414 1.414L10.828 11z"/></svg>
                Back to Destinations
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 sticky top-0 z-10">
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
                            <td class="px-6 py-3 text-sm md:text-base text-gray-800">
                                <div class="font-medium text-gray-900">{{ $app->user->name ?? '—' }}</div>
                                <div class="mt-1 flex flex-wrap items-center gap-1">
                                    @if($app->user?->category?->name)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-blue-50 text-blue-700">{{ $app->user->category->name }}</span>
                                    @endif
                                    @if($app->user?->qualification_level)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-purple-50 text-purple-700">{{ ucwords($app->user->qualification_level) }}</span>
                                    @endif
                                    @if(strtolower((string) optional(optional($app->user)->category)->name) === 'elimu')
                                        @if($app->user?->edu_subject_one)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200">{{ $app->user->edu_subject_one }}</span>
                                        @endif
                                        @if($app->user?->edu_subject_two)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200">{{ $app->user->edu_subject_two }}</span>
                                        @endif
                                    @elseif(strtolower((string) optional(optional($app->user)->category)->name) === 'afya')
                                        @if($app->user?->health_department)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] bg-rose-50 text-rose-700 ring-1 ring-rose-200">{{ $app->user->health_department }}</span>
                                        @endif
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-3 text-sm md:text-base text-gray-800 whitespace-nowrap">{{ $app->fromRegion->name ?? '—' }}</td>
                            <td class="px-6 py-3 text-sm md:text-base text-gray-800 whitespace-nowrap">{{ $app->toRegion->name ?? '—' }}</td>
                            <td class="px-6 py-3 text-sm md:text-base min-w-[220px]">
                                <div class="flex items-center justify-end gap-3">
                                    @php
                                        $req = $requestedMap[$app->id] ?? null;
                                    @endphp
                                    @if(auth()->id() === ($app->user_id ?? null))
                                        <button type="button" class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-500 rounded cursor-not-allowed" disabled>
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
                                        <button type="button" class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-500 rounded cursor-not-allowed" disabled>
                                            {{ $label }}
                                        </button>
                                        <a href="{{ route('requests.show', $req['id']) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white text-blue-700 ring-1 ring-blue-200 rounded-md hover:bg-blue-50 font-semibold shadow-sm" data-requires-payment>
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 5c-7 0-10 7-10 7s3 7 10 7 10-7 10-7-3-7-10-7zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10z"/></svg>
                                            View request
                                        </a>
                                    @else
                                        <button type="button" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-semibold shadow-sm open-exchange" data-modal="modal-app-{{ $app->id }}" title="Request exchange" data-requires-payment>
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M7 7h11a1 1 0 0 1 0 2H9.414l2.293 2.293a1 1 0 1 1-1.414 1.414L6 8.414 10.293 4.12a1 1 0 1 1 1.414 1.414L9.414 7zM17 17H6a1 1 0 0 1 0-2h8.586l-2.293-2.293a1 1 0 1 1 1.414-1.414L18 15.586 13.707 19.88a1 1 0 0 1-1.414-1.414L15.586 17z"/></svg>
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
                                                        <div class="mt-1">Sector:
                                                            @if(auth()->user()?->category?->name)
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">{{ auth()->user()->category->name }}</span>
                                                            @else
                                                                —
                                                            @endif
                                                            @if(auth()->user()?->qualification_level)
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-50 text-purple-700">{{ ucwords(auth()->user()->qualification_level) }}</span>
                                                            @endif
                                                        </div>
                                                        <div class="mt-1">
                                                            @if(strtolower((string) optional(optional(auth()->user())->category)->name) === 'elimu')
                                                                @if(auth()->user()?->edu_subject_one)
                                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200">{{ auth()->user()->edu_subject_one }}</span>
                                                                @endif
                                                                @if(auth()->user()?->edu_subject_two)
                                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200">{{ auth()->user()->edu_subject_two }}</span>
                                                                @endif
                                                            @elseif(strtolower((string) optional(optional(auth()->user())->category)->name) === 'afya')
                                                                @if(auth()->user()?->health_department)
                                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 ring-1 ring-rose-200">{{ auth()->user()->health_department }}</span>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="p-3 rounded-md border bg-gray-50">
                                                        <div class="font-semibold text-gray-800 mb-1">Target Application</div>
                                                        <div>Owner: {{ $app->user->name ?? '—' }}</div>
                                                        <div>Station: {{ $app->user->station->name ?? '—' }}</div>
                                                        <div>District: {{ $app->user->district->name ?? '—' }}</div>
                                                        <div class="mt-1 text-gray-600">Route: {{ $app->fromRegion->name ?? '—' }} → {{ $app->toRegion->name ?? '—' }}</div>
                                                        <div class="mt-1">Sector:
                                                            @if($app->user?->category?->name)
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">{{ $app->user->category->name }}</span>
                                                            @else
                                                                —
                                                            @endif
                                                            @if($app->user?->qualification_level)
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-50 text-purple-700">{{ ucwords($app->user->qualification_level) }}</span>
                                                            @endif
                                                        </div>
                                                        <div class="mt-1">
                                                            @if(strtolower((string) optional(optional($app->user)->category)->name) === 'elimu')
                                                                @if($app->user?->edu_subject_one)
                                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200">{{ $app->user->edu_subject_one }}</span>
                                                                @endif
                                                                @if($app->user?->edu_subject_two)
                                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200">{{ $app->user->edu_subject_two }}</span>
                                                                @endif
                                                            @elseif(strtolower((string) optional(optional($app->user)->category)->name) === 'afya')
                                                                @if($app->user?->health_department)
                                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 ring-1 ring-rose-200">{{ $app->user->health_department }}</span>
                                                                @endif
                                                            @endif
                                                        </div>
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
