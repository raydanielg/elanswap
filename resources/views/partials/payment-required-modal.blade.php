<div id="payment-required-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center" aria-hidden="true" role="dialog" aria-modal="true">
  <div class="absolute inset-0 bg-gray-900/60 transition-opacity"></div>
  <div class="relative mx-auto w-full max-w-md px-6">
    <div class="bg-white rounded-2xl shadow-2xl p-6 text-center transition-all duration-200 ease-out transform opacity-0 translate-y-3" data-dialog-panel>
      <div class="mx-auto mb-4 w-14 h-14 rounded-full bg-blue-50 flex items-center justify-center">
        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20z"/>
        </svg>
      </div>
      <h3 class="text-lg font-semibold text-gray-900">Huduma hii inahitaji malipo</h3>
      <p class="mt-2 text-sm text-gray-600">Ili kuendelea kutumia huduma hii, tafadhali fanya malipo kwanza. Baada ya malipo utakubalika kuendelea mara moja.</p>
      <div class="mt-6 flex items-center justify-center gap-3">
        <a href="{{ route('payment.index') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800" style="background-color:#1d4ed8;color:#ffffff;">Nenda kulipia</a>
        <button type="button" data-close-payment-modal class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Funga</button>
      </div>
    </div>
  </div>
</div>
<script>
(function(){
  const modal = document.getElementById('payment-required-modal');
  if(!modal) return;
  const backdrop = modal.querySelector('.absolute.inset-0');
  const panel = modal.querySelector('[data-dialog-panel]');
  const closeBtns = modal.querySelectorAll('[data-close-payment-modal]');

  function animateOpen(){
    modal.classList.remove('hidden');
    // kick off transition
    requestAnimationFrame(()=>{
      panel.classList.remove('opacity-0','translate-y-3');
    });
  }

  function animateClose(){
    panel.classList.add('opacity-0','translate-y-3');
    // after transition hide modal
    const onEnd = ()=>{
      modal.classList.add('hidden');
      panel.removeEventListener('transitionend', onEnd);
    };
    panel.addEventListener('transitionend', onEnd);
  }

  closeBtns.forEach(b=>b.addEventListener('click', animateClose));
  if(backdrop){ backdrop.addEventListener('click', animateClose); }

  // Esc to close
  document.addEventListener('keydown', function(e){
    if(e.key === 'Escape' && !modal.classList.contains('hidden')){
      animateClose();
    }
  });

  // Global hook: any element with [data-requires-payment] will trigger this modal when UNPAID=true
  document.addEventListener('click', function(e){
    const t = e.target.closest('[data-requires-payment]');
    if(!t) return;
    if(window.UNPAID === true){
      e.preventDefault();
      animateOpen();
    }
  }, true);
})();
</script>
