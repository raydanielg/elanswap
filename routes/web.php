<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\FeatureController as AdminFeatureController;
use App\Models\User;
use App\Models\Log;
use App\Models\OtpVerification;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\NewsletterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\ExchangeRequestController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Admin\BlogPostController as AdminBlogPostController;
use App\Http\Controllers\ProfileCompletionController;
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
    $applicationsCount = \App\Models\Application::where('user_id', auth()->id())->count();
    // Destinations counter: total pending applications overall
    $destinationsCount = \App\Models\Application::where('status', 'pending')->count();
    return view('dashboard', compact('applicationsCount', 'destinationsCount'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Profile completion dependent data endpoints
    Route::get('/profile/regions', [ProfileCompletionController::class, 'regions'])->name('profile.regions');
    Route::get('/profile/districts', [ProfileCompletionController::class, 'districts'])->name('profile.districts');
    Route::get('/profile/categories', [ProfileCompletionController::class, 'categories'])->name('profile.categories');
    Route::get('/profile/stations', [ProfileCompletionController::class, 'stations'])->name('profile.stations');
    Route::post('/profile/complete', [ProfileCompletionController::class, 'store'])->name('profile.complete.store');

    // Applications page + AJAX search
    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/search', [ApplicationController::class, 'search'])->name('applications.search');
    Route::get('/applications/create', [ApplicationController::class, 'create'])->name('applications.create');
    Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store');
    Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
    // JSON + actions
    Route::get('/applications/{application}/details', [ApplicationController::class, 'details'])->name('applications.details');
    Route::post('/applications/{application}/approve', [ApplicationController::class, 'approve'])->name('applications.approve');
    Route::post('/applications/{application}/approve-with/{match}', [ApplicationController::class, 'approveWithMatch'])->name('applications.approve.match');
    Route::post('/applications/{application}/reject', [ApplicationController::class, 'reject'])->name('applications.reject');
    Route::post('/applications/{application}/request-deletion', [ApplicationController::class, 'requestDeletion'])->name('applications.requestDeletion');

    // Destinations
    Route::get('/destinations', [DestinationController::class, 'index'])->name('destinations.index');
    Route::get('/destinations/{region}', [DestinationController::class, 'show'])->name('destinations.show');

    // Exchange Requests
    Route::post('/exchange-requests', [ExchangeRequestController::class, 'store'])->name('exchange-requests.store');
    Route::post('/exchange-requests/{requestModel}/accept', [ExchangeRequestController::class, 'accept'])->name('exchange-requests.accept');
    Route::post('/exchange-requests/{requestModel}/reject', [ExchangeRequestController::class, 'reject'])->name('exchange-requests.reject');

    // My Requests (sent by current user)
    Route::get('/my-requests', [ExchangeRequestController::class, 'index'])->name('requests.index');
    Route::get('/my-requests/{requestModel}', [ExchangeRequestController::class, 'show'])->name('requests.show');

    // Blog (public pages under auth for now)
    Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
});

// Admin area
Route::middleware(['auth', 'verified', \App\Http\Middleware\RoleMiddleware::class . ':admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Announcements (Features) management
    Route::resource('features', AdminFeatureController::class)->names('admin.features');
    Route::post('features/{feature}/toggle', [AdminFeatureController::class, 'toggle'])->name('admin.features.toggle');

    // Blog posts CRUD
    Route::resource('blog-posts', AdminBlogPostController::class)->names('admin.blog');
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
        $otpRequests = OtpVerification::with('user')->latest('created_at')->limit(10)->get();

        return view('super.dashboard', compact('userCount', 'activeSessions', 'reportsCount', 'recentLogs', 'otpRequests'));
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
