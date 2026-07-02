<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Attendence;
use App\Models\Employee;
use Carbon\Carbon;

use Illuminate\Http\JsonResponse;


class AttendanceController extends Controller
{
   public function index(Request $request)
    {
        $emp=Employee::where(['iStatus'=>1])->get();

        return view('admin.attendence.index',compact('emp'));

    }
  public function fetchEvents(Request $request)
  {
        $empid = $request->input('empid');
        $start = Carbon::parse($request->input('start'))->startOfDay();
        $end = Carbon::parse($request->input('end'))->endOfDay();

        $holidays = DB::table('holiday_master')->whereBetween('date', [$start, $end])->get(['date', 'name']);

        $events = Attendence::where('empId', $empid)
            ->whereBetween('start_date_time', [$start, $end])
            ->get(['start_date_time as start', 'end_date_time as end', 'empId', 'day']);

        // Format holiday data for FullCalendar
        $holidayEvents = $holidays->map(function ($holiday) {
            return [
                'title' => 'Holiday: ' . $holiday->name,
                'start' => $holiday->date,
                'allDay' => true, // Show as an all-day event
                'backgroundColor' => '#FF0000', // Red background for holidays
                'borderColor' => '#FF0000',
                'textColor' => '#FFFFFF'
            ];
        });

        
        return response()->json(array_merge($events->toArray(), $holidayEvents->toArray()));



  }
  public function markAttendance(Request $request)
    {
     
    //   $Attendence=Attendence::where(['empid'=>$request->empid])->whereDate('created_at', $request->date)->get();
        $Attendence = Attendence::where(['empid' => $request->empid])->whereDate('start_date_time', $request->date)->whereDate('end_date_time', $request->date)->get();
    
      if(sizeof($Attendence) == 0)
      {
          $Attendence=new Attendence();
          $Attendence->empId=$request->empid;
          $Attendence->start_date_time=$request->date;
          $Attendence->end_date_time=$request->date;
          $Attendence->day=$request->status;
          $Attendence->save();

      }else{
          DB::table('attendence_master')
            ->where(['empid' => $request->empid])
            ->whereDate('start_date_time', $request->date)
            ->whereDate('end_date_time', $request->date)
            ->update([
                // 'start_date_time' =>$request->date,
                // 'end_date_time' =>$request->date,
                'day' =>$request->status,
            ]);
      }
        
        return response()->json(['success' => true]);
    }

} 
