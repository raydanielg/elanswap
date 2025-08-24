<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config('app.name', 'ElanSwap') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/app.css') }}" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-900">
    <x-site-header />

    <!-- Hero -->
    <section id="home" class="bg-gradient-to-br from-primary-950 via-primary-900 to-primary-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid md:grid-cols-2 gap-10 items-center">
                <div>
                    <h1 class="text-4xl md:text-5xl font-extrabold leading-tight">Harakisha uhamisho wa watumishi kwa njia rahisi</h1>
                    <p class="mt-4 text-blue-200">ElanSwap inarahisisha mchakato wa kuhamisha vituo vya kazi kwa haraka na ufanisi zaidi.</p>
                    <div class="mt-8 flex items-center space-x-4">
                        <a href="{{ route('register') }}" class="px-6 py-3 rounded-lg bg-white text-primary-900 font-semibold">Anza Sasa</a>
                        <a href="#features" class="px-6 py-3 rounded-lg border border-blue-300 text-blue-100 hover:bg-primary-800">Jifunze Zaidi</a>
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
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h3 class="text-2xl font-bold">Tayari kuanza?</h3>
            <p class="mt-2 text-gray-600">Jisajili leo na uanze kutumia ElanSwap.</p>
            <div class="mt-6">
                <a href="{{ route('register') }}" class="px-6 py-3 rounded-lg bg-primary-900 text-white font-semibold">Jisajili</a>
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
