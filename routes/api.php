<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('registrations', [AdminController::class, 'registrations']);
Route::get('current_month_fee_collections', [AdminController::class, 'currentMonthFeeCollection']);
Route::get('recent_fee_collections', [AdminController::class, 'recentFeeCollections']);
Route::get('fee_collections', [AdminController::class, 'fetchFeeCollections']);
Route::post('register_member', [AdminController::class, 'registerMember']);
Route::post('addupdate_client', [AdminController::class, 'addUpdateClient']);
Route::post('add_membership', [AdminController::class, 'addMembership']);
Route::post('edit_memberships', [AdminController::class, 'editMemberships']);
Route::post('add_health_record', [AdminController::class, 'addHealthRecord']);
Route::get('get_payment_schedule/{member_id}', [AdminController::class, 'getPaymentSchedule']);
Route::post('save_config', [AdminController::class, 'saveConfig']);
Route::post('process_fee_payment', [AdminController::class, 'processFeePayment']);
Route::post('process_multiple_fee_payment', [AdminController::class, 'processMultipleFeePayment']);
Route::get('download_receipt/{id}', [AdminController::class, 'downloadReceipt']);
Route::post('save_attendance/{member_id}', [AdminController::class, 'saveAttendance']);
Route::get('attendance_report', [AdminController::class, 'attendanceReportData']);
Route::get('attendance_report/download', [AdminController::class, 'downloadAttendanceReport']);
Route::get('members_with_pending_fees', [AdminController::class, 'membersWithPendingFees']);
Route::get('recent_registrations', [AdminController::class, 'recentRegistrations']);
Route::post('register_trainer', [AdminController::class, 'registerTrainer']);
Route::post('allot_trainer', [AdminController::class, 'allotTrainer']);
Route::get('get_trainers_for_member', [AdminController::class, 'getTrainers']);
Route::get('trainer_allotments', [AdminController::class, 'trainerAllotments']);
Route::get('get_attendance_report/{member_id}', [AdminController::class, 'getAttendanceReport']);
Route::get('today_attendance', [AdminController::class, 'todayAttendance']);
Route::get('fetch_member_progress/{member_id}/{metric}', [AdminController::class, 'fetchMemberProgress']);