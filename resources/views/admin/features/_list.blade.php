<div class="relative overflow-x-auto bg-white border border-gray-200 shadow-sm sm:rounded-lg w-full">
    <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
                <th scope="col" class="p-4">
                    <div class="flex items-center">
                        <input id="checkbox-all" type="checkbox" class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-primary-500 focus:ring-2">
                        <label for="checkbox-all" class="sr-only">checkbox</label>
                    </div>
                </th>
                <th scope="col" class="px-6 py-3">Title</th>
                <th scope="col" class="px-6 py-3">Icon</th>
                <th scope="col" class="px-6 py-3">Sort</th>
                <th scope="col" class="px-6 py-3">Active</th>
                <th scope="col" class="px-6 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($features as $feature)
                <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                    <td class="w-4 p-4">
                        <div class="flex items-center">
                            <input id="checkbox-feature-{{ $feature->id }}" type="checkbox" class="row-check w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-primary-500 focus:ring-2">
                            <label for="checkbox-feature-{{ $feature->id }}" class="sr-only">checkbox</label>
                        </div>
                    </td>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        <div>{{ $feature->title }}</div>
                        <div class="text-gray-500 line-clamp-1">{{ Str::limit($feature->description, 100) }}</div>
                    </th>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $feature->icon ?: '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $feature->sort_order }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($feature->is_active)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200/60">Active</span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-gray-50 text-gray-700 ring-1 ring-gray-200/60">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center justify-end gap-3">
                            <form action="{{ route('admin.features.toggle', $feature) }}" method="POST">
                                @csrf
                                <button type="submit" title="{{ $feature->is_active ? 'Unpublish' : 'Publish' }}" aria-label="{{ $feature->is_active ? 'Unpublish' : 'Publish' }}" class="h-8 w-8 inline-flex items-center justify-center rounded border hover:bg-gray-50">
                                    @if($feature->is_active)
                                        <span class="material-symbols-outlined text-[18px] text-gray-700">visibility_off</span>
                                    @else
                                        <span class="material-symbols-outlined text-[18px] text-gray-700">visibility</span>
                                    @endif
                                </button>
                            </form>
                            <a href="{{ route('admin.features.edit', $feature) }}" title="Edit" aria-label="Edit" class="h-8 w-8 inline-flex items-center justify-center rounded border hover:bg-gray-50">
                                <span class="material-symbols-outlined text-[18px] text-gray-700">edit</span>
                            </a>
                            <form action="{{ route('admin.features.destroy', $feature) }}" method="POST" onsubmit="return confirm('Delete this announcement?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Delete" aria-label="Delete" class="h-8 w-8 inline-flex items-center justify-center rounded border hover:bg-red-50 text-red-600 border-red-200">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="px-6 py-6 text-center text-gray-500" colspan="6">No announcements yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-3">{{ $features->withQueryString()->links() }}</div>
</div>
