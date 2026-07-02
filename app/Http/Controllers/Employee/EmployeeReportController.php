<?php
namespace App\Http\Controllers\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Attendence;
use App\Models\Employee;
use App\Models\SalaryDetail;

use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Barryvdh\DomPDF\Facade\Pdf;

class EmployeeReportController extends Controller
{

  public function monthly(Request $request,$todate="",$fromdate="")
  {
      $Empid = Auth::guard('web_employees')->user()->empid;

     /* $monthly = Attendence::select('attendence_master.*','employee_master.name')->where(['attendence_master.empId'=>$id])
          ->when($request->fromdate, fn ($query, $FromDate) => $query->where('start_date_time', '>=', date('Y-m-d 00:00:00', strtotime($FromDate))))
          ->when($request->todate, fn ($query, $ToDate) => $query->where('start_date_time', '<=', date('Y-m-d 23:59:59', strtotime($ToDate))))
          ->when(!$request->fromdate && !$request->todate, fn ($query) => $query->whereBetween('attendence_master.created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]))
          ->join('employee_master', 'employee_master.empid', '=', 'attendence_master.empId')
        //   ->orderBy('employee_master.name','asc')
                    ->orderBy('employee_master.created_at', 'desc')

          ->paginate(env('PER_PAGE_COUNT'));

          $FromDate=$request->fromdate;
          $ToDate=$request->todate;*/

    $fromDate = $request->fromdate ? Carbon::parse($request->fromdate)->format('Y-m-d') : Carbon::now()->startOfMonth()->format('Y-m-d');
    $toDate = $request->todate ? Carbon::parse($request->todate)->format('Y-m-d') : Carbon::now()->endOfMonth()->format('Y-m-d');

    // Generate list of dates in range
    $dates = collect();
    $start = Carbon::parse($fromDate);
    $end = Carbon::parse($toDate);

    while ($start->lte($end)) {
        $dates->push([
            'date' => $start->copy()->format('Y-m-d'),
            'day' => $start->copy()->format('l'), // Get day name
        ]);
        $start->addDay();
    }

    // Fetch holidays
    $holidays = DB::table('holiday_master')
        ->whereBetween('date', [$fromDate, $toDate])
        ->pluck('name', 'date'); // Get holiday name mapped to date

    // Fetch attendance data
    $attendanceData = DB::table('attendence_master')
        ->select(
            'attendence_master.*',
            'employee_master.name as employee_name',
            DB::raw('DATE(attendence_master.created_at) as date')
        )
        ->join('employee_master', 'employee_master.empid', '=', 'attendence_master.empId')
        ->when($Empid, fn ($query, $Empid) => $query->where('attendence_master.empId', '=', $Empid))
        ->whereBetween('attendence_master.created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'])
        ->orderBy('attendence_master.created_at', 'desc')
        ->get()
        ->groupBy('date');

    // Merge Dates with Attendance, Holidays, and Sundays
    $result = collect();
    foreach ($dates as $entry) {
        $date = $entry['date'];
        $day = $entry['day'];
        $attendance = $attendanceData->get($date, collect())->first();
        
        $result->push([
            'date' => $date,
            'empId' => $attendance->empId ?? null,
            'employee_name' => $attendance->employee_name ?? null,
            'start_date_time' => $attendance->start_date_time ?? null,
            'end_date_time' => $attendance->end_date_time ?? null,
            'start_latitude' => $attendance->start_latitude ?? null,
            'end_latitude' => $attendance->end_latitude ?? null,
            'start_longitude' => $attendance->start_longitude ?? null,
            'end_longitude' => $attendance->end_longitude ?? null,
            'start_address' => $attendance->start_address ?? null,
            'end_address' => $attendance->end_address ?? null,
            'comment' => $attendance->comment ?? null,
            'day' => $attendance->day ?? null,
            'holiday_name' => $holidays[$date] ?? ($day == 'Sunday' ? 'Sunday' : null),
            'status' => ($attendance && $attendance->end_date_time)
            ? 'P'
            : ($holidays[$date] ?? ($day == 'Sunday' ? 'Sunday' : 'A')),        ]);
    }

    // Paginate manually
    $perPage = env('PER_PAGE_COUNT', 20);
    $page = request()->get('page', 1);
    $monthly = new LengthAwarePaginator(
        $result->forPage($page, $perPage), // Get items for current page
        $result->count(), // Total items
        $perPage, // Items per page
        $page, // Current page
        ['path' => request()->url(), 'query' => request()->query()] // Preserve query params
    );

    $FromDate=$request->fromdate;
          $ToDate=$request->todate;
          $empid=$request->empid;

    return view('Employee.report.monthly_report',compact('monthly','FromDate','ToDate'));

  }
  public function export_monthly($FromDate="",$ToDate="")
   {

    $id = Auth::guard('web_employees')->user()->empid;

      $fromDate = $FromDate ? Carbon::parse($FromDate)->format('Y-m-d') : Carbon::now()->startOfMonth()->format('Y-m-d');
    $toDate = $ToDate ? Carbon::parse($ToDate)->format('Y-m-d') : Carbon::now()->endOfMonth()->format('Y-m-d');

    // Generate list of dates in range
    $dates = collect();
    $start = Carbon::parse($fromDate);
    $end = Carbon::parse($toDate);

    while ($start->lte($end)) {
        $dates->push([
            'date' => $start->copy()->format('Y-m-d'),
            'day' => $start->copy()->format('l'), // Get day name
        ]);
        $start->addDay();
    }

    // Fetch holidays
    $holidays = DB::table('holiday_master')
        ->whereBetween('date', [$fromDate, $toDate])
        ->pluck('name', 'date'); // Get holiday name mapped to date

    // Fetch attendance data
    $attendanceData = DB::table('attendence_master')
        ->select(
            'attendence_master.*',
            'employee_master.name as employee_name',
            DB::raw('DATE(attendence_master.created_at) as date')
        )
        ->join('employee_master', 'employee_master.empid', '=', 'attendence_master.empId')
        ->when($id, fn ($query, $Empid) => $query->where('attendence_master.empId', '=', $Empid))
        ->whereBetween('attendence_master.created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'])
        ->orderBy('attendence_master.created_at', 'desc')
        ->get()
        ->groupBy('date');

    // Merge Dates with Attendance, Holidays, and Sundays
    $result = collect();
    foreach ($dates as $entry) {
        $date = $entry['date'];
        $day = $entry['day'];
        $attendance = $attendanceData->get($date, collect())->first();
        
        $result->push([
            'date' => $date,
            'empId' => $attendance->empId ?? null,
            'employee_name' => $attendance->employee_name ?? null,
            'start_date_time' => $attendance->start_date_time ?? null,
            'end_date_time' => $attendance->end_date_time ?? null,
            'start_latitude' => $attendance->start_latitude ?? null,
            'end_latitude' => $attendance->end_latitude ?? null,
            'start_longitude' => $attendance->start_longitude ?? null,
            'end_longitude' => $attendance->end_longitude ?? null,
            'start_address' => $attendance->start_address ?? null,
            'end_address' => $attendance->end_address ?? null,
            'comment' => $attendance->comment ?? null,
            'day' => $attendance->day ?? null,
            'holiday_name' => $holidays[$date] ?? ($day == 'Sunday' ? 'Sunday' : null),
           'status' => ($attendance && $attendance->end_date_time)
            ? 'P'
            : ($holidays[$date] ?? ($day == 'Sunday' ? 'Sunday' : 'A')),        ]);
    }

   


    return view('Employee.report.export_monthly_report',compact('result'));

   }
   public function salary(Request $request)
    {
        $id = Auth::guard('web_employees')->user()->empid;

        $salaryData = SalaryDetail::select('salary_detail.*','salary_master.salary_year','salary_master.salary_month',DB::raw('(select name from employee_master where employee_master.empid = salary_detail.employeeId  limit 1) as employeeName'))->join('salary_master','salary_detail.salaryId','=','salary_master.sid')->where('employeeId', $id)
                    ->when($request->month, fn ($query, $monthh) => $query->where('salary_month', $monthh))
                    ->when($request->year, fn ($query, $yearr) => $query->where('salary_year', $yearr))
                    ->orderBy('salary_year', 'desc') // First by year
                    ->orderBy('salary_month', 'desc') // Then by month
        ->paginate(30);

            $month=$request->month;
            $year=$request->year;
            

        return view('Employee.report.view',compact('salaryData','month','year'));
    }
    public function pdf($id)
    {
        try 
        {
            $salary = SalaryDetail::select('salary_detail.*','salary_master.salary_year','salary_master.salary_month',DB::raw('(select name from employee_master where employee_master.empid = salary_detail.employeeId  limit 1) as employeeName'))->join('salary_master','salary_detail.salaryId','=','salary_master.sid')->where('sDetailId', $id)->first();

            $pdf = PDF::loadview('Employee.report.salary_slip', ['salary' => $salary])->setPaper('a4','portrait');
            return $pdf->stream('salary.pdf');

        } catch (\Throwable $th) {

            // Rollback & Return Error Message
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
       public function getSalaryDetails($id)
    {

        $salary = SalaryDetail::select('salary_detail.*','salary_master.salary_year','salary_master.salary_month',DB::raw('(select name from employee_master where employee_master.empid = salary_detail.employeeId  limit 1) as employeeName'))->join('salary_master','salary_detail.salaryId','=','salary_master.sid')->where('sDetailId', $id)->first();
        if (!$salary) {
            return response()->json(['error' => 'Salary details not found'], 404);
        }

        return response()->json([
            'sid'=>$salary->sDetailId,
            'employeeId'=>$salary->employeeId,
            'employeeName'=>$salary->employeeName,
            'basic_salary'=>$salary->basic_salary,
            'monthYear'=> date('M',strtotime($salary->salary_month)) .' '.date('Y',strtotime($salary->salary_year))."'",
            'net_pay'=>$salary->net_pay,
            'center'=>$salary->center,
            'Incentive'=>$salary->Incentive,
            'Bonus'=>$salary->Bonus,
            'Others'=>$salary->Others,
            'Total_A'=>$salary->Total_A,
            'WDIM'=> $salary->WDIM,
            'HDIM'=>$salary->HDIM,
            'Leave_ded'=>$salary->Leave_ded,
            'PT'=>$salary->PT,
            'TDS'=>$salary->TDS,
            'Loan_Advance'=>$salary->Loan_Advance,
            'Total_B'=>$salary->Total_B,
            'Accumlated'=>$salary->Accumlated,
            'Used'=>$salary->Used,
            'leave_taken'=>$salary->Leave_taken,
            'half_day'=>$salary->half_day_leave,
            'total_half_day'=>$salary->total_half_day,
            'total_absent'=>$salary->full_day_leave,
            'Rem'=>$salary->Rem,            
        ]);
    }
}
