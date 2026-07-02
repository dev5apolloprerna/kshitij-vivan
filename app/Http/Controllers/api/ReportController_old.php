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

                        $monthly = Attendence::select('attendence_master.*','employee_master.name')
                        ->where(['attendence_master.empId'=>$request->id])
                       ->when($request->fromdate, fn ($query, $FromDate) => $query->where('start_date_time', '>=', date('Y-m-d 00:00:00', strtotime($FromDate))))
                       ->when($request->todate, fn ($query, $ToDate) => $query->where('start_date_time', '<=', date('Y-m-d 23:59:59', strtotime($ToDate))))
                       ->when(!$request->fromdate && !$request->todate, fn ($query) => $query->whereBetween('attendence_master.created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]))
                       ->join('employee_master', 'employee_master.empid', '=', 'attendence_master.empId')
                      // ->orderBy('employee_master.name','asc')
                      ->orderBy('employee_master.created_at', 'desc')->get();

                        if(sizeof($monthly) != 0)
                        {
                             foreach($monthly as $val)
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
                                $monthlydata[] = array(
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


                        $events = Attendence::select('start_date_time', 'end_date_time', 'created_at', 'empId', 'day')->where('empId', $request->id)
                            ->whereBetween('start_date_time', [$start_date, $end_date])->orderBy('start_date_time','asc')
                            ->get();
                        if(sizeof($events) != 0)
                        {
                             foreach($events as $val)
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

                                $attendence[] = array(
                                    "emp_id" => $val->empId,
                                    "start_date_time" => date('d-m-Y h:i A',strtotime($val->start_date_time)) ?? '-',
                                    "end_date_time" => date('d-m-Y h:i A',strtotime($val->end_date_time)) ?? '-',
                                    "day" => $day,
                                );
                            }
                                return response()->json([
                                    'status' => 'success',
                                    'message' => 'Attendence Report',
                                    'attendance_report' => $attendence  
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
   
}