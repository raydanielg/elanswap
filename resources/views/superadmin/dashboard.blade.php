@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Superadmin Dashboard</h1>
    <p>Welcome, {{ auth()->user()->name }} (role: {{ auth()->user()->role }}).</p>
</div>
@endsection
