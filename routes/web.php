<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminController;

Route::middleware(['client_settings'])->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('index');
    Route::post('login', [AuthController::class, 'login'])->name('login');
});

Route::middleware(['check_authentication', 'client_settings'])->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('admin/{id}', [AdminController::class, 'memberShow'])->name('member.show');
    Route::get('member/progress_tracker', [AdminController::class, 'memberProgressTracker'])->name('member.progress_tracker');
    Route::get('member/registration', [AdminController::class, 'memberRegistration'])->name('member.registration');
    Route::get('member/allot_trainer', [AdminController::class, 'memberAllotTrainer'])->name('member.allot_trainer');
    Route::get('member/transfer_membership', [AdminController::class, 'transferMembership'])->name('member.transfer_membership');
    Route::get('member/{id}', [AdminController::class, 'memberShow'])->name('member.show');
    Route::get('client', [AdminController::class, 'getClient'])->name('get_client');
    Route::get('memberships', [AdminController::class, 'getMemberships'])->name('get_memberships');
    Route::get('fee_collection', [AdminController::class, 'feeCollection'])->name('get_fee_collection');
    Route::get('fee_collections', [AdminController::class, 'feeCollections'])->name('get_fee_collections');
    Route::get('config', [AdminController::class, 'getConfig'])->name('get_config');
    Route::get('monthly/receipt/download/{id}/{membershipId}', [AdminController::class, 'downloadReceipt'])->name('receipt.download');
    Route::get('onetime/receipt/download/{id}/{membershipId}', [AdminController::class, 'downloadOneTimeReceipt'])->name('one_time_receipt.download');
    Route::get('attendance', [AdminController::class, 'getAttendance'])->name('get_attendance');
    Route::get('attendance/report', [AdminController::class, 'attendanceReport'])->name('attendance_report');
    Route::get('trainer/registration', [AdminController::class, 'trainerRegistration'])->name('trainer.registration');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});