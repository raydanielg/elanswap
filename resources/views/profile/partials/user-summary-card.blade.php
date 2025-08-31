@php($u = Auth::user()->loadMissing(['region','district','category','station']))
<div class="bg-white shadow sm:rounded-lg overflow-hidden">
    <div class="px-4 py-5 sm:p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Muhtasari wa Wasifu</h3>
            @if(method_exists($u, 'hasPaid'))
                @php($paid = $u->hasPaid())
                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $paid ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ $paid ? 'Paid' : 'Unpaid' }}
                </span>
            @endif
        </div>
        <dl class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
            <div>
                <dt class="text-sm font-medium text-gray-500">Jina Kamili</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $u->name }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Namba ya Simu</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $u->phone }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Barua Pepe</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $u->email }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Mkoa</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ optional($u->region)->name }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Wilaya</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ optional($u->district)->name }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Sekta</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ optional($u->category)->name }}</dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-gray-500">Kituo cha Kazi</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ optional($u->station)->name }}</dd>
            </div>
        </dl>
    </div>
</div>
