<x-guest-layout>
    <h2 class="text-2xl font-bold text-center text-gray-900 mb-6">Create your Elanswap account</h2>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
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
                    autocomplete="tel"
                    placeholder="712345678"
                    pattern="[0-9]{9}"
                    title="Please enter your 9-digit phone number (without the leading 0)"
                />
            </div>
            @error('phone')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email (Optional) -->
        <div>
            <x-input-label for="email" :value="__('Email (Optional)')" />
            <x-text-input 
                id="email" 
                class="block mt-1 w-full" 
                type="email" 
                name="email" 
                :value="old('email')" 
                autocomplete="email"
                placeholder="you@example.com"
            />
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

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
            <x-primary-button class="w-full justify-center">
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
</x-guest-layout>
