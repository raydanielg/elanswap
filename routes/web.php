<?php

use App\Http\Controllers\ProfileController;
use App\Models\User;
use App\Models\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\NewsletterController;
use Illuminate\Support\Facades\Route;
use App\Models\Feature;

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
Route::middleware(['auth', 'verified', \App\Http\Middleware\RoleMiddleware::class . ':admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

// Superadmin area (URL prefix /super, route name unchanged)
Route::middleware(['auth', 'verified', \App\Http\Middleware\RoleMiddleware::class . ':superadmin'])->prefix('super')->group(function () {
    // Hitting /super should go to dashboard
    Route::get('/', function () {
        return redirect()->route('superadmin.dashboard');
    });
    Route::get('/dashboard', function () {
        $userCount = User::count();
        $activeSessions = DB::table('sessions')->count();
        $reportsCount = Log::count();
        $recentLogs = Log::with('user')->latest('created_at')->limit(6)->get();

        return view('super.dashboard', compact('userCount', 'activeSessions', 'reportsCount', 'recentLogs'));
    })->name('superadmin.dashboard');
});

// Legacy path support: /superadmin/dashboard -> /super/dashboard
Route::middleware(['auth', 'verified', \App\Http\Middleware\RoleMiddleware::class . ':superadmin'])->prefix('superadmin')->group(function () {
    // Hitting /superadmin should go to dashboard
    Route::get('/', function () {
        return redirect()->route('superadmin.dashboard');
    });
    Route::get('/dashboard', function () {
        return redirect()->route('superadmin.dashboard');
    });
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
