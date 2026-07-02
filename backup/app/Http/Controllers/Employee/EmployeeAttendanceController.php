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
        $id=Auth::guard('web_employees')->user()->empid;
        try
        {
            if (empty($request->start_latitude) || empty($request->start_longitude)) {
                return redirect()->back()->with('error', 'Please enable location');
            }
            $Attendence=Attendence::where(['empid'=>$id])->whereDate('start_date_time', Carbon::today())->get();

            $Employee=Employee::select('in_time','morning_half_day_in_time','evening_half_day_in_time','grace_period')->where(['empid'=>$id])->first();


            if(sizeof($Attendence) == 0)
            {
                $startDateTime = Carbon::now(); // 2025-02-22 14:08:30 
                $morningHalfday = Carbon::parse($Employee->morning_half_day_in_time); // e.g., 12:00 PM
                $inTime = Carbon::parse($Employee->in_time); // e.g., 9:00 AM
                $gracePeriod = $Employee->grace_period ?? 10; // Grace period in minutes (default: 10)

                // Calculate the allowed in-time with grace period
                $allowedInTime = $inTime->copy()->addMinutes($gracePeriod);

                if ($startDateTime > $morningHalfday) {
                    // If login is after morning half-day time (e.g., 12 PM), it's a full-day leave
                    $dayType = 3; // Full day leave
                }
                elseif ($startDateTime > $allowedInTime || $startDateTime > $morningHalfday) 
                {
                    // If login is after the grace period, it's a half-day
                    $dayType = 2; // Half day
                } else {
                    // If login is within the allowed time, it's a full day
                    $dayType = NULL; // Full day
                }

                

                $Attendence=new Attendence();
                $Attendence->empId=$id;
                $Attendence->start_date_time=Carbon::now();
                $Attendence->start_latitude=$request->start_latitude;
                $Attendence->start_longitude=$request->start_longitude;
                $Attendence->start_address=$request->start_address;
                $Attendence->day=$dayType;
                $Attendence->save();

              return redirect()->back()->with('success', 'Day started successfully');

            
            } else 
            {
              return redirect()->back()->with('error', 'Day Already Started');

            }
            
            //return redirect()->route('employee.index')->with('success','Employee Created Successfully');
        } catch (\Exception $e) {
            // return redirect()->back()->with('error', 'An error occurred while updating the employee.');
        }
    }
    public function end_day(Request $request)
    {
       try{
            if (empty($request->end_latitude) || empty($request->end_longitude)) {
                return redirect()->back()->with('error', 'Please enable location');
            }
             $id=Auth::guard('web_employees')->user()->empid;

            $Attendence=Attendence::where(['empid'=>$id])->whereDate('end_date_time', Carbon::today())->get();

            if(sizeof($Attendence) == 0)
            {

                $attendancedata = Attendence::where(['empid' => $id])->whereDate('start_date_time', Carbon::today())->first();
                $employee=Employee::select('grace_period','in_time','out_time','morning_half_day_in_time','morning_half_day_out_time')->where(['empid'=>$id])->first();
                if(!empty($attendancedata))
                {
                    /*$endDateTime = Carbon::parse(Carbon::now());*/
                  
                    $startDateTime = Carbon::parse($attendancedata->start_date_time);
                    $endDateTime = Carbon::parse( Carbon::parse(Carbon::now())); // Use Carbon::now() in real scenario
                    $grace_period = $employee->grace_period; // in minutes

                    $officialStartTime = Carbon::parse(Carbon::today()->format('Y-m-d') . $employee->in_time);
                    $officialEndTime = Carbon::parse(Carbon::today()->format('Y-m-d') . $employee->out_time);

                    $allowedStartTime = $officialStartTime->copy()->addMinutes($grace_period);
                    $allowedEndTime = $officialEndTime->copy()->subMinutes($grace_period); // Leaving early?

                    $morningHalfdayIn = Carbon::parse(Carbon::today()->format('Y-m-d') . ' ' . $employee->morning_half_day_in_time);
                    $morningHalfdayOut = Carbon::parse(Carbon::today()->format('Y-m-d') . ' ' . $employee->morning_half_day_out_time);


                    /*if ($startDateTime->gt($allowedStartTime)) {
                        // Late beyond grace = half day
                        $dayType = 2;
                        $hoursWorked = 0;
                    } elseif ($endDateTime->lt($allowedEndTime)) {
                        // Left early before allowed time = half day
                        $dayType = 2;
                        $hoursWorked = $startDateTime->diffInHours($endDateTime);
                    } else {
                        // Came on time, stayed long enough
                        $totalMinutesWorked = $startDateTime->diffInMinutes($endDateTime);
                        $hoursWorked = $startDateTime->diffInHours($endDateTime);
                        $dayType = ($totalMinutesWorked >= (9 * 60)) ? 1 : 2;
                    }*/

                if ($startDateTime->gt($morningHalfdayIn) || $endDateTime->lt($morningHalfdayOut)) {
                    $dayType = 3; // Full day leave
                    $hoursWorked = 0;
                }
                //  Half day if came late OR left early
                elseif ($startDateTime->gt($allowedStartTime) || $endDateTime->lt($allowedEndTime)) {
                    $dayType = 2; // Half day
                    $hoursWorked = $startDateTime->diffInHours($endDateTime);
                }
                //  Check for 9-hour rule
                else {
                    $totalMinutesWorked = $startDateTime->diffInMinutes($endDateTime);
                    $hoursWorked = $startDateTime->diffInHours($endDateTime);

                    $dayType = ($totalMinutesWorked >= (9 * 60)) ? 1 : 2;
                }



                    $data = array(
                        'end_date_time' => Carbon::parse(Carbon::now()),
                        'end_latitude'=>$request->end_latitude,
                        'end_longitude'=>$request->end_longitude,
                        'end_address'=>$request->end_address,
                        'day' =>$dayType,
                        'working_hrs' =>$hoursWorked
                        );
                    Attendence::where(["empid"=>$id,"start_date_time"=>$attendancedata->start_date_time])->update($data);
    
                    return redirect()->back()->with('success', 'Day closed successfully');
                }else{
                    return redirect()->back()->with('error', 'You have to first strat your day');
 
                }
        
            }else 
            {
              return redirect()->back()->with('error', 'Day already closed');

            }
        } catch (\Exception $e) {
            // Log the exception or handle it in any other way you prefer
            return redirect()->back()->with('error', 'An error occurred while updating the employee.');
        }
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
