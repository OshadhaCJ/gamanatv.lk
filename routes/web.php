<?php

use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\BusController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeviceLogController;
use App\Http\Controllers\Admin\IncidentController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\OwnerController;
use App\Http\Controllers\Admin\ProcessingUnitController;
use App\Http\Controllers\Admin\RouteController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StorageSwapController;
use App\Http\Controllers\Admin\StorageUnitController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeviceWebhookController;
use App\Http\Controllers\GoogleAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Called directly by in-bus hardware — no auth, no CSRF (see bootstrap/app.php).
Route::match(['get', 'post'], '/webhook/device-status', [DeviceWebhookController::class, 'store'])
    ->name('webhook.device-status');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

    Route::get('/verify', [AuthController::class, 'showVerify'])->name('verify');
    Route::post('/verify', [AuthController::class, 'verifyOtp'])->name('verify.store');
    Route::post('/verify/resend', [AuthController::class, 'resendOtp'])->name('verify.resend');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');

    Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'role:developer,admin,coordinator'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/data', [DashboardController::class, 'data'])->name('dashboard.data');
        Route::post('/dashboard/alerts/{bus}/dismiss', [DashboardController::class, 'dismissAlert'])->name('dashboard.alerts.dismiss');

        Route::get('/logs', [DeviceLogController::class, 'index'])->name('logs');
        Route::post('/logs/{log}/resolve', [DeviceLogController::class, 'resolve'])->name('logs.resolve');

        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance');
        Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');

        Route::get('/storage-swaps', [StorageSwapController::class, 'index'])->name('storage-swaps');
        Route::post('/storage-swaps', [StorageSwapController::class, 'store'])->name('storage-swaps.store');
        Route::post('/storage-swaps/{swap}/acknowledge', [StorageSwapController::class, 'acknowledge'])->name('storage-swaps.acknowledge');

        Route::get('/incidents', [IncidentController::class, 'index'])->name('incidents');
        Route::post('/incidents', [IncidentController::class, 'store'])->name('incidents.store');

        Route::resource('buses', BusController::class);
        Route::resource('owners', OwnerController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('routes', RouteController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('processing-units', ProcessingUnitController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('storage-units', StorageUnitController::class)->only(['index', 'store', 'update', 'destroy']);

        Route::get('/locations/districts', [LocationController::class, 'districts'])->name('locations.districts');
        Route::get('/locations/cities', [LocationController::class, 'cities'])->name('locations.cities');
        Route::post('/locations/cities', [LocationController::class, 'storeCity'])->name('locations.cities.store');
    });

// Settings hold third-party credentials (Google OAuth, SMS/WhatsApp gateway
// keys) — restricted to developer/admin, not coordinators.
Route::middleware(['auth', 'role:developer,admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('settings');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

        Route::resource('users', UserController::class)->only(['index', 'store', 'update', 'destroy']);
    });
