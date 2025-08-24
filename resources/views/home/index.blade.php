<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config('app.name', 'ElanSwap') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data class="bg-gray-50 text-gray-900 dark:bg-primary-950 dark:text-white">
    <x-site-header />

    <!-- Hero with background slider -->
    <section id="home" class="relative overflow-hidden text-white" 
             x-data="{
                i: 0,
                delay: 5000,
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
                  setInterval(()=>{ this.i = (this.i+1) % this.imgs.length }, this.delay)
                }
             }" x-init="start()">

        <!-- Slider images -->
        <div class="absolute inset-0">
            <template x-for="(src, idx) in imgs" :key="idx">
                <img :src="src" :alt="'slide-'+idx" class="absolute inset-0 w-full h-full object-cover transform scale-105"
                     x-show="i===idx" x-transition.opacity.duration.800ms>
            </template>
            <!-- Gradient overlay: deep navy to dark blue for readability -->
            <div class="absolute inset-0 bg-gradient-to-br from-primary-950/90 via-primary-900/85 to-primary-800/80"></div>
        </div>

        <div class="relative z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
                <div class="grid md:grid-cols-2 gap-10 items-center">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-extrabold leading-tight">
                            Badili namna ya uhamisho wa watumishi — haraka, salama na wazi.
                        </h1>
                        <p class="mt-4 text-blue-100/90">
                            ElanSwap inakuunganisha na vituo vinavyolingana kwa akili ya kiotomatiki,
                            ikirahisisha maamuzi na kuokoa muda wa usimamizi.
                        </p>
                        <ul class="mt-6 space-y-2 text-blue-100/90">
                            <li class="flex items-start gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-emerald-400"></span> Ulinganifu wa nafasi kwa kutumia vigezo vyako (mkoa, taaluma, muda).</li>
                            <li class="flex items-start gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-emerald-400"></span> Uwazi wa mchakato na taarifa za wakati halisi.</li>
                            <li class="flex items-start gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-emerald-400"></span> Usalama wa taarifa na ufuatiliaji wa hatua.</li>
                        </ul>
                        <div class="mt-8 flex items-center gap-4">
                            <a href="{{ route('register') }}" class="px-6 py-3 rounded-lg bg-white text-primary-900 font-semibold shadow hover:shadow-md transition">
                                <span x-text="$store.ui.t('get_started')">Get Started</span>
                            </a>
                            <a href="#features" class="px-6 py-3 rounded-lg border border-white/40 text-white/90 hover:bg-white/10 transition">
                                <span x-text="$store.ui.t('learn_more')">Learn More</span>
                            </a>
                        </div>
                    </div>
                    <div class="relative">
                        <div class="bg-white/10 border border-white/10 rounded-2xl p-6 shadow-2xl">
                            <div class="grid grid-cols-2 gap-4 text-center">
                                <div class="p-4 rounded-lg bg-white/10">
                                    <div class="text-3xl font-bold">10x</div>
                                    <div class="text-blue-200 text-sm">Kasi ya mchakato</div>
                                </div>
                                <div class="p-4 rounded-lg bg-white/10">
                                    <div class="text-3xl font-bold">24/7</div>
                                    <div class="text-blue-200 text-sm">Upatikanaji</div>
                                </div>
                                <div class="p-4 rounded-lg bg-white/10">
                                    <div class="text-3xl font-bold">99.9%</div>
                                    <div class="text-blue-200 text-sm">Uhakika</div>
                                </div>
                                <div class="p-4 rounded-lg bg-white/10">
                                    <div class="text-3xl font-bold">Secure</div>
                                    <div class="text-blue-200 text-sm">Ulinzi wa data</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Regions -->
    <section id="regions" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-8">Regions</h2>
            <p class="text-gray-600">Orodha ya mikoa na vituo vitapatikana hapa (tutaunganisha na `region.json`).</p>
        </div>
    </section>

    <!-- About -->
    <section id="about" class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-4">Kuhusu ElanSwap</h2>
            <p class="text-gray-700">ElanSwap inaleta urahisi katika uhamisho wa watumishi kwa kutumia teknolojia rahisi na salama.</p>
        </div>
    </section>

    <!-- Contact -->
    <section id="contact" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-6">Mawasiliano</h2>
            <p class="text-gray-700">Wasiliana nasi kupitia barua pepe: support@elanswap.com</p>
        </div>
    </section>

    <!-- FAQ -->
    <section id="faq" class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-6">Maswali ya Mara kwa Mara (FAQ)</h2>
            <ul class="space-y-4 text-gray-700">
                <li><strong>Je, nawezaje kuanza?</strong> Bofya Get Started na ujisajili.</li>
                <li><strong>Je, namba ya simu inatakiwa kwenye muundo gani?</strong> Tumia muundo 2557XXXXXXXX.</li>
            </ul>
        </div>
    </section>

    <!-- Features -->
    <section id="features" class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-8">Vipengele Muhimu</h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="p-6 rounded-xl bg-white shadow-sm border">
                    <h3 class="font-semibold text-lg">Uhamisho Rahisi</h3>
                    <p class="mt-2 text-gray-600">Dhibiti mchakato wa uhamisho kwa hatua chache.</p>
                </div>
                <div class="p-6 rounded-xl bg-white shadow-sm border">
                    <h3 class="font-semibold text-lg">Ulinzi wa Taarifa</h3>
                    <p class="mt-2 text-gray-600">Usalama wa kiwango cha juu kulinda taarifa zako.</p>
                </div>
                <div class="p-6 rounded-xl bg-white shadow-sm border">
                    <h3 class="font-semibold text-lg">Ripoti za Haraka</h3>
                    <p class="mt-2 text-gray-600">Pata takwimu na ripoti kwa wakati.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-16 bg-white dark:bg-primary-900/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h3 class="text-2xl font-bold" x-text="$store.ui.t('ready_start')">Ready to start?</h3>
            <p class="mt-2 text-gray-600">Jisajili leo na uanze kutumia ElanSwap.</p>
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
