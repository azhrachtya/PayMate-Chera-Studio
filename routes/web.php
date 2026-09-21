<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PayrollPeriodController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::get('/', DashboardController::class . '@index')->name('dashboard');

    Route::resource('employees', EmployeeController::class);

    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('attendance/{store}', [AttendanceController::class, 'show'])->name('attendance.show');
    Route::get('attendance/{store}/create', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/payroll-detail/{payroll}', [PayrollController::class, 'detail'])->name('payroll.detail');
    Route::get('payroll', [PayrollController::class, 'index'])->name('payroll.index');
    Route::get('payroll-periode/create', [PayrollPeriodController::class, 'create'])->name('payroll-periode.create');
    Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
    Route::post('payroll-periode', [PayrollPeriodController::class, 'store'])->name('payroll-periode.store');
    Route::get('payroll-periode/{period}/edit', [PayrollPeriodController::class, 'edit'])->name('payroll-periode.edit');
    Route::put('payroll-periode/{period}', [PayrollPeriodController::class, 'update'])->name('payroll-periode.update');
    Route::delete('payroll-periode/{period}', [PayrollPeriodController::class, 'destroy'])->name('payroll-periode.destroy');
    Route::get('payroll/{period}', [PayrollController::class, 'show'])->name('payroll.show');
    Route::post('payroll/{period}/proses', [PayrollController::class, 'proses'])->name('payroll.proses');
    Route::post('payroll/{period}/proses/{employee}', [PayrollController::class, 'prosesSatu'])->name('payroll.proses-satu');

    Route::post('payroll-slip/{payroll}/kirim', [PayrollController::class, 'kirimSlip'])->name('payroll.kirim-slip');
    Route::post('payroll/{period}/kirim-semua', [PayrollController::class, 'kirimSemuaSlip'])->name('payroll.kirim-semua');

    // Route bawaan Breeze untuk halaman profil HRD (ganti nama/password/hapus akun)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';