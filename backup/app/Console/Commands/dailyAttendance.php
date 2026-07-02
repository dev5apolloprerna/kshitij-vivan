<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendence;
use App\Models\Employee;
use App\Models\Holiday;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class dailyAttendance extends Command
{

  protected $signature = 'daily:attendence';
  protected $description = 'daily attendence update';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $Holiday=Holiday::where(['iStatus'=>1,'isDelete'=>0,'date'=>Carbon::today()])->first();
        if(!empty($Holiday))
        {
             $data = Employee::select('employee_master.empid', 'employee_master.name', 'attendence_master.attendenceId as attendence_id')
                ->leftJoin('attendence_master', function($join) 
                {
                    $join->on('employee_master.empid', '=', 'attendence_master.empId')
                         ->whereDate('attendence_master.created_at', Carbon::today());
                })
                ->whereNull('attendence_master.attendenceId') // Filter employees with no attendance record for today
                ->get();

                if(sizeof($data) != 0)
                {

                    foreach ($data as $emp) 
                    {
                        $Attendence = new Attendence();
                        $Attendence->empId=$emp->empid;
                        $Attendence->day=4;
                        $Attendence->save();
                    }
                    $this->info("Attendance Updated Successfully");

                }else
                {
                    $this->error("No more data to update!");
                }
        }else
        {
        
            $data = Employee::select('employee_master.empid', 'employee_master.name', 'attendence_master.attendenceId as attendence_id')
                    ->leftJoin('attendence_master', function($join) 
                    {
                        $join->on('employee_master.empid', '=', 'attendence_master.empId')
                             ->whereDate('attendence_master.created_at', Carbon::today());
                    })
                    ->whereNull('attendence_master.attendenceId') // Filter employees with no attendance record for today
                    ->get();

            if(sizeof($data) != 0)
            {

                foreach ($data as $emp) 
                {
                    $Attendence = new Attendence();
                    $Attendence->empId=$emp->empid;
                    $Attendence->day=3;
                    $Attendence->save();
                }

                $this->info("Attendance Updated Successfully");

            }else
            {
                $this->error("No more data to update!");
            }
        }
    }
}
