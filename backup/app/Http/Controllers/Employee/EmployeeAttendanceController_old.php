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
                    $dayType = 1; // Full day
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
                $employee=Employee::select('grace_period')->where(['empid'=>$id])->first();
                if(!empty($attendancedata))
                {
                    $startDateTime = Carbon::parse($attendancedata->start_date_time);
                    $endDateTime = Carbon::parse(Carbon::now());
    
                    $grace_period = $employee->grace_period; // Assuming $employee object exists

                    $hoursWorked = $startDateTime->diffInHours($endDateTime);

                    $totalMinutesWorked = $startDateTime->diffInMinutes($endDateTime);
                    $requiredFullDayMinutes = (9 * 60) + $grace_period; // 9 hours + dynamic extra time

                    // Determine if it's a full day or half day
                    $dayType = ($totalMinutesWorked >= $requiredFullDayMinutes) ? 1 : 2;


                    // Determine if it's a full day or half day
                    /*$dayType = 'Full Day';
                    if ($hoursWorked < 9) 
                    {
                        $dayType = 2;
                    }else{
                        $dayType = 1;
                    }*/
    
    
                    $data = array(
                        'end_date_time' =>Carbon::now(),
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

        $start = Carbon::parse($request->input('start'))->startOfDay();
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

        return response()->json(array_merge($events->toArray(), $holidayEvents->toArray()));
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
