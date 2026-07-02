<?php
namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Models\Employee;
use App\Models\Attendence;
use App\Models\User;

use Hash;
use Carbon\Carbon;



class EmployeeAttendanceController extends Controller
{
   public function emp_start_day(Request $request)
    {
        $id = Auth::guard('web_employees')->user()->empid;

        try {
            if (empty($request->start_latitude) || empty($request->start_longitude)) {
                return redirect()->back()->with('error', 'Please enable location');
            }

            $alreadyStarted = Attendence::where('empId', $id)
                ->whereDate('start_date_time', Carbon::today())
                ->exists();

            if ($alreadyStarted) {
                return redirect()->back()->with('error', 'Day Already Started');
            }

            $employee = Employee::select(
                    'in_time',
                    'out_time',
                    'grace_period',
                    'morning_half_day_in_time',
                    'morning_half_day_out_time'
                )
                ->where('empid', $id)
                ->first();

            if (!$employee) {
                return redirect()->back()->with('error', 'Employee timing not found');
            }

            $startDateTime = Carbon::now();

            /*
                Start day time par final full day confirm nahi kari sakay,
                karan ke out time end_day par check thase.
                Late hoy to provisional half / absent set kari diye.
            */
            $dayType = $this->getStartDayType($startDateTime, $employee);

            $attendance = new Attendence();
            $attendance->empId = $id;
            $attendance->start_date_time = $startDateTime;
            $attendance->start_latitude = $request->start_latitude;
            $attendance->start_longitude = $request->start_longitude;
            $attendance->start_address = $request->start_address;
            $attendance->day = $dayType;
            $attendance->save();

            return redirect()->back()->with('success', 'Day started successfully');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while starting the day.');
        }
    }

    public function end_day(Request $request)
    {
        try {
            if (empty($request->end_latitude) || empty($request->end_longitude)) {
                return redirect()->back()->with('error', 'Please enable location');
            }

            $id = Auth::guard('web_employees')->user()->empid;

            $alreadyClosed = Attendence::where('empId', $id)
                ->whereDate('start_date_time', Carbon::today())
                ->whereNotNull('end_date_time')
                ->exists();

            if ($alreadyClosed) {
                return redirect()->back()->with('error', 'Day already closed');
            }

            $attendancedata = Attendence::where('empId', $id)
                ->whereDate('start_date_time', Carbon::today())
                ->whereNull('end_date_time')
                ->first();

            if (empty($attendancedata)) {
                return redirect()->back()->with('error', 'You have to first start your day');
            }

            $employee = Employee::select(
                    'grace_period',
                    'in_time',
                    'out_time',
                    'morning_half_day_in_time',
                    'morning_half_day_out_time'
                )
                ->where('empid', $id)
                ->first();

            if (!$employee) {
                return redirect()->back()->with('error', 'Employee timing not found');
            }

            $startDateTime = Carbon::parse($attendancedata->start_date_time);
            $endDateTime = Carbon::now();

            $dayType = $this->getFinalDayType($startDateTime, $endDateTime, $employee);

            $totalMinutesWorked = $startDateTime->diffInMinutes($endDateTime);
            $hoursWorked = floor($totalMinutesWorked / 60);

            Attendence::where('empId', $id)
                ->where('attendenceId', $attendancedata->attendenceId)
                ->update([
                    'end_date_time' => $endDateTime,
                    'end_latitude' => $request->end_latitude,
                    'end_longitude' => $request->end_longitude,
                    'end_address' => $request->end_address,
                    'day' => $dayType,
                    'working_hrs' => $hoursWorked,
                ]);

            return redirect()->back()->with('success', 'Day closed successfully');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the employee.');
        }
    }
    
    private function makeTodayTime($time)
    {
        return Carbon::parse(Carbon::today()->toDateString() . ' ' . $time);
    }

    private function getStartDayType(Carbon $startDateTime, $employee)
    {
        $gracePeriod = (int) ($employee->grace_period ?? 0);

        $officialStartTime = $this->makeTodayTime($employee->in_time);
        $allowedStartTime = $officialStartTime->copy()->addMinutes($gracePeriod);

        $halfDayLastInTime = $this->makeTodayTime($employee->morning_half_day_in_time);

        /*
            day value:
            NULL = pending, final decision end_day par thase
            2 = Half Day
            3 = Absent
        */

        if ($startDateTime->gt($halfDayLastInTime)) {
            return 3; // 11:30 AM pachi start kare to absent
        }

        if ($startDateTime->gt($allowedStartTime)) {
            return 2; // Grace pachi but half-day limit pela start kare to half day
        }

        return null; // On time start, final present end_day par check thase
    }

    private function getFinalDayType(Carbon $startDateTime, Carbon $endDateTime, $employee)
    {
        $gracePeriod = (int) ($employee->grace_period ?? 0);

        $officialStartTime = $this->makeTodayTime($employee->in_time);
        $officialEndTime = $this->makeTodayTime($employee->out_time);

        $allowedStartTime = $officialStartTime->copy()->addMinutes($gracePeriod);
        $allowedEndTime = $officialEndTime->copy()->subMinutes($gracePeriod);

        $halfDayLastInTime = $this->makeTodayTime($employee->morning_half_day_in_time);
        $halfDayMinimumOutTime = $this->makeTodayTime($employee->morning_half_day_out_time);

        /*
            day value:
            1 = Present / Full Day
            2 = Half Day
            3 = Absent
        */

        if ($startDateTime->gt($halfDayLastInTime) || $endDateTime->lt($halfDayMinimumOutTime)) {
            return 3; // Absent
        }

        if ($startDateTime->gt($allowedStartTime) || $endDateTime->lt($allowedEndTime)) {
            return 2; // Half Day
        }

        return 1; // Full Day
    }

     public function index(Request $request)
    {
        $emp=Employee::where(['iStatus'=>1])->get();

        return view('Employee.attendance.index',compact('emp'));

    }
   public function fetchEvents(Request $request)
    {
        $empid = Auth::guard('web_employees')->user()->empid;

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
        });*/
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
     //   return response()->json(array_merge($events->toArray(), $holidayEvents->toArray()));
    }


      public function markAttendance(Request $request)
        {
         
        //   $Attendence=Attendence::where(['empid'=>$request->empid])->whereDate('created_at', $request->date)->get();
            $Attendence = Attendence::where(['empid' => $request->empid])->whereDate('start_date_time', $request->date)->whereDate('end_date_time', $request->date)->get();
        
          if(sizeof($Attendence) == 0)
          {
              $Attendence=new Attendence();
              $Attendence->empId=$request->empid;
              $Attendence->start_date_time=$request->date;
              $Attendence->end_date_time=$request->date;
              $Attendence->day=$request->status;
              $Attendence->save();

          }else{
              DB::table('attendence_master')
                ->where(['empid' => $request->empid])
                ->whereDate('start_date_time', $request->date)
                ->whereDate('end_date_time', $request->date)
                ->update([
                    // 'start_date_time' =>$request->date,
                    // 'end_date_time' =>$request->date,
                    'day' =>$request->status,
                ]);
          }
            
            return response()->json(['success' => true]);
        }
    
    public function getLiveWorkTimeData(Request $request)
    {
        $id = Auth::guard('web_employees')->user()->empid;                                                                                                                                                      
        $attendance = Attendence::where(['empid' => $id])
            ->whereDate('start_date_time', Carbon::today())
            ->first();

        if ($attendance) {
            // Check if the day has ended
            $isDayEnded = $attendance->end_date_time ? true : false;

            return response()->json([
                'start_date_time' => $attendance->start_date_time,
                'day_ended' => $isDayEnded,
            ]);
        }

        return response()->json(['error' => 'No attendance record found for today.'], 404);
    }
}
