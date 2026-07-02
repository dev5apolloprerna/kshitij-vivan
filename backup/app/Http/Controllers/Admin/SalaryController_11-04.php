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
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;


class SalaryController extends Controller
{

	public function index(Request $request)
	{
		try
		{

			$datacount=SalaryMaster::when($request->month, fn ($query, $month) => $query->where('salary_month', $month))
                                   ->when($request->year, fn ($query, $year) => $query->where('salary_year', $year))->count();


			$months = range(1, 12); // All months from January to December
			$year = $request->year ?? date('Y'); // Default to the current year if not provided

			// Get existing salary month entries for the given year
		    $existingEntries = SalaryMaster::where('salary_year', $year)
		        ->get(['salary_month', 'sid','is_process','confirm_date'])
		        ->keyBy('salary_month'); // Store data with month as key for easy lookup

			    $missingMonths = [];

    // Identify missing months
    foreach ($months as $month) 
    {
        if (!isset($existingEntries[$month])) {
            $missingMonths[] = [
                'month' => $month,
                'year' => $year,
                'sid' => null, // No SID because it's missing
                'is_process' => null, // No SID because it's missing
                'confirm_date' => null // No SID because it's missing
            ];
        } else {
            $missingMonths[] = [
                'month' => $month,
                'year' => $year,
                'sid' => $existingEntries[$month]->sid, // Assign SID if exists
                'is_process' => $existingEntries[$month]->is_process, // Assign SID if exists
                'confirm_date' => $existingEntries[$month]->confirm_date // Assign SID if exists
            ];
        }
    }



			$month=$request->month;
			$year=$request->year;
			
			return view('admin.salary_process.index',compact('datacount','month','year','missingMonths'));

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
            

	            if($request->directcreate)
	            {
	    		return redirect()->back();

	            }else{

	    		return view('admin.salary_process.ajax_index', compact('attendance','month','year','search','create'))->render();
	            }

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

          	/*$salaryData = Employee::where(['iStatus' => 1, 'isDelete' => 0])
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
            ->get();*/



				// Get all dates for the selected month except Sundays
				$startDate = Carbon::createFromDate($year, $month, 1);
				$endDate = $startDate->copy()->endOfMonth();
				$allDates = [];


				$holidays = DB::table('holiday_master')
				    ->whereBetween('date', [$startDate, $endDate])
				    ->pluck('date')
				    ->toArray(); // Get holiday dates as an array


				for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
				    if ($date->format('N') != 7) { // Exclude Sundays
				        $allDates[] = $date->toDateString();
				    }
				}

				$salaryData = Employee::where(['iStatus' => 1, 'isDelete' => 0])
				    ->leftJoin('attendence_master', 'attendence_master.empId', '=', 'employee_master.empid')
				    ->when($request->month, fn ($query) => $query->whereMonth('attendence_master.start_date_time', $month))
				    ->when($request->year, fn ($query) => $query->whereYear('attendence_master.start_date_time', $year))
				    ->select(
				        'employee_master.empid',
				        'employee_master.salary',
				        'employee_master.balance_cl',
				        'employee_master.balance_cf',
				    	DB::raw("SUM(CASE 
					    WHEN attendence_master.day = '3' 
					        OR attendence_master.end_date_time IS NULL AND attendence_master.day IS NULL 
					    THEN 1 ELSE 0 END) as total_absent"),
				        DB::raw("SUM(CASE WHEN attendence_master.day = '2' THEN 1 ELSE 0 END) as total_half_day"),
				        DB::raw("SUM(CASE WHEN attendence_master.day = '2' THEN 0.5 ELSE 0 END) as half_day"),
				        DB::raw("GROUP_CONCAT(DISTINCT DATE(attendence_master.start_date_time)) as present_dates") // Fetch all present dates
				    )
				    ->groupBy('employee_master.empid', 'employee_master.salary')
				    ->get();

				// Process absences for missing dates
					$salaryData->transform(function ($employee) use ($allDates, $holidays) {
				    $presentDates = explode(',', $employee->present_dates ?? '');
				    
				    // Exclude holidays from the missing dates
				    $workingDays = array_diff($allDates, $holidays); 
				    $missingDates = array_diff($workingDays, $presentDates); // Remove present days from working days

				    $employee->total_absent += count($missingDates); // Only count fully absent days
				    return $employee;
				});


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
						//$balance_cl = ($value->balance_cl == 0) ? 1 : $value->balance_cl;
						$balance_cl = $value->balance_cl+$value->balance_cf;

						// Corrected leave calculation
						$total_leave = $value->total_absent + ($value->total_half_day * 0.5);

						$Leave_ded = 0;
						$Total_B = 0;
						$rem = 0;

						$Total_A = $basic_salary + ($request->Incentive ?? 0) + ($request->Bonus ?? 0) + ($request->Others ?? 0);

						// Balance CL is only considered for full-day leave
						$used_full_day_leave = min($balance_cl, $value->total_absent);
						$unpaid_full_day_leave = max(0, $value->total_absent - $used_full_day_leave);

						// Half-day leaves are always unpaid
						$unpaid_half_day_leave = $value->total_half_day * 0.5;

						// Final used leave calculation (Only full-day leave can be "used leave")
						$used_leave = $used_full_day_leave;

						// Total unpaid leave (including full days & half days)
						$unpaid_leave = $unpaid_full_day_leave + $unpaid_half_day_leave;

						// Salary deductions
						//$deduction_for_absent = ($basic_salary / 30) * $unpaid_leave;
						$deduction_for_absent = ($basic_salary / 30) * $unpaid_full_day_leave;
						$deduction_for_half_day = ($basic_salary / 30) * 0.5 * $value->total_half_day;
						$Leave_ded = $deduction_for_absent + $deduction_for_half_day;

						$Total_B = $Leave_ded + ($request->PT ?? 0) + ($request->TDS ?? 0) + ($request->Loan_Advance ?? 0);
						$net_pay = $Total_A - $Total_B;

						// Ensure remaining leave balance is not negative
						$rem = max(0, $balance_cl - $used_full_day_leave);

						// HDIM Calculation (Unpaid leave days)
						$HDIM = $unpaid_leave;

						// Save Salary Details
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
						$SalaryDetail->Leave_taken = $HDIM ?? 0;
						$SalaryDetail->Rem = $rem ?? 0;
						$SalaryDetail->half_day_leave = $value->half_day ?? 0;
					    $SalaryDetail->total_half_day = $value->total_half_day ?? 0;
						$SalaryDetail->full_day_leave = $value->total_absent ?? 0;
						$SalaryDetail->save();



						$SalaryMaster = SalaryMaster::find($sid);
						if (!$SalaryMaster) {
						    return back()->with('error', 'Salary Master record not found.');
						}
						$SalaryMaster->is_process = $request->is_process;
						$SalaryMaster->save();
	                	
	                	/*$Employee = Employee::find($value->empid);
						if (!$Employee) {
						    return back()->with('error', 'Employee record not found.');
						}

						// Prevent balance_cl from updating incorrectly
						if ($used_leave > 0) {
						    $Employee->balance_cl = max(0, $Employee->balance_cl - $used_leave); // Ensure balance does not go negative
						}

						$Employee->save();*/


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

     	/*try{*/

    		$month = $request->month;
    		$year = $request->year;
    	    $sid=$request->salaryId;
            $create=1;
          	/*$salaryData = Employee::where(['iStatus' => 1, 'isDelete' => 0])
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
            ->get();*/

            $startDate = Carbon::createFromDate($year, $month, 1);
			$endDate = $startDate->copy()->endOfMonth();
			$allDates = [];


			$holidays = DB::table('holiday_master')
			    ->whereBetween('date', [$startDate, $endDate])
			    ->pluck('date')
			    ->toArray(); // Get holiday dates as an array


			for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
			    if ($date->format('N') != 7) { // Exclude Sundays
			        $allDates[] = $date->toDateString();
			    }
			}

			$salaryData = Employee::where(['iStatus' => 1, 'isDelete' => 0])
			    ->leftJoin('attendence_master', 'attendence_master.empId', '=', 'employee_master.empid')
			    ->when($request->month, fn ($query) => $query->whereMonth('attendence_master.start_date_time', $month))
			    ->when($request->year, fn ($query) => $query->whereYear('attendence_master.start_date_time', $year))
			    ->select(
			        'employee_master.empid',
			        'employee_master.salary',
			        'employee_master.balance_cl',
			        'employee_master.balance_cf',
			        DB::raw("SUM(CASE 
					    WHEN attendence_master.day = '3' 
					        OR attendence_master.end_date_time IS NULL AND attendence_master.day IS NULL 
					    THEN 1 ELSE 0 END) as total_absent"),
			        DB::raw("SUM(CASE WHEN attendence_master.day = '2' THEN 1 ELSE 0 END) as total_half_day"),
			        DB::raw("SUM(CASE WHEN attendence_master.day = '2' THEN 0.5 ELSE 0 END) as half_day"),
			        DB::raw("GROUP_CONCAT(DISTINCT DATE(attendence_master.start_date_time)) as present_dates") // Fetch all present dates
			    )
			    ->groupBy('employee_master.empid', 'employee_master.salary')
			    ->get();

			// Process absences for missing dates
				$salaryData->transform(function ($employee) use ($allDates, $holidays) {
			    $presentDates = explode(',', $employee->present_dates ?? '');
			    
			    // Exclude holidays from the missing dates
			    $workingDays = array_diff($allDates, $holidays); 
			    $missingDates = array_diff($workingDays, $presentDates); // Remove present days from working days

			    $employee->total_absent += count($missingDates); // Only count fully absent days
			    return $employee;
			});
				
				//return $salaryData;
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
						//$balance_cl = ($value->balance_cl == 0) ? 1 : $value->balance_cl;
						$balance_cl = $value->balance_cl+$value->balance_cf;

						// Corrected leave calculation
						$total_leave = $value->total_absent + ($value->total_half_day * 0.5);

						$Leave_ded = 0;
						$Total_B = 0;
						$rem = 0;

						$Total_A = $basic_salary + ($request->Incentive ?? 0) + ($request->Bonus ?? 0) + ($request->Others ?? 0);

						// Balance CL is only considered for full-day leave
						$used_full_day_leave = min($balance_cl, $value->total_absent);
						$unpaid_full_day_leave = max(0, $value->total_absent - $used_full_day_leave);

						// Half-day leaves are always unpaid
						$unpaid_half_day_leave = $value->total_half_day * 0.5;

						// Final used leave calculation (Only full-day leave can be "used leave")
						$used_leave = $used_full_day_leave;

						// Total unpaid leave (including full days & half days)
						$unpaid_leave = $unpaid_full_day_leave + $unpaid_half_day_leave;

						// Salary deductions
					//	$deduction_for_absent = ($basic_salary / 30) * $unpaid_leave;
						$deduction_for_absent = ($basic_salary / 30) * $unpaid_full_day_leave;
						$deduction_for_half_day = ($basic_salary / 30) * 0.5 * $value->total_half_day;
						$Leave_ded = $deduction_for_absent + $deduction_for_half_day;

						$Total_B = $Leave_ded + ($request->PT ?? 0) + ($request->TDS ?? 0) + ($request->Loan_Advance ?? 0);
						$net_pay = $Total_A - $Total_B;

						// Ensure remaining leave balance is not negative
						$rem = max(0, $balance_cl - $used_full_day_leave);

						// HDIM Calculation (Unpaid leave days)
						$HDIM = $unpaid_leave;

						// Save Salary Details
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
						$SalaryDetail->Leave_taken = $HDIM ?? 0;
						$SalaryDetail->Rem = $rem ?? 0;
						$SalaryDetail->total_half_day = $value->total_half_day ?? 0;
						$SalaryDetail->half_day_leave = $value->half_day ?? 0;
						$SalaryDetail->full_day_leave = $value->total_absent ?? 0;
						$SalaryDetail->save();




						$SalaryMaster = SalaryMaster::find($sid);
						if (!$SalaryMaster) {
						    return back()->with('error', 'Salary Master record not found.');
						}
						$SalaryMaster->is_process = $request->is_process;
						$SalaryMaster->save();
	                	
	                	/*$Employee = Employee::find($value->empid);
						if (!$Employee) {
						    return back()->with('error', 'Employee record not found.');
						}

						// Prevent balance_cl from updating incorrectly
						if ($used_leave > 0) {
						    $Employee->balance_cl = max(0, $Employee->balance_cl - $used_leave); // Ensure balance does not go negative
						}

						$Employee->save();*/


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

    	 	$detail=SalaryDetail::where(['salaryId'=>$id])->get();

    	 	foreach ($detail as $key => $value) 
    	 	{
	    	 	$Employee = Employee::find($value->employeeId);
				if (!$Employee) 
				{
				    return back()->with('error', 'Employee record not found.');
				}
				$Employee->balance_cf = $value->Rem; // Ensure balance does not go negative

				$Employee->save();
    	 	}

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
        $monthYear = date('M Y', strtotime("{$salary->salary_year}-{$salary->salary_month}-01"));

        return response()->json([
        	'sid'=>$salary->sDetailId,
        	'employeeId'=>$salary->employeeId,
            'employeeName'=>$salary->employeeName,
            'basic_salary'=>$salary->basic_salary,
            'monthYear'=> $monthYear,
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
            'half_day'=>$salary->half_day_leave,
            'total_half_day'=>$salary->total_half_day,
            'total_absent'=>$salary->full_day_leave,
            'Rem'=>$salary->Rem,            
        ]);
    }
    public function updateSalaryDetailData(Request $request)
    {
    	try
    	{

    	    $SalaryDetail = SalaryDetail::select('salary_detail.*',
	    	     DB::raw('(select balance_cl from employee_master where employee_master.empid = salary_detail.employeeId  limit 1) as balance_cl'),
	    	     DB::raw('(select balance_cf from employee_master where employee_master.empid = salary_detail.employeeId  limit 1) as balance_cf')
    	    	,DB::raw('(select name from employee_master where employee_master.empid = salary_detail.employeeId  limit 1) as employeeName'))->where('sDetailId', $request->sid)->first();

			if (!$SalaryDetail) {
	            return response()->json(['error' => 'Salary details not found'], 404);
	        }else
	        {
	        			$basic_salary = $SalaryDetail->basic_salary;
						//$balance_cl = ($SalaryDetail->balance_cl == 0) ? 1 : $SalaryDetail->balance_cl;
						$balance_cl = $SalaryDetail->balance_cl+$value->balance_cf;

						// Corrected leave calculation
						$total_leave = $request->total_absent + ($request->total_half_day * 0.5);

						// Balance CL is only used for full-day leave
						$used_full_day_leave = min($balance_cl, $request->total_absent);
						$unpaid_full_day_leave = max(0, $request->total_absent - $used_full_day_leave);

						// Half-day leaves are always unpaid
						$unpaid_half_day_leave = $request->total_half_day * 0.5;

						// Final used leave calculation (Only full-day leave can be "used leave")
						$used_leave = $used_full_day_leave;

						// Total unpaid leave (includes unpaid full-day + half-day leaves)
						$unpaid_leave = $unpaid_full_day_leave + $unpaid_half_day_leave;

						// Salary deductions
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

						// Prevent remaining leave balance from going negative
						$rem = max(0, $balance_cl - $total_leave);
                        $total_half_day=$request->total_half_day;
                        
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
						$SalaryDetail->Rem = $rem;  // Updated remaining leave balance
						$SalaryDetail->Accumlated = $request->Accumlated ?? 0;
						$SalaryDetail->Used = $used_leave ?? 0;
						$SalaryDetail->Leave_taken = $unpaid_leave ?? 0;
						$SalaryDetail->half_day_leave = $request->total_half_day ?? 0;
						$SalaryDetail->total_half_day = $total_half_day ?? 0;
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
     public function pdf($id)
    {
        try 
        {
            $salary = SalaryDetail::select('salary_detail.*','salary_master.salary_year','salary_master.salary_month',DB::raw('(select name from employee_master where employee_master.empid = salary_detail.employeeId  limit 1) as employeeName'))->join('salary_master','salary_detail.salaryId','=','salary_master.sid')->where('sDetailId', $id)->first();

            $pdf = PDF::loadview('admin.salary_process.salary_slip', ['salary' => $salary])->setPaper('a4','portrait');
            return $pdf->stream('salary.pdf');

        } catch (\Throwable $th) {

            // Rollback & Return Error Message
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function ReGenerateEMP(Request $request)
    {


    	    $datacount=SalaryDetail::where(['sDetailId'=>$request->salaryId])->join('salary_master','salary_detail.salaryId','=','salary_master.sid')->first();

            $startDate = Carbon::createFromDate($datacount->salary_year, $datacount->salary_month, 1);
			$endDate = $startDate->copy()->endOfMonth();
			$allDates = [];
			$sid=$datacount->sDetailId;


			$holidays = DB::table('holiday_master')
			    ->whereBetween('date', [$startDate, $endDate])
			    ->pluck('date')
			    ->toArray(); // Get holiday dates as an array


			for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
			    if ($date->format('N') != 7) { // Exclude Sundays
			        $allDates[] = $date->toDateString();
			    }
			}

			$salaryData = Employee::where(['iStatus' => 1, 'isDelete' => 0,'employee_master.empid'=>$datacount->employeeId])
			    ->leftJoin('attendence_master', 'attendence_master.empId', '=', 'employee_master.empid')
			    ->when($datacount->salary_month, fn ($query) => $query->whereMonth('attendence_master.start_date_time', $datacount->salary_month))
			    ->when($datacount->salary_year, fn ($query) => $query->whereYear('attendence_master.start_date_time', $datacount->salary_year))
			    ->select(
			        'employee_master.empid',
			        'employee_master.salary',
			        'employee_master.balance_cl',
			        'employee_master.balance_cf',
			        DB::raw("SUM(CASE 
					    WHEN attendence_master.day = '3' 
					        OR attendence_master.end_date_time IS NULL AND attendence_master.day IS NULL 
					    THEN 1 ELSE 0 END) as total_absent"),
			        DB::raw("SUM(CASE WHEN attendence_master.day = '2' THEN 1 ELSE 0 END) as total_half_day"),
			        DB::raw("SUM(CASE WHEN attendence_master.day = '2' THEN 0.5 ELSE 0 END) as half_day"),
			        DB::raw("GROUP_CONCAT(DISTINCT DATE(attendence_master.start_date_time)) as present_dates") // Fetch all present dates
			    )
			    ->groupBy('employee_master.empid', 'employee_master.salary')
			    ->get();

			// Process absences for missing dates
				$salaryData->transform(function ($employee) use ($allDates, $holidays) {
			    $presentDates = explode(',', $employee->present_dates ?? '');
			    
			    // Exclude holidays from the missing dates
			    $workingDays = array_diff($allDates, $holidays); 
			    $missingDates = array_diff($workingDays, $presentDates); // Remove present days from working days

			    $employee->total_absent += count($missingDates); // Only count fully absent days
			    return $employee;
			});
				
				
            	$SalaryDetail=SalaryDetail::where(['sDetailId'=>$sid])->first();

				$data=SalaryDetail::where(['sDetailId'=>$sid])->count();
			
				$SalaryMaster=SalaryMaster::where(['sid'=>$datacount->sid])->count();
			foreach ($salaryData as $value) 
            {
                if($value->salary == 0 || $value->salary == null)
                {
                   return 1;

                }else
                {                  
	                 if($data != 0)
	                 {
	                	$basic_salary = $value->salary;
						//$balance_cl = ($value->balance_cl == 0) ? 1 : $value->balance_cl;
						$balance_cl = $value->balance_cl+$value->balance_cf;

						// Corrected leave calculation
						$total_leave = $value->total_absent + ($value->total_half_day * 0.5);

						$Leave_ded = 0;
						$Total_B = 0;
						$rem = 0;

						$Total_A = $basic_salary + ($request->Incentive ?? 0) + ($request->Bonus ?? 0) + ($request->Others ?? 0);

						// Balance CL is only considered for full-day leave
						$used_full_day_leave = min($balance_cl, $value->total_absent);
						$unpaid_full_day_leave = max(0, $value->total_absent - $used_full_day_leave);

						// Half-day leaves are always unpaid
						$unpaid_half_day_leave = $value->total_half_day * 0.5;

						// Final used leave calculation (Only full-day leave can be "used leave")
						$used_leave = $used_full_day_leave;

						// Total unpaid leave (including full days & half days)
						$unpaid_leave = $unpaid_full_day_leave + $unpaid_half_day_leave;

						// Salary deductions
						//$deduction_for_absent = ($basic_salary / 30) * $unpaid_leave;
						$deduction_for_absent = ($basic_salary / 30) * $unpaid_full_day_leave;
						$deduction_for_half_day = ($basic_salary / 30) * 0.5 * $value->total_half_day;
						$Leave_ded = $deduction_for_absent + $deduction_for_half_day;

						$Total_B = $Leave_ded + ($request->PT ?? 0) + ($request->TDS ?? 0) + ($request->Loan_Advance ?? 0);
						$net_pay = $Total_A - $Total_B;

						// Ensure remaining leave balance is not negative
						$rem = max(0, $balance_cl - $used_full_day_leave);

						// HDIM Calculation (Unpaid leave days)
						$HDIM = $unpaid_leave;

						// Save Salary Details

						$SalaryDetail->employeeId = $value->empid;
						$SalaryDetail->month = $datacount->salary_month;
						$SalaryDetail->year = $datacount->salary_year;
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
						$SalaryDetail->Leave_taken = $HDIM ?? 0;
						$SalaryDetail->Rem = $rem ?? 0;
						$SalaryDetail->half_day_leave = $value->half_day ?? 0;
						$SalaryDetail->full_day_leave = $value->total_absent ?? 0;
						$SalaryDetail->save();




						$SalaryMaster = SalaryMaster::find($sid);
						if (!$SalaryMaster) {
						    return back()->with('error', 'Salary Master record not found.');
						}
						$SalaryMaster->is_process = $request->is_process;
						$SalaryMaster->save();
	                	
	                	/*$Employee = Employee::find($value->empid);
						if (!$Employee) {
						    return back()->with('error', 'Employee record not found.');
						}

						// Prevent balance_cl from updating incorrectly
						if ($used_leave > 0) {
						    $Employee->balance_cl = max(0, $Employee->balance_cl - $used_leave); // Ensure balance does not go negative
						}

						$Employee->save();*/

		        return redirect()->back()->with('success','Salary Detail Data Updated Successfully');

	                }else{
	                	return 2;
	                }
            	}
            }

            $attendance=SalaryMaster::when($datacount->salary_month, fn ($query, $month) => $query->where('salary_month', $month))
	            ->when($datacount->salary_year, fn ($query, $year) => $query->where('salary_year', $year))->paginate(30);

	            $month=$datacount->salary_month;
	            $month=$datacount->salary_year;
    		return view('admin.salary_process.ajax_index', compact('attendance','month','year','create'))->render();

    }

}