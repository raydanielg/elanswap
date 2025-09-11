@php($u = Auth::user()->loadMissing(['region','district','category','station']))
@php($sector = strtolower((string) optional($u->category)->name))
<div class="bg-white shadow sm:rounded-lg overflow-hidden">
    <div class="px-4 py-5 sm:p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg leading-6 font-semibold text-gray-900">Muhtasari wa Wasifu</h3>
            @if(method_exists($u, 'hasPaid'))
                @php($paid = $u->hasPaid())
                <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold {{ $paid ? 'bg-green-100 text-green-800 ring-1 ring-green-200' : 'bg-yellow-100 text-yellow-800 ring-1 ring-yellow-200' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                        @if($paid)
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l3.999-4z" clip-rule="evenodd" />
                        @else
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9 7h2v5H9V7zm0 6h2v2H9v-2z" clip-rule="evenodd" />
                        @endif
                    </svg>
                    {{ $paid ? 'Paid' : 'Unpaid' }}
                </span>
            @endif
        </div>
        <dl class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
            <div>
                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Jina Kamili</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $u->name }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Namba ya Simu</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $u->phone }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Barua Pepe</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $u->email }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Mkoa</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ optional($u->region)->name }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Wilaya</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ optional($u->district)->name }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Sekta</dt>
                <dd class="mt-2">
                    @if($u->category)
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 ring-1 ring-blue-200">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a1 1 0 01.894.553l2 4A1 1 0 0114 8H6a1 1 0 01-.894-1.447l2-4A1 1 0 017 2h5zM5 10a1 1 0 011-1h10a1 1 0 011 1v9a3 3 0 01-3 3H8a3 3 0 01-3-3v-9z"/></svg>
                            {{ $u->category->name }}
                        </span>
                    @else
                        <span class="text-sm text-gray-500">—</span>
                    @endif
                </dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Kituo cha Kazi</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ optional($u->station)->name }}</dd>
            </div>
        </dl>

        <!-- Sector specific details -->
        @if($sector === 'elimu' || $sector === 'afya')
            <div class="mt-6 border-t pt-4">
                <h4 class="text-sm font-semibold text-gray-900 mb-3">Taarifa za Taaluma</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Ngazi ya Elimu</div>
                        <div class="mt-1">
                            @if($u->qualification_level)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-50 text-purple-700 ring-1 ring-purple-200">
                                    {{ ucwords($u->qualification_level) }}
                                </span>
                            @else
                                <span class="text-sm text-gray-500">—</span>
                            @endif
                        </div>
                    </div>

                    @if($sector === 'elimu')
                        <div class="sm:col-span-2">
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Masomo (Elimu)</div>
                            <div class="mt-1 flex flex-wrap gap-2">
                                @if($u->edu_subject_one)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200">{{ $u->edu_subject_one }}</span>
                                @endif
                                @if($u->edu_subject_two)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200">{{ $u->edu_subject_two }}</span>
                                @endif
                                @if(!($u->edu_subject_one || $u->edu_subject_two))
                                    <span class="text-sm text-gray-500">—</span>
                                @endif
                            </div>
                        </div>
                    @elseif($sector === 'afya')
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Idara / Utengo (Afya)</div>
                            <div class="mt-1">
                                @if($u->health_department)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-rose-50 text-rose-700 ring-1 ring-rose-200">{{ $u->health_department }}</span>
                                @else
                                    <span class="text-sm text-gray-500">—</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
