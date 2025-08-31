@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-gray-900">Destinations</h1>
        <div class="border-t border-dashed border-gray-300 mt-2"></div>
    </div>

    @if($regions->isEmpty())
        <div class="bg-white border rounded p-6 text-gray-600">No regions found.</div>
    @else
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($regions as $region)
                <a href="{{ route('destinations.show', $region) }}" class="block rounded-xl border border-gray-200 bg-white p-4 hover:shadow-md hover:border-indigo-200 transition transform hover:-translate-y-0.5" data-requires-payment>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-8 h-8 rounded-full bg-indigo-500 text-white flex items-center justify-center text-xs font-bold">
                                {{ strtoupper(mb_substr($region->name, 0, 1)) }}
                            </div>
                            <div class="truncate">
                                <div class="text-sm font-semibold text-gray-900 truncate">{{ $region->name }}</div>
                                <div class="text-xs text-gray-500">Destination region</div>
                            </div>
                        </div>
                        <span class="inline-flex items-center justify-center text-xs font-semibold px-2.5 py-1 rounded-full bg-yellow-100 text-yellow-800">
                            {{ $region->applications_to_count }}
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
