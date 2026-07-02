<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\SalaryMaster;
use App\Models\SalaryDetail;
use App\Models\Attendence;
use App\Models\User;
use Hash;


class SalaryController extends Controller
{

	public function index(Request $request)
	{
		try
		{

			$datacount=SalaryMaster::when($request->month, fn ($query, $month) => $query->where('salary_month', $month))
                                   ->when($request->year, fn ($query, $year) => $query->where('salary_year', $year))->count();

			$month=$request->month;
			$year=$request->year;
			
			return view('admin.salary_process.index',compact('datacount','month','year'));

        } catch (\Exception $e) 
        {
             return redirect()->back()->with('error', 'An error occurred .');
        }
    }
    public function create_salary(Request $request)
    {
      try{ 
    	   	$month = $request->month;
    		$year = $request->year;
    		$search=$request->search;
    		$create=$request->create;


			$data=SalaryMaster::when($request->month, fn ($query, $month) => $query->where('salary_month', $month))->when($request->year, fn ($query, $year) => $query->where('salary_year', $year))->count();
			if($data == 0)
			{

				 $SalaryMaster=new SalaryMaster();
	        	 $SalaryMaster->salary_month=$month;
	        	 $SalaryMaster->salary_year=$year;
	        	 $SalaryMaster->is_process=$request->is_process;
	        	 $SalaryMaster->is_attendace=$request->is_attendace;
	        	 $SalaryMaster->save();

	           
			}
			$attendance=SalaryMaster::when($request->month, fn ($query, $month) => $query->where('salary_month', $month))
	            ->when($request->year, fn ($query, $year) => $query->where('salary_year', $year))->paginate(30);
            


	    		return view('admin.salary_process.ajax_index', compact('attendance','month','year','search','create'))->render();

    	} catch (\Exception $e) 
        {
             return redirect()->back()->with('error', 'An error occurred .');
        }

    }
    public function process_salary(Request $request,SalaryMaster $SalaryMaster)
    {
   		/*try
    	{*/
    		$month = $request->month;
    		$year = $request->year;
    	    $sid=$request->salaryId;
    		$create=$request->create;

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
				
				$data=SalaryDetail::where(['salaryId'=>$sid])->count();
				$SalaryMaster=SalaryMaster::where(['sid'=>$sid])->count();
			foreach ($salaryData as $value) 
            {
                if($value->salary == 0 || $value->salary == null)
                {
                   return 1;

                }else
                {                  
	                 if($data == 0)
	                 {
	                	$basic_salary = $value->salary;
	                	$balance_cl = ($value->balance_cl == 0) ? 1 : $value->balance_cl;

						// Corrected half-day calculation
						$total_leave = $value->total_absent + ($value->total_half_day * 0.5);

						$Leave_ded = 0;
						$Total_B = 0;
						$rem = 0;

						$Total_A = $basic_salary + $request->Incentive + $request->Bonus + $request->Others;

						// Determine how many leaves are covered by balance
						$used_leave = min($balance_cl, $total_leave);
						$unpaid_leave = max(0, $total_leave - $used_leave);

						// Calculate salary deductions
						$deduction_for_absent = ($basic_salary / 30) * $unpaid_leave;
						$deduction_for_half_day = ($basic_salary / 30) * 0.5 * $value->total_half_day;

						$net_pay = $basic_salary - ($deduction_for_absent + $deduction_for_half_day);

						// Leave Deduction Calculation
						$Leave_ded = $deduction_for_absent + $deduction_for_half_day;
						$Total_B = $Leave_ded + $request->PT + $request->TDS + $request->Loan_Advance;

						$net_pay = $Total_A - $Total_B;
						$rem = $balance_cl - $used_leave; // Remaining leave balance

						// HDIM Calculation (Unpaid leave days)
						$HDIM = $unpaid_leave;

						$SalaryDetail = new SalaryDetail();
						$SalaryDetail->salaryId = $sid;
						$SalaryDetail->employeeId = $value->empid;
						$SalaryDetail->month = $request->month;
						$SalaryDetail->year = $request->year;
						$SalaryDetail->center = $request->center ?? 0;
						$SalaryDetail->net_pay = $net_pay ?? 0;
						$SalaryDetail->basic_salary = $basic_salary ?? 0;
						$SalaryDetail->Incentive = $request->Incentive ?? 0;
						$SalaryDetail->Bonus = $request->Bonus ?? 0;
						$SalaryDetail->Others = $request->Others ?? 0;
						$SalaryDetail->Total_A = $Total_A ?? 0;
						$SalaryDetail->WDIM = $request->WDIM ?? 30;
						$SalaryDetail->HDIM = $HDIM ?? 0;
						$SalaryDetail->Leave_ded = $Leave_ded ?? 0;
						$SalaryDetail->PT = $request->PT ?? 0;
						$SalaryDetail->TDS = $request->TDS ?? 0;
						$SalaryDetail->Loan_Advance = $request->Loan_Advance ?? 0;
						$SalaryDetail->Total_B = $Total_B ?? 0;
						$SalaryDetail->Accumlated = $balance_cl ?? 1;
						$SalaryDetail->Used = $used_leave ?? 0;
						$SalaryDetail->Leave_taken = $total_leave ?? 0;
						$SalaryDetail->Rem = $rem ?? 0;
						$SalaryDetail->half_day_leave = $value->total_half_day ?? 0;
						$SalaryDetail->full_day_leave = $value->total_absent ?? 0;
						$SalaryDetail->save();

						$SalaryMaster = SalaryMaster::find($sid);
						if (!$SalaryMaster) {
						    return back()->with('error', 'Salary Master record not found.');
						}
						$SalaryMaster->is_process = $request->is_process;
						$SalaryMaster->save();
						
					    $Employee = Employee::find($value->empid);
						if (!$Employee) 
						{
						    return back()->with('error', 'Employee record not found.');
						}
						$Employee->balance_cl = $rem ?? 1;
						$Employee->save();
	                	
	                }else{
	                	return 2;
	                }
            	}
            }

            $attendance=SalaryMaster::when($request->month, fn ($query, $month) => $query->where('salary_month', $month))
	            ->when($request->year, fn ($query, $year) => $query->where('salary_year', $year))->paginate(30);

    		return view('admin.salary_process.ajax_index', compact('attendance','month','year','create'))->render();

			/*}catch(\Exception $e)
	    	{
	    		return redirect()->back()->with('error','An error occurred.');
	    	}*/
    }
    public function ReGenerate(Request $request)
    {

    // 	try{

    		$month = $request->month;
    		$year = $request->year;
    	    $sid=$request->salaryId;
            $create=1;
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
				
            	$delete=SalaryDetail::where(['salaryId'=>$sid])->delete();

				$data=SalaryDetail::where(['salaryId'=>$sid])->count();
				$SalaryMaster=SalaryMaster::where(['sid'=>$sid])->count();
			foreach ($salaryData as $value) 
            {
                if($value->salary == 0 || $value->salary == null)
                {
                   return 1;

                }else
                {                  
	                 if($data == 0)
	                 {
	                	$basic_salary = $value->salary;
	                	$balance_cl = ($value->balance_cl == 0) ? 1 : $value->balance_cl;

						// Corrected half-day calculation
						$total_leave = $value->total_absent + ($value->total_half_day * 0.5);

						$Leave_ded = 0;
						$Total_B = 0;
						$rem = 0;

						$Total_A = $basic_salary + $request->Incentive + $request->Bonus + $request->Others;

						// Determine how many leaves are covered by balance
						$used_leave = min($balance_cl, $total_leave);
						$unpaid_leave = max(0, $total_leave - $used_leave);

						// Calculate salary deductions
						$deduction_for_absent = ($basic_salary / 30) * $unpaid_leave;
						$deduction_for_half_day = ($basic_salary / 30) * 0.5 * $value->total_half_day;

						$net_pay = $basic_salary - ($deduction_for_absent + $deduction_for_half_day);

						// Leave Deduction Calculation
						$Leave_ded = $deduction_for_absent + $deduction_for_half_day;
						$Total_B = $Leave_ded + $request->PT + $request->TDS + $request->Loan_Advance;

						$net_pay = $Total_A - $Total_B;
						$rem = $balance_cl - $used_leave; // Remaining leave balance

						// HDIM Calculation (Unpaid leave days)
						$HDIM = $unpaid_leave;

						$SalaryDetail = new SalaryDetail();
						$SalaryDetail->salaryId = $sid;
						$SalaryDetail->employeeId = $value->empid;
						$SalaryDetail->month = $request->month;
						$SalaryDetail->year = $request->year;
						$SalaryDetail->center = $request->center ?? 0;
						$SalaryDetail->net_pay = $net_pay ?? 0;
						$SalaryDetail->basic_salary = $basic_salary ?? 0;
						$SalaryDetail->Incentive = $request->Incentive ?? 0;
						$SalaryDetail->Bonus = $request->Bonus ?? 0;
						$SalaryDetail->Others = $request->Others ?? 0;
						$SalaryDetail->Total_A = $Total_A ?? 0;
						$SalaryDetail->WDIM = $request->WDIM ?? 30;
						$SalaryDetail->HDIM = $HDIM ?? 0;
						$SalaryDetail->Leave_ded = $Leave_ded ?? 0;
						$SalaryDetail->PT = $request->PT ?? 0;
						$SalaryDetail->TDS = $request->TDS ?? 0;
						$SalaryDetail->Loan_Advance = $request->Loan_Advance ?? 0;
						$SalaryDetail->Total_B = $Total_B ?? 0;
						$SalaryDetail->Accumlated = $balance_cl ?? 1;
						$SalaryDetail->Used = $used_leave ?? 0;
						$SalaryDetail->Leave_taken = $total_leave ?? 0;
						$SalaryDetail->Rem = $rem ?? 0;
						$SalaryDetail->half_day_leave = $value->total_half_day ?? 0;
						$SalaryDetail->full_day_leave = $value->total_absent ?? 0;
						$SalaryDetail->save();


						$SalaryMaster = SalaryMaster::find($sid);
						if (!$SalaryMaster) {
						    return back()->with('error', 'Salary Master record not found.');
						}
						$SalaryMaster->is_process = $request->is_process;
						$SalaryMaster->save();
	                	
	                	$Employee = Employee::find($value->empid);
						if (!$Employee) 
						{
						    return back()->with('error', 'Employee record not found.');
						}
						$Employee->balance_cl = $rem ?? 1;
						$Employee->save();

	                }else{
	                	return 2;
	                }
            	}
            }

            $attendance=SalaryMaster::when($request->month, fn ($query, $month) => $query->where('salary_month', $month))
	            ->when($request->year, fn ($query, $year) => $query->where('salary_year', $year))->paginate(30);

    		return view('admin.salary_process.ajax_index', compact('attendance','month','year','create'))->render();
		/*}catch(\Exception $e)
    	{
    		return redirect()->back()->with('error','An error occurred.');
    	}*/
    }
    public function confirm_salary($id)
    {
    	try{

    	 $data=SalaryMaster::where('sid', $id)->first();
    	 if($data)
    	 {
    	 	$data->confirm_date=today();
    	 	$data->save();
    	 }
    	  return response()->json(['message' => 'Salary confirm successfully.'], 200);


		}catch(\Exception $e)
    	{
    		return redirect()->back()->with('error','An error occurred.');
    	}
    }
    public function view($id)
    {
    	$salaryData = SalaryDetail::select('salary_detail.*','salary_master.salary_year','salary_master.confirm_date','salary_master.salary_month',DB::raw('(select name from employee_master where employee_master.empid = salary_detail.employeeId  limit 1) as employeeName'))->join('salary_master','salary_detail.salaryId','=','salary_master.sid')->where('salaryId', $id)->paginate(30);

    	return view('admin.salary_process.view',compact('salaryData'));
    }
    public function getSalaryDetails($id)
    {

        $salary = SalaryDetail::select('salary_detail.*','salary_master.salary_year','salary_master.salary_month',DB::raw('(select name from employee_master where employee_master.empid = salary_detail.employeeId  limit 1) as employeeName'))->join('salary_master','salary_detail.salaryId','=','salary_master.sid')->where('sDetailId', $id)->first();
        if (!$salary) {
            return response()->json(['error' => 'Salary details not found'], 404);
        }

        return response()->json([
        	'sid'=>$salary->sDetailId,
        	'employeeId'=>$salary->employeeId,
            'employeeName'=>$salary->employeeName,
            'basic_salary'=>$salary->basic_salary,
            'month'=>$salary->salary_month,
            'year'=>$salary->salary_year,
            'net_pay'=>$salary->net_pay,
            'center'=>$salary->center,
            'Incentive'=>$salary->Incentive,
            'Bonus'=>$salary->Bonus,
            'Others'=>$salary->Others,
            'Total_A'=>$salary->Total_A,
            'WDIM'=> $salary->WDIM,
            'HDIM'=>$salary->HDIM,
            'Leave_ded'=>$salary->Leave_ded,
            'PT'=>$salary->PT,
            'TDS'=>$salary->TDS,
            'Loan_Advance'=>$salary->Loan_Advance,
            'Total_B'=>$salary->Total_B,
            'Accumlated'=>$salary->Accumlated,
            'Used'=>$salary->Used,
            'leave_taken'=>$salary->Leave_taken,
            'total_half_day'=>$salary->half_day_leave,
            'total_absent'=>$salary->full_day_leave,
            'Rem'=>$salary->Rem,            
        ]);
    }
    public function updateSalaryDetailData(Request $request)
    {
    	try
    	{

    	    $SalaryDetail = SalaryDetail::select('salary_detail.*',
	    	     DB::raw('(select balance_cl from employee_master where employee_master.empid = salary_detail.employeeId  limit 1) as balance_cl')
    	    	,DB::raw('(select name from employee_master where employee_master.empid = salary_detail.employeeId  limit 1) as employeeName'))->where('sDetailId', $request->sid)->first();

			if (!$SalaryDetail) {
	            return response()->json(['error' => 'Salary details not found'], 404);
	        }else
	        {
	        			$basic_salary = $SalaryDetail->basic_salary;
						$balance_cl = $SalaryDetail->balance_cl; // Get available leave balance

						$total_leave = $request->total_absent + ($request->total_half_day * 0.5);

						// Determine how many leaves are covered by balance
						$used_leave = min($balance_cl, $total_leave);
						$unpaid_leave = max(0, $total_leave - $used_leave);

						// Calculate salary deductions
						$deduction_for_absent = ($basic_salary / 30) * $unpaid_leave;
						$deduction_for_half_day = ($basic_salary / 30) * 0.5 * $request->total_half_day;

						$net_pay = $basic_salary - ($deduction_for_absent + $deduction_for_half_day);

						// Other Salary Components
						$incentive = $request->Incentive ?? $SalaryDetail->Incentive;
						$Bonus = $request->Bonus ?? $SalaryDetail->Bonus;
						$PT = $request->PT ?? $SalaryDetail->PT;
						$TDS = $request->TDS ?? $SalaryDetail->TDS;
						$Loan_Advance = $request->Loan_Advance ?? $SalaryDetail->Loan_Advance;

						$Total_A = $basic_salary + $incentive + $Bonus + $request->Others;
						$Leave_ded = ($basic_salary > 0) ? ($basic_salary / 30) * $unpaid_leave : 0;
						$Total_B = $Leave_ded + $PT + $TDS + $Loan_Advance;
						$net_pay = $Total_A - $Total_B;

						// Update Salary Details
						$SalaryDetail->center = $request->center;
						$SalaryDetail->net_pay = $net_pay;
						$SalaryDetail->basic_salary = $basic_salary;
						$SalaryDetail->Incentive = $request->Incentive;
						$SalaryDetail->Bonus = $request->Bonus;
						$SalaryDetail->Others = $request->Others;
						$SalaryDetail->Total_A = $Total_A;
						$SalaryDetail->WDIM = $request->WDIM;
						$SalaryDetail->HDIM = $unpaid_leave;  // Corrected calculation
						$SalaryDetail->Leave_ded = $Leave_ded;
						$SalaryDetail->PT = $request->PT;
						$SalaryDetail->TDS = $request->TDS;
						$SalaryDetail->Loan_Advance = $request->Loan_Advance;
						$SalaryDetail->Total_B = $Total_B;
						$SalaryDetail->Rem = $request->Rem;
						$SalaryDetail->Accumlated = $request->Accumlated ?? 0;
						$SalaryDetail->Used = $used_leave ?? 0;
						$SalaryDetail->Leave_taken = $total_leave ?? 0;
						$SalaryDetail->half_day_leave = $request->total_half_day ?? 0;
						$SalaryDetail->full_day_leave = $request->total_absent ?? 0;
						$SalaryDetail->save();

		        return redirect()->back()->with('success','Salary Detail Data Updated Successfully');
		        // return response()->json([
		        //     'success' => true,
		        //     'message' => 'Salary details updated successfully!'
		        // ]);
	        }
	    } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting records: ' . $e->getMessage()], 500);
        }
    }
    public function updateAttendanceData(Request $request)
    {
    	    $SalaryDetail = SalaryDetail::select('salary_detail.*',DB::raw('(select name from employee_master where employee_master.empid = salary_detail.employeeId  limit 1) as employeeName'))->join('employee_master','employee_master.empid','=','salary_detail.employeeId')->where('sDetailId', $request->sid)->first();

			if (!$SalaryDetail) {
	            return response()->json(['error' => 'Salary details not found'], 404);
	        }else
	        {
	        			$basic_salary=$SalaryDetail->basic_salary;
	        			$balance_cl = ($SalaryDetail->balance_cl == 0) ? 1 : $SalaryDetail->balance_cl;
                        $Leave_ded=0;
                        $Total_B=0;
	                	$Total_A=$basic_salary+$SalaryDetail->Incentive+$SalaryDetail->Bonus+$SalaryDetail->Others;
						//$Leave_ded = ($Total_A > 0) ? ($Total_A / 30) * $request->HDIM : 0;
	                    $total_leave=$request->total_absent+$request->half_day;
						$used = ($total_leave == 1) ? 1 : ($total_leave - $balance_cl);

	        			if($SalaryDetail->basic_salary != $total_leave)
	                	{
                          $adjusted_absent = max(0, $request->total_absent - $balance_cl);

                          $deduction_for_absent = $SalaryDetail->basic_salary / 30 * $adjusted_absent; // Assuming a 30-day month
                          $deduction_for_half_day = ($SalaryDetail->basic_salary / 30) * 0.5 * $request->total_half_day;
                          $net_pay = $SalaryDetail->basic_salary - ($deduction_for_absent + $deduction_for_half_day);
                          $Leave_ded = $basic_salary - $net_pay;
                          $Total_B=$Leave_ded+$SalaryDetail->PT+$SalaryDetail->TDS+$SalaryDetail->Loan_Advance;

	                	}else{
	                	  	  	$net_pay=$Total_A-$Total_B;
  
	                	}
	                	
	               		$SalaryDetail->Accumlated=$request->Accumlated ?? 0;
	                	$SalaryDetail->Used=$used ?? 0;
	                	$SalaryDetail->Leave_taken=$total_leave ?? 0;
	                	$SalaryDetail->half_day_leave=$request->total_half_day ?? 0;
	                	$SalaryDetail->full_day_leave=$request->total_absent ?? 0;
	               	    $SalaryDetail->Total_A=$Total_A ?? 0;
	               	    $SalaryDetail->Total_B=$Total_B ?? 0;
	               	    $SalaryDetail->Leave_ded=$Leave_ded ?? 0;
	               	    $SalaryDetail->net_pay=$net_pay ?? 0;
	                	$SalaryDetail->save();

	        	/*return response()->json([
		            'success' => true,
		            'message' => 'Salary details updated successfully!'
		        ]);*/
		        return redirect()->back()->with('success','Salary Attendence Data Updated Successfully');

	        }
	 }
     public function deleteSalaryRecords($month, $year)
    {
        try 
        {
        	$datacount=SalaryMaster::when($month, fn ($query, $monthh) => $query->where('salary_month', $monthh))
                                   ->when($year, fn ($query, $yearr) => $query->where('salary_year', $yearr))->get();

           foreach ($datacount as $key => $value) 
           {
	            SalaryDetail::where('salaryId', $value->sid)->delete();
           
           }
            SalaryMaster::where('salary_month', $month)->where('salary_year', $year)->delete();

            return response()->json(['message' => 'Salary records deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting records: ' . $e->getMessage()], 500);
        }
    }

}