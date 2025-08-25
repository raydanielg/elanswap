@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto p-4 sm:p-6">
    <h1 class="text-xl font-semibold text-gray-800">Regions</h1>

    <div class="mt-4 grid gap-4 sm:grid-cols-2">
        <div class="bg-white rounded-lg shadow ring-1 ring-black/5 p-4">
            <div class="text-sm font-medium text-gray-700">Add Region</div>
            <form class="mt-3 flex gap-2" method="post" action="{{ url('/admin/locations/regions') }}">
                @csrf
                <input name="name" class="flex-1 rounded-md border-gray-300 focus:ring-primary-500 focus:border-primary-500" placeholder="Region name" />
                <button class="px-3 py-2 rounded-md bg-primary-600 text-white">Add</button>
            </form>
            @if($errors->any())
                <div class="mt-2 text-xs text-rose-700">{{ $errors->first() }}</div>
            @endif
            @if(session('status'))
                <div class="mt-2 text-xs text-green-700">{{ session('status') }}</div>
            @endif
            @if(session('error'))
                <div class="mt-2 text-xs text-rose-700">{{ session('error') }}</div>
            @endif
        </div>
        <div class="bg-white rounded-lg shadow ring-1 ring-black/5 overflow-hidden">
            <div class="px-4 py-2 border-b text-sm font-medium text-gray-700">All Regions</div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left">Name</th>
                            <th class="px-3 py-2 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($regions as $r)
                        <tr>
                            <td class="px-3 py-2">{{ $r->name }}</td>
                            <td class="px-3 py-2 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <form method="post" action="{{ url('/admin/locations/regions/'.$r->id) }}" onsubmit="return confirm('Update region?');">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="name" value="{{ $r->name }}" class="rounded-md border-gray-300 focus:ring-primary-500 focus:border-primary-500 text-sm w-44" />
                                        <button class="px-2 py-1 rounded-md bg-gray-800 text-white text-xs">Save</button>
                                    </form>
                                    <form method="post" action="{{ url('/admin/locations/regions/'.$r->id) }}" onsubmit="return confirm('Delete region?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="p-1.5 rounded hover:bg-rose-50 text-rose-700" title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-3h4m-6 3h8"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-2">{{ $regions->links() }}</div>
        </div>
    </div>
</div>
@endsection
