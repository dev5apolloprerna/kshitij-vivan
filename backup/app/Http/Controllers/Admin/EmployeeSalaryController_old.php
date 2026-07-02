<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\SalaryLedger;
use App\Models\Attendence;
use App\Models\User;
use Hash;


class EmployeeSalaryController extends Controller
{

	public function index(Request $request)
	{
		try
		{

			return view('admin.salary_processed.index');

        } catch (\Exception $e) 
        {
             return redirect()->back()->with('error', 'An error occurred .');
        }
    }
    public function create_salary(Request $request)
    {
    	try
    	{

    		$month = $request->month;
    		$year = $request->year;

          $salaryData = Employee::where(['iStatus' => 1, 'isDelete' => 0])
                        ->leftJoin('attendence_master', 'attendence_master.empId', '=', 'employee_master.empid')
                        ->when($request->month, fn ($query, $month) => $query->whereMonth('attendence_master.start_date_time', $month))
                        ->when($request->year, fn ($query, $year) => $query->whereYear('attendence_master.start_date_time', $year))
                        ->select(
                            'employee_master.empid',
                            'employee_master.salary',
                            'employee_master.balance_cl',
                            DB::raw("SUM(CASE WHEN attendence_master.day = '3' THEN 1 ELSE 0 END) as total_absent"),
                            DB::raw("SUM(CASE WHEN attendence_master.day = '2' THEN 1 ELSE 0 END) as total_half_day"),
                            DB::raw("SUM(CASE WHEN attendence_master.day = '2' THEN 0.5 ELSE 0 END) as half_day")
                        )
                        ->groupBy('employee_master.empid', 'employee_master.salary')
                        ->get();
                    $ledger=SalaryLedger::when($request->month, fn ($query, $month) => $query->where('salary_month', $month))
                                   ->when($request->year, fn ($query, $year) => $query->where('salary_year', $year))->count();

                    foreach ($salaryData as $value) 
                    {
                        if($value->salary == 0 || $value->salary == null)
                        {
                            return 1;

                        }else
                        {

                             if($ledger == 0)
                             {
                                       $ledger = SalaryLedger::where('empId', $value->empid)->latest()->first();

                                       $opening_balance = $ledger->closing_balance ?? 0; // Carry forward the previous balance
                                       $credit_balance = $value->balance_cl ?? 1; // Employee balance CL if available
                                       $debit_balance = 0;

                                      $deduction_for_absent = $value->salary / 30 * $value->total_absent; // Assuming a 30-day month
                                      $deduction_for_half_day = ($value->salary / 30) * 0.5 * $value->total_half_day;

                                      $total_leave=$value->total_absent+$value->half_day;

                                      $net_amount = $value->salary - ($deduction_for_absent + $deduction_for_half_day);
                                      $closing_balance = $opening_balance + $credit_balance - $debit_balance;

                                      // Step 4: Create a new Salary Ledger entry
                                      $SalaryLedger = new SalaryLedger();
                                      $SalaryLedger->empid = $value->empid;
                                      $SalaryLedger->opening_balance = $opening_balance;
                                      $SalaryLedger->credit_balance = $credit_balance;
                                      $SalaryLedger->debit_balance = $debit_balance;
                                      $SalaryLedger->closing_balance = $closing_balance;
                                      $SalaryLedger->employee_salary = $value->salary;
                                      $SalaryLedger->net_amount = $net_amount;
                                      $SalaryLedger->employee_leave = $total_leave;
                                      $SalaryLedger->salary_month = $request->month;
                                      $SalaryLedger->salary_year = $request->year;
                                      $SalaryLedger->save();

                                       if($closing_balance != 0)
                                       {
                                           $value->balance_cl = $closing_balance;
                                           $value->save();

                                       }else{
                                           $value->balance_cl = $total_leave;
                                           $value->save();
     
                                       }

                             }
                         }
                    }

            $ledger=SalaryLedger::select('salary_ledger.*',DB::raw('(select name from employee_master where employee_master.empid = salary_ledger.empid  limit 1) as employeeName'))->leftjoin('employee_master','employee_master.empid','=','salary_ledger.empid')
            ->when($request->month, fn ($query, $month) => $query->where('salary_month', $month))
            ->when($request->year, fn ($query, $year) => $query->where('salary_year', $year))->paginate(30);


    		return view('admin.salary_processed.ajax_index', compact('ledger'))->render();

    	}
    	catch (\Exception $e) 
        {
             return redirect()->back()->with('error', 'An error occurred .');
        }
    
    }
    public function leave_detail(Request $request,$id)
    {
        try{

            $data = Employee::where(['iStatus' => 1, 'isDelete' => 0,'empid'=>$id])
                        ->leftJoin('attendence_master', 'attendence_master.empId', '=', 'employee_master.empid')
                        ->when($request->month, fn ($query, $month) => $query->whereMonth('attendence_master.start_date_time', $month))
                        ->when($request->year, fn ($query, $year) => $query->whereYear('attendence_master.start_date_time', $year))
                        ->select(
                            'employee_master.empid',
                            'employee_master.salary',
                            'employee_master.balance_cl',
                            DB::raw("SUM(CASE WHEN attendence_master.day = '3' THEN 1 ELSE 0 END) as total_absent"),
                            DB::raw("SUM(CASE WHEN attendence_master.day = '2' THEN 1 ELSE 0 END) as total_half_day"),
                            DB::raw("SUM(CASE WHEN attendence_master.day = '2' THEN 0.5 ELSE 0 END) as half_day")
                        )
                        ->groupBy('employee_master.empid', 'employee_master.salary')
                        ->first();

            echo json_encode($data);
        } catch (\Exception $e) {
           report($e);
         return false;
        }
    } 

}