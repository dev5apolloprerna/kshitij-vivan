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
                        "login_in" => $login,
                        "login_out" => $logout,
                        "device_token" => $Employee->device_token
                    );

                    return response()->json([
                        "ErrorCode" => "0",
                        'Status' => 'Success',
                        'Message' => 'OTP is valid',
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


            $credentials = $request->only('username','password');

            if (Auth::guard('employee')->attempt($credentials)) 
            {
                $AuthCustomer = Auth::guard('employee')->user();

                $token = JWTAuth::fromUser($AuthCustomer);

                if (!empty($AuthCustomer)) 
                {
                    $Employee = Employee::where(['iStatus' => 1, 'isDelete' => 0, 'username' => $request->username])->first();
                   
                   
                    if (!$token) 
                    {
                        return response()->json([
                            "ErrorCode" => "1",
                            'Status' => 'Failed',
                            'Message' => 'Unauthorized',
                        ], 401);
                    }else
                    {
                        $updateToken=DB::table('employee_master')
                            ->where(['empid' => $Employee->empid])
                            ->update([
                                'device_token' => $token,
                            ]);


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
    public function start_day(Request $request)
    {
        if(auth()->guard('employee')->user())
        {
            $user_login = auth()->guard('employee')->user();
            $userData = Employee::where(['username'=>$request->username])->first();
             if(!empty($userData))
             {
                if (Hash::check($request->password, $userData->password))
                {
                    $Employee = Employee::where(['iStatus' => 1, 'isDelete' => 0, 'username' => $request->username])->first();

                    if($Employee != null)
                    {

                        $Attendence=Attendence::where(['empid'=>$Employee->empid])->where('start_date_time', '>=', date('Y-m-d 00:00:00'))->get();
                        if(sizeof($Attendence) == 0)
                        {
                            $Attendence=new Attendence();
                            $Attendence->empId=$Employee->empid;
                            $Attendence->start_date_time=$request->start_date_time;
                            $Attendence->start_latitude=$request->start_latitude;
                            $Attendence->start_longitude=$request->start_longitude;
                            $Attendence->start_address=$request->start_address;
                            $Attendence->save();

                            return response()->json([
                                'status' => 'success',
                                'message' => 'Attendence Added Successfully'
                            ]);
                        } else 
                        {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Today Attendence Already done',
                        ]);
                    }

                    } else {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'No Data Found!',
                            'Employee' => []
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
            $userData = Employee::where(['username'=>$request->username])->first();
             if(!empty($userData))
             {
                if (Hash::check($request->password, $userData->password))
                {
                    $Employee = Employee::where(['iStatus' => 1, 'isDelete' => 0, 'username' => $request->username])->first();

                    if($Employee != null)
                    {

                        $Attendence=Attendence::where(['empid'=>$Employee->empid])->where('end_date_time', '<=', date('Y-m-d 23:59:59'))->get();
                        if(sizeof($Attendence) == 0)
                        {

                            $data = Attendence::where(['empid' => $Employee->empid])->first();

                            $startDateTime = Carbon::parse($data->start_date_time);
                            $endDateTime = Carbon::parse($request->end_date_time);

                            $hoursWorked = $startDateTime->diffInHours($endDateTime);

                            // Determine if it's a full day or half day
                            $dayType = 'Full Day';
                            if ($hoursWorked < 9) {
                                $dayType = 2;
                            }else{
                                $dayType = 1;
                            }



                            DB::table('attendence_master')
                            ->where(['empid' => $Employee->empid])
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
                                'message' => 'Day Closed Successfully'
                            ]);
                        } else 
                        {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Day Already Closed',
                        ]);
                    }

                    } else {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'No Data Found!',
                            'Employee' => []
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