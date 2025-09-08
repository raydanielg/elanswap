<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\FeatureController as AdminFeatureController;
use App\Models\User;
use App\Models\Log;
use App\Models\OtpVerification;
use App\Models\Application;
use App\Models\Category;
use App\Models\Region;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\NewsletterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\ExchangeRequestController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Admin\BlogPostController as AdminBlogPostController;
use App\Http\Controllers\Admin\ExchangeRequestController as AdminExchangeRequestController;
use App\Http\Controllers\ProfileCompletionController;
use App\Http\Controllers\PaymentController;
use App\Models\Feature;
use Illuminate\Http\Request;

// Serve the exported Next.js frontend as the public homepage
Route::get('/', function () {
    return redirect('/site/');
})->name('home.public');

// Redirect clean paths to exported static pages under /site/
Route::get('/about', fn() => redirect('/site/about/'));
Route::get('/regions', fn() => redirect('/site/regions/'));
Route::get('/features', fn() => redirect('/site/features/'));
Route::get('/contact', fn() => redirect('/site/contact/'));
Route::get('/categories', fn() => redirect('/site/categories/'));
Route::get('/privacy', fn() => redirect('/site/privacy/'));
Route::get('/terms', fn() => redirect('/site/terms/'));
Route::get('/help', fn() => redirect('/site/help/'));

// Keep the previous homepage available at /landing
Route::get('/landing', function () {
    $features = Feature::active()->orderBy('sort_order')->orderBy('id')->get();
    return view('home.index', compact('features'));
})->name('landing');

// Public tracking endpoint: GET /track?tracking_id=ELS0001
Route::get('/track', function (Request $request) {
    $code = trim((string) $request->query('tracking_id', ''));
    if ($code === '') {
        return response()->json([
            'ok' => false,
            'status' => 'invalid',
            'message' => 'Tracking ID is required.',
        ], 400);
    }

    $app = Application::with(['toRegion','toDistrict','fromRegion','fromDistrict','pairedApplication'])
        ->where('code', $code)
        ->first();

    if (!$app) {
        return response()->json([
            'ok' => false,
            'status' => 'not_found',
            'message' => 'Hakuna ombi lenye ID hiyo.',
        ], 404);
    }

    $matched = (bool) $app->paired_application_id;
    return response()->json([
        'ok' => true,
        'status' => $app->status,
        'matched' => $matched,
        'code' => $app->code,
        'data' => [
            'from' => [
                'region' => optional($app->fromRegion)->name,
                'district' => optional($app->fromDistrict)->name,
            ],
            'to' => [
                'region' => optional($app->toRegion)->name,
                'district' => optional($app->toDistrict)->name,
            ],
            'paired_code' => optional($app->pairedApplication)->code,
        ],
        'message' => $matched ? 'Ombi limepata mechi.' : 'Bado halijapata mechi.',
    ]);
})->name('track');

// Installer
Route::middleware('installer')->group(function () {
    Route::get('/install', [InstallController::class, 'index'])->name('installer.index');
    Route::post('/install', [InstallController::class, 'store'])->name('installer.store');
});

// Stations within a region that have applications (from_station)
Route::get('/api/regions/{region}/stations-with-apps', function (Region $region) {
    $stations = DB::table('stations')
        ->join('districts', 'stations.district_id', '=', 'districts.id')
        ->leftJoin('applications', 'applications.from_station_id', '=', 'stations.id')
        ->where('districts.region_id', $region->id)
        ->groupBy('stations.id', 'stations.name', 'stations.district_id', 'stations.category_id')
        ->select(
            'stations.id',
            'stations.name',
            'stations.district_id',
            DB::raw('COUNT(applications.id) as applications_count')
        )
        ->havingRaw('COUNT(applications.id) > 0')
        ->orderByDesc('applications_count')
        ->orderBy('stations.name')
        ->get();

    // Attach district name for display
    $districtNames = \App\Models\District::whereIn('id', $stations->pluck('district_id'))->pluck('name', 'id');

    $data = $stations->map(function ($s) use ($districtNames) {
        return [
            'id' => (int) $s->id,
            'name' => $s->name,
            'district' => (string) ($districtNames[$s->district_id] ?? ''),
            'applications_count' => (int) $s->applications_count,
        ];
    });

    return response()->json([
        'ok' => true,
        'region' => [ 'id' => $region->id, 'name' => $region->name ],
        'data' => $data,
    ]);
});

// Applications for a specific station (from_station)
Route::get('/api/stations/{station}/applications', function (\App\Models\Station $station) {
    $apps = \App\Models\Application::query()
        ->where('from_station_id', $station->id)
        ->orderByDesc('submitted_at')
        ->limit(50)
        ->get()
        ->map(function ($a) {
            return [
                'id' => $a->id,
                'code' => $a->code,
                'status' => $a->status,
                'submitted_at' => optional($a->submitted_at)->toIso8601String(),
                'from_district_id' => $a->from_district_id,
                'to_region_id' => $a->to_region_id,
                'to_district_id' => $a->to_district_id,
            ];
        });

    return response()->json([
        'ok' => true,
        'station' => [ 'id' => $station->id, 'name' => $station->name ],
        'data' => $apps,
    ]);
});

// Districts under a specific region
Route::get('/api/regions/{region}/districts', function (Region $region) {
    $districts = \App\Models\District::query()
        ->where('region_id', $region->id)
        ->withCount('stations')
        ->orderBy('name')
        ->get()
        ->map(fn($d) => [
            'id' => $d->id,
            'name' => $d->name,
            'slug' => Str::slug($d->name),
            'stations_count' => (int) $d->stations_count,
        ]);

    return response()->json([
        'ok' => true,
        'region' => [
            'id' => $region->id,
            'name' => $region->name,
            'slug' => Str::slug($region->name),
        ],
        'data' => $districts,
    ]);
});

// Public regions JSON for the Next.js frontend
Route::get('/api/regions', function () {
    $regions = Region::query()
        ->select('regions.*')
        ->withCount('districts')
        ->selectSub(
            DB::table('stations')
                ->join('districts', 'stations.district_id', '=', 'districts.id')
                ->whereColumn('districts.region_id', 'regions.id')
                ->selectRaw('COUNT(*)'),
            'stations_count'
        )
        ->orderBy('name')
        ->get()
        ->map(fn($r) => [
            'id' => $r->id,
            'name' => $r->name,
            'slug' => Str::slug($r->name),
            'districts_count' => (int) ($r->districts_count ?? 0),
            'stations_count' => (int) ($r->stations_count ?? 0),
        ]);

    return response()->json([
        'ok' => true,
        'data' => $regions,
    ]);
});

Route::get('/home', function () {
    $user = auth()->user();
    if ($user && in_array($user->role ?? 'user', ['admin','superadmin'], true)) {
        return redirect()->route('admin.dashboard');
    }
    $completed = $user && ($user->region_id && $user->district_id && $user->category_id && $user->station_id);
    if (! $completed) {
        return redirect()->route('profile.edit');
    }
    // If profile completed but not paid, send to profile to finalize
    if ($user && method_exists($user, 'hasPaid') && ! $user->hasPaid()) {
        return redirect()->route('profile.edit')->with('status', 'Tafadhali kamilisha malipo ili kuendelea.');
    }
    return redirect()->route('dashboard');
})->middleware(['auth', 'verified'])->name('home');

Route::get('/dashboard', function () {
    $applicationsCount = \App\Models\Application::where('user_id', auth()->id())->count();
    // Destinations counter: total pending applications overall
    $destinationsCount = \App\Models\Application::where('status', 'pending')->count();
    return view('dashboard', compact('applicationsCount', 'destinationsCount'));
})->middleware(['auth', 'verified', \App\Http\Middleware\EnsureProfileCompleted::class, \App\Http\Middleware\EnsurePaymentCompleted::class])->name('dashboard');

// Payment page (accessible after auth, even if unpaid)
Route::middleware(['auth', \App\Http\Middleware\EnsureProfileCompleted::class])->group(function () {
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');
    Route::post('/payment', [PaymentController::class, 'pay'])->name('payment.pay');
    // Initiate a push payment (mock)
    Route::post('/payment/push', [PaymentController::class, 'requestPush'])->name('payment.push');
    // Poll latest payment status for current user
    Route::get('/payment/status', [PaymentController::class, 'status'])->name('payment.status');
});

// Webhook listener (public, CSRF-exempt via middleware config)
Route::match(['GET', 'POST'], '/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');
// Alternate status callback listener compatible with raw php://input JSON
Route::post('/payment/status-notify', [PaymentController::class, 'statusNotify'])->name('payment.status.notify');

// Profile pages (accessible without payment, but require profile completion middleware to manage flow)
Route::middleware(['auth', \App\Http\Middleware\EnsureProfileCompleted::class])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Profile completion dependent data endpoints
    Route::get('/profile/regions', [ProfileCompletionController::class, 'regions'])->name('profile.regions');
    Route::get('/profile/districts', [ProfileCompletionController::class, 'districts'])->name('profile.districts');
    Route::get('/profile/categories', [ProfileCompletionController::class, 'categories'])->name('profile.categories');
    Route::get('/profile/stations', [ProfileCompletionController::class, 'stations'])->name('profile.stations');
    Route::post('/profile/complete', [ProfileCompletionController::class, 'store'])->name('profile.complete.store');
});

// App features require completed profile; payment only for selected actions
Route::middleware(['auth', \App\Http\Middleware\EnsureProfileCompleted::class])->group(function () {
    // Applications (read-only routes allowed without payment)
    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/search', [ApplicationController::class, 'search'])->name('applications.search');
    Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::get('/applications/{application}/peek', [ApplicationController::class, 'peek'])->name('applications.peek');
    Route::get('/applications/{application}/details', [ApplicationController::class, 'details'])->name('applications.details');

    // Applications (actions require payment)
    Route::get('/applications/create', [ApplicationController::class, 'create'])->middleware('paid')->name('applications.create');
    Route::post('/applications', [ApplicationController::class, 'store'])->middleware('paid')->name('applications.store');
    Route::post('/applications/{application}/approve', [ApplicationController::class, 'approve'])->middleware('paid')->name('applications.approve');
    Route::post('/applications/{application}/approve-with/{match}', [ApplicationController::class, 'approveWithMatch'])->middleware('paid')->name('applications.approve.match');
    Route::post('/applications/{application}/reject', [ApplicationController::class, 'reject'])->middleware('paid')->name('applications.reject');
    Route::post('/applications/{application}/request-deletion', [ApplicationController::class, 'requestDeletion'])->middleware('paid')->name('applications.requestDeletion');

    // Destinations (list allowed, viewing a specific region requires payment)
    Route::get('/destinations', [DestinationController::class, 'index'])->name('destinations.index');
    Route::get('/destinations/{region}', [DestinationController::class, 'show'])->middleware('paid')->name('destinations.show');

    // Exchange Requests (actions require payment)
    Route::post('/exchange-requests', [ExchangeRequestController::class, 'store'])->middleware('paid')->name('exchange-requests.store');
    Route::post('/exchange-requests/{requestModel}/accept', [ExchangeRequestController::class, 'accept'])->middleware('paid')->name('exchange-requests.accept');
    Route::post('/exchange-requests/{requestModel}/reject', [ExchangeRequestController::class, 'reject'])->middleware('paid')->name('exchange-requests.reject');

    // My Requests (requires payment)
    Route::get('/my-requests', [ExchangeRequestController::class, 'index'])->middleware('paid')->name('requests.index');
    Route::get('/my-requests/{requestModel}', [ExchangeRequestController::class, 'show'])->middleware('paid')->name('requests.show');

    // Blog (view allowed)
    Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
});

// Admin area
Route::middleware(['auth', 'verified', \App\Http\Middleware\RoleMiddleware::class . ':admin'])->prefix('admin')->group(function () {
    // Hitting /admin should go to admin dashboard
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Announcements (Features) management
    Route::resource('features', AdminFeatureController::class)->names('admin.features');
    Route::post('features/{feature}/toggle', [AdminFeatureController::class, 'toggle'])->name('admin.features.toggle');

    // Blog posts CRUD
    Route::resource('blog-posts', AdminBlogPostController::class)->names('admin.blog');

    // Exchange Requests (admin overview)
    Route::get('/requests', [AdminExchangeRequestController::class, 'index'])->name('admin.requests.index');
    Route::get('/requests/{exchangeRequest}', [AdminExchangeRequestController::class, 'show'])->name('admin.requests.show');
    Route::post('/requests/{exchangeRequest}/approve', [AdminExchangeRequestController::class, 'approve'])->name('admin.requests.approve');
    Route::post('/requests/{exchangeRequest}/reject', [AdminExchangeRequestController::class, 'reject'])->name('admin.requests.reject');

    // Applications (admin overview)
    Route::get('/applications', [\App\Http\Controllers\Admin\ApplicationController::class, 'index'])->name('admin.applications.index');
    Route::get('/applications/{application}/peek', [\App\Http\Controllers\Admin\ApplicationController::class, 'peek'])->name('admin.applications.peek');
    Route::delete('/applications/{application}', [\App\Http\Controllers\Admin\ApplicationController::class, 'destroy'])->name('admin.applications.destroy');

    // Admin user profile view/edit
    Route::get('/users/{user}/profile', [\App\Http\Controllers\Admin\UserProfileController::class, 'show'])->name('admin.users.profile');
    Route::put('/users/{user}/profile', [\App\Http\Controllers\Admin\UserProfileController::class, 'update'])->name('admin.users.profile.update');

    // Admin self profile (no user id)
    Route::get('/profile', [\App\Http\Controllers\Admin\UserProfileController::class, 'profile'])->name('admin.profile');
    Route::put('/profile', [\App\Http\Controllers\Admin\UserProfileController::class, 'profileUpdate'])->name('admin.profile.update');

    // Admin change password
    Route::get('/profile/password', [\App\Http\Controllers\Admin\UserProfileController::class, 'password'])->name('admin.profile.password');
    Route::put('/profile/password', [\App\Http\Controllers\Admin\UserProfileController::class, 'passwordUpdate'])->name('admin.profile.password.update');

    // Admin Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'general'])->name('admin.settings.general');
    Route::put('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'updateGeneral'])->name('admin.settings.general.update');
    Route::get('/settings/branding', [\App\Http\Controllers\Admin\SettingsController::class, 'branding'])->name('admin.settings.branding');
    Route::get('/settings/email', [\App\Http\Controllers\Admin\SettingsController::class, 'email'])->name('admin.settings.email');
    Route::put('/settings/email', [\App\Http\Controllers\Admin\SettingsController::class, 'updateEmail'])->name('admin.settings.email.update');
    // Placeholder routes for existing menu items
    Route::view('/settings/site-management', 'admin.settings.site-management')->name('admin.settings.site');
    Route::view('/settings/other', 'admin.settings.other')->name('admin.settings.other');

    // Blog (admin)
    Route::get('/blog', [\App\Http\Controllers\Admin\PostController::class, 'index'])->name('admin.blog.index');
    Route::get('/blog/create', [\App\Http\Controllers\Admin\PostController::class, 'create'])->name('admin.blog.create');
    Route::post('/blog', [\App\Http\Controllers\Admin\PostController::class, 'store'])->name('admin.blog.store');
    Route::get('/blog/{post}/edit', [\App\Http\Controllers\Admin\PostController::class, 'edit'])->name('admin.blog.edit');
    Route::put('/blog/{post}', [\App\Http\Controllers\Admin\PostController::class, 'update'])->name('admin.blog.update');
    Route::delete('/blog/{post}', [\App\Http\Controllers\Admin\PostController::class, 'destroy'])->name('admin.blog.destroy');
    // Convenience manage path
    Route::get('/blog/manage', function () { return redirect()->route('admin.blog.create'); });

    // Locations (admin overview)
    Route::get('/locations', [\App\Http\Controllers\Admin\LocationController::class, 'index'])->name('admin.locations.index');
    // Regions
    Route::get('/locations/regions', [\App\Http\Controllers\Admin\RegionController::class, 'index'])->name('admin.locations.regions');
    Route::post('/locations/regions', [\App\Http\Controllers\Admin\RegionController::class, 'store'])->name('admin.locations.regions.store');
    Route::put('/locations/regions/{region}', [\App\Http\Controllers\Admin\RegionController::class, 'update'])->name('admin.locations.regions.update');
    Route::delete('/locations/regions/{region}', [\App\Http\Controllers\Admin\RegionController::class, 'destroy'])->name('admin.locations.regions.destroy');
    // Districts
    Route::get('/locations/districts', [\App\Http\Controllers\Admin\DistrictController::class, 'index'])->name('admin.locations.districts');
    Route::post('/locations/districts', [\App\Http\Controllers\Admin\DistrictController::class, 'store'])->name('admin.locations.districts.store');
    Route::put('/locations/districts/{district}', [\App\Http\Controllers\Admin\DistrictController::class, 'update'])->name('admin.locations.districts.update');
    Route::delete('/locations/districts/{district}', [\App\Http\Controllers\Admin\DistrictController::class, 'destroy'])->name('admin.locations.districts.destroy');

    // Users (admin management)
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    // Convenience filter path for banned users (must be before users/{user})
    Route::get('/users/banned', function () {
        return redirect()->route('admin.users.index', ['banned' => 'yes']);
    });
    Route::get('/users/{user}/peek', [\App\Http\Controllers\Admin\UserController::class, 'peek'])->name('admin.users.peek');
    Route::post('/users/{user}/ban', [\App\Http\Controllers\Admin\UserController::class, 'ban'])->name('admin.users.ban');
    Route::post('/users/{user}/unban', [\App\Http\Controllers\Admin\UserController::class, 'unban'])->name('admin.users.unban');
    Route::post('/users/{user}/reset-password', [\App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->name('admin.users.reset');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');
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

// Public categories JSON for the Next.js frontend
Route::get('/api/categories', function () {
    $cats = Category::query()
        ->when(Schema::hasColumn('categories', 'is_active'), fn($q) => $q->where('is_active', 1))
        ->withCount('stations')
        ->orderBy('name')
        ->get()
        ->map(fn($c) => [
            'id' => $c->id,
            'name' => $c->name,
            'slug' => Str::slug($c->name),
            'count' => $c->stations_count,
        ]);

    return response()->json([
        'ok' => true,
        'data' => $cats,
    ]);
});
