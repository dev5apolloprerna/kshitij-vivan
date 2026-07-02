<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\Models\Employee;
use App\Models\Attendence;
use Carbon\Carbon;

class AuthController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:employee', ['except' => ['login']]);
    // }

    public function login(Request $request)
    {
       try {
            $credentials = $request->only('username','password');

            if (Auth::guard('employee')->attempt($credentials)) {
                $AuthCustomer = Auth::guard('employee')->user();

                $token = JWTAuth::fromUser($AuthCustomer);
                $Employee = Employee::where(['iStatus' => 1, 'isDelete' => 0, 'username' => $request->username])->first();

                if (!empty($AuthCustomer) && !empty($Employee)) 
                {
                    $Attendence = Attendence::where(['empId' => $Employee->empid])->whereDate('start_date_time', Carbon::today())->first();

                   if(!empty($Attendence))
                   {
                        if (!empty($Attendence->start_date_time) && !empty($Attendence->end_date_time) && $Attendence->end_date_time !== "0000-00-00 00:00:00") {
                                $login = 1;
                                $logout = 1;
                            } else if (!empty($Attendence->start_date_time) && (empty($Attendence->end_date_time) || $Attendence->end_date_time === "0000-00-00 00:00:00")) {
                                $login = 1;
                                $logout = 0;
                            } else {
                                $login = 0;
                                $logout = 0;
                            }

                    }else{
                        $login=0;
                        $logout=0;
                    }
                   
                    if (!$token) 
                    {
                        return response()->json([
                            "ErrorCode" => "1",
                            'Status' => 'Failed',
                            'Message' => 'Unauthorized',
                        ], 401);
                    }if($Employee->device_token == null)
                    {
                            $updateToken=DB::table('employee_master')
                            ->where(['empid' => $Employee->empid])
                            ->update([
                                'device_token' => $request->device_token,
                            ]);
                    }
                    else if($request->device_token != $Employee->device_token)
                    {
                        return response()->json([
                            "ErrorCode" => "1",
                            'Status' => 'Failed',
                            'Message' => 'Device Token Not Match',
                        ], 401);
                    }

                    $userdata = array(
                        "id" => $Employee->empid,
                        "name" => $Employee->name,
                        "email" => $Employee->email,
                        "location" => $Employee->location,
                        "username" => $Employee->username,
                        "mobile" => $Employee->mobile,
                        "start_time" => $login,
                        "end_time" => $logout,
                        "device_token" => $Employee->device_token
                    );

                    return response()->json([
                        "ErrorCode" => "0",
                        'Status' => 'Success',
                        'Message' => 'Login success',
                        'Reseller' => $userdata,
                        'key' => env('RAZORPAY_KEY'),
                        'salt' => env('RAZORPAY_SECRET'),

                        'authorisation' => [
                                'token' => $token,
                                'type' => 'bearer',
                            ]
                    ]);
                } else {
                    return response()->json([
                        "ErrorCode" => "1",
                        'Status' => 'Failed',
                        'Message' => 'Employee Not Found.',
                    ], 401);
                }
            } else {

                return response()->json([
                    "ErrorCode" => "1",
                    'Status' => 'Failed',
                    'Message' => 'Invalid Login Id Or Password',
                ], 401);
            }
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
    
    public function logout()
    {
        auth()->guard('employee')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function change_password(Request $request)
    {

        try
        {
            $request->validate([
                    'new_password' => 'required',
                    'confirm_password' => 'required'
            ]);

            if(auth()->guard('employee')->user())
            {
                $user_login = auth()->guard('employee')->user();
                $Employee = Employee::where(['iStatus' => 1, 'isDelete' => 0, 'username'=>$request->username])->first();
                 if(!empty($Employee))
             {
                   
                   if($request->device_token != $Employee->device_token)
                    {
                        return response()->json([
                            "ErrorCode" => "1",
                            'Status' => 'Failed',
                            'Message' => 'Device Token Not Match',
                        ], 401);
                    }

                    $newpassword = $request->new_password;
                    $confirmpassword = $request->confirm_password;

                
                    if($newpassword == $confirmpassword) 
                    {
                        $emp = DB::table('employee_master')
                            ->where(['iStatus' => 1, 'empid' => $Employee->empid])
                            ->update([
                                'password' => Hash::make($confirmpassword),
                            ]);
                            return response()->json([
                                    'status' => 'success',
                                    'message' => 'Password Updated Successfully'
                                ]);

                    }else 
                    {
                        return response()->json([
                                'status' => 'error',
                                'message' => 'password and confirm password does not match',
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
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }

    }
    public function start_day(Request $request)
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

                        $Attendence=Attendence::where(['empid'=>$Employee->empid])->whereDate('start_date_time', Carbon::today())->get();

                        if(sizeof($Attendence) == 0)
                        {
                            //$startDateTime = Carbon::now(); // 2025-02-22 14:08:30 
                            $startDateTime = $request->start_date_time; // 2025-02-22 14:08:30 
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
                            $Attendence->empId=$Employee->empid;
                            $Attendence->start_date_time=$request->start_date_time;
                            $Attendence->start_latitude=$request->start_latitude;
                            $Attendence->start_longitude=$request->start_longitude;
                            $Attendence->start_address=$request->start_address;
                            $Attendence->day=$dayType;
                            $Attendence->save();

                            return response()->json([
                                'status' => 'success',
                                'message' => 'Day start successfully',
                                'start_time' => 1
                            ]);
                        } else 
                        {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Day already started',
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
    public function end_day(Request $request)
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
                        $Attendence=Attendence::where(['empid'=>$Employee->empid])->whereDate('end_date_time', Carbon::today())->get();
                        if(sizeof($Attendence) == 0)
                        {
                
                            $data = Attendence::where(['empid' => $Employee->empid])->whereDate('start_date_time', Carbon::today())->first();
                             $employee=Employee::select('grace_period','in_time','out_time','morning_half_day_in_time','morning_half_day_out_time')->where(['empid'=>$Employee->empid])->first();


                             if(!empty($data))
                            {
                              
                                $startDateTime = Carbon::parse($data->start_date_time);
                                $endDateTime = Carbon::parse( Carbon::parse($request->end_date_time)); // Use Carbon::now() in real scenario
                                $grace_period = $employee->grace_period; // in minutes

                                $officialStartTime = Carbon::parse(Carbon::today()->format('Y-m-d') . $employee->in_time);
                                $officialEndTime = Carbon::parse(Carbon::today()->format('Y-m-d') . $employee->out_time);

                                $allowedStartTime = $officialStartTime->copy()->addMinutes($grace_period);
                                $allowedEndTime = $officialEndTime->copy()->subMinutes($grace_period); // Leaving early?

                                $morningHalfdayIn = Carbon::parse(Carbon::today()->format('Y-m-d') . ' ' . $employee->morning_half_day_in_time);
                                $morningHalfdayOut = Carbon::parse(Carbon::today()->format('Y-m-d') . ' ' . $employee->morning_half_day_out_time);

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



                            DB::table('attendence_master')
                            ->where(['empid' => $Employee->empid,'start_date_time'=>$data->start_date_time])
                            ->update([
                                'end_date_time' =>$request->end_date_time,
                                'end_latitude' =>$request->end_latitude,
                                'end_longitude' =>$request->end_longitude,
                                'end_address' =>$request->end_address,
                                'day' =>$dayType,
                                'working_hrs' =>$request->hoursWorked,
                                'comment' =>$request->comment,
                            ]);
                            return response()->json([
                                'status' => 'success',
                                'message' => 'Day closed successfully',
                                'end_time' => 1

                            ]);
                            }else{
                                return response()->json([
                                'status' => 'error',
                                'message' => 'You have to first strat your day',
                            ]);
             
                            }
             
                        } else 
                        {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Day already closed',
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
             
        }else{
            return response()->json([
                    'status' => 'error',
                    'message' => 'User is not Authorised.',
            ], 401);
        }
    }

}