<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\NewsletterController;
use Illuminate\Support\Facades\Route;
use App\Models\Feature;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    $features = Feature::active()->orderBy('sort_order')->orderBy('id')->get();
    return view('home.index', compact('features'));
})->name('home.public');

// Installer
Route::middleware('installer')->group(function () {
    Route::get('/install', [InstallController::class, 'index'])->name('installer.index');
    Route::post('/install', [InstallController::class, 'store'])->name('installer.store');
});

Route::get('/home', function () {
    return redirect()->route('dashboard');
})->middleware(['auth', 'verified'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin area
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

// Superadmin area
Route::middleware(['auth', 'verified', 'role:superadmin'])->prefix('superadmin')->group(function () {
    Route::get('/dashboard', function () {
        return view('superadmin.dashboard');
    })->name('superadmin.dashboard');
});

require __DIR__.'/auth.php';

// Newsletter
Route::post('/newsletter', [NewsletterController::class, 'store'])->name('newsletter.store');

// TEMP: Debug latest SMS log (remove in production)
Route::get('/debug/sms-log', function () {
    $log = DB::table('logs')->where('log_type','sms')->orderByDesc('id')->first();
    if (!$log) {
        return response()->json(['message' => 'No SMS logs found'], 404);
    }
    return response()->json([
        'id'        => $log->id,
        'status'    => $log->status,
        'phone'     => $log->phone,
        'text'      => $log->text,
        'user_agent'=> json_decode($log->user_agent ?? '{}', true),
        'created_at'=> $log->created_at,
    ]);
});
