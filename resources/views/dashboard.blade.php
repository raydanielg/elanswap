<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Card -->
            @if(Auth::user()->hasCompletedProfile())
                <!-- Complete Profile Welcome Card -->
                <div class="bg-gradient-to-r from-primary-600 to-primary-700 rounded-xl shadow-lg mb-8 overflow-hidden">
                    <div class="px-8 py-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="h-16 w-16 rounded-full bg-white/20 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h1 class="text-2xl font-bold text-black">
                                        Welcome back, {{ Auth::user()->name }}! 👋
                                    </h1>
                                    <p class="text-primary-100 mt-1">
                                        Great to see you again. Your profile is complete and ready to go!
                                    </p>
                                </div>
                            </div>
                            <div class="hidden md:block">
                                <div class="flex items-center space-x-2 bg-white/10 rounded-lg px-4 py-2">
                                    <svg class="w-5 h-5 text-green-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-white font-medium">Profile Complete</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Incomplete Profile Welcome Card -->
                <div class="bg-gradient-to-r from-orange-500 to-red-500 rounded-xl shadow-lg mb-8 overflow-hidden">
                    <div class="px-8 py-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="h-16 w-16 rounded-full bg-white/20 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h1 class="text-2xl font-bold text-white">
                                        Welcome to ElanSwap! 🚀
                                    </h1>
                                    <p class="text-orange-100 mt-1">
                                        Please complete your profile to get started and unlock all features.
                                    </p>
                                </div>
                            </div>
                            <div class="hidden md:block">
                                <div class="flex items-center space-x-2 bg-white/10 rounded-lg px-4 py-2">
                                    <div class="w-8 h-8 relative">
                                        <svg class="w-8 h-8 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10"></circle>
                                        </svg>
                                        <svg class="w-8 h-8 absolute top-0 left-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="stroke-dasharray: {{ Auth::user()->getProfileCompletionPercentage() * 0.628 }}, 62.8;">
                                            <circle cx="12" cy="12" r="10" stroke-width="2" stroke-linecap="round" transform="rotate(-90 12 12)"></circle>
                                        </svg>
                                    </div>
                                    <span class="text-white font-medium">{{ Auth::user()->getProfileCompletionPercentage() }}% Complete</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Missing Fields Alert -->
                        @if(count(Auth::user()->getMissingProfileFields()) > 0)
                            <div class="mt-6 bg-white/10 rounded-lg p-4">
                                <div class="flex items-start space-x-3">
                                    <svg class="w-5 h-5 text-yellow-300 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <h3 class="text-white font-semibold">Complete these fields:</h3>
                                        <ul class="text-orange-100 text-sm mt-1 space-y-1">
                                            @foreach(Auth::user()->getMissingProfileFields() as $field)
                                                <li>• {{ $field }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-white text-orange-600 font-semibold rounded-lg hover:bg-orange-50 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Complete Profile
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Quick Actions (CTA) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                <a href="{{ route('applications.index') }}" class="block rounded-xl bg-primary-600 text-white shadow hover:bg-primary-700 transition p-5 text-center">
                    <span class="inline-block text-lg font-bold">My Application</span>
                </a>
                <a href="{{ route('destinations.index') }}" class="block rounded-xl bg-green-600 text-black shadow hover:bg-green-700/90 transition p-5 text-center">
                    <span class="inline-block text-lg font-bold">Requests</span>
                </a>
            </div>

            <!-- Top Stats -->
            @php
                // Attempt to compute total regions from region.json
                $regionsCount = 0;
                try {
                    $jsonPath = base_path('region.json');
                    if (file_exists($jsonPath)) {
                        $data = json_decode(file_get_contents($jsonPath), true);
                        if (is_array($data)) {
                            // Support either flat array or keyed under 'regions'
                            $regionsCount = isset($data['regions']) && is_array($data['regions'])
                                ? count($data['regions'])
                                : (is_array($data) ? count($data) : 0);
                        }
                    }
                } catch (\Throwable $e) { $regionsCount = 0; }

                // Fallbacks if controller hasn't provided them
                $applicationsCount = $applicationsCount ?? 0;
                $destinationsCount = $destinationsCount ?? 0;
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <!-- My Applications -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 relative">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-2 bg-primary-100 rounded-lg">
                                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-600 flex items-center">My Applications
                                    <button type="button" class="ml-2" data-popover-target="po-apps" aria-label="Maelezo" aria-controls="po-apps" aria-expanded="false">
                                        <svg class="w-4 h-4 text-gray-400 hover:text-gray-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                                    </button>
                                </h3>
                                <p class="text-2xl font-bold text-gray-900">{{ $applicationsCount }}</p>
                            </div>
                        </div>
                        <a href="{{ route('applications.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">View</a>
                    </div>
                    <!-- Popover: My Applications -->
                    <div id="po-apps" role="tooltip" class="fixed z-50 invisible opacity-0 transition-opacity duration-200 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg shadow p-3 w-72">
                        <p>Hii ni idadi ya maombi uliyoweka. Bonyeza "View" kuona na kuhariri maombi yako.</p>
                        <div data-popper-arrow></div>
                    </div>
                </div>

                <!-- Destinations -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 relative">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-2 bg-indigo-100 rounded-lg">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-600 flex items-center">Destinations
                                    <button type="button" class="ml-2" data-popover-target="po-dest" aria-label="Maelezo" aria-controls="po-dest" aria-expanded="false">
                                        <svg class="w-4 h-4 text-gray-400 hover:text-gray-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                                    </button>
                                </h3>
                                <p class="text-2xl font-bold text-gray-900">{{ $destinationsCount }}</p>
                            </div>
                        </div>
                        <a href="{{ route('destinations.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">Browse</a>
                    </div>
                    <!-- Popover: Destinations -->
                    <div id="po-dest" role="tooltip" class="fixed z-50 invisible opacity-0 transition-opacity duration-200 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg shadow p-3 w-72">
                        <p>Hapa unaona idadi ya mikoa yenye maombi ya kubadilishana vituo. Bonyeza "Browse" kuchagua mkoa na kuona maombi.</p>
                        <div data-popper-arrow></div>
                    </div>
                </div>

                <!-- Total Regions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 relative">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-2 bg-emerald-100 rounded-lg">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-600 flex items-center">Total Regions
                                    <button type="button" class="ml-2" data-popover-target="po-reg" aria-label="Maelezo" aria-controls="po-reg" aria-expanded="false">
                                        <svg class="w-4 h-4 text-gray-400 hover:text-gray-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                                    </button>
                                </h3>
                                <p class="text-2xl font-bold text-gray-900">{{ $regionsCount }}</p>
                            </div>
                        </div>
                        <a href="#regions" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">Explore</a>
                    </div>
                    <!-- Popover: Total Regions -->
                    <div id="po-reg" role="tooltip" class="fixed z-50 invisible opacity-0 transition-opacity duration-200 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg shadow p-3 w-72">
                        <p>Jumla ya mikoa iliyopo kwenye mfumo. Hii ni rejea tu, si lazima kila mkoa uwe na maombi.</p>
                        <div data-popper-arrow></div>
                    </div>
                </div>
            </div>

            <!-- Regions List Card -->
            <div id="regions" class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between relative">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">Mikoa (Regions)
                        <button type="button" class="ml-2" data-popover-target="po-regions-section" aria-label="Maelezo" aria-controls="po-regions-section" aria-expanded="false">
                            <svg class="w-4 h-4 text-gray-400 hover:text-gray-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                        </button>
                    </h3>
                    <a href="#regions" class="text-sm text-primary-600 hover:text-primary-700 font-medium">Browse</a>
                    <div id="po-regions-section" role="tooltip" class="fixed z-50 invisible opacity-0 transition-opacity duration-200 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg shadow p-3 w-80">
                        <p>Hapa ni orodha ya mikoa yote. Bonyeza mkoa kuona maombi yaliyopo kwenye mkoa huo.</p>
                        <div data-popper-arrow></div>
                    </div>
                </div>
                <div class="p-6">
                    @php
                        $regions = \App\Models\Region::orderBy('name')->get();
                    @endphp
                    @if($regions->isEmpty())
                        <p class="text-gray-500 text-sm">Hakuna Mikoa kupatikana kwa sasa.</p>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                            @foreach($regions as $r)
                                <div class="flex items-center gap-2 p-3 rounded-lg border border-gray-100 hover:border-primary-200 hover:bg-primary-50/40 transition">
                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 6 0z" /></svg>
                                    <span class="text-sm text-gray-800">{{ $r->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Activity + Announcements + Last Login in one row -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Activity (User only) -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between relative">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">Recent Activity
                                <button type="button" class="ml-2" data-popover-target="po-recent" aria-label="Maelezo" aria-controls="po-recent" aria-expanded="false">
                                    <svg class="w-4 h-4 text-gray-400 hover:text-gray-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                                </button>
                            </h3>
                            <p class="text-xs text-gray-500">Your actions only</p>
                        </div>
                        @php
                            $recentCount = \App\Models\Log::where('user_id', Auth::id())->count();
                        @endphp
                        <span class="text-xs text-gray-500">{{ $recentCount }} total</span>
                        <div id="po-recent" role="tooltip" class="fixed z-50 invisible opacity-0 transition-opacity duration-200 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg shadow p-3 w-80">
                            <p>Muhtasari wa matukio yako ya karibuni (k.m. kuingia, kuhariri profaili, kuweka maombi). Inaonekana wewe pekee.</p>
                            <div data-popper-arrow></div>
                        </div>
                    </div>
                    <div class="p-6">
                        @php
                            $recentLogs = \App\Models\Log::where('user_id', Auth::id())
                                ->orderByDesc('created_at')
                                ->limit(6)
                                ->get();
                            $typeMeta = [
                                'login' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'icon' => 'M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3zm0 2c-2.761 0-5 2.239-5 5h10c0-2.761-2.239-5-5-5z'],
                                'application' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                                'profile' => ['bg' => 'bg-violet-50', 'text' => 'text-violet-600', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                                'activity' => ['bg' => 'bg-gray-50', 'text' => 'text-gray-600', 'icon' => 'M12 8v4l3 3'],
                            ];
                        @endphp
                        @if($recentLogs->isEmpty())
                            <div class="flex items-center gap-3 text-gray-500 text-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" /></svg>
                                <span>No recent activity found.</span>
                            </div>
                        @else
                            <ul class="divide-y divide-gray-100">
                                @foreach($recentLogs as $log)
                                    @php
                                        $meta = $typeMeta[$log->log_type ?? 'activity'] ?? $typeMeta['activity'];
                                        $when = $log->created_at ? $log->created_at->diffForHumans() : (optional($log->record_date)?->diffForHumans() ?? '-');
                                        $title = $log->text ?? ucfirst($log->log_type ?? 'Activity');
                                    @endphp
                                    <li class="py-3 flex items-start">
                                        <div class="mr-3 mt-0.5 {{ $meta['bg'] }} {{ $meta['text'] }} p-1.5 rounded">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $meta['icon'] }}" />
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm text-gray-800">{{ $title }}</p>
                                            <p class="text-xs text-gray-500">{{ $when }}</p>
                                        </div>
                                        @if(!empty($log->status))
                                            <span class="ml-3 text-[11px] px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">{{ $log->status }}</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <!-- Announcements -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between relative">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">Announcements
                            <button type="button" class="ml-2" data-popover-target="po-ann" aria-label="Maelezo" aria-controls="po-ann" aria-expanded="false">
                                <svg class="w-4 h-4 text-gray-400 hover:text-gray-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                            </button>
                        </h3>
                        <div class="flex items-center gap-2">
                            <span class="loader text-blue-900" aria-label="Loading"></span>
                            <span class="text-xs text-gray-500">Loading</span>
                        </div>
                        <div id="po-ann" role="tooltip" class="fixed z-50 invisible opacity-0 transition-opacity duration-200 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg shadow p-3 w-80">
                            <p>Habari au taarifa muhimu kutoka kwa mfumo wa ElanSwap. Tazama hapa kujua jipya.</p>
                            <div data-popper-arrow></div>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        @php
                            $announcements = \App\Models\Feature::active()->orderBy('sort_order')->limit(5)->get();
                            if ($announcements->isEmpty()) {
                                // Fallback: show latest features if none are marked active
                                $announcements = \App\Models\Feature::orderByDesc('id')->limit(5)->get();
                            }
                        @endphp
                        @forelse($announcements as $item)
                            <div class="flex items-start">
                                <div class="mr-3 p-2 rounded bg-primary-50 text-primary-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900">{{ $item->title }}</h4>
                                    <p class="text-sm text-gray-600">{{ $item->description }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">No announcements right now.</p>
                        @endforelse
                    </div>
                </div>
                <!-- Last Login History -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between relative">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">Last Login History
                            <button type="button" class="ml-2" data-popover-target="po-login" aria-label="Maelezo" aria-controls="po-login" aria-expanded="false">
                                <svg class="w-4 h-4 text-gray-400 hover:text-gray-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                            </button>
                        </h3>
                        <span class="text-xs text-gray-500">Security overview</span>
                        <div id="po-login" role="tooltip" class="fixed z-50 invisible opacity-0 transition-opacity duration-200 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg shadow p-3 w-80">
                            <p>Orodha ya kuingia kwako (tarehe, IP, kifaa). Inakusaidia kufuatilia usalama wa akaunti yako.</p>
                            <div data-popper-arrow></div>
                        </div>
                    </div>
                    <div class="p-6">
                        @php
                            $loginLogs = \App\Models\Log::where('user_id', Auth::id())
                                ->where('log_type', 'login')
                                ->orderByDesc('created_at')
                                ->limit(5)
                                ->get();
                        @endphp
                        @if($loginLogs->isEmpty())
                            <p class="text-gray-500 text-sm">No login history yet.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead>
                                        <tr class="text-left text-gray-500">
                                            <th class="py-2 pr-4">Date</th>
                                            <th class="py-2 pr-4">IP</th>
                                            <th class="py-2">Agent</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($loginLogs as $log)
                                            <tr>
                                                <td class="py-2 pr-4">{{ optional($log->created_at)->format('M d, Y H:i') ?? optional($log->record_date)->format('M d, Y') }}</td>
                                                <td class="py-2 pr-4">{{ $log->ip_address ?? '-' }}</td>
                                                <td class="py-2 truncate max-w-[240px]" title="{{ $log->user_agent }}">{{ \Illuminate\Support\Str::limit($log->user_agent, 60) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        (function(){
          const tooltips = () => Array.from(document.querySelectorAll('[role="tooltip"]'));
          const buttons = () => Array.from(document.querySelectorAll('[data-popover-target]'));

          const setAria = (btn, expanded) => {
            if (!btn) return;
            btn.setAttribute('aria-expanded', expanded ? 'true' : 'false');
          };

          const hideAll = () => {
            tooltips().forEach(el => { el.classList.add('invisible'); el.classList.add('opacity-0'); });
            buttons().forEach(b => setAria(b, false));
          };

          const position = (btn, pop) => {
            const rect = btn.getBoundingClientRect();
            const scrollY = window.scrollY || document.documentElement.scrollTop;
            const scrollX = window.scrollX || document.documentElement.scrollLeft;
            pop.style.top = (rect.bottom + scrollY + 8) + 'px';
            pop.style.left = Math.min(rect.left + scrollX, window.innerWidth - 320) + 'px';
          };

          const showPopover = (btn) => {
            const id = btn?.getAttribute('data-popover-target');
            if (!id) return;
            const pop = document.getElementById(id);
            if (!pop) return;
            hideAll();
            position(btn, pop);
            pop.classList.remove('invisible');
            pop.classList.remove('opacity-0');
            setAria(btn, true);
          };

          document.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-popover-target]');
            const insidePopover = e.target.closest('[role="tooltip"]');
            if (insidePopover && !btn) return; // allow clicks inside
            if (!btn) { hideAll(); return; }
            // if same button already open, close; else open
            const id = btn.getAttribute('data-popover-target');
            const pop = document.getElementById(id);
            const isOpen = pop && !pop.classList.contains('invisible');
            if (isOpen) { hideAll(); return; }
            showPopover(btn);
          });

          // Touch/Pointer support for mobile
          document.addEventListener('pointerdown', (e) => {
            const btn = e.target.closest('[data-popover-target]');
            if (!btn) return;
            e.preventDefault();
            e.stopPropagation();
            const id = btn.getAttribute('data-popover-target');
            const pop = document.getElementById(id);
            const isOpen = pop && !pop.classList.contains('invisible');
            if (isOpen) { hideAll(); return; }
            showPopover(btn);
          }, { passive: false });

          // Hover and focus support
          buttons().forEach(btn => {
            const id = btn.getAttribute('data-popover-target');
            const pop = document.getElementById(id);
            if (!pop) return;
            let hideTimer;
            const show = () => { clearTimeout(hideTimer); showPopover(btn); };
            const scheduleHide = () => {
              hideTimer = setTimeout(() => {
                pop.classList.add('invisible');
                pop.classList.add('opacity-0');
                setAria(btn, false);
              }, 150);
            };
            btn.addEventListener('mouseenter', show);
            btn.addEventListener('focus', show);
            btn.addEventListener('mouseleave', scheduleHide);
            btn.addEventListener('blur', scheduleHide);
            // Prevent immediate hide on quick taps
            btn.addEventListener('touchstart', (ev) => { clearTimeout(hideTimer); showPopover(btn); }, { passive: true });
            pop.addEventListener('mouseenter', () => clearTimeout(hideTimer));
            pop.addEventListener('mouseleave', scheduleHide);
          });

          // Close on scroll/resize/ESC
          window.addEventListener('scroll', hideAll, { passive: true });
          window.addEventListener('resize', hideAll);
          document.addEventListener('keydown', (e) => { if (e.key === 'Escape') hideAll(); });
        })();
    </script>
</x-app-layout>
