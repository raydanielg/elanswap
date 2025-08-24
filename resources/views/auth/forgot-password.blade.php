<x-guest-layout>
    <h2 class="text-2xl font-bold text-center text-gray-900 mb-6">Reset Your Password</h2>

    <div class="mb-4 text-sm text-gray-600">
        {{ __('Enter your phone number and we will send you a verification code to reset your password.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <!-- Phone Number -->
        <div>
            <x-input-label for="phone" :value="__('Phone Number')" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <span class="text-gray-500">+255</span>
                </div>
                <x-text-input 
                    id="phone" 
                    class="block mt-1 w-full pl-14" 
                    type="tel" 
                    name="phone" 
                    :value="old('phone')" 
                    required 
                    autofocus 
                    autocomplete="tel"
                    inputmode="numeric"
                    maxlength="9"
                    placeholder="712345678"
                />
            </div>
            @error('phone')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="pt-4">
            <x-primary-button class="w-full justify-center">
                {{ __('Send Verification Code') }}
            </x-primary-button>
        </div>
    </form>

    <div class="text-center text-sm mt-4">
        <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">
            {{ __('Back to login') }}
        </a>
    </div>
    <script>
        // Keep only digits and limit to 9
        document.getElementById('phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 9) value = value.substring(0, 9);
            e.target.value = value;
        });
    </script>
</x-guest-layout>
