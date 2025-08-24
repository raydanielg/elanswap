@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-gray-900">{{ $region->name }}</h1>
        <div class="border-t border-dashed border-gray-300 mt-2"></div>
    </div>

    <div class="bg-white rounded-md border border-gray-200 shadow-sm">
        <div class="p-4 flex items-center justify-between">
            <div class="text-sm text-gray-600">Applications to this region</div>
            <a href="{{ route('destinations.index') }}" class="text-sm text-primary-600 hover:text-primary-700">Back to Destinations</a>
        </div>
        <div class="overflow-x-auto -mx-4 sm:mx-0">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">No</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Applicant</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">From</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">To</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($apps as $i => $app)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 text-sm text-gray-700">{{ ($apps->currentPage() - 1) * $apps->perPage() + $i + 1 }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ $app->user->name ?? '—' }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ $app->fromRegion->name ?? '—' }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ $app->toRegion->name ?? '—' }}</td>
                            <td class="px-4 py-2 text-sm">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('applications.show', $app) }}" class="inline-flex items-center px-2 py-1 border rounded hover:bg-gray-50" title="View">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-gray-700"><path d="M12 5c-7 0-10 7-10 7s3 7 10 7 10-7 10-7-3-7-10-7zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                    @if(auth()->id() !== ($app->user_id ?? null))
                                    <details class="relative">
                                        <summary class="list-none inline-flex items-center px-2 py-1 border rounded hover:bg-gray-50 cursor-pointer select-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-emerald-600"><path d="M7 10h10l-3.5-3.5 1.4-1.4L21 11l-6.1 5.9-1.4-1.4L17 12H7v-2zm10 4H7l3.5 3.5-1.4 1.4L3 13l6.1-5.9 1.4 1.4L7 10h10v4z"/></svg>
                                        </summary>
                                        <div class="absolute z-10 right-0 mt-2 w-80 bg-white border border-gray-200 rounded shadow-lg p-3">
                                            <form method="POST" action="{{ route('exchange-requests.store') }}" class="space-y-2">
                                                @csrf
                                                <input type="hidden" name="application_id" value="{{ $app->id }}">
                                                <label class="block text-xs font-medium text-gray-600">Select your application (optional)</label>
                                                <select name="requester_application_id" class="w-full border-gray-300 rounded text-sm">
                                                    <option value="">— None —</option>
                                                    @foreach($myPendingApps as $mine)
                                                        <option value="{{ $mine->id }}">#{{ $mine->tracking_code ?? $mine->id }} — {{ $mine->fromRegion->name ?? '-' }} → {{ $mine->toRegion->name ?? '-' }}</option>
                                                    @endforeach
                                                </select>
                                                <label class="block text-xs font-medium text-gray-600">Message (optional)</label>
                                                <textarea name="message" rows="2" class="w-full border-gray-300 rounded text-sm" placeholder="Write a short note..."></textarea>
                                                <div class="flex justify-end gap-2 pt-1">
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-emerald-600 text-white rounded text-sm hover:bg-emerald-700">Send</button>
                                                </div>
                                            </form>
                                        </div>
                                    </details>
                                    @endif
                                </div>
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
