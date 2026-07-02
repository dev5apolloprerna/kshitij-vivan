<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Attendence;
use App\Models\Employee;
use Carbon\Carbon;

use Illuminate\Http\JsonResponse;


class AttendanceController extends Controller
{
   public function index(Request $request)
    {
        $emp=Employee::where(['iStatus'=>1])->get();

        return view('admin.attendence.index',compact('emp'));

    }
  public function fetchEvents(Request $request)
  {
        $empid = $request->input('empid');
        /*$start = Carbon::parse($request->input('start'))->startOfDay();
        $end = Carbon::parse($request->input('end'))->endOfDay();

        $holidays = DB::table('holiday_master')->whereBetween('date', [$start, $end])->get(['date', 'name']);

        $events = Attendence::where('empId', $empid)
            ->whereBetween('start_date_time', [$start, $end])
            ->get(['start_date_time as start', 'end_date_time as end', 'empId', 'day']);

        // Format holiday data for FullCalendar
        $holidayEvents = $holidays->map(function ($holiday) {
            return [
                'title' => 'Holiday: ' . $holiday->name,
                'start' => $holiday->date,
                'allDay' => true, // Show as an all-day event
                'backgroundColor' => '#FF0000', // Red background for holidays
                'borderColor' => '#FF0000',
                'textColor' => '#FFFFFF'
            ];
        });

        
        return response()->json(array_merge($events->toArray(), $holidayEvents->toArray()));*/

        $start = Carbon::parse($request->input('start'))->startOfDay();
        $end = Carbon::parse($request->input('end'))->endOfDay();

        // Fetch holidays
        $holidays = DB::table('holiday_master')
            ->whereBetween('date', [$start, $end])
            ->get(['date', 'name']);

        // Fetch attendance records
        $attendanceRecords = Attendence::where('empId', $empid)
            ->whereBetween('start_date_time', [$start, $end])
            ->get(['start_date_time as start', 'end_date_time as end', 'empId', 'day','comment','role_id']);

        // Convert attendance dates to an array for quick lookup
        $attendedDates = $attendanceRecords->map(function ($record) {
            return Carbon::parse($record->start)->toDateString();
        })->toArray();

        // Generate an array of all dates in the range, skipping Sundays
        $allDates = [];
        $currentDate = $start->copy();

        while ($currentDate->lte($end)) {
            if ($currentDate->dayOfWeek !== Carbon::SUNDAY) { // Skip Sundays
                $allDates[] = $currentDate->toDateString();
            }
            $currentDate->addDay();
        }

        // Identify absent days (dates not in attendance and not holidays)
        $absentDays = array_diff($allDates, $attendedDates, $holidays->pluck('date')->toArray());

        // Format holidays for FullCalendar
        $holidayEvents = $holidays->map(function ($holiday) {
            return [
                'title' => 'Holiday: ' . $holiday->name,
                'start' => $holiday->date,
                'allDay' => true,
                'backgroundColor' => '#FF0000',
                'borderColor' => '#FF0000',
                'textColor' => '#FFFFFF'
            ];
        });

        // Format absent days as FullCalendar events
        $absentEvents = array_map(function ($date) {
            return [
                'title' => 'Absent',
                'start' => $date,
                'allDay' => true,
                'backgroundColor' => 'orange',
                'borderColor' => 'orange',
                'textColor' => '#FFFFFF'

            ];
        }, $absentDays);

        // Merge all events (attendance, holidays, and absents)
        $events = array_merge($attendanceRecords->toArray(), $holidayEvents->toArray(), $absentEvents);

        return response()->json($events);


  }
   public function markAttendance(Request $request)
    {
     
    //   $Attendence=Attendence::where(['empid'=>$request->empid])->whereDate('created_at', $request->date)->get();


/*        $Attendence = Attendence::where('empid', $request->empid)
        ->whereDate('start_date_time', $request->date)
        ->whereDate('end_date_time', $request->date)
        ->first();
*/

   $Attendence = Attendence::where('empid', $request->empid)
    ->where(function ($query) use ($request) {
        $query->whereDate('start_date_time', $request->date)
              ->orWhere(function ($q) use ($request) {
                  $q->whereNotNull('end_date_time')
                    ->whereDate('end_date_time', $request->date);
              });
    })
    ->first();



    if (!$Attendence) {
        // Insert new record
        $Attendence = new Attendence();
        $Attendence->empId = $request->empid;
        $Attendence->start_date_time = $request->date;
        $Attendence->end_date_time = $request->date;
        $Attendence->day = $request->status;
        $Attendence->comment = $request->comment;
        $Attendence->role_id = 1;
        $Attendence->save();
        
    } else {
        // Update existing record

        $Attendence->day = $request->status;
        $Attendence->comment = $request->comment;
        $Attendence->role_id = 1;
        $Attendence->save();
    }


        return response()->json(['success' => true]);
    }

} 
