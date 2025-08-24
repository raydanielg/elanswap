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
                                    <h1 class="text-2xl font-bold text-white">
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
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-2 bg-primary-100 rounded-lg">
                                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-600">My Applications</h3>
                                <p class="text-2xl font-bold text-gray-900">{{ $applicationsCount }}</p>
                            </div>
                        </div>
                        <a href="#" class="text-sm text-primary-600 hover:text-primary-700 font-medium">View</a>
                    </div>
                </div>

                <!-- Destinations -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-2 bg-indigo-100 rounded-lg">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-600">Destinations</h3>
                                <p class="text-2xl font-bold text-gray-900">{{ $destinationsCount }}</p>
                            </div>
                        </div>
                        <a href="#" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">Browse</a>
                    </div>
                </div>

                <!-- Total Regions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-2 bg-emerald-100 rounded-lg">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-600">Total Regions</h3>
                                <p class="text-2xl font-bold text-gray-900">{{ $regionsCount }}</p>
                            </div>
                        </div>
                        <a href="#regions" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">Explore</a>
                    </div>
                </div>
            </div>

            <!-- Recent Activity + Announcements -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Activity (User only) -->
                <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
                        <span class="text-xs text-gray-500">Your latest actions</span>
                    </div>
                    <div class="p-6">
                        @php
                            $recentLogs = \App\Models\Log::where('user_id', Auth::id())
                                ->orderByDesc('created_at')
                                ->limit(6)
                                ->get();
                        @endphp
                        @if($recentLogs->isEmpty())
                            <p class="text-gray-500 text-sm">No recent activity found.</p>
                        @else
                            <ul class="divide-y divide-gray-100">
                                @foreach($recentLogs as $log)
                                    <li class="py-3 flex items-start">
                                        <div class="mr-3 mt-0.5 text-gray-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm text-gray-800">{{ $log->text ?? ucfirst($log->log_type ?? 'activity') }}</p>
                                            <p class="text-xs text-gray-500">{{ optional($log->created_at)->format('M d, Y H:i') ?? optional($log->record_date)->format('M d, Y') }}</p>
                                        </div>
                                        @if(!empty($log->status))
                                            <span class="ml-3 text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600">{{ $log->status }}</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <!-- Announcements (moved up) -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Announcements</h3>
                        <span class="text-xs text-gray-500">Latest news</span>
                    </div>
                    <div class="p-6 space-y-4">
                        @php
                            $announcements = \App\Models\Feature::active()->orderBy('sort_order')->limit(5)->get();
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
            </div>

            <!-- Last Login History -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
                <div class="lg:col-span-3 bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Last Login History</h3>
                        <span class="text-xs text-gray-500">Security overview</span>
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
</x-app-layout>
