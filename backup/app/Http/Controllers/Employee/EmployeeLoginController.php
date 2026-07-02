<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;
use Session;
use Illuminate\Support\Facades\Auth;
use Mail;

class EmployeeLoginController extends Controller
{
    //
    public function loginform()
    {
        return view('Employee.userLogin');
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

       $email=$request->email;
       $password=$request->password;
       $user = Employee::where(['username'=>$email,'iStatus'=>1])->first();
       
       $credentials = array(
            "username" => $email,
            "password" => $password
       );
    //   dd($user);

        if ($user && ($user->role_id == 2 || $user->role_id == 3)) 
        {
            if (Auth::guard('web_employees')->attempt($credentials)) 
            {


                $user=Employee::select('name','empid','email')->where(['empid'=>$user->empid])->first();
                if(!empty($user))
                {
                    $request->session()->put('empid', $user->empid);
                    $request->session()->put('name',$user->name);
                    $request->session()->put('user_role_id','2');
                }else {
                    return redirect()
                        ->back()
                        ->with('error', 'User Not Found');
                }
                // $request->session()->put('CustomerImagePath',$customer->image_path);
                
                return redirect()->route('userhome');
                
            } else {
               
                
                return redirect()
                    ->back()
                    ->with('error', 'Enter Email or Password is Incorrect');
            }
        } else {

            return redirect()
                ->back()
                ->with('error', 'Inactive  User Can not Login. Please Contact to admin.');
        }

    }

    public function logout(Request $request)
    {
            Auth::guard('web_employees')->logout();
        $request->session()->invalidate();
        // Regenerate the session token to prevent session fixation attacks
        $request->session()->regenerateToken();


        $request->session()->forget('name');
        $request->session()->forget('user_role_id');
        $request->session()->forget('empid');
        return view('Employee.logout');
    }

}
