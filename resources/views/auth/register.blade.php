<x-guest-layout>
    <h2 class="text-2xl font-bold text-center text-gray-900 mb-6">Create your Elanswap account</h2>

    @php($__appHost = parse_url(config('app.url'), PHP_URL_HOST) ?? request()->getHost())
    <form id="registerForm" method="POST" action="{{ route('register') }}" class="space-y-4" data-app-host="{{ $__appHost }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input 
                id="name" 
                class="block mt-1 w-full" 
                type="text" 
                name="name" 
                :value="old('name')" 
                required 
                autofocus 
                autocomplete="name" 
                placeholder="John Doe"
            />
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Phone Number -->
        <div>
            <x-input-label for="phone" :value="__('Phone Number')" />
            <div class="relative">
                <x-text-input 
                    id="phone" 
                    class="block mt-1 w-full" 
                    type="tel" 
                    name="phone" 
                    :value="old('phone')" 
                    required 
                    autocomplete="tel"
                    inputmode="numeric"
                    maxlength="16"
                    placeholder="0742710054"
                />
            </div>
            <p class="mt-1 text-xs text-gray-500">Unaweza kuandika kama: <span class="font-medium">0742 710 054</span> au <span class="font-medium">255742710054</span> au <span class="font-medium">742710054</span>. Tutarekebisha kiotomatiki.</p>
            <p id="phoneFormatError" class="mt-1 text-sm text-red-600 hidden" aria-live="polite"></p>
            @error('phone')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email (hidden, auto-generated from phone + host) -->
        <input type="hidden" id="email" name="email" value="">

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-4">
            <x-primary-button id="registerBtn" class="w-full justify-center">
                {{ __('Create Account') }}
            </x-primary-button>
        </div>

        <div class="text-center text-sm mt-4">
            <span class="text-gray-600">{{ __('Already have an account?') }}</span>
            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">
                {{ __('Sign in') }}
            </a>
        </div>
    </form>
    <script>
        function formatTZ(digits) {
            if (!digits) return '';
            let out = '';
            if (digits.startsWith('255')) {
                const rest = digits.slice(3);
                out = '255' + (rest ? ' ' + groupRest(rest) : '');
            } else if (digits.startsWith('0')) {
                const rest = digits.slice(1);
                out = '0' + (rest ? ' ' + groupRest(rest) : '');
            } else {
                out = groupRest(digits);
            }
            return out.trim();
        }

        function groupRest(s) {
            const a = s.slice(0, 3);
            const b = s.slice(3, 6);
            const c = s.slice(6, 9);
            return [a, b, c].filter(Boolean).join(' ');
        }

        // Auto-format while typing
        (function(){
            const el = document.getElementById('phone');
            if (el) {
                el.addEventListener('input', function(e){
                    let v = e.target.value.replace(/\D/g, '');
                    if (v.length > 12) v = v.substring(0,12);
                    e.target.value = formatTZ(v);
                    const err = document.getElementById('phoneFormatError');
                    if (err) {
                        if (!err.classList.contains('hidden')) err.classList.add('hidden');
                        err.textContent = '';
                    }
                });
            }
        })();

        // Normalize before submit and show loading state
        document.getElementById('registerForm').addEventListener('submit', function(e){
            const phoneEl = document.getElementById('phone');
            const emailEl = document.getElementById('email');
            const btn = document.getElementById('registerBtn');
            const err = document.getElementById('phoneFormatError');
            let raw = (phoneEl.value || '').replace(/\D/g, '');

            let normalized = null;
            if (raw.length === 10 && raw.startsWith('0')) {
                normalized = raw.substring(1);
            } else if (raw.length === 12 && raw.startsWith('255')) {
                normalized = raw.substring(3);
            } else if (raw.length === 9) {
                normalized = raw;
            }

            if (!normalized || normalized.length !== 9) {
                e.preventDefault();
                if (err) {
                    err.textContent = 'Tafadhali weka namba sahihi: anza na 0 (mf. 0742710054) au 255...';
                    err.classList.remove('hidden');
                }
                return;
            }

            if (btn) {
                btn.setAttribute('disabled', 'disabled');
                btn.classList.add('opacity-60', 'cursor-not-allowed');
                const original = btn.textContent;
                btn.dataset.originalText = original;
                btn.textContent = 'Inasajili...';
            }

            phoneEl.value = normalized;
            // Build email from normalized phone and app host
            const host = this.getAttribute('data-app-host') || (window.location && window.location.hostname) || 'example.com';
            if (emailEl) {
                emailEl.value = normalized + '@' + host;
            }
        });
    </script>
</x-guest-layout>
