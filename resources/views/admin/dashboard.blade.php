@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-6">
    <div class="mb-4">
        <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900">Dashboard</h1>
        <div class="mt-2 border-b"></div>
    </div>

    <div class="mb-4 rounded-lg bg-white shadow p-4 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500">Welcome back</p>
            <p class="text-xl sm:text-2xl font-semibold text-gray-900">Welcome to Admin, {{ auth()->user()->name ?? 'Admin' }}</p>
            <p class="mt-1 text-sm text-gray-600">Manage users, requests, content and insights here.</p>
        </div>
        <img src="{{ asset('assets/empty-blog.svg') }}" alt="Welcome" class="hidden sm:block h-14 w-auto opacity-80">
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 mt-4">
        <div class="rounded-lg p-4 bg-white text-gray-900 shadow">
            <div class="flex items-center justify-between">
                <div x-data="{count:0,target: {{ \App\Models\User::count() }},dur:700}"
                     x-init="(() => { const s=performance.now(); const step=(t)=>{ const p=Math.min((t-s)/dur,1); count=Math.floor(target*p); if(p<1) requestAnimationFrame(step); else count=target; }; requestAnimationFrame(step); })()">
                    <div class="text-xs uppercase tracking-wide text-gray-500">Total Users</div>
                    <div class="mt-1 text-2xl font-semibold"><span x-text="count.toLocaleString()">0</span></div>
                </div>
                <div class="h-8 w-8 rounded bg-blue-100 flex items-center justify-center text-blue-600">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m6-6a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg p-4 bg-white text-gray-900 shadow">
            <div class="flex items-center justify-between">
                <div x-data="{count:0,target: {{ \App\Models\Visit::count() }},dur:700}"
                     x-init="(() => { const s=performance.now(); const step=(t)=>{ const p=Math.min((t-s)/dur,1); count=Math.floor(target*p); if(p<1) requestAnimationFrame(step); else count=target; }; requestAnimationFrame(step); })()">
                    <div class="text-xs uppercase tracking-wide text-gray-500">Visitors</div>
                    <div class="mt-1 text-2xl font-semibold"><span x-text="count.toLocaleString()">0</span></div>
                </div>
                <div class="h-8 w-8 rounded bg-emerald-100 flex items-center justify-center text-emerald-600">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3zm0 0c-4 0-7 2-7 4v2h14v-2c0-2-3-4-7-4z"/></svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg p-4 bg-white text-gray-900 shadow">
            <div class="flex items-center justify-between">
                <div x-data="{count:0,target: {{ \App\Models\Region::count() }},dur:700}"
                     x-init="(() => { const s=performance.now(); const step=(t)=>{ const p=Math.min((t-s)/dur,1); count=Math.floor(target*p); if(p<1) requestAnimationFrame(step); else count=target; }; requestAnimationFrame(step); })()">
                    <div class="text-xs uppercase tracking-wide text-gray-500">Regions</div>
                    <div class="mt-1 text-2xl font-semibold"><span x-text="count.toLocaleString()">0</span></div>
                </div>
                <div class="h-8 w-8 rounded bg-indigo-100 flex items-center justify-center text-indigo-600">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg p-4 bg-white text-gray-900 shadow">
            <div class="flex items-center justify-between">
                <div x-data="{count:0,target: {{ \App\Models\Post::count() }},dur:700}"
                     x-init="(() => { const s=performance.now(); const step=(t)=>{ const p=Math.min((t-s)/dur,1); count=Math.floor(target*p); if(p<1) requestAnimationFrame(step); else count=target; }; requestAnimationFrame(step); })()">
                    <div class="text-xs uppercase tracking-wide text-gray-500">Blog Posts</div>
                    <div class="mt-1 text-2xl font-semibold"><span x-text="count.toLocaleString()">0</span></div>
                </div>
                <div class="h-8 w-8 rounded bg-rose-100 flex items-center justify-center text-rose-600">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V7h18v12a2 2 0 01-2 2zM7 7V5a2 2 0 012-2h6a2 2 0 012 2v2"/></svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg p-4 bg-white text-gray-900 shadow">
            <div class="flex items-center justify-between">
                <div x-data="{count:0,target: {{ \App\Models\ExchangeRequest::count() }},dur:700}"
                     x-init="(() => { const s=performance.now(); const step=(t)=>{ const p=Math.min((t-s)/dur,1); count=Math.floor(target*p); if(p<1) requestAnimationFrame(step); else count=target; }; requestAnimationFrame(step); })()">
                    <div class="text-xs uppercase tracking-wide text-gray-500">Requests</div>
                    <div class="mt-1 text-2xl font-semibold"><span x-text="count.toLocaleString()">0</span></div>
                </div>
                <div class="h-8 w-8 rounded bg-amber-100 flex items-center justify-center text-amber-600">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </div>
            </div>
        </div>
    </div>

    @php
        $recentLogs = \App\Models\Log::latest('created_at')->limit(5)->get();
        $recentFeatures = \App\Models\Feature::latest('created_at')->limit(5)->get();
        $recentUsers = \App\Models\User::latest('created_at')->limit(5)->get();

        $topRegions = \App\Models\Region::select('id','name')->get();
        // Get top 5 regions by applications (to_region_id)
        $regionApps = [];
        foreach ($topRegions as $r) {
            $regionApps[$r->name] = \App\Models\Application::where('to_region_id',$r->id)->count();
        }
        arsort($regionApps);
        $regionApps = array_slice($regionApps, 0, 5, true);
        $regionLabels = array_keys($regionApps);
        $regionAppCounts = array_values($regionApps);
        $regionReqCounts = [];
        foreach ($regionLabels as $name) {
            $region = \App\Models\Region::where('name',$name)->first();
            $regionReqCounts[] = $region ? \App\Models\ExchangeRequest::whereHas('application', function($q) use ($region){ $q->where('to_region_id',$region->id); })->count() : 0;
        }

        // Real dynamic status breakdowns for Exchange Requests
        $reqStatusMap = \App\Models\ExchangeRequest::select('status', \Illuminate\Support\Facades\DB::raw('count(*) as c'))
            ->groupBy('status')
            ->pluck('c', 'status')
            ->toArray();
        // Preferred order for clarity; any unknown statuses will follow
        $preferredOrder = ['pending','approved','resolved','rejected','cancelled'];
        $orderedLabels = [];
        $orderedCounts = [];
        foreach ($preferredOrder as $key) {
            if (array_key_exists($key, $reqStatusMap)) {
                $orderedLabels[] = $key;
                $orderedCounts[] = (int)$reqStatusMap[$key];
                unset($reqStatusMap[$key]);
            }
        }
        // Append any remaining statuses alphabetically
        if (!empty($reqStatusMap)) {
            ksort($reqStatusMap);
            foreach ($reqStatusMap as $k => $v) {
                $orderedLabels[] = $k;
                $orderedCounts[] = (int)$v;
            }
        }
        // Fallback when there is no data
        if (empty($orderedLabels)) {
            $orderedLabels = ['no data'];
            $orderedCounts = [1];
        }
        $reqStatusLabels = $orderedLabels;
        $reqStatusCounts = $orderedCounts;
    @endphp

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="rounded-lg bg-white shadow p-4">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-semibold">Recent Activity</h3>
                <a href="#" class="text-xs text-blue-600">View all</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <tbody class="divide-y">
                        @forelse($recentLogs as $log)
                        <tr>
                            <td class="py-2 pr-3 truncate max-w-[12rem]">{{ $log->text }}</td>
                            <td class="py-2 px-3 text-gray-500">{{ $log->status }}</td>
                            <td class="py-2 pl-3 text-gray-400 text-right whitespace-nowrap">{{ $log->created_at?->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr><td class="py-2 text-gray-500">No recent activity</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-lg bg-white shadow p-4">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-semibold">Recent Announcements</h3>
                <a href="{{ route('admin.features.index') }}" class="text-xs text-blue-600">Manage</a>
            </div>
            <ul class="divide-y text-sm">
                @forelse($recentFeatures as $f)
                    <li class="py-2 flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <div class="font-medium truncate">{{ $f->title }}</div>
                            <div class="text-gray-500 truncate">{{ $f->description }}</div>
                        </div>
                        @if($f->is_active)
                        <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded text-[11px] bg-green-100 text-green-700">Active</span>
                        @else
                        <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded text-[11px] bg-gray-100 text-gray-700">Draft</span>
                        @endif
                    </li>
                @empty
                    <li class="py-2 text-gray-500">No announcements</li>
                @endforelse
            </ul>
        </div>

        <div class="rounded-lg bg-white shadow p-4">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-semibold">Recent Users</h3>
                <a href="{{ url('/admin/users') }}" class="text-xs text-blue-600">All users</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <tbody class="divide-y">
                        @forelse($recentUsers as $u)
                        <tr>
                            <td class="py-2 pr-3">{{ $u->name }}</td>
                            <td class="py-2 px-3 text-gray-500 hidden sm:table-cell">{{ $u->email }}</td>
                            <td class="py-2 pl-3 text-gray-400 text-right whitespace-nowrap">{{ $u->created_at?->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr><td class="py-2 text-gray-500">No recent users</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2 rounded-lg bg-white shadow p-4">
            <div class="mb-2">
                <h3 class="text-sm font-semibold">Applications vs Requests by Top Regions</h3>
            </div>
            <div class="relative h-64">
                <canvas id="barRegions"></canvas>
            </div>
        </div>
        <div class="rounded-lg bg-white shadow p-4">
            <div class="mb-2">
                <h3 class="text-sm font-semibold">Requests Status (Real Data)</h3>
            </div>
            <div class="relative h-64">
                <canvas id="pieStatus"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const regionLabels = @json($regionLabels);
  const appCounts = @json($regionAppCounts);
  const reqCounts = @json($regionReqCounts);

  const ctxBar = document.getElementById('barRegions');
  if (ctxBar) {
    new Chart(ctxBar, {
      type: 'bar',
      data: {
        labels: regionLabels,
        datasets: [
          {label: 'Applications', data: appCounts, backgroundColor: 'rgba(59, 130, 246, 0.85)', borderColor: 'rgb(59, 130, 246)', borderWidth: 0, borderRadius: 6, maxBarThickness: 28, categoryPercentage: 0.6, barPercentage: 0.8},
          {label: 'Requests', data: reqCounts, backgroundColor: 'rgba(16, 185, 129, 0.85)', borderColor: 'rgb(16, 185, 129)', borderWidth: 0, borderRadius: 6, maxBarThickness: 28, categoryPercentage: 0.6, barPercentage: 0.8},
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: { duration: 0 },
        transitions: { active: { animation: { duration: 0 } } },
        scales: {
          x: { stacked: false, ticks: { autoSkip: false } },
          y: { beginAtZero: true, ticks: { precision: 0 } }
        },
        plugins: {
          legend: { position: 'bottom' },
          tooltip: {
            callbacks: {
              label: (ctx) => {
                const i = ctx.dataIndex;
                const totalAtI = (appCounts[i] || 0) + (reqCounts[i] || 0);
                const v = ctx.parsed.y || 0;
                const pct = totalAtI ? ((v / totalAtI) * 100).toFixed(0) + '%' : '0%';
                return `${ctx.dataset.label}: ${v} (${pct})`;
              }
            }
          }
        }
      }
    });
  }

  const pie = document.getElementById('pieStatus');
  if (pie) {
    new Chart(pie, {
      type: 'doughnut',
      data: {
        labels: @json($reqStatusLabels),
        datasets: [
          {
            label: 'Requests',
            data: @json($reqStatusCounts),
            backgroundColor: (function(){
              // Consistent color mapping by status
              const map = {
                pending: '#f59e0b',
                approved: '#34d399',
                resolved: '#60a5fa',
                rejected: '#ef4444',
                cancelled: '#a78bfa'
              };
              const fallbacks = ['#93c5fd','#86efac','#fde68a','#fca5a5','#f472b6','#22d3ee','#a3a3a3'];
              const labels = @json($reqStatusLabels);
              return labels.map((l, i) => map[l] || fallbacks[i % fallbacks.length]);
            })(),
            borderWidth: 0,
            hoverOffset: 6,
            spacing: 2
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: { duration: 0 },
        cutout: '60%',
        plugins: {
          legend: { position: 'bottom' },
          tooltip: {
            callbacks: {
              label: (ctx) => {
                const counts = ctx.dataset.data;
                const total = counts.reduce((a, b) => a + b, 0) || 1;
                const v = ctx.parsed || 0;
                const pct = ((v / total) * 100).toFixed(0) + '%';
                return `${ctx.label}: ${v} (${pct})`;
              }
            }
          }
        }
      }
    });
  }
});
</script>
@endpush
