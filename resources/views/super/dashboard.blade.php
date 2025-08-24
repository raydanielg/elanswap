@extends('super.layouts.app')

@section('content')
<div class="max-w-7xl w-full mx-auto">
  <div class="mb-4 sm:mb-6">
    <h1 class="text-2xl font-bold">Dashboard</h1>
    <div class="mt-1 text-gray-600">Welcome back, {{ auth()->user()->name }}.</div>
    <!-- dashed underline -->
    <div class="mt-3 border-t border-dashed border-gray-300"></div>
  </div>

  <!-- KPI Cards (colorful with counters) -->
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4">
    <div class="rounded-lg p-4 sm:p-5 bg-gradient-to-br from-sky-50 to-sky-100 border border-sky-200">
      <div class="flex items-center justify-between">
        <div class="text-sm text-sky-800">Users</div>
        <span class="material-symbols-outlined text-sky-600">group</span>
      </div>
      <div class="mt-1 text-2xl font-semibold text-sky-900"><span data-counter data-target="{{ (int) ($userCount ?? 0) }}">0</span></div>
    </div>
    <div class="rounded-lg p-4 sm:p-5 bg-gradient-to-br from-emerald-50 to-emerald-100 border border-emerald-200">
      <div class="flex items-center justify-between">
        <div class="text-sm text-emerald-800">Active Sessions</div>
        <span class="material-symbols-outlined text-emerald-600">schedule</span>
      </div>
      <div class="mt-1 text-2xl font-semibold text-emerald-900"><span data-counter data-target="{{ (int) ($activeSessions ?? 0) }}">0</span></div>
    </div>
    <div class="rounded-lg p-4 sm:p-5 bg-gradient-to-br from-amber-50 to-amber-100 border border-amber-200">
      <div class="flex items-center justify-between">
        <div class="text-sm text-amber-800">Reports</div>
        <span class="material-symbols-outlined text-amber-600">assignment</span>
      </div>
      <div class="mt-1 text-2xl font-semibold text-amber-900"><span data-counter data-target="{{ (int) ($reportsCount ?? 0) }}">0</span></div>
    </div>
  </div>

  <div class="mt-4 sm:mt-6 grid grid-cols-1 lg:grid-cols-3 gap-3 sm:gap-4">
    <!-- Recent Login Activity -->
    <div class="lg:col-span-2 rounded-lg border border-gray-200 bg-white p-4 sm:p-5">
      <h2 class="text-lg font-semibold flex items-center gap-2"><span class="material-symbols-outlined">history</span> Recent Login Activity</h2>
      @php
        $loginLogs = ($recentLogs ?? collect())->where('log_type', 'login')->take(10);
      @endphp
      @if($loginLogs->isEmpty())
        <div class="mt-3 text-sm text-gray-600">No activity yet.</div>
      @else
        <div class="mt-3 overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead>
              <tr class="text-left text-gray-500">
                <th class="py-2 pr-4">Time</th>
                <th class="py-2 pr-4">User</th>
                <th class="py-2 pr-4">IP</th>
                <th class="py-2">Agent</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              @foreach($loginLogs as $log)
                <tr>
                  <td class="py-2 pr-4 whitespace-nowrap">{{ optional($log->created_at)->diffForHumans() }}</td>
                  <td class="py-2 pr-4 whitespace-nowrap">{{ $log->user->name ?? 'System' }}</td>
                  <td class="py-2 pr-4 whitespace-nowrap">{{ $log->ip_address ?? '-' }}</td>
                  <td class="py-2">{{ \Illuminate\Support\Str::limit($log->user_agent ?? '-', 60) }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>

    <!-- OTP Requests Table -->
    <div class="rounded-lg border border-gray-200 bg-white p-4 sm:p-5">
      <h2 class="text-lg font-semibold flex items-center gap-2"><span class="material-symbols-outlined">key</span> OTP Requests</h2>
      @php
        $otpList = ($otpRequests ?? collect());
      @endphp
      @if($otpList->isEmpty())
        <div class="mt-3 text-sm text-gray-600">No OTP requests found.</div>
      @else
        <div class="mt-3 overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead>
              <tr class="text-left text-gray-500">
                <th class="py-2 pr-4">Time</th>
                <th class="py-2 pr-4">User</th>
                <th class="py-2 pr-4">Phone</th>
                <th class="py-2 pr-4">OTP</th>
                <th class="py-2 pr-4">Verified</th>
                <th class="py-2">Expires</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              @foreach($otpList as $otp)
                <tr>
                  <td class="py-2 pr-4 whitespace-nowrap">{{ optional($otp->created_at)->diffForHumans() }}</td>
                  <td class="py-2 pr-4 whitespace-nowrap">{{ $otp->user->name ?? '—' }}</td>
                  <td class="py-2 pr-4 whitespace-nowrap">{{ $otp->phone ?? '—' }}</td>
                  <td class="py-2 pr-4 whitespace-nowrap font-mono">{{ $otp->otp_plain ?? '• • • • • •' }}</td>
                  <td class="py-2 pr-4 whitespace-nowrap">
                    @if($otp->is_verified)
                      <span class="inline-flex items-center px-2 py-0.5 rounded bg-emerald-50 text-emerald-700">Yes</span>
                    @else
                      <span class="inline-flex items-center px-2 py-0.5 rounded bg-amber-50 text-amber-700">No</span>
                    @endif
                  </td>
                  <td class="py-2 whitespace-nowrap">{{ optional($otp->expires_at)->diffForHumans() }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>

    <!-- Calendar + Quick Links -->
    <div class="rounded-lg border border-gray-200 bg-white p-4 sm:p-5">
      <h2 class="text-lg font-semibold flex items-center gap-2"><span class="material-symbols-outlined">calendar_month</span> Calendar</h2>
      @php
        $today = \Carbon\Carbon::today();
        $start = (clone $today)->startOfMonth()->startOfWeek();
        $end = (clone $today)->endOfMonth()->endOfWeek();
      @endphp
      <div class="mt-3">
        <div class="grid grid-cols-7 text-xs text-gray-500">
          <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
        </div>
        <div class="mt-1 grid grid-cols-7 gap-1">
          @for($date = $start->copy(); $date <= $end; $date->addDay())
            <div class="h-9 rounded flex items-center justify-center text-xs {{ ($date->month === $today->month) ? 'bg-gray-50 text-gray-700' : 'text-gray-400' }} {{ $date->isToday() ? 'ring-2 ring-primary-500 font-semibold' : '' }}">
              {{ $date->day }}
            </div>
          @endfor
        </div>
      </div>

      <div class="mt-5">
        <h3 class="text-sm font-semibold">Quick Links</h3>
        <ul class="mt-2 space-y-2 text-sm">
          <li><a class="text-primary-600 hover:underline" href="{{ url('/super/users') }}">Manage Users</a></li>
          <li><a class="text-primary-600 hover:underline" href="{{ url('/super/settings/general') }}">System Settings</a></li>
          <li><a class="text-primary-600 hover:underline" href="{{ url('/super/reports') }}">View Reports</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- OTP Requests Table -->
  <div class="mt-4 sm:mt-6 rounded-lg border border-gray-200 bg-white p-4 sm:p-5">
    <h2 class="text-lg font-semibold flex items-center gap-2"><span class="material-symbols-outlined">key</span> OTP Requests</h2>
    @php
      $otpList = ($otpRequests ?? collect());
    @endphp
    @if($otpList->isEmpty())
      <div class="mt-3 text-sm text-gray-600">No OTP requests found.</div>
    @else
      <div class="mt-3 overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead>
            <tr class="text-left text-gray-500">
              <th class="py-2 pr-4">Time</th>
              <th class="py-2 pr-4">User</th>
              <th class="py-2 pr-4">Phone</th>
              <th class="py-2 pr-4">OTP</th>
              <th class="py-2 pr-4">Verified</th>
              <th class="py-2">Expires</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @foreach($otpList as $otp)
              <tr>
                <td class="py-2 pr-4 whitespace-nowrap">{{ optional($otp->created_at)->diffForHumans() }}</td>
                <td class="py-2 pr-4 whitespace-nowrap">{{ $otp->user->name ?? '—' }}</td>
                <td class="py-2 pr-4 whitespace-nowrap">{{ $otp->phone ?? '—' }}</td>
                <td class="py-2 pr-4 whitespace-nowrap font-mono">{{ $otp->otp_plain ?? '• • • • • •' }}</td>
                <td class="py-2 pr-4 whitespace-nowrap">
                  @if($otp->is_verified)
                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-emerald-50 text-emerald-700">Yes</span>
                  @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-amber-50 text-amber-700">No</span>
                  @endif
                </td>
                <td class="py-2 whitespace-nowrap">{{ optional($otp->expires_at)->diffForHumans() }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>

  <!-- Counter animation script -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const counters = document.querySelectorAll('[data-counter]');
      counters.forEach(el => {
        const target = parseInt(el.getAttribute('data-target') || '0', 10);
        const duration = 800; // ms
        const start = performance.now();
        function tick(now){
          const p = Math.min(1, (now - start) / duration);
          const val = Math.floor(target * (0.5 - Math.cos(Math.PI * p) / 2)); // easeInOut
          el.textContent = val.toLocaleString();
          if(p < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
      });
    });
  </script>
</div>
@endsection
