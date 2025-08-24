@extends('super.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
  <div class="mb-6">
    <h1 class="text-2xl font-bold">Dashboard</h1>
    <p class="text-gray-600">Welcome back, {{ auth()->user()->name }}.</p>
  </div>

  <!-- KPI Cards -->
  <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="rounded-lg border border-gray-200 bg-white p-4">
      <div class="text-sm text-gray-500 flex items-center gap-2"><span class="material-symbols-outlined text-primary-600">group</span> Users</div>
      <div class="mt-1 text-2xl font-semibold">{{ number_format($userCount ?? 0) }}</div>
    </div>
    <div class="rounded-lg border border-gray-200 bg-white p-4">
      <div class="text-sm text-gray-500 flex items-center gap-2"><span class="material-symbols-outlined text-primary-600">schedule</span> Active Sessions</div>
      <div class="mt-1 text-2xl font-semibold">{{ number_format($activeSessions ?? 0) }}</div>
    </div>
    <div class="rounded-lg border border-gray-200 bg-white p-4">
      <div class="text-sm text-gray-500 flex items-center gap-2"><span class="material-symbols-outlined text-primary-600">assignment</span> Reports</div>
      <div class="mt-1 text-2xl font-semibold">{{ number_format($reportsCount ?? 0) }}</div>
    </div>
    <div class="rounded-lg border border-gray-200 bg-white p-4">
      <div class="text-sm text-gray-500 flex items-center gap-2"><span class="material-symbols-outlined text-primary-600">monitoring</span> System Health</div>
      <div class="mt-1 text-2xl font-semibold">—</div>
    </div>
  </div>

  <div class="mt-6 grid lg:grid-cols-3 gap-4">
    <!-- Recent Activity -->
    <div class="lg:col-span-2 rounded-lg border border-gray-200 bg-white p-4">
      <h2 class="text-lg font-semibold">Recent Activity</h2>
      @if(($recentLogs ?? collect())->isEmpty())
        <div class="mt-3 text-sm text-gray-600">No activity yet.</div>
      @else
        <div class="mt-3 overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead>
              <tr class="text-left text-gray-500">
                <th class="py-2 pr-4">Time</th>
                <th class="py-2 pr-4">User</th>
                <th class="py-2 pr-4">Type</th>
                <th class="py-2">Message</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              @foreach($recentLogs as $log)
                <tr>
                  <td class="py-2 pr-4 whitespace-nowrap">{{ optional($log->created_at)->diffForHumans() }}</td>
                  <td class="py-2 pr-4 whitespace-nowrap">{{ $log->user->name ?? 'System' }}</td>
                  <td class="py-2 pr-4 whitespace-nowrap"><span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-100 text-gray-700">{{ $log->log_type ?? 'log' }}</span></td>
                  <td class="py-2">{{ \Illuminate\Support\Str::limit($log->text ?? '-', 120) }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>

    <!-- Quick Links -->
    <div class="rounded-lg border border-gray-200 bg-white p-4">
      <h2 class="text-lg font-semibold">Quick Links</h2>
      <ul class="mt-3 space-y-2 text-sm">
        <li><a class="text-primary-600 hover:underline" href="{{ url('/super/users') }}">Manage Users</a></li>
        <li><a class="text-primary-600 hover:underline" href="{{ url('/super/settings/general') }}">System Settings</a></li>
        <li><a class="text-primary-600 hover:underline" href="{{ url('/super/reports') }}">View Reports</a></li>
      </ul>
    </div>
  </div>
</div>
@endsection
