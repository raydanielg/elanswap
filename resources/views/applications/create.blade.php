@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">New Application</h1>
        <p class="text-sm text-gray-600">Jaza maombi kwa hatua mbili: Thibitisha taarifa zako kisha chagua kituo unachotaka kwenda.</p>
    </div>

    <div x-data="applicationWizard()" x-init="init()" class="bg-white border rounded-md">
        <!-- Progress -->
        <div class="border-b px-4 py-3">
            <div class="flex items-center gap-2 text-sm">
                <div :class="step===1 ? 'text-primary-700 font-semibold' : 'text-gray-500'">1. Taarifa Zako</div>
                <div class="flex-1 h-px bg-gray-200"></div>
                <div :class="step===2 ? 'text-primary-700 font-semibold' : 'text-gray-500'">2. Chagua Unakoenda</div>
            </div>
        </div>

        <form method="POST" action="{{ route('applications.store') }}" class="p-4">
            @csrf

            <!-- Step 1: current profile snapshot -->
            <div x-show="step===1" x-cloak>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-600">Jina Kamili</label>
                        <input type="text" readonly value="{{ $user->name }}" class="mt-1 w-full border rounded px-3 py-2 bg-gray-50" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Namba ya Simu</label>
                        <input type="text" readonly value="{{ $user->phone }}" class="mt-1 w-full border rounded px-3 py-2 bg-gray-50" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Mkoa (Sasa)</label>
                        <input type="text" readonly value="{{ optional($user->region)->name }}" class="mt-1 w-full border rounded px-3 py-2 bg-gray-50" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Wilaya (Sasa)</label>
                        <input type="text" readonly value="{{ optional($user->district)->name }}" class="mt-1 w-full border rounded px-3 py-2 bg-gray-50" />
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm text-gray-600">Kituo cha Kazi (Sasa)</label>
                        <input type="text" readonly value="{{ optional($user->station)->name }}" class="mt-1 w-full border rounded px-3 py-2 bg-gray-50" />
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('applications.index') }}" class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-50">Cancel</a>
                    <button type="button" @click="next()" class="px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700">Confirm & Continue</button>
                </div>
            </div>

            <!-- Step 2: choose destination -->
            <div x-show="step===2" x-cloak>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-600">Mkoa Unaoenda</label>
                        <select x-model.number="to_region_id" @change="loadDistricts()" name="to_region_id" class="mt-1 w-full border rounded px-3 py-2" required>
                            <option value="" disabled selected>Chagua mkoa...</option>
                            <template x-for="r in regions" :key="r.id">
                                <option :value="r.id" x-text="r.name"></option>
                            </template>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Wilaya Unaoenda</label>
                        <select x-model.number="to_district_id" name="to_district_id" class="mt-1 w-full border rounded px-3 py-2" required :disabled="!to_region_id">
                            <option value="" disabled selected>Chagua wilaya...</option>
                            <template x-for="d in districts" :key="d.id">
                                <option :value="d.id" x-text="d.name"></option>
                            </template>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm text-gray-600">Sababu (hiari)</label>
                        <textarea name="reason" rows="4" class="mt-1 w-full border rounded px-3 py-2" placeholder="Andika sababu kama ipo..."></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-between gap-3">
                    <button type="button" @click="prev()" class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-50">Back</button>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function applicationWizard(){
    return {
        step: 1,
        regions: [],
        districts: [],
        to_region_id: null,
        to_district_id: null,
        init(){ this.fetchRegions(); },
        next(){ this.step = 2; },
        prev(){ this.step = 1; },
        async fetchRegions(){
            try{
                const res = await fetch('{{ route('profile.regions') }}');
                this.regions = await res.json();
            }catch(e){ console.error(e); }
        },
        async loadDistricts(){
            this.to_district_id = null;
            this.districts = [];
            if(!this.to_region_id) return;
            try{
                const url = new URL('{{ route('profile.districts') }}', window.location.origin);
                url.searchParams.set('region_id', this.to_region_id);
                const res = await fetch(url);
                this.districts = await res.json();
            }catch(e){ console.error(e); }
        }
    }
}
</script>
@endsection
