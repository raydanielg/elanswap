<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-50">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div class="flex items-center mb-4">
                <a href="{{ url()->previous() }}" class="mr-2 text-gray-600 hover:text-gray-800" aria-label="Back">
                    &larr;
                </a>
                <h2 class="text-lg font-semibold text-gray-800">{{ __('Verify your phone') }}</h2>
            </div>
            <div class="mb-4 text-sm text-gray-600">
                {{ __('We have sent a 6-digit verification code to your phone number.') }}
                @isset($phone)
                    <span class="font-medium text-gray-800">+{{ $phone }}</span>
                @endisset
                {{ __('Please enter it below to verify your account.') }}
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <form method="POST" action="{{ route('otp.verify.submit') }}" class="space-y-4">
                @csrf

                <!-- OTP Code -->
                <div>
                    <x-input-label for="otp" :value="__('Verification Code')" />
                    <x-text-input 
                        id="otp" 
                        class="block mt-1 w-full text-center text-2xl tracking-widest" 
                        type="text" 
                        name="otp" 
                        inputmode="numeric"
                        pattern="[0-9]*"
                        maxlength="6"
                        required 
                        autofocus 
                        autocomplete="one-time-code"
                    />
                    <p class="mt-1 text-sm text-gray-500">
                        {{ __('Enter the 6-digit code sent to your phone.') }}
                    </p>
                </div>

                <div class="flex items-center justify-between mt-6">
                    <x-primary-button>
                        {{ __('Verify') }}
                    </x-primary-button>

                    <button 
                        id="resendBtn"
                        type="button"
                        disabled
                        onclick="event.preventDefault(); document.getElementById('resend-otp-form').submit();"
                        class="text-sm text-blue-600 disabled:text-gray-400 disabled:cursor-not-allowed hover:text-blue-500"
                    >
                        {{ __('Resend Code') }} (<span id="countdown">60</span>s)
                    </button>
                </div>

                <div class="mt-3 text-right">
                    <button type="button" id="openChangeNumber" class="text-sm text-gray-600 hover:text-gray-800">
                        {{ __('Change number') }}
                    </button>
                </div>
            </form>

            <form id="resend-otp-form" action="{{ route('otp.resend') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </div>

    <!-- Change Number Modal -->
    <div id="changeNumberModal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
        <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">{{ __('Change phone number') }}</h3>
                <button id="closeChangeNumber" class="text-gray-500 hover:text-gray-700" aria-label="Close">&times;</button>
            </div>
            <form method="POST" action="{{ route('otp.change_number') }}" class="space-y-4">
                @csrf
                <div>
                    <x-input-label for="new_phone" :value="__('New phone number')" />
                    <x-text-input id="new_phone" name="phone" type="text" class="block mt-1 w-full" required autofocus value="{{ $phone ?? '' }}" />
                    <p class="mt-1 text-xs text-gray-500">{{ __('Enter your new phone number to receive a new OTP.') }}</p>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" id="cancelChangeNumber" class="text-sm px-3 py-2 rounded bg-gray-100 hover:bg-gray-200">{{ __('Cancel') }}</button>
                    <x-primary-button>{{ __('Save & Send OTP') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Auto submit when 6 digits entered
        document.getElementById('otp').addEventListener('input', function() {
            if (this.value.length === 6) {
                this.form.submit();
            }
        });

        // Resend countdown (60s)
        (function () {
            var seconds = 60;
            var countdownEl = document.getElementById('countdown');
            var resendBtn = document.getElementById('resendBtn');
            var timer = setInterval(function () {
                seconds--;
                if (countdownEl) countdownEl.textContent = seconds;
                if (seconds <= 0) {
                    clearInterval(timer);
                    if (resendBtn) {
                        resendBtn.disabled = false;
                        if (countdownEl) countdownEl.parentElement.textContent = '{{ __('Resend Code') }}';
                    }
                }
            }, 1000);
        })();

        // Change number modal controls
        (function(){
            var modal = document.getElementById('changeNumberModal');
            var openBtn = document.getElementById('openChangeNumber');
            var closeBtn = document.getElementById('closeChangeNumber');
            var cancelBtn = document.getElementById('cancelChangeNumber');
            function open(){ if(modal){ modal.classList.remove('hidden'); modal.classList.add('flex'); } }
            function close(){ if(modal){ modal.classList.add('hidden'); modal.classList.remove('flex'); } }
            if(openBtn) openBtn.addEventListener('click', open);
            if(closeBtn) closeBtn.addEventListener('click', close);
            if(cancelBtn) cancelBtn.addEventListener('click', function(e){ e.preventDefault(); close(); });
            // Close on backdrop click
            if(modal) modal.addEventListener('click', function(e){ if(e.target === modal) close(); });
        })();
    </script>
    @endpush
</x-guest-layout>
