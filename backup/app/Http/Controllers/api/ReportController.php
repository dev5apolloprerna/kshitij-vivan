<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Models\Employee;
use App\Models\Attendence;
use Carbon\Carbon;

class ReportController extends Controller
{

    public function today_report(Request $request)
    {
        if(auth()->guard('employee')->user())
        {
            $user_login = auth()->guard('employee')->user();
            $Employee = Employee::where(['iStatus' => 1, 'isDelete' => 0, 'username'=>$request->username])->first();
             if(!empty($Employee))
             {
                if (Hash::check($request->password, $Employee->password))
                {
                        if($request->device_token != $Employee->device_token)
                        {
                            return response()->json([
                                "ErrorCode" => "1",
                                'Status' => 'Failed',
                                'Message' => 'Device Token Not Match',
                            ], 401);
                        }

                        $today = Attendence::select('attendence_master.*', 'employee_master.name')
                                ->join('employee_master', 'employee_master.empid', '=', 'attendence_master.empId')
                                ->whereDate('attendence_master.created_at', Carbon::today()) // Filter for today's data
                                ->orderBy('employee_master.created_at', 'desc')->get();
                        if(sizeof($today) != 0)
                        {
                             foreach($today as $val)
                            {
                                 if($val->day == 1)
                                    { $day='P'; }
                                 elseif($val->day == 2)
                                    { $day='H'; }
                                 elseif($val->day == 3)
                                    { $day='A'; }
                                 elseif($val->day == 4)
                                    { $day='L'; }
                                 else
                                    { $day='-'; }
                                $todaydata[] = array(
                                    "attendenceId"=>$val->attendenceId,
                                    "emp_id" => $val->empId,
                                    "emp_name" => $val->name,
                                    "start_date_time" => date('d-m-Y h:i A',strtotime($val->start_date_time)) ?? '-',
                                    "start_latitude" => $val->start_latitude,
                                    "start_longitude" => $val->start_longitude,
                                    "start_address" => $val->start_address,
                                    "start_address" => $val->start_address,
                                    "end_date_time" => date('d-m-Y h:i A',strtotime($val->end_date_time)) ?? '-',
                                    "end_latitude" => $val->end_latitude,
                                    "end_longitude" => $val->end_longitude,
                                    "end_address" => $val->end_address,
                                    "day" => $day,
                                    "comment" => $val->comment,
                                );
                            }
                                return response()->json([
                                    'status' => 'success',
                                    'message' => 'Today Report',
                                    'today_report' => $todaydata
                                ]);
                        } else 
                        {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'No data found',
                        ]);
                        }

                    
                }else 
                 {
                    return response()->json([
                        'status' => 'error1',
                        'message' => 'Invalid login.',
                    ], 401);
                }

             } 
             else 
             {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User Not Found.',
                ], 401);
            }
        }else{
            return response()->json([
                    'status' => 'error',
                    'message' => 'User is not Authorised.',
            ], 401);
        }
    }

    public function monthly_report(Request $request)
    {

        if(auth()->guard('employee')->user())
        {
            $user_login = auth()->guard('employee')->user();
            $Employee = Employee::where(['iStatus' => 1, 'isDelete' => 0, 'username'=>$request->username])->first();
             if(!empty($Employee))
             {
                if (Hash::check($request->password, $Employee->password))
                {
                        if($request->device_token != $Employee->device_token)
                        {
                            return response()->json([
                                "ErrorCode" => "1",
                                'Status' => 'Failed',
                                'Message' => 'Device Token Not Match',
                            ], 401);
                        }

                        /*$monthly = Attendence::select('attendence_master.*','employee_master.name')
                        ->where(['attendence_master.empId'=>$request->id])
                       ->when($request->fromdate, fn ($query, $FromDate) => $query->where('start_date_time', '>=', date('Y-m-d 00:00:00', strtotime($FromDate))))
                       ->when($request->todate, fn ($query, $ToDate) => $query->where('start_date_time', '<=', date('Y-m-d 23:59:59', strtotime($ToDate))))
                       ->when(!$request->fromdate && !$request->todate, fn ($query) => $query->whereBetween('attendence_master.created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]))
                       ->join('employee_master', 'employee_master.empid', '=', 'attendence_master.empId')
                      // ->orderBy('employee_master.name','asc')
                      ->orderBy('employee_master.created_at', 'desc')->get();*/

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
                        ->when($request->id, fn ($query, $Empid) => $query->where('attendence_master.empId', '=', $Empid))
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
                            'attendenceId' => $attendance->attendenceId ?? null,
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
                            : ($holidays[$date] ?? ($day == 'Sunday' ? 'Sunday' : 'A')),
                        ]);
                    }

                        if(sizeof($result) != 0)
                        {
                             foreach($result as $val)
                            {
                                 
                                if ($val['day'] == 1)
                                    { $day="P"; }
                                else if ($val['day'] == 2)
                                    { $day="H"; }
                                else if ($val['status'] == 'Sunday')
                                    { $day="Sunday"; }
                                else if($val['holiday_name'])
                                    { $day= $val['holiday_name']; }
                                else
                                    { $day="A"; }
                                


                                if($val['start_date_time'] != NULL )
                                { 
                                    $startday=date('d-m-Y h:i A',strtotime($val['start_date_time'])); 
                                }
                                else
                                { $startday=date('d-m-Y',strtotime($val['date'])); }
                               

                               if($val['end_date_time'] != NULL && $val['end_date_time'] != '30-11--0001')
                                { $endday=date('d-m-Y h:i A',strtotime($val['end_date_time'])); }
                                else
                                    { $endday='-'; }
                                


                                $monthlydata[] = array(
                                    "attendenceId"=>$val['attendenceId'],
                                    "emp_id" => $val['empId'],
                                    "emp_name" => $val['employee_name'],
                                    "start_date_time" => $startday ?? '-',
                                    "start_latitude" => $val['start_latitude'],
                                    "start_longitude" => $val['start_longitude'],
                                    "start_address" => $val['start_address'],
                                    "end_date_time" => $endday ?? '-',
                                    "end_latitude" => $val['end_latitude'],
                                    "end_longitude" => $val['end_longitude'],
                                    "end_address" => $val['end_address'],
                                    "day" => $day,
                                    "comment" => $val['comment'],
                                );
                            }
                                return response()->json([
                                    'status' => 'success',
                                    'message' => 'Monthly Report',
                                    'monthly_report' => $monthlydata  
                                ]);
                        } else 
                        {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'No data found',
                        ]);
                        }

                    
                }else 
                 {
                    return response()->json([
                        'status' => 'error1',
                        'message' => 'Invalid login.',
                    ], 401);
                }

             } 
             else 
             {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User Not Found.',
                ], 401);
            }
        }else{
            return response()->json([
                    'status' => 'error',
                    'message' => 'User is not Authorised.',
            ], 401);
        }
    }
     public function employee_attendance(Request $request)
    {
        if(auth()->guard('employee')->user())
        {
            $user_login = auth()->guard('employee')->user();
            $Employee = Employee::where(['iStatus' => 1, 'isDelete' => 0, 'username'=>$request->username])->first();
             if(!empty($Employee))
             {
                if (Hash::check($request->password, $Employee->password))
                {
                        if($request->device_token != $Employee->device_token)
                        {
                            return response()->json([
                                "ErrorCode" => "1",
                                'Status' => 'Failed',
                                'Message' => 'Device Token Not Match',
                            ], 401);
                        }

                    $start_date = $request->start_date ?? Carbon::now()->startOfMonth()->toDateString();
                    $end_date = $request->end_date ?? Carbon::now()->endOfMonth()->toDateString();
                    $empId=$request->id;

                            $dates = [];
                            $current = Carbon::parse($start_date);
                            $end = Carbon::parse($end_date);

                            while ($current->lte($end)) {
                                $dates[] = $current->copy();
                                $current->addDay();
                            }

                            // Step 2: Fetch attendance
                            $attendance = Attendence::where('empId', $empId)
                                ->whereBetween('start_date_time', [$start_date, $end_date])
                                ->get();

                            // Step 3: Fetch holidays
                            $holidays = DB::table('holiday_master')
                                ->whereBetween('date', [$start_date, $end_date])
                                ->pluck('name', 'date') // '2024-04-10' => 'Eid'
                                ->toArray();

                            // Step 4: Process each date
                            $attendence = [];
                            foreach ($dates as $date) {
                                $formattedDate = $date->toDateString();
                                $dayName = $date->format('l'); // Sunday, Monday, etc.

                                // Match attendance by date
                                $record = $attendance->first(function ($item) use ($formattedDate) {
                                    return Carbon::parse($item->start_date_time)->toDateString() === $formattedDate;
                                });

                                if ($record) {
                                    // Map day status
                                   $endTime = $record->end_date_time;

                                    switch ($record->day) {
                                        case 1: // Working day
                                            if ($endTime == '-' || $endTime == null || $endTime == 'null') {
                                                $dayStatus = 'A'; // Absent if no checkout
                                            } else {
                                                $start = Carbon::parse($record->start_date_time);
                                                $end = Carbon::parse($record->end_date_time);
                                                $hours = $start->diffInHours($end);

                                                $dayStatus = ($hours < 4) ? 'Half' : 'P';
                                            }
                                            break;

                                        case 2:
                                            $dayStatus = 'H'; // Holiday
                                            break;

                                        case 3:
                                            $dayStatus = 'A'; // Absent
                                            break;

                                        case 4:
                                            $dayStatus = 'L'; // Leave
                                            break;

                                        default:
                                            $dayStatus = 'A'; // Unknown or Sunday
                                            break;
                                    }



                                    $attendence[] = [
                                        "emp_id" => $record->empId,
                                        "start_date_time" => $record->start_date_time ? Carbon::parse($record->start_date_time)->format('d-m-Y h:i A') : '-',
                                        "end_date_time" => $record->end_date_time ? Carbon::parse($record->end_date_time)->format('d-m-Y h:i A') : '-',
                                        "day" => $dayStatus,
                                    ];
                                } elseif (isset($holidays[$formattedDate])) {
                                    $attendence[] = [
                                        "emp_id" => $empId,
                                        "start_date_time" => $formattedDate,
                                        "end_date_time" => '-',
                                        "day" => 'Holiday: ' . $holidays[$formattedDate],
                                    ];
                                } elseif ($dayName === 'Sunday') {
                                    $attendence[] = [
                                        "emp_id" => $empId,
                                        "start_date_time" => $formattedDate,
                                        "end_date_time" => '-',
                                        "day" => 'Sunday',
                                    ];
                                } else {
                                    // Absent if no attendance, no holiday, and not Sunday
                                    $attendence[] = [
                                        "emp_id" => $empId,
                                        "start_date_time" => $formattedDate,
                                        "end_date_time" => '-',
                                        "day" => 'A',
                                    ];
                                }
                            }

                            if (!empty($attendence)) {
                                return response()->json([
                                    'status' => 'success',
                                    'message' => 'Attendance Report',
                                    'attendance_report' => $attendence,
                                ]);
                            } else {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'No data found',
                                ]);
                            }


                    
                }else 
                 {
                    return response()->json([
                        'status' => 'error1',
                        'message' => 'Invalid login.',
                    ], 401);
                }

             } 
             else 
             {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User Not Found.',
                ], 401);
            }
        }else{
            return response()->json([
                    'status' => 'error',
                    'message' => 'User is not Authorised.',
            ], 401);
        }
    }
   
}