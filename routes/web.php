<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;

// == LANDING PAGE ==
Route::get('/', [ReportController::class, 'publicIndex'])->name('landing');
Route::get('/reports/public', [ReportController::class, 'publicReportIndex'])->name('report.public_index');
Route::get('/temuan/search', [ReportController::class, 'publicSearch'])->name('temuan.search');
Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show'); // ✅ Taruh di sini, HAPUS duplikat yang lain

// == AUTH ==
// == AUTH ==
Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('login');  // ✅ Hapus ->middleware('guest')
    
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('guest')  // ✅ Tetap pakai guest di POST
    ->name('login.attempt');
    
Route::get('/register', [AuthController::class, 'showRegistrationForm'])
    ->name('register');  // ✅ Hapus ->middleware('guest')
    
Route::post('/register', [AuthController::class, 'register'])
    ->middleware('guest')  // ✅ Tetap pakai guest di POST
    ->name('register.attempt');

// == AUTHENTICATED ROUTES ==
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('reports', ReportController::class)->except(['destroy', 'show']); // ✅ tambah 'show' di except

    // == PROFILE (SHARED BY ALL ROLES) ==
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // == PENGGUNA (ROLE: PENGGUNA) ==
    Route::middleware('role:pengguna')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('dashboard');
        Route::resource('claims', ClaimController::class)->except(['destroy']);
    });

    // == PETUGAS (ROLE: PETUGAS) ==
    Route::middleware('auth')->prefix('petugas')->name('petugas.')->group(function () {
        Route::get('/dashboard', [PetugasController::class, 'dashboard'])->name('dashboard');
        Route::get('/dashboard/trend-data', [PetugasController::class, 'getWarehouseTrend'])->name('dashboard.trend');
        // Reports Management by Petugas
        Route::get('/reports', [PetugasController::class, 'reports'])->name('reports');
        Route::get('/reports/create', [PetugasController::class, 'createReport'])->name('reports.create');
        Route::post('/reports', [PetugasController::class, 'storeReport'])->name('reports.store');
        Route::get('/reports/{report}', [PetugasController::class, 'showReport'])->name('reports.show');
        Route::get('/reports/{report}/edit', [PetugasController::class, 'edit'])->name('reports.edit');
        Route::put('/reports/{report}', [PetugasController::class, 'update'])->name('reports.update');
        Route::put('/reports/{report}/validate', [PetugasController::class, 'validateReport'])->name('reports.validate');
        Route::get('/reports/{report}/assign', [PetugasController::class, 'assignClaim'])->name('reports.assign');
        Route::post('/reports/{report}/assign', [PetugasController::class, 'storeAssignedClaim'])->name('reports.assign.store');
        Route::delete('/reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');

        // Claims Management by Petugas
        Route::get('/claims', [PetugasController::class, 'claims'])->name('claims');
        Route::get('/claims/{claim}', [ClaimController::class, 'show'])->name('claims.show');
        Route::put('/claims/{claim}/validate', [PetugasController::class, 'validateClaim'])->name('claims.validate');
        Route::delete('/claims/{claim}', [ClaimController::class, 'destroy'])->name('claims.destroy');
    });

    // == ADMIN (ROLE: ADMIN) ==
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        // Tambahkan ini di dalam group prefix 'admin'
        Route::get('/dashboard/trend-data', [AdminController::class, 'getTrendData'])->name('dashboard.trend');
        Route::get('/dashboard/location-details', [AdminController::class, 'getLocationDetails'])->name('dashboard.location-details');
        
        // Temporary route for fixing data. Please remove after use.
        Route::get('/fix-report-statuses', [AdminController::class, 'fixReportStatuses'])->name('admin.fix-statuses');
        Route::get('/backfill-analytics', [AdminController::class, 'backfillAnalytics'])->name('admin.backfill-analytics');

        // Validation Pages
        Route::get('/reports/validation', [ReportController::class, 'validationPage'])->name('reports.validation');
        Route::get('/claims/validation', [ClaimController::class, 'validation'])->name('claims.validation');
        
        // Report Actions
        Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
        Route::get('/reports/{report}/edit', [ReportController::class, 'edit'])->name('reports.edit');
        Route::put('/reports/{report}', [ReportController::class, 'update'])->name('reports.update');
        Route::put('/reports/{report}/validate', [ReportController::class, 'validation'])->name('reports.validate');
        Route::delete('/reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');

        // Claim Actions
        Route::get('/claims/{claim}', [ClaimController::class, 'show'])->name('claims.show'); // Tambahan jika admin perlu lihat detail klaim
        Route::put('/claims/{claim}/validate', [ClaimController::class, 'validateClaim'])->name('claims.validate');
        Route::delete('/claims/{claim}', [ClaimController::class, 'destroy'])->name('claims.destroy');

        // User Management (CRUD)
        Route::resource('users', UserController::class);
        
        // Category, Building, Room Management
        Route::resource('categories', CategoryController::class);
        Route::resource('buildings', BuildingController::class);
        Route::resource('rooms', RoomController::class);

        // Export Reports
        Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
    });
});
