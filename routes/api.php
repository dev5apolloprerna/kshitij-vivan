<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\ReportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/change_password', [AuthController::class, 'change_password'])->name('change_password');
Route::post('/start_day', [AuthController::class, 'start_day'])->name('start_day');
Route::post('/end_day', [AuthController::class, 'end_day'])->name('end_day');


Route::post('/today_report', [ReportController::class, 'today_report'])->name('today_report');
Route::post('/monthly_report', [ReportController::class, 'monthly_report'])->name('monthly_report');
Route::post('/employee_attendance', [ReportController::class, 'employee_attendance'])->name('employee_attendance');
