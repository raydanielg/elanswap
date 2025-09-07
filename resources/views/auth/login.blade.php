<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />
    
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">
                        {{ __('Whoops! Something went wrong.') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome back</h1>
        <p class="text-gray-600">Sign in to your ElanSwap account</p>
    </div>


    <form id="loginForm" method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Phone Number -->
        <div>
            <x-input-label for="phone" :value="__('Phone Number')" class="sr-only" />
            <div class="relative">
                <x-text-input 
                    id="phone" 
                    class="block w-full" 
                    type="tel" 
                    name="phone" 
                    :value="old('phone')" 
                    required 
                    autofocus 
                    autocomplete="tel" 
                    inputmode="numeric"
                    maxlength="16"
                    placeholder="0742710054"
                    :hasError="$errors->has('phone')"
                />
            </div>
            <p class="mt-1 text-xs text-gray-500">Unaweza kuandika kama: <span class="font-medium">0742 710 054</span> au <span class="font-medium">255742710054</span> au <span class="font-medium">742710054</span>. Tutarekebisha kiotomatiki.</p>
            <p id="phoneFormatError" class="mt-1 text-sm text-red-600 hidden" aria-live="polite"></p>
            @error('phone')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Password')" class="sr-only" />
                @if (Route::has('password.request'))
                    <a class="text-sm font-medium text-primary-600 hover:text-primary-500" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>
            <div class="mt-1 relative">
                <x-text-input 
                    id="password" 
                    class="block w-full pr-10"
                    type="password"
                    name="password"
                    required 
                    autocomplete="current-password" 
                    placeholder="Enter your password"
                    :hasError="$errors->has('password')"
                />
                <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePassword()">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
            <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                {{ __('Remember me') }}
            </label>
        </div>

        <div>
            <x-primary-button id="loginBtn" class="w-full justify-center">
                {{ __('Sign in') }}
            </x-primary-button>
        </div>
    </form>

    <div class="mt-6 text-center text-sm">
        <p class="text-gray-600">
            Don't have an account?
            <a href="{{ route('register') }}" class="font-medium text-primary-600 hover:text-primary-500">
                {{ __('Sign up') }}
            </a>
        </p>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
        }

        function formatTZ(digits) {
            // digits: only numbers, max 12
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
            // Group as 3-3-3 from start
            const a = s.slice(0, 3);
            const b = s.slice(3, 6);
            const c = s.slice(6, 9);
            return [a, b, c].filter(Boolean).join(' ');
        }

        // Auto-format while typing, allow up to 12 digits; reset validation message when typing
        document.getElementById('phone').addEventListener('input', function(e) {
            let digits = e.target.value.replace(/\D/g, '');
            if (digits.length > 12) digits = digits.substring(0, 12);
            e.target.value = formatTZ(digits);
            const err = document.getElementById('phoneFormatError');
            if (!err.classList.contains('hidden')) err.classList.add('hidden');
            err.textContent = '';
        });

        // Normalize before submit and show loading state
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const phoneEl = document.getElementById('phone');
            const btn = document.getElementById('loginBtn');
            const err = document.getElementById('phoneFormatError');
            let raw = (phoneEl.value || '').replace(/\D/g, '');

            // Determine acceptable formats and normalize to 9-digit local expected by backend
            // Accepted: 0XXXXXXXXX (10) -> drop leading 0
            //           255XXXXXXXXX (12) -> drop 255
            //           XXXXXXXXX (9) -> as-is
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
                err.textContent = 'Tafadhali weka namba sahihi: anza na 0 (mf. 0742710054) au 255...';
                err.classList.remove('hidden');
                return;
            }

            // Loading state
            btn.setAttribute('disabled', 'disabled');
            btn.classList.add('opacity-60', 'cursor-not-allowed');
            const original = btn.textContent;
            btn.dataset.originalText = original;
            btn.textContent = 'Inapakia...';

            // Put normalized value back before submit
            phoneEl.value = normalized;
        });
    </script>
</x-guest-layout>
