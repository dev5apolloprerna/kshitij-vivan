<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Attendence;
use App\Models\Employee;
use App\Models\Holiday;
use Carbon\Carbon;

use Illuminate\Pagination\LengthAwarePaginator;

class ReportController extends Controller
{

  public function today(Request $request)
  {

    $today = Attendence::select('attendence_master.*', 'employee_master.name')
            ->join('employee_master', 'employee_master.empid', '=', 'attendence_master.empId')
            ->whereDate('attendence_master.created_at', Carbon::today()) // Filter for today's data
            /*->orderBy('employee_master.name', 'asc')*/
            ->orderBy('employee_master.created_at', 'desc')
            ->paginate(env('PER_PAGE_COUNT'));


    return view('admin.report.today_report',compact('today'));
  } 
  public function edit_attendance(Request $request,$id)
  {
    $data=Attendence::select('attendence_master.*',DB::raw('(select name from employee_master where employee_master.empid=attendence_master.empId limit 1) as empname'))->where(['empId'=>$id])->first();
    echo json_encode($data);
  }
  public function update_attendance(Request $request)
  {
        try
        {
              $data = array(
                'day'=>$request->day,
                );
            Attendence::where("empId","=",$request->empid)->update($data);
            return redirect()->back()->with('success', 'Attendence Updated Successfully');
        } catch (\Exception $e) {
            // Log the exception or handle it in any other way you prefer
            return redirect()->back()->with('error', 'An error occurred while updating the employee.');
        }

  }
  public function monthly(Request $request,$todate="",$fromdate="",$empid="")
  {
      

    $emp=Employee::where(['iStatus'=>1,'isDelete'=>0])->orderBy('name','asc')->get();


    $fromDate = $request->fromdate ? Carbon::parse($request->fromdate)->format('Y-m-d') : Carbon::now()->startOfMonth()->format('Y-m-d');
    $toDate = $request->todate ? Carbon::parse($request->todate)->format('Y-m-d') : Carbon::now()->endOfMonth()->format('Y-m-d');

    // Generate list of dates in range
        $dates = collect();
        $start = Carbon::parse($fromDate);
        $end = Carbon::parse($toDate);
        
        while ($start->lte($end)) {
            $dates->push([
                'date' => $start->format('Y-m-d'),
                'day' => $start->format('l'),
            ]);
            $start->addDay();
        }


    // Fetch holidays
    $holidays = DB::table('holiday_master')
    ->whereBetween('date', [$fromDate, $toDate])
    ->pluck('name', 'date');


    // Fetch attendance data
    $attendanceData = DB::table('attendence_master')
        ->select(
            'attendence_master.*',
            'employee_master.name as employee_name',
            DB::raw('DATE(attendence_master.created_at) as date')
        )
        ->join('employee_master', 'employee_master.empid', '=', 'attendence_master.empId')
        ->when($request->empid, fn ($query, $Empid) => $query->where('attendence_master.empId', '=', $Empid))
        ->whereBetween('attendence_master.created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'])
        ->orderBy('attendence_master.created_at', 'desc')
        ->get()
        ->groupBy('date');


    // Merge Dates with Attendance, Holidays, and Sundays
    $result = collect();
    foreach ($dates as $entry) 
    {
        $date = $entry['date'];
        $day = $entry['day'];
        $attendanceList = $attendanceData->get($date, collect());
    
        // Show attendance for all employees (not just one entry)
        foreach ($attendanceList as $attendance) 
        {
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
                'day' => $attendance->day,
                'holiday_name' => $holidays[$date] ?? ($day == 'Sunday' ? 'Sunday' : null),
                'status' => ($attendance->end_date_time)
                    ? 'P'
                    : ($holidays[$date] ?? ($day == 'Sunday' ? 'Sunday' : 'A')),

            ]);
    }

    // If no attendance found, still show date (to cover absents or holidays)
    if ($attendanceList->isEmpty()) {
        $result->push([
            'date' => $date,
            'empId' => null,
            'employee_name' => null,
            'start_date_time' => null,
            'end_date_time' => null,
            'start_latitude' => null,
            'end_latitude' => null,
            'start_longitude' => null,
            'end_longitude' => null,
            'start_address' => null,
            'end_address' => null,
            'comment' => null,
            'day' => null,
             'holiday_name' => $holidays[$date] ?? ($day == 'Sunday' ? 'Sunday' : null),
'status' => $holidays[$date] 
    ?? ($day == 'Sunday' ? 'Sunday' : 'A'),

     
        ]);
    }
}


    // Paginate manually
    $perPage = env('PER_PAGE_COUNT', 20);
    $page = request()->get('page', 1);
    
    $monthly = new LengthAwarePaginator(
        $result->forPage($page, $perPage),
        $result->count(),
        $perPage,
        $page,
        ['path' => request()->url(), 'query' => request()->query()]
    );


         $FromDate=$request->fromdate;
          $ToDate=$request->todate;
          $empid=$request->empid;

    return view('admin.report.monthly_report',compact('monthly','emp','FromDate','ToDate','empid','result'));

  }
    public function export_today(Request $request)
    {
    try{
            $today = Attendence::select('attendence_master.*', 'employee_master.name')
            ->join('employee_master', 'employee_master.empid', '=', 'attendence_master.empId')
            ->whereDate('attendence_master.created_at', Carbon::today()) // Filter for today's data
            /*->orderBy('employee_master.name', 'asc')*/
            ->orderBy('employee_master.created_at', 'desc')
            ->get();

        return view('admin.report.export_today_report', compact('today'));
    } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }
    public function export_monthly($FromDate="",$ToDate="",$EmpId="")
    {
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
        ->when($EmpId, fn ($query, $Empid) => $query->where('attendence_master.empId', '=', $Empid))
        ->whereBetween('attendence_master.created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'])
        ->orderBy('attendence_master.created_at', 'desc')
        ->get()
        ->groupBy('date');

    // Merge Dates with Attendance, Holidays, and Sundays
     $result = collect();
    foreach ($dates as $entry) 
    {
        $date = $entry['date'];
        $day = $entry['day'];
        $attendanceList = $attendanceData->get($date, collect());
    
        // Show attendance for all employees (not just one entry)
        foreach ($attendanceList as $attendance) {
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
                'day' => $attendance->day,
                'holiday_name' => $holidays[$date] ?? ($day == 'Sunday' ? 'Sunday' : null),
                'status' => ($attendance && $attendance->end_date_time)
                    ? 'P'
                    : ($holidays[$date] ?? ($day == 'Sunday' ? 'Sunday' : 'A')),
            ]);
    }

    // If no attendance found, still show date (to cover absents or holidays)
    if ($attendanceList->isEmpty()) {
        $result->push([
            'date' => $date,
            'empId' => null,
            'employee_name' => null,
            'start_date_time' => null,
            'end_date_time' => null,
            'start_latitude' => null,
            'end_latitude' => null,
            'start_longitude' => null,
            'end_longitude' => null,
            'start_address' => null,
            'end_address' => null,
            'comment' => null,
            'day' => null,
            'holiday_name' => $holidays[$date] ?? ($day == 'Sunday' ? 'Sunday' : null),
            'status' => $holidays[$date] ?? ($day == 'Sunday' ? 'Sunday' : 'A'),
        ]);
    }
}

     return view('admin.report.export_monthly_report',compact('result'));


    }
    public function getSundays(Request $request) {
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    if (!$startDate || !$endDate) {
        return response()->json(['error' => 'Start date and end date are required.'], 400);
    }

    // Get all Sundays in range
    $sundays = [];
    $currentDate = strtotime($startDate);

    while ($currentDate <= strtotime($endDate)) {
        if (date('l', $currentDate) === 'Sunday') { // Check if it's Sunday
            $sundays[] = date('Y-m-d', $currentDate);
        }
        $currentDate = strtotime('+1 day', $currentDate);
    }

    return response()->json($sundays); // Return JSON response
}


}
