<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Hash;


class EmployeeController extends Controller
{

    public function index(Request $request)
    {
        try{

          $employee = Employee::select('employee_master.*', 'reporting_employee.name as report_name')
            ->leftJoin('employee_master as reporting_employee', 'employee_master.report_to', '=', 'reporting_employee.empid')
            ->when($request->search, fn ($query, $search) => $query->where('employee_master.name', 'like', '%' . $search . '%'))
            ->orderBy('employee_master.empid', 'desc')
            ->paginate(env('PER_PAGE_COUNT'));


            $search=$request->search;

        return view('admin.employee.index', compact('employee','search'));
        } catch (\Exception $e) {
            // Log the exception or handle it in any other way you prefer
            return redirect()->back()->with('error', 'An error occurred while updating the employee.');
        }
    }
    public function create()
    {
             $employee=Employee::select('empid','name')->where(['role_id'=>3])->orderBy('name','asc')->get();
        return view('admin.employee.add',compact('employee'));

    }

    public function store(Request $request)
    {
            $request->validate([
                'name' => 'required',
                'mobile' => 'required',
                'email' => 'required|unique:employee_master',
                'role_id' => 'required',
            ]);


             try
             {
                $employee = new employee();
                $employee->name=$request->name;
                $employee->email=$request->email;
                $employee->mobile=$request->mobile;
                $employee->location=$request->location;
                $employee->in_time=$request->in_time;
                $employee->out_time=$request->out_time;
                $employee->grace_period=$request->grace_period;
                $employee->salary=$request->salary;
                $employee->morning_half_day_in_time=$request->morning_half_day_in_time;
                $employee->morning_half_day_out_time=$request->morning_half_day_out_time;
/*                $employee->evening_half_day_in_time=$request->evening_half_day_in_time;
                $employee->evening_half_day_out_time=$request->evening_half_day_out_time;*/
                $employee->balance_cl=$request->balance_cl;
                $employee->balance_cf=$request->balance_cf;
                $employee->username=$request->username;
                $employee->password=Hash::make($request->password);
                $employee->report_to=$request->report_to;
                $employee->role_id=$request->role_id;
                $employee->save();

                return redirect()->route('employee.index')->with('success','Employee Created Successfully');
             } catch (\Exception $e) {
                 return redirect()->back()->with('error', 'An error occurred while updating the employee.');
             }
    }

    public function edit(employee $employee,$id)
    {
        $employee = Employee::where(['empid' => $id])->first();
         $employeelist=Employee::select('empid','name')->where(['role_id'=>3])->orderBy('name','asc')->get();

        return view('admin.employee.edit',compact('employee','employeelist'));
   }

    public function update(Request $request, $id)
    {

        $request->validate([
                'email' => 'required|unique:employee_master,email,'. $id . ',empid',
            ]);
            

        try {
            $employee=Employee::where(['empid'=>$id])->first();
            if($request->role_id == 3)
            {   
                $report_to=NULL;
            }else{
                $report_to=$request->report_to;
            }

            $data = array(
                'name'=>$request->name,
                'email'=>$request->email,
                'mobile'=>$request->mobile,
                'location'=>$request->location,
                'in_time'=>$request->in_time,
                'out_time'=>$request->out_time,
                'grace_period'=>$request->grace_period,
                'salary'=>$request->salary,
                'morning_half_day_in_time'=>$request->morning_half_day_in_time,
                'morning_half_day_out_time'=>$request->morning_half_day_out_time,
/*                'evening_half_day_in_time'=>$request->evening_half_day_in_time,
                'evening_half_day_out_time'=>$request->evening_half_day_out_time,*/
                'balance_cl'=>$request->balance_cl,
                'balance_cf'=>$request->balance_cf,
                'report_to'=>$report_to,
                'role_id'=>$request->role_id,
                );
            Employee::where("empid","=",$id)->update($data);


        return redirect()->route('employee.index')->with('success','Employee Updated Successfully');
        } catch (\Exception $e) {
            // Log the exception or handle it in any other way you prefer
            return redirect()->back()->with('error', 'An error occurred while updating the employee.');
        }
    }
    public function clear_device_token(Request $request)
    {
        //try{
            
        $id = $request->empid;

        $emp = Employee::where('empid', $id)->first();
        
        if ($emp) {
            if ($emp->device_token === null) {
                return back()->with('error', 'Device Token is already cleared.');
            } else {
                Employee::where('empid', $id)->update(['device_token' => null]);
                return back()->with('success', 'Device Token cleared successfully.');
            }
        } else {
            return back()->with('error', 'Employee not found.');
        }
        

        /*} catch (\Exception $e) {
            // Log the exception or handle it in any other way you prefer
            return redirect()->back()->with('error', 'An error occurred while updating the employee.');
        }*/
    }
    public function delete(Request $request)
    {       
    try{

        $id=$request->empid;
        $employee=Employee::where(['empid'=>$id])->first();

        Employee::where('empid','=',$id)->delete();

        return back()->with('success','Employee Deleted Successfully');

        } catch (\Exception $e) {
            // Log the exception or handle it in any other way you prefer
            return redirect()->back()->with('error', 'An error occurred while updating the employee.');
        }
    }
    public function changepassword($id)
    {
        $userid=$id;
        return view('admin.employee.changepassword', compact('userid'));
    }
    public function updatepassword(Request $request,$id)
    {
     try{

        $employee = Employee::where('empid', '=', $id)->first();

                $newpassword = $request->new_password;
                $confirmpassword = $request->new_confirm_password;

                if ($newpassword == $confirmpassword) 
                {
                        $Employee = DB::table('employee_master')
                        ->where(['empid' => $id])
                        ->update([
                            'password' => Hash::make($confirmpassword),
                        ]);
    
                    return back()->with('success', 'Employee Password Updated Successfully.');
                } else {
                    return back()->with('error', 'Password and Confirm Password does not match');
                }
        
         } catch (\Exception $e) {
            // Log the exception or handle it in any other way you prefer
            return redirect()->back()->with('error', 'An error occurred while updating the employee.');
        }
    }
     public function editStatus(Request $request,$id)
    {
        try
        {
            $data=Employee::where(['empid'=>$request->id])->first();
            if($data->iStatus == 1)
            {
             $status=0;   
            }else{
            $status=1;     
            }
           
             $update = DB::table('employee_master')
                ->where(['empid' => $request->id])
                ->update([
                    'iStatus' => $status
                ]);
    
        return  redirect()->route('employee.index')->with('success', 'Status Updated Successfully.');
        } catch (\Exception $e) {

        report($e);
 
        return false;
        }
    }
  
}
