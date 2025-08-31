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
                    
                </div>
            @else
                @php($hasPaid = Auth::user()->hasPaid())
                @if(!$hasPaid)
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <div class="max-w-2xl">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Wasifu wako umekamilika</h3>
                            <p class="text-sm text-gray-600 mb-4">Ili kuendelea kutumia huduma zote, tafadhali fanya malipo sasa. Utaelekezwa kiotomatiki kwenye ukurasa wa malipo ndani ya <span id="pay-countdown" class="font-semibold">2</span> sekunde.</p>

                            <div class="flex justify-end">
                                <button data-pay-cta data-url="{{ route('payment.index') }}" type="button"
                                    class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-white font-semibold bg-gradient-to-r from-red-500 to-rose-600 shadow-[0_8px_20px_-6px_rgba(244,63,94,0.65)] hover:from-red-600 hover:to-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 ring-1 ring-rose-200/60 transform hover:scale-[1.04] active:scale-100 transition duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Lipa sasa (<span class="count">2</span>)
                                </button>
                            </div>
                            <p class="mt-2 text-xs text-gray-500">Bofya kitufe kwenda mara moja, au subiri uhamishwe kiotomatiki.</p>
                        </div>
                    </div>

                    <script>
                        (function () {
                            const buttons = Array.from(document.querySelectorAll('[data-pay-cta]'));
                            const cd = document.getElementById('pay-countdown');
                            if (buttons.length === 0 || !cd) return;
                            const url = buttons[0].dataset.url;
                            const spans = buttons.map(b => b.querySelector('span.count')).filter(Boolean);
                            let n = 2;
                            const updateUI = () => {
                                spans.forEach(s => s.textContent = String(n));
                                cd.textContent = String(n);
                                buttons.forEach(b => b.classList.toggle('animate-pulse'));
                            };
                            updateUI();
                            const intId = setInterval(() => {
                                n--;
                                if (n <= 0) {
                                    clearInterval(intId);
                                    window.location.href = url;
                                    return;
                                }
                                updateUI();
                            }, 1000);
                            buttons.forEach(b => b.addEventListener('click', () => { window.location.href = url; }));
                        })();
                    </script>

                    <div class="p-4 sm:p-8">
                        @include('profile.partials.user-summary-card')
                        <div class="mt-4 flex justify-end">
                            <button data-pay-cta data-url="{{ route('payment.index') }}" type="button"
                                class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-white font-semibold bg-gradient-to-r from-red-500 to-rose-600 shadow-[0_8px_20px_-6px_rgba(244,63,94,0.65)] hover:from-red-600 hover:to-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 ring-1 ring-rose-200/60 transform hover:scale-[1.04] active:scale-100 transition duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Lipa sasa (<span class="count">2</span>)
                            </button>
                        </div>
                    </div>
                @else
                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg text-green-800 text-sm">
                        Asante! Wasifu wako umekamilika na malipo yamekamilika.
                    </div>

                    <div class="p-4 sm:p-8">
                        @include('profile.partials.user-summary-card')
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
