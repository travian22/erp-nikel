<?php

use App\Http\Controllers\ApplicationLogController;
use App\Http\Controllers\BookingApprovalController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RentalCompanyController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleCategoryController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard (Admin & Approver)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Shared Booking Index & Show
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');

    // Admin Only Routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('vehicles', VehicleController::class);
        Route::resource('drivers', DriverController::class);
        Route::resource('rental-companies', RentalCompanyController::class);
        Route::resource('locations', LocationController::class);
        Route::resource('categories', VehicleCategoryController::class);
        Route::resource('users', UserController::class);

        Route::get('/bookings-create', [BookingController::class, 'create'])->name('bookings.create');
        Route::post('/bookings-store', [BookingController::class, 'store'])->name('bookings.store');
        Route::get('/bookings/{booking}/complete', [BookingController::class, 'completeForm'])->name('bookings.complete');
        Route::post('/bookings/{booking}/complete', [BookingController::class, 'storeComplete'])->name('bookings.store-complete');

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

        Route::get('/logs', [ApplicationLogController::class, 'index'])->name('logs.index');
    });

    // Approver Only Routes
    Route::middleware('role:approver')->group(function () {
        Route::get('/approvals', [BookingApprovalController::class, 'index'])->name('approvals.index');
        Route::post('/approvals/{approval}/approve', [BookingApprovalController::class, 'approve'])->name('approvals.approve');
        Route::post('/approvals/{approval}/reject', [BookingApprovalController::class, 'reject'])->name('approvals.reject');
    });
});

require __DIR__.'/auth.php';
