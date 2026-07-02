<?php
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\HolidayMasterController;
use App\Http\Controllers\Admin\EmployeeSalaryController;
use App\Http\Controllers\Admin\SalaryController;

use App\Http\Controllers\Employee\EmployeeLoginController;
use App\Http\Controllers\Employee\EmployeeHomeController;
use App\Http\Controllers\Employee\EmployeeAttendanceController;
use App\Http\Controllers\Employee\EmployeeReportController;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::fallback(function () {
//     return response()->view('errors.404', [], 404);
// });


Route::get('/login', function () {
    return redirect()->route('login');
});


Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Profile Routes
Route::prefix('profile')->name('profile.')->middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'getProfile'])->name('detail');
    Route::get('/edit', [HomeController::class, 'EditProfile'])->name('EditProfile');
    Route::post('/update', [HomeController::class, 'updateProfile'])->name('update');
    Route::post('/change-password', [HomeController::class, 'changePassword'])->name('change-password');
});
Route::any('/employee/get-live-work-time-data', [EmployeeAttendanceController::class, 'getLiveWorkTimeData'])->name('getLiveWorkTimeData');

Route::get('logout', [LoginController::class, 'logout'])->name('logout');

// Roles
Route::resource('roles', App\Http\Controllers\RolesController::class);

// Permissions
Route::resource('permissions', App\Http\Controllers\PermissionsController::class);

//Employee
Route::prefix('admin')->name('employee.')->middleware('auth')->group(function () {
    Route::any('employee', [EmployeeController::class,'index'])->name('index');
    Route::get('employee/create/', [EmployeeController::class, 'create'])->name('create');
    Route::post('employee/store', [EmployeeController::class, 'store'])->name('store');
    Route::get('employee/edit/{id?}', [EmployeeController::class, 'edit'])->name('edit');
    Route::post('employee/update/{id?}', [EmployeeController::class, 'update'])->name('update');
    Route::delete('employee/delete', [EmployeeController::class, 'delete'])->name('destroy');
    Route::post('employee/clear_token/{id?}', [EmployeeController::class, 'clear_device_token'])->name('clear_device_token');

    Route::get('employee/changepassword/{id?}', [EmployeeController::class, 'changepassword'])->name('changepassword');
    Route::post('employee/updatepassword/{id?}', [EmployeeController::class, 'updatepassword'])->name('updatepassword');
    Route::post('employee/editStatus/{id?}', [EmployeeController::class, 'editStatus'])->name('editStatus');

});

// Report
Route::prefix('admin')->name('report.')->middleware('auth')->group(function () {
    Route::any('report/today', [ReportController::class,'today'])->name('today');
    Route::any('report/monthly/{todate?}/{fromdate?}/{id?}', [ReportController::class,'monthly'])->name('monthly');
    Route::get('employee/edit_attendance/{id?}', [ReportController::class, 'edit_attendance'])->name('edit_attendance');
    Route::post('employee/update_attendance', [ReportController::class, 'update_attendance'])->name('update_attendance');
    Route::get('report/export_today', [ReportController::class,'export_today'])->name('export_today');
Route::get('report/export_monthly/{FromDate?}/{ToDate?}/{EmpId?}', [ReportController::class,'export_monthly'])->name('export_monthly');
Route::get('report/get-sundays', [ReportController::class, 'getSundays']);

});

// Attendance
Route::prefix('admin')->name('attendance.')->middleware('auth')->group(function () {
    Route::any('attendance/calendar', [AttendanceController::class,'index'])->name('index');
    //Route::post('attendance/calenderAjax', [AttendanceController::class,'ajax'])->name('ajax');
    Route::get('/attendance/events', [AttendanceController::class, 'fetchEvents'])->name('events');
    Route::post('/attendance/mark', [AttendanceController::class, 'markAttendance'])->name('mark');
});

//holiday Master
Route::prefix('admin')->name('holiday.')->middleware('auth')->group(function () {
    Route::get('/holiday/index', [HolidayMasterController::class, 'index'])->name('index');
    Route::post('/holiday/store', [HolidayMasterController::class, 'create'])->name('store');
    Route::get('/holiday/edit/{id?}', [HolidayMasterController::class, 'editview'])->name('edit');
    Route::post('/holiday/update', [HolidayMasterController::class, 'update'])->name('update');
    Route::delete('/holiday/delete', [HolidayMasterController::class, 'delete'])->name('delete');
});

//ledger Master
Route::prefix('admin')->name('salary_processed.')->middleware('auth')->group(function () {
    Route::any('/salary_processed/index', [EmployeeSalaryController::class, 'index'])->name('index');
    Route::get('/salary_processed/view/{id?}', [EmployeeSalaryController::class, 'view'])->name('view');
    Route::post('/salary_processed/ajax_salary_detail', [EmployeeSalaryController::class, 'ajax_salary_detail'])->name('ajax_salary_detail');
    Route::get('/salary_processed/leave_detail/{id?}', [EmployeeSalaryController::class, 'leave_detail'])->name('leave_detail');
    Route::get('/salary_processed/getSalaryDetails/{id?}', [EmployeeSalaryController::class, 'getSalaryDetails'])->name('getSalaryDetails');
    Route::get('/salary_processed/editData/{id?}', [EmployeeSalaryController::class, 'editData'])->name('editData');
    Route::post('/salary_processed/updateData', [EmployeeSalaryController::class, 'updateData'])->name('updateData');
    Route::delete('/salary_processed/delete', [EmployeeSalaryController::class, 'delete'])->name('delete');
    Route::get('/salary_processed/export_salary/{month?}/{year?}', [EmployeeSalaryController::class, 'export_salary'])->name('export_salary');
    Route::get('/salary_processed/view/{id?}', [EmployeeSalaryController::class, 'view'])->name('view');

});

//ledger Master
Route::prefix('admin')->name('salary_process.')->middleware('auth')->group(function () {
    Route::get('/salary_process/index', [SalaryController::class, 'index'])->name('index');
    Route::post('/salary_process/create_salary', [SalaryController::class, 'create_salary'])->name('create_salary');
    Route::post('/salary_process/process_salary', [SalaryController::class, 'process_salary'])->name('process_salary');
    Route::post('/salary_process/ReGenerate', [SalaryController::class, 'ReGenerate'])->name('ReGenerate');
    Route::get('/salary_process/view/{id?}', [SalaryController::class, 'view'])->name('view');
    Route::post('/salary_process/updateSalaryDetailData', [SalaryController::class, 'updateSalaryDetailData'])->name('updateSalaryDetailData');
    Route::post('/salary_process/updateAttendanceData', [SalaryController::class, 'updateAttendanceData'])->name('updateAttendanceData');
    Route::get('/salary_process/leave_detail/{id?}', [SalaryController::class, 'leave_detail'])->name('leave_detail');
    Route::delete('/salary_process/delete-salary/{month}/{year}', [SalaryController::class, 'deleteSalaryRecords']);

    Route::get('/salary_process/export_salary/{todate?}/{fromdate?}', [SalaryController::class, 'export_salary'])->name('export_salary');
    Route::get('salary_process/getSalaryDetails/{id}', [SalaryController::class, 'getSalaryDetails']);
    Route::get('salary_process/confirm-salary/{id}', [SalaryController::class, 'confirm_salary']);
    Route::get('salary_process/pdf/{id}', [SalaryController::class, 'pdf'])->name('pdf');
    Route::post('salary_process/ReGenerateEMP', [SalaryController::class, 'ReGenerateEMP'])->name('ReGenerateEMP');

});


//inquiry
Route::prefix('admin')->name('Inquiry.')->middleware('auth')->group(function () {
    Route::get('Inquiry/index', [InquiryController::class, 'index'])->name('index');
    Route::delete('/Inquiry-delete', [InquiryController::class, 'delete'])->name('delete');
    Route::get('Inquiry/view/{id?}', [InquiryController::class, 'view'])->name('view');
});


/*---------------------------------------------admin route end-----------------------------------------------------*/

//==============================================Employee Login====================================================

Route::get('employee/login', [EmployeeLoginController::class, 'loginform'])->name('user_login');
Route::post('employee/login', [EmployeeLoginController::class, 'login'])->name('userLogin');

//Forgot-Password Page
 Route::post('employee/Forgotpassword', [EmployeeHomeController::class, 'PasswordForgot'])->name('password_forgot');

//New-Password Page
Route::get('employee/home', [EmployeeHomeController::class, 'index'])->name('userhome');
Route::get('employee/logout', [EmployeeLoginController::class, 'logout'])->name('empuserlogout');


Route::prefix('employee')->name('empprofile.')->middleware(['auth:web_employees'])->group(function () {
    Route::get('/userprofile', [EmployeeHomeController::class, 'getProfile'])->name('employee-detail');
    Route::get('/edit', [EmployeeHomeController::class, 'EditProfile'])->name('EditProfile');
    Route::post('/update', [EmployeeHomeController::class, 'updateProfile'])->name('update');
    Route::any('/changePassword', [EmployeeHomeController::class, 'changePassword'])->name('userchangepassword');
});

Route::prefix('employee')->name('empattendance.')->middleware(['auth:web_employees'])->group(function () {
    Route::any('employee/attendance', [EmployeeAttendanceController::class,'index'])->name('index');
    Route::post('employee/emp_start_day', [EmployeeAttendanceController::class, 'emp_start_day'])->name('emp_start_day');
    Route::post('employee/end_day', [EmployeeAttendanceController::class, 'end_day'])->name('end_day');

  //calender attendance
    Route::any('employee/attendance-calendar', [EmployeeAttendanceController::class,'index'])->name('index');
    Route::get('/employee/attendance-events', [EmployeeAttendanceController::class, 'fetchEvents'])->name('events');
    Route::post('/employee/attendance-mark', [EmployeeAttendanceController::class, 'markAttendance'])->name('mark');
});
    

// Report
Route::prefix('employee')->name('empreport.')->middleware(['auth:web_employees'])->group(function () {
    Route::any('employee-report/monthly/{todate?}/{fromdate?}', [EmployeeReportController::class,'monthly'])->name('monthly');
    Route::get('employee-report/export_monthly/{FromDate?}/{ToDate?}', [EmployeeReportController::class,'export_monthly'])->name('export_monthly');
    Route::any('employee-report/salary', [EmployeeReportController::class,'salary'])->name('empSalary');
    Route::get('employee-report/pdf/{id}', [EmployeeReportController::class, 'pdf'])->name('pdf');
    Route::get('employee-report/getSalaryDetails/{id}', [EmployeeReportController::class, 'getSalaryDetails']);

});



