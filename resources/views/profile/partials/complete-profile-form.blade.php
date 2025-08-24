<div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg border border-orange-200">
    <div class="mb-4 flex items-start">
        <svg class="w-6 h-6 text-orange-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Complete your profile</h3>
            <p class="text-sm text-gray-600">Jaza taarifa hizi ili kufungua huduma zote.</p>
        </div>
    </div>

    <div x-data="completeProfileWizard()">
        <form method="POST" action="{{ route('profile.complete.store') }}" @submit="submitting=true">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mkoa (Region)</label>
                    <input type="hidden" name="region_id" :value="region_id || ''">
                    <select x-model="region_name" @change="onRegionChange()" class="w-full border rounded-lg px-3 py-2">
                        <option value="">-- Chagua Mkoa --</option>
                        <template x-for="r in regionsDisplay" :key="r.name">
                            <option :value="r.name" x-text="r.name"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Wilaya (District)</label>
                    <input type="hidden" name="district_id" :value="district_id || ''">
                    <select x-model="district_name" class="w-full border rounded-lg px-3 py-2" :disabled="!region_name">
                        <option value="">-- Chagua Wilaya --</option>
                        <template x-for="d in districtsDisplay" :key="d.name">
                            <option :value="d.name" x-text="d.name"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sekta (Category)</label>
                    <select name="category_id" x-model.number="category_id" class="w-full border rounded-lg px-3 py-2">
                        <option value="">-- Chagua Sekta --</option>
                        <template x-for="c in categories" :key="c.id">
                            <option :value="c.id" x-text="c.name"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kituo cha Kazi (Andika jina)</label>
                    <input type="text" name="station_name" x-model="station_name" class="w-full border rounded-lg px-3 py-2" :disabled="!district_id" placeholder="Mfano: Shule ya Msingi Nyamagana">
                    <p class="text-xs text-gray-500 mt-1">Andika jina la kituo chako cha kazi. Tutaunda au kutumia kilichopo.</p>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between">
                <div class="text-xs text-gray-500" x-text="progress + '% complete'"></div>
                <button type="submit" class="px-4 py-2 rounded-lg bg-primary-600 text-white hover:bg-primary-700 disabled:opacity-50" :disabled="!canSubmit || submitting">
                    <span x-show="!submitting">Save</span>
                    <span x-show="submitting">Saving...</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Reuse the same Alpine component logic
    function completeProfileWizard() {
        return {
            show: true,
            submitting: false,
            regionsDisplay: [],
            districtsDisplay: [],
            categories: [],
            region_name: '',
            district_name: '',
            region_id: null,
            district_id: null,
            category_id: null,
            station_name: '',
            error: '',
            get progress() {
                let fields = [this.region_id, this.district_id, this.category_id, this.station_name && this.station_name.trim().length > 2];
                const filled = fields.filter(v => !!v).length;
                return Math.round((filled / fields.length) * 100);
            },
            get canSubmit() {
                return !!(this.region_id && this.district_id && this.category_id && this.station_name && this.station_name.trim().length > 2);
            },
            init() {
                this.loadRegions();
                this.loadCategories();
                this.$watch('district_name', (value) => {
                    if (this._districtIdByName && value) {
                        this.district_id = this._districtIdByName[value] || null;
                    }
                });
                this.$watch('region_name', () => {
                    if (this._regionIdByName) {
                        this.region_id = this._regionIdByName[this.region_name] || null;
                    }
                });
            },
            loadRegions() {
                const predefined = {
                    'Mwanza': ['Ilemela','Kwimba','Sengerema','Nyamagana','Magu','Ukerewe','Misungwi'],
                    'Dar es Salaam': ['Ilala','Kinondoni','Temeke','Ubungo','Kigamboni'],
                    'Arusha': ['Arusha City','Arusha','Karatu','Longido','Meru','Monduli','Ngorongoro'],
                };
                this.regionsDisplay = Object.keys(predefined).map(n => ({ name: n }));
                this._predefined = predefined;
                this.resolveRegionIds();
            },
            onRegionChange() {
                this.district_name = '';
                this.district_id = null;
                const list = this._predefined?.[this.region_name] || [];
                this.districtsDisplay = list.map(n => ({ name: n }));
                this.resolveDistrictIds();
            },
            async resolveRegionIds() {
                try {
                    const res = await fetch('{{ route('profile.regions') }}', { headers: { 'Accept': 'application/json' } });
                    if (!res.ok) return;
                    const apiRegions = await res.json();
                    const found = apiRegions.find(r => r.name === this.region_name);
                    this.region_id = found ? found.id : null;
                    this._regionIdByName = Object.fromEntries(apiRegions.map(r => [r.name, r.id]));
                } catch (_) { }
            },
            async resolveDistrictIds() {
                try {
                    const regionId = this._regionIdByName?.[this.region_name] || this.region_id;
                    if (!regionId) return;
                    const res = await fetch(`{{ route('profile.districts') }}?region_id=${regionId}`, { headers: { 'Accept': 'application/json' } });
                    if (!res.ok) return;
                    const apiDistricts = await res.json();
                    this._districtIdByName = Object.fromEntries(apiDistricts.map(d => [d.name, d.id]));
                    const did = this._districtIdByName[this.district_name];
                    this.district_id = did ?? null;
                } catch (_) { }
            },
            async loadCategories() {
                try {
                    const res = await fetch('{{ route('profile.categories') }}', { headers: { 'Accept': 'application/json' } });
                    if (!res.ok) throw new Error('Failed to load categories');
                    this.categories = await res.json();
                } catch (e) { }
            },
        }
    }
</script>
