<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(!Auth::user()->hasCompletedProfile())
                @include('profile.partials.complete-profile-form')

                <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-yellow-800 text-sm">
                    Tafadhali kamilisha wasifu kwanza. Sehemu zingine zimezimwa hadi umalize.
                </div>

                <div class="opacity-50 pointer-events-none select-none">
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
    
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <div class="max-w-xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
    
                    
                </div>
            @else
                <div class="p-4 bg-green-50 border border-green-200 rounded-lg text-green-800 text-sm">
                    Asante! Wasifu wako umekamilika.
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Payment CTA -->
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <h3 class="text-lg font-medium text-gray-900">Malipo ya Huduma</h3>
                        <p class="mt-1 text-sm text-gray-600">Ili kufungua huduma zote, tafadhali fanya malipo. Bonyeza kitufe hapa chini kuendelea.</p>
                        <div class="mt-4">
                            <button type="button" id="pay-now" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                Fanya Malipo
                            </button>
                            <div id="pay-waiting" class="hidden mt-3 p-2 text-sm bg-yellow-50 text-yellow-800 rounded">
                                Tunatayarisha malipo... tafadhali subiri sekunde chache.
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    (function () {
                        const btn = document.getElementById('pay-now');
                        const waiting = document.getElementById('pay-waiting');
                        const statusUrl = @json(route('billing.status'));
                        const createUrl = @json(route('billing.create'));
                        const dashboardUrl = @json(route('dashboard'));
                        const phone = @json(Auth::user()->phone ?? '');

                        function poll() {
                            fetch(statusUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
                                .then(r => r.json())
                                .then(data => {
                                    if (data && data.paid) {
                                        window.location.href = dashboardUrl;
                                    } else {
                                        setTimeout(poll, 3000);
                                    }
                                })
                                .catch(() => setTimeout(poll, 4000));
                        }

                        btn?.addEventListener('click', function () {
                            waiting.classList.remove('hidden');
                            btn.disabled = true;
                            const fd = new FormData();
                            fd.append('method', 'mpesa');
                            if (phone) fd.append('phone', phone);

                            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                            fetch(createUrl, {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': token,
                                },
                                body: fd,
                            })
                            .then(async (r) => {
                                if (!r.ok) {
                                    const err = await r.json().catch(() => ({}));
                                    throw new Error(err.message || 'Imeshindikana kuanzisha malipo');
                                }
                                return r.json();
                            })
                            .then((resp) => {
                                if (resp && resp.payment_url) {
                                    window.location.href = resp.payment_url; // Nenda moja kwa moja Selcom
                                    return;
                                }
                                setTimeout(poll, 1200);
                            })
                            .catch((e) => {
                                alert(e.message || 'Hitilafu imetokea. Jaribu tena.');
                                waiting.classList.add('hidden');
                                btn.disabled = false;
                            });
                        });
                    })();
                </script>

            @endif
        </div>
    </div>
</x-app-layout>
