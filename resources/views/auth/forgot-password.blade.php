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
                <x-text-input 
                    id="phone" 
                    class="block mt-1 w-full" 
                    type="tel" 
                    name="phone" 
                    :value="old('phone')" 
                    required 
                    autofocus 
                    autocomplete="tel"
                    inputmode="numeric"
                    maxlength="16"
                    placeholder="0742710054"
                />
            </div>
            <p class="mt-1 text-xs text-gray-500">Unaweza kuandika kama: <span class="font-medium">0742 710 054</span> au <span class="font-medium">255742710054</span> au <span class="font-medium">742710054</span>. Tutarekebisha kiotomatiki.</p>
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
        function groupRest(s){ const a=s.slice(0,3), b=s.slice(3,6), c=s.slice(6,9); return [a,b,c].filter(Boolean).join(' '); }
        function formatTZ(d){ if(!d) return ''; if(d.startsWith('255')){ const r=d.slice(3); return ('255' + (r?(' '+groupRest(r)):'')).trim(); } if(d.startsWith('0')){ const r=d.slice(1); return ('0' + (r?(' '+groupRest(r)):'')).trim(); } return groupRest(d).trim(); }
        document.getElementById('phone').addEventListener('input', function(e){ let v=e.target.value.replace(/\D/g,''); if(v.length>12) v=v.substring(0,12); e.target.value=formatTZ(v); });
    </script>
</x-guest-layout>
