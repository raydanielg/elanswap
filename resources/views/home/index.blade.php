<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config('app.name', 'ElanSwap') }}</title>
    <!-- Material Symbols (Google Icons) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:FILL@0..1&display=swap" rel="stylesheet">
    <!-- Tailwind (built via CLI) -->
    <link rel="stylesheet" href="{{ asset('assets/app.css') }}" />
    <!-- Alpine.js (CDN) for x- directives -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body x-data class="bg-gray-50 text-gray-900 dark:bg-primary-950 dark:text-white">
    <x-site-header />

    <!-- Hero with background slider -->
    <section id="home" class="relative overflow-hidden text-white min-h-[70vh] md:min-h-[80vh]" 
             style="clip-path: polygon(0 0, 100% 0, 100% 93%, 0 100%);" 
             x-data="{
                i: 0,
                delay: 5000,
                fadeRed: true,
                captions: {
                  en: ['Fast transfers', 'Secure and transparent', 'Smart matching'],
                  sw: ['Uhamisho wa haraka', 'Salama na wazi', 'Uwazi na usalama']
                },
                imgs: [
                  '{{ asset('african-woman-teaching-children-class_23-2148892563 (1).jpg') }}',
                  '{{ asset('black-cheerful-woman-smiling-sitting-table-stock-photo_195114-64871.jpg') }}',
                  '{{ asset('black-female-teacher-stands-front-explains-lesson-by-reading-from-book-from-desk_404612-572.jpg') }}',
                  '{{ asset('business-meeting-woman-speaker-communication-from-women-employee-with-analysis-presentation-whiteboard-planning-worker-staff-solution-from-collaboration-teamwork-working-team_590464-161971.jpg') }}',
                  '{{ asset('cheerful-black-female-teacher-with-workbooks-standing-near-whiteboard-stock-photo_195114-65145.jpg') }}',
                  '{{ asset('group-african-kids-paying-attention-class_23-2148892516.jpg') }}',
                  '{{ asset('group-african-kids-paying-attention-class_23-2148892518.jpg') }}',
                  '{{ asset('three-factory-workers-safety-hats-discussing-manufacture-plan_1303-30650.jpg') }}',
                  '{{ asset('woman-teaching-classroom_23-2151696399.jpg') }}',
                  '{{ asset('woman-teaching-kids-class_23-2148892553.jpg') }}'
                ],
                start(){
                  setInterval(()=>{ this.i = (this.i+1) % this.imgs.length }, this.delay);
                  setInterval(()=>{ this.fadeRed = !this.fadeRed }, 4000);
                }
             }" x-init="start()">

        <!-- Slider images -->
        <div class="absolute inset-0">
            <template x-for="(src, idx) in imgs" :key="idx">
                <img :src="src" :alt="'slide-'+idx" class="absolute inset-0 w-full h-full object-cover object-center"
                     x-show="i===idx" x-transition.opacity.duration.800ms>
            </template>
            <!-- Gradient overlay: start dark blue and end red for the requested vibe -->
            <div class="absolute inset-0 bg-gradient-to-br from-primary-950/90 via-primary-900/80 to-red-800/70"></div>
            <!-- Animated red overlay fading in/out on top -->
            <div x-show="fadeRed" x-transition.opacity.duration.1000ms class="absolute inset-0 bg-gradient-to-tr from-red-800/35 via-red-700/25 to-red-900/35 mix-blend-multiply"></div>
        </div>

        <div class="relative z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24 min-h-[70vh] md:min-h-[80vh] flex items-center">
                <div class="grid md:grid-cols-1 gap-10 items-center">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-extrabold leading-tight">ElanSwap</h1>
                        <p class="mt-4 text-blue-100/90 text-base md:text-lg">
                            ElanSwap ni mfumo wa kidijitali unaolenga kurahisisha mchakato wa kubadilishana vituo vya kazi kwa wafanyakazi wa sekta mbalimbali. Mfumo huu unawawezesha wafanyakazi kuunda akaunti zao, kuonyesha mahitaji yao ya kubadilisha kituo, na kupata mechi zinazofaa kulingana na vigezo vyao vya kijiografia, cheo, na sehemu wanayotaka kwenda.
                        </p>
                        <div class="mt-8 flex items-center gap-4">
                            <a href="{{ route('register') }}" class="px-6 py-3 rounded-lg bg-white text-primary-900 font-semibold shadow hover:shadow-md transition">
                                <span x-text="$store.ui.t('get_started')">Get Started</span>
                            </a>
                            <a href="#features" class="px-6 py-3 rounded-lg border border-white/40 text-white/90 hover:bg-white/10 transition">
                                <span x-text="$store.ui.t('learn_more')">Learn More</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features (DB-driven) -->
    <section id="features" class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-8">Features of this system</h2>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse(($features ?? []) as $feature)
                    <div x-data="{ visible: false }" x-init="setTimeout(() => visible = true, {{ $loop->index * 120 }})"
                         x-show="visible"
                         x-transition:enter="transition duration-500 ease-out"
                         x-transition:enter-start="opacity-0 translate-y-3 scale-[0.98]"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         class="group p-6 rounded-2xl bg-white dark:bg-primary-900/10 shadow-sm border border-gray-100 dark:border-primary-800 hover:shadow-lg hover:-translate-y-1 transition transform">
                        <div class="flex items-start gap-4">
                            <div class="shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-red-500 text-white flex items-center justify-center ring-2 ring-white/60 dark:ring-white/10 shadow">
                                <x-feature-icon :name="$feature->icon" />
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg text-gray-900 dark:text-white group-hover:text-primary-700 dark:group-hover:text-white/90 transition">{{ $feature->title }}</h3>
                                <p class="mt-1 text-gray-600 dark:text-gray-300">{{ $feature->description }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-600">No features yet.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Regions -->
    <section id="regions" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-8" x-text="$store.ui.t('regions_title')"></h2>
            <p class="text-gray-600" x-text="$store.ui.t('regions_desc')"></p>
        </div>
    </section>

    <!-- About -->
    <section id="about" class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-4" x-text="$store.ui.t('about_title')"></h2>
            <p class="text-gray-700" x-text="$store.ui.t('about_desc')"></p>
        </div>
    </section>

    <!-- Contact -->
    <section id="contact" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-6" x-text="$store.ui.t('contact_title')"></h2>
            <p class="text-gray-700" x-text="$store.ui.t('contact_desc')"></p>
        </div>
    </section>

    <!-- FAQ -->
    <section id="faq" class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-6" x-text="$store.ui.t('faq_title')"></h2>
            <ul class="space-y-4 text-gray-700">
                <li><strong x-text="$store.ui.t('faq_q1')"></strong> <span x-text="$store.ui.t('faq_a1')"></span></li>
                <li><strong x-text="$store.ui.t('faq_q2')"></strong> <span x-text="$store.ui.t('faq_a2')"></span></li>
            </ul>
        </div>
    </section>

    

    <!-- CTA -->
    <section class="py-16 bg-white dark:bg-primary-900/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h3 class="text-2xl font-bold" x-text="$store.ui.t('ready_start')">Ready to start?</h3>
            <p class="mt-2 text-gray-600" x-text="$store.ui.t('cta_desc')"></p>
            <div class="mt-6">
                <a href="{{ route('register') }}" class="px-6 py-3 rounded-lg bg-white text-primary-900 font-semibold shadow hover:shadow-md transition">
                    <span x-text="$store.ui.t('register')">Register</span>
                </a>
            </div>
        </div>
    </section>

    <footer class="bg-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-sm text-gray-500">
            &copy; {{ date('Y') }} ElanSwap. All rights reserved.
        </div>
    </footer>
</body>
</html>
