@extends('super.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
  <div class="mb-6">
    <h1 class="text-2xl font-bold">Dashboard</h1>
    <p class="text-gray-600">Welcome back, {{ auth()->user()->name }}.</p>
  </div>

  <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="rounded-lg border border-gray-200 bg-white p-4">
      <div class="text-sm text-gray-500">Users</div>
      <div class="mt-1 text-2xl font-semibold">—</div>
    </div>
    <div class="rounded-lg border border-gray-200 bg-white p-4">
      <div class="text-sm text-gray-500">Active Sessions</div>
      <div class="mt-1 text-2xl font-semibold">—</div>
    </div>
    <div class="rounded-lg border border-gray-200 bg-white p-4">
      <div class="text-sm text-gray-500">Reports</div>
      <div class="mt-1 text-2xl font-semibold">—</div>
    </div>
    <div class="rounded-lg border border-gray-200 bg-white p-4">
      <div class="text-sm text-gray-500">System Health</div>
      <div class="mt-1 text-2xl font-semibold">—</div>
    </div>
  </div>

  <div class="mt-6 grid lg:grid-cols-3 gap-4">
    <div class="lg:col-span-2 rounded-lg border border-gray-200 bg-white p-4">
      <h2 class="text-lg font-semibold">Recent Activity</h2>
      <div class="mt-3 text-sm text-gray-600">No activity yet.</div>
    </div>
    <div class="rounded-lg border border-gray-200 bg-white p-4">
      <h2 class="text-lg font-semibold">Quick Links</h2>
      <ul class="mt-3 space-y-2 text-sm">
        <li><a class="text-primary-600 hover:underline" href="#">Manage Users</a></li>
        <li><a class="text-primary-600 hover:underline" href="#">System Settings</a></li>
        <li><a class="text-primary-600 hover:underline" href="#">View Reports</a></li>
      </ul>
    </div>
  </div>
</div>
@endsection
