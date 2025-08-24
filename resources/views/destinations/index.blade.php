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
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($regions as $region)
                <a href="{{ route('destinations.show', $region) }}" class="block bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow p-5">
                    <div class="flex items-center justify-between">
                        <div class="text-gray-900 font-semibold">{{ $region->name }}</div>
                        <span class="inline-flex items-center justify-center text-xs font-medium px-2.5 py-1 rounded-full bg-blue-50 text-blue-700">
                            {{ $region->applications_to_count }}
                        </span>
                    </div>
                    <div class="mt-2 text-sm text-gray-500">Applications to this destination</div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
