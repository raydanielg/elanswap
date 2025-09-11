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
                    <select x-model.number="region_id" @change="onRegionChange()" class="w-full border rounded-lg px-3 py-2">
                        <option value="">-- Chagua Mkoa --</option>
                        <template x-for="r in regionsDisplay" :key="r.id">
                            <option :value="r.id" x-text="r.name"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Wilaya (District)</label>
                    <input type="hidden" name="district_id" :value="district_id || ''">
                    <select x-model.number="district_id" class="w-full border rounded-lg px-3 py-2" :disabled="!region_id">
                        <option value="">-- Chagua Wilaya --</option>
                        <template x-for="d in districtsDisplay" :key="d.id">
                            <option :value="d.id" x-text="d.name"></option>
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
                <!-- Qualification Level -->
                <div x-show="category_id" x-cloak>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ngazi ya Elimu</label>
                    <select name="qualification_level" x-model="qualification_level" class="w-full border rounded-lg px-3 py-2">
                        <option value="">-- Chagua Ngazi --</option>
                        <option value="degree">Degree</option>
                        <option value="diploma">Diploma</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Chagua kama una Degree au Diploma.</p>
                </div>
                <!-- Elimu specific: Subjects -->
                <div x-show="sectorName === 'elimu'" x-cloak class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Somo la Kwanza</label>
                        <input type="text" name="edu_subject_one" x-model="edu_subject_one" class="w-full border rounded-lg px-3 py-2" placeholder="Mfano: Hisabati">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Somo la Pili</label>
                        <input type="text" name="edu_subject_two" x-model="edu_subject_two" class="w-full border rounded-lg px-3 py-2" placeholder="Mfano: Fizikia">
                    </div>
                </div>
                <!-- Afya specific: Department -->
                <div x-show="sectorName === 'afya'" x-cloak>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Idara / Utengo (Afya)</label>
                    <input type="text" name="health_department" x-model="health_department" class="w-full border rounded-lg px-3 py-2" placeholder="Mfano: Uuguzi, Maabara, Dawa">
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
            region_id: null,
            district_id: null,
            category_id: null,
            qualification_level: '',
            edu_subject_one: '',
            edu_subject_two: '',
            health_department: '',
            station_name: '',
            error: '',
            get sectorName() {
                const cat = this.categories.find(c => c.id === this.category_id);
                return (cat && cat.name ? cat.name.toLowerCase() : '');
            },
            get progress() {
                let fields = [this.region_id, this.district_id, this.category_id];
                // base station name
                fields.push(this.station_name && this.station_name.trim().length > 2);
                // sector-specific
                if (this.category_id) {
                    fields.push(this.qualification_level);
                    if (this.sectorName === 'elimu') {
                        fields.push(this.edu_subject_one && this.edu_subject_one.trim().length > 1);
                        fields.push(this.edu_subject_two && this.edu_subject_two.trim().length > 1);
                    } else if (this.sectorName === 'afya') {
                        fields.push(this.health_department && this.health_department.trim().length > 1);
                    }
                }
                const filled = fields.filter(v => !!v).length;
                return Math.round((filled / fields.length) * 100);
            },
            get canSubmit() {
                if (!(this.region_id && this.district_id && this.category_id && this.station_name && this.station_name.trim().length > 2)) {
                    return false;
                }
                // sector rules
                if (!this.qualification_level) return false;
                if (this.sectorName === 'elimu') {
                    return !!(this.edu_subject_one && this.edu_subject_one.trim().length > 1 && this.edu_subject_two && this.edu_subject_two.trim().length > 1);
                }
                if (this.sectorName === 'afya') {
                    return !!(this.health_department && this.health_department.trim().length > 1);
                }
                return true;
            },
            init() {
                this.loadRegions();
                this.loadCategories();
                this.$watch('region_id', () => this.onRegionChange());
            },
            async loadRegions() {
                try {
                    const res = await fetch('{{ route('profile.regions') }}', { headers: { 'Accept': 'application/json' } });
                    if (!res.ok) throw new Error('Failed to load regions');
                    this.regionsDisplay = await res.json();
                } catch (_) { this.regionsDisplay = []; }
            },
            async onRegionChange() {
                this.district_id = null;
                this.districtsDisplay = [];
                if (!this.region_id) return;
                try {
                    const res = await fetch(`{{ route('profile.districts') }}?region_id=${this.region_id}`, { headers: { 'Accept': 'application/json' } });
                    if (!res.ok) throw new Error('Failed to load districts');
                    this.districtsDisplay = await res.json();
                } catch (_) { this.districtsDisplay = []; }
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
