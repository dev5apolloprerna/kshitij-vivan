<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Holiday;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HolidayMasterController extends Controller
{
    public function index(Request $request)
    {
        try{
            $holiday = Holiday::orderBy('id', 'desc')->paginate(env('PER_PAGE_COUNT'));
            return view('admin.holiday.index', compact('holiday'));
        } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }

    public function create(Request $request)
    {
        try
        {
            $Data = array(
                'name' => $request->name,
                'date' => $request->date,
            );
            DB::table('holiday_master')->insert($Data);

            return back()->with('success', 'Holiday Created Successfully.');
        } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }

    public function editview(Request $request, $id)
    {
        try
        {
            $data = Holiday::where(['iStatus' => 1, 'isDelete' => 0, 'id' => $id])->first();

            echo json_encode($data);
        } catch (\Exception $e) {
               report($e);
             return false;
        }
    }

    public function update(Request $request)
    {
        try{

        $update = DB::table('holiday_master')
            ->where(['iStatus' => 1, 'isDelete' => 0, 'id' => $request->id])
            ->update([
                'name' => $request->name,
                'date' => $request->date
            ]);

        return back()->with('success', 'Holiday Updated Successfully.');
    
        } catch (\Exception $e) {

                report($e);
         
                return false;
            }
        }
    


    public function delete(Request $request)
    {
        try{
        DB::table('holiday_master')->where(['iStatus' => 1, 'isDelete' => 0, 'id' => $request->id])->delete();

        return back()->with('success', 'Holiday Deleted Successfully!.');
        } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }
}
