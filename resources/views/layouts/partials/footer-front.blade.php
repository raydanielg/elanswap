<footer class="border-t border-primary-800 bg-gradient-to-b from-primary-900 to-primary-950 text-blue-100">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
      <!-- Brand + Description -->
      <div class="md:col-span-2">
        <a href="{{ route('home.public') }}" class="flex items-center gap-2">
          <img src="{{ asset('logo.png') }}" alt="ElanSwap logo" class="h-10 w-10 object-contain"/>
          <span class="text-2xl font-bold tracking-tight text-white">ElanSwap</span>
        </a>
        <p class="mt-4 text-[15px] leading-relaxed text-blue-200/90 max-w-2xl">
          ElanSwap ni <span class="text-white/95 font-semibold">mfumo wa kisasa</span> unaokuwezesha kubadilishana vituo vya kazi kwa urahisi.
          Unda ombi lako, pokea pendekezo la mechi, kisha hama kwa <span class="text-white/90">utaratibu na kasi</span>.
        </p>

        <!-- Socials -->
        <div class="mt-5 flex items-center gap-3">
          <a href="#" class="p-2 rounded-md ring-1 ring-white/10 hover:bg-white/10 hover:text-white transition" aria-label="Facebook">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 10-11.5 9.9v-7h-2v-3h2v-2.3c0-2 1.2-3.1 3-3.1.9 0 1.8.1 1.8.1v2h-1c-1 0-1.3.6-1.3 1.2V12h2.3l-.4 3h-1.9v7A10 10 0 0022 12z"/></svg>
          </a>
          <a href="#" class="p-2 rounded-md ring-1 ring-white/10 hover:bg-white/10 hover:text-white transition" aria-label="Twitter">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M22 5.8c-.7.3-1.5.6-2.3.6.8-.5 1.4-1.2 1.7-2-.8.5-1.7.9-2.6 1.1C18 4.7 17 4.2 16 4.2c-2 0-3.6 1.7-3.6 3.6 0 .3 0 .6.1.8-3-.1-5.7-1.6-7.5-3.8-.3.6-.5 1.2-.5 1.9 0 1.3.7 2.4 1.7 3.1-.6 0-1.2-.2-1.7-.5v.1c0 1.8 1.3 3.3 3 3.6-.3.1-.7.1-1 .1-.3 0-.5 0-.8-.1.5 1.5 1.9 2.6 3.6 2.7-1.3 1-3 1.6-4.7 1.6h-1c1.7 1.1 3.8 1.8 6 1.8 7.2 0 11.2-6 11.2-11.2v-.5c.8-.6 1.4-1.2 1.9-2z"/></svg>
          </a>
          <a href="#" class="p-2 rounded-md ring-1 ring-white/10 hover:bg-white/10 hover:text-white transition" aria-label="Instagram">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M7 2C4.2 2 2 4.2 2 7v10c0 2.8 2.2 5 5 5h10c2.8 0 5-2.2 5-5V7c0-2.8-2.2-5-5-5H7zm10 2c1.7 0 3 1.3 3 3v10c0 1.7-1.3 3-3 3H7c-1.7 0-3-1.3-3-3V7c0-1.7 1.3-3 3-3h10zm-5 3.5A5.5 5.5 0 1017.5 13 5.5 5.5 0 0012 7.5zm0 2A3.5 3.5 0 1115.5 13 3.5 3.5 0 0112 9.5zM18 6.2a.8.8 0 11-1.6 0 .8.8 0 011.6 0z"/></svg>
          </a>
          <a href="#" class="p-2 rounded-md ring-1 ring-white/10 hover:bg-white/10 hover:text-white transition" aria-label="LinkedIn">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M6.94 6.5a1.94 1.94 0 11-3.88 0 1.94 1.94 0 013.88 0zM3.5 8.75h3.9V20.5H3.5V8.75zm6.16 0H13.3v1.6h.06c.53-1 1.81-2.06 3.73-2.06 3.99 0 4.72 2.63 4.72 6.05v6.16h-3.9v-5.46c0-1.3-.03-2.98-1.82-2.98-1.82 0-2.1 1.42-2.1 2.88v5.56H9.66V8.75z"/></svg>
          </a>
        </div>
      </div>

      <!-- Quick Links -->
      <div>
        <div class="text-sm font-semibold text-white tracking-wide uppercase">Viungo vya Haraka</div>
        <ul class="mt-3 space-y-2 text-sm">
          <li><a href="{{ route('dashboard') }}" class="hover:underline hover:text-white/90">Nyumbani (Dashboard)</a></li>
          <li><a href="{{ route('destinations.index') }}" class="hover:underline hover:text-white/90">Vituo (Destinations)</a></li>
          <li><a href="{{ route('applications.index') }}" class="hover:underline hover:text-white/90">Maombi Yangu</a></li>
          <li><a href="{{ route('requests.index') }}" class="hover:underline hover:text-white/90" data-requires-payment>Mialiko Yangu</a></li>
          <li><a href="{{ route('blog.index') }}" class="hover:underline hover:text-white/90">Blogu</a></li>
        </ul>
      </div>

      <!-- Contact -->
      <div>
        <div class="text-sm font-semibold text-white tracking-wide uppercase">Mawasiliano</div>
        <ul class="mt-3 space-y-2 text-sm">
          <li class="flex items-center gap-2">
            <svg class="w-4 h-4 text-blue-200" viewBox="0 0 24 24" fill="currentColor"><path d="M6.62 10.79a15.05 15.05 0 006.59 6.59l2.2-2.2a1 1 0 011.02-.24c1.12.37 2.33.57 3.57.57a1 1 0 011 1V21a1 1 0 01-1 1C10.85 22 2 13.15 2 2a1 1 0 011-1h3.5a1 1 0 011 1c0 1.24.2 2.45.57 3.57a1 1 0 01-.25 1.02l-2.2 2.2z"/></svg>
            <a href="tel:+255712345678" class="hover:underline">+255 712 345 678</a>
          </li>
          <li class="flex items-center gap-2">
            <svg class="w-4 h-4 text-blue-200" viewBox="0 0 24 24" fill="currentColor"><path d="M2 4a2 2 0 012-2h16a2 2 0 012 2v.5l-10 6.25L2 4.5V4zm0 3.2v12.8A2 2 0 004 22h16a2 2 0 002-2V7.2l-9.36 5.85a2 2 0 01-2.08 0L2 7.2z"/></svg>
            <a href="mailto:support@elanswap.com" class="hover:underline">support@elanswap.com</a>
          </li>
          <li class="flex items-center gap-2">
            <svg class="w-4 h-4 text-blue-200" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.1 2 5 5.1 5 9c0 6.2 7 13 7 13s7-6.8 7-13c0-3.9-3.1-7-7-7zm0 9.5A2.5 2.5 0 119.5 9 2.5 2.5 0 0112 11.5z"/></svg>
            <span>Dar es Salaam, Tanzania</span>
          </li>
        </ul>
      </div>
    </div>

    <!-- Bottom bar -->
    <div class="mt-10 pt-6 border-t border-primary-800 flex flex-col sm:flex-row items-center justify-between gap-3">
      <p class="text-xs text-blue-200/90">© {{ date('Y') }} ElanSwap. Haki zote zimehifadhiwa.</p>
      <p class="text-xs text-blue-200/90">
        Imetengenezwa kwa <span class="text-rose-400">♥</span> na <span class="font-semibold text-white">ElanTechnologies</span>
      </p>
    </div>
  </div>
</footer>
