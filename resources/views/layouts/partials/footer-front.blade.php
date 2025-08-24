<footer class="p-4 bg-white md:p-8 lg:p-10 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
  <div class="mx-auto max-w-screen-xl text-center">
      <a href="{{ route('home.public') }}" class="flex justify-center items-center text-2xl font-semibold text-gray-900 dark:text-white">
          <img src="{{ asset('logo.png') }}" alt="ElanSwap logo" class="mr-2 h-8 w-8 object-contain"/>
          ElanSwap
      </a>
      <p class="my-6 text-gray-500 dark:text-gray-400">Elanswap ni mfumo wa kidijitali unaolenga kurahisisha mchakato wa kubadilishana vituo vya kazi kwa wafanyakazi wa sekta mbalimbali. Mfumo huu unawawezesha wafanyakazi kuunda akaunti zao, kuonyesha mahitaji yao ya kubadilisha kituo, na kupata mechi zinazofaa kulingana na vigezo vyao vya kijiografia, cheo, na sehemu wanayotaka kwenda.</p>
      <ul class="flex flex-wrap justify-center items-center mb-6 gap-x-3 gap-y-2">
          <li>
              <a href="{{ route('home.public') }}" class="inline-block px-3 py-1 rounded-full text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 transition">Home</a>
          </li>
          <li>
              <a href="#" class="inline-block px-3 py-1 rounded-full text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 transition">Regions</a>
          </li>
          <li>
              <a href="#" class="inline-block px-3 py-1 rounded-full text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 transition">About</a>
          </li>
          <li>
              <a href="#" class="inline-block px-3 py-1 rounded-full text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 transition">Contact</a>
          </li>
          <li>
              <a href="#" class="inline-block px-3 py-1 rounded-full text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 transition">FAQ</a>
          </li>
      </ul>
      <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© {{ date('Y') }} <a href="{{ route('home.public') }}" class="hover:underline">ElanSwap</a>. All Rights Reserved.</span>
  </div>
</footer>
