@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto p-4 sm:p-6">
    <h1 class="text-xl font-semibold text-gray-800">Locations</h1>

    <div class="mt-4 grid gap-4 sm:grid-cols-3">
        <div class="bg-white rounded-lg shadow ring-1 ring-black/5 p-4">
            <div class="text-sm text-gray-500">Regions</div>
            <div class="mt-1 text-2xl font-semibold text-gray-900">{{ $counts['regions'] }}</div>
            <div class="mt-3">
                <a href="{{ url('/admin/locations/regions') }}" class="inline-flex items-center px-3 py-1.5 rounded-md bg-primary-600 text-white text-sm">Manage Regions</a>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow ring-1 ring-black/5 p-4">
            <div class="text-sm text-gray-500">Districts</div>
            <div class="mt-1 text-2xl font-semibold text-gray-900">{{ $counts['districts'] }}</div>
            <div class="mt-3">
                <a href="{{ url('/admin/locations/districts') }}" class="inline-flex items-center px-3 py-1.5 rounded-md bg-primary-600 text-white text-sm">Manage Districts</a>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow ring-1 ring-black/5 p-4">
            <div class="text-sm text-gray-500">Quick Add Region</div>
            <form class="mt-3 flex gap-2" method="post" action="{{ url('/admin/locations/regions') }}">
                @csrf
                <input name="name" class="flex-1 rounded-md border-gray-300 focus:ring-primary-500 focus:border-primary-500" placeholder="New region name" />
                <button class="px-3 py-2 rounded-md bg-primary-600 text-white">Add</button>
            </form>
            @if(session('status'))
            <div class="mt-2 text-xs text-green-700">{{ session('status') }}</div>
            @endif
            @if(session('error'))
            <div class="mt-2 text-xs text-rose-700">{{ session('error') }}</div>
            @endif
        </div>
    </div>

    <div class="mt-6 grid gap-4 sm:grid-cols-2">
        <div class="bg-white rounded-lg shadow ring-1 ring-black/5">
            <div class="px-4 py-2 border-b text-sm font-medium text-gray-700">Regions</div>
            <div class="p-2 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left">Name</th>
                            <th class="px-3 py-2 text-left">Districts</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($regions as $r)
                        <tr>
                            <td class="px-3 py-2">{{ $r->name }}</td>
                            <td class="px-3 py-2 text-gray-600">{{ $r->districts_count }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow ring-1 ring-black/5">
            <div class="px-4 py-2 border-b text-sm font-medium text-gray-700">Recent Districts</div>
            <div class="p-2 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left">Name</th>
                            <th class="px-3 py-2 text-left">Region</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($districts as $d)
                        <tr>
                            <td class="px-3 py-2">{{ $d->name }}</td>
                            <td class="px-3 py-2 text-gray-600">{{ $d->region->name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
