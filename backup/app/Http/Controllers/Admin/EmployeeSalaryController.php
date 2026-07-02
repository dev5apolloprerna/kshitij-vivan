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
use App\Exports\SalaryExport;
use Maatwebsite\Excel\Facades\Excel;

use Hash;

use Barryvdh\DomPDF\Facade\Pdf;

class EmployeeSalaryController extends Controller
{

	public function index(Request $request)
	{
		try
		{

            $month = $request->month;
            $year = $request->year;

            $attendance=SalaryMaster::when($request->month, fn ($query, $month) => $query->where('salary_month', $month))
                                   ->when($request->year, fn ($query, $year) => $query->where('salary_year', $year))
                                    ->whereNotNull('confirm_date')
                                    ->orderBy('salary_month','desc')
                                    ->paginate(10);

			return view('admin.salary_processed.index',compact('attendance','month','year'));

        } catch (\Exception $e) 
        {
             return redirect()->back()->with('error', 'An error occurred .');
        }
    }
      public function getSalaryDetails($id)
    {
        $salary = SalaryDetail::select('salary_detail.*','salary_master.salary_year','salary_master.salary_month',DB::raw('(select name from employee_master where employee_master.empid = salary_detail.employeeId  limit 1) as employeeName'))->where('sDetailId', $id)->join('salary_master','salary_detail.salaryId','=','salary_master.sid')->first();


        if (!$salary) {
            return response()->json(['error' => 'Salary details not found'], 404);
        }
        $monthYear = date('M Y', strtotime("{$salary->salary_year}-{$salary->salary_month}-01"));

        return response()->json([
            'sid'=>$salary->sDetailId,
            'employeeId'=>$salary->employeeId ?? '-',
            'monthYear'=> $monthYear,
            'employeeName'=>$salary->employeeName ?? '-',
            'basic_salary'=>$salary->basic_salary ?? '-',
            'net_pay'=>$salary->net_pay ?? '-',
            'Incentive'=>$salary->Incentive ?? '-',
            'Bonus'=>$salary->Bonus ?? '-',
            'Others'=>$salary->Others ?? '-',
            'Total_A'=>$salary->Total_A ?? '-',
            'WDIM'=>$salary->WDIM ?? '-',
            'HDIM'=>$salary->HDIM ?? '-',
            'Leave_ded'=>$salary->Leave_ded ?? '-',
            'PT'=>$salary->PT ?? '-',
            'TDS'=>$salary->TDS ?? '-',
            'Loan_Advance'=>$salary->Loan_Advance ?? '-',
            'Total_B'=>$salary->Total_B ?? '-',
            'Accumlated'=>$salary->Accumlated ?? '-',
            'Used'=>$salary->Used ?? '-',
            'leave_taken'=>$salary->Leave_taken ?? '-',
            'Rem'=>$salary->Rem ?? '-',            
            'halfdayleave'=>$salary->half_day_leave ?? '-',            
            'totalhalfdayleave'=>$salary->total_half_day ?? '-',            
            'fulldayleave'=>$salary->full_day_leave ?? '-',            
        ]);
    }

     public function editData($id)
    {
        $salary = SalaryDetail::select('salary_detail.*',DB::raw('(select name from employee_master where employee_master.empid = salary_detail.employeeId  limit 1) as employeeName'))->where('salaryId', $id)->first();


        if (!$salary) {
            return response()->json(['error' => 'Salary details not found'], 404);
        }

        return response()->json([
            'salaryId'=>$salary->salaryId ?? '-',
            'Accumlated'=>$salary->Accumlated ?? '-',
            'Used'=>$salary->Used ?? '-',
            'leave_taken'=>$salary->Leave_taken ?? '-',
            'halfdayleave'=>$salary->half_day_leave ?? '-',            
            'fulldayleave'=>$salary->full_day_leave ?? '-',            
        ]);
    }
    public function updateData($id)
    {

                $SalaryDetail = SalaryDetail::select('salary_detail.*',DB::raw('(select name from employee_master where employee_master.empid = salary_detail.employeeId  limit 1) as employeeName'))->where('salaryId', $id)->first();

                if($SalaryDetail)
                {

                        $basic_salary=$value->salary;
                        $Total_A=$basic_salary+$SalaryDetail->Incentive+$SalaryDetail->Bonus+$SalaryDetail->Others;
                        $Leave_ded = ($SalaryDetail->WDIM != 0) ? ($Total_A / $SalaryDetail->WDIM) * $SalaryDetail->HDIM : 0;
                        $Total_B=$Leave_ded+$SalaryDetail->PT+$SalaryDetail->TDS+$SalaryDetail->Loan_Advance;
                        $net_pay=$Total_A-$Total_B;
                        $total_leave=$request->fulldayleave+$request->halfdayleave;
                        $used=$total_leave-$value->balance_cl;
                        
                        if($value->balance_cl != $total_leave)
                        {
                          $deduction_for_absent = $value->salary / 30 * $request->fulldayleave; // Assuming a 30-day month
                          $deduction_for_half_day = ($value->salary / 30) * 0.5 * $request->halfdayleave;
                          $net_pay = $value->salary - ($deduction_for_absent + $deduction_for_half_day);

                        }
                        $SalaryDetail->net_pay=$net_pay;
                        $SalaryDetail->basic_salary=$basic_salary;
                        $SalaryDetail->Incentive=$request->Incentive;
                        $SalaryDetail->Bonus=$request->Bonus;
                        $SalaryDetail->Others=$request->Others;
                        $SalaryDetail->Total_A=$Total_A;
                        $SalaryDetail->WDIM=$request->WDIM;
                        $SalaryDetail->HDIM=$request->HDIM;
                        $SalaryDetail->Leave_ded=$Leave_ded;
                        $SalaryDetail->PT=$request->PT;
                        $SalaryDetail->TDS=$request->TDS;
                        $SalaryDetail->Loan_Advance=$request->Loan_Advance;
                        $SalaryDetail->Total_B=$Total_B;
                        $SalaryDetail->Accumlated=$value->balance_cl;
                        $SalaryDetail->Used=$used;
                        $SalaryDetail->Leave_taken=$total_leave;
                        $SalaryDetail->Rem=$request->Rem;
                        $SalaryDetail->half_day_leave=$request->halfdayleave ?? 0;
                        $SalaryDetail->full_day_leave=$request->fulldayleave ?? 0;
                        $SalaryDetail->save();

                }
                return redirect()->back()->with('success','Leave Updated Successfully');

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
    public function view($id)
    {
        $data=SalaryMaster::select('salary_month','salary_year')->where(['sid'=>$id])->first();
        $salaryData = SalaryDetail::select('salary_detail.*','salary_master.salary_year','salary_master.salary_month',DB::raw('(select name from employee_master where employee_master.empid = salary_detail.employeeId  limit 1) as employeeName'))->join('salary_master','salary_detail.salaryId','=','salary_master.sid')->where('salaryId', $id)->paginate(30);

    	return view('admin.salary_processed.view',compact('salaryData','data'));
    }

    public function export_salary(Request $request,$month=null,$year=null)
    {
           /* $fileName = 'pay_slip_' . now()->format('d-m-Y_H-i-s') . '.xlsx';
            return Excel::download(new SalaryExport($month, $year), $fileName);*/

            $attendance=SalaryMaster::select('salary_master.*','salary_detail.*',DB::raw('(select name from employee_master where employee_master.empid = salary_detail.employeeId  limit 1) as employeeName'))->join('salary_detail','salary_detail.salaryId','=','salary_master.sid')->leftjoin('employee_master','employee_master.empid','=','salary_detail.employeeId')
            ->when($month, fn ($query, $Month) => $query->where('salary_month', $Month))
            ->when($year, fn ($query, $Year) => $query->where('salary_year', $Year))->paginate(30);

            return view('admin.salary_processed.export_salary_data', compact('attendance'));

    }
    public function pdf($id)
    {
        try 
        {
            $salary = SalaryDetail::select('salary_detail.*','salary_master.salary_year','salary_master.salary_month',DB::raw('(select name from employee_master where employee_master.empid = salary_detail.employeeId  limit 1) as employeeName'))->join('salary_master','salary_detail.salaryId','=','salary_master.sid')->where('sDetailId', $id)->first();

            $pdf = PDF::loadview('admin.salary_processed.salary_slip', ['salary' => $salary])->setPaper('a4','portrait');
            return $pdf->stream('salary.pdf');

        } catch (\Throwable $th) {

            // Rollback & Return Error Message
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}