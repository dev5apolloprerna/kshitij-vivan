<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use App\Models\Service;
use App\Models\News;
use App\Models\Partner;
use App\Models\Inquiry;
use App\Models\Jobs;
use App\Models\AppliedJob;
use App\Models\MetaData;


class frontController extends Controller
{
    public function index()
    {
                $seo=MetaData::where(['id'=>1])->first();

        $news=News::where(['iStatus'=>1,'isDelete'=>0])->orderBy('id', 'asc')->take(4)->get();
        $service=Service::where(['iStatus'=>1,'isDelete'=>0])->orderBy('serviceName', 'asc')->take(4)->get();

        return view('frontview.index',compact('news','service','seo'));
    }
    public function about(){
                $seo=MetaData::where(['id'=>2])->first();

        return view('frontview.about',compact('seo'));
    }
    public function contact()
    {
        $seo=MetaData::where(['id'=>3])->first();
        return view('frontview.contact',compact('seo'));
    }
  
    public function services(Request $request,$id)
    {

        $service=Service::where(['serviceId'=>$id,'iStatus'=>1,'isDelete'=>0])->first();
        $serviceList=Service::where(['iStatus'=>1,'isDelete'=>0])->orderBy('serviceName', 'asc')->get();

        return view('frontview.services',compact('service','serviceList'));
    }

    public function partner()
    {
        $partner=Partner::where(['iStatus'=>1,'isDelete'=>0])->get();
        return view('frontview.partner',compact('partner'));
    }
    public function news()
    {
        $news=News::where(['iStatus'=>1,'isDelete'=>0])->orderBy('id', 'asc')->paginate(env('PER_PAGE_COUNT'));
        return view('frontview.news',compact('news'));
    }
    public function newsdetail($id)
    {
        $news=News::where(['iStatus'=>1,'isDelete'=>0,'id'=>$id])->first();

        return view('frontview.news-detail',compact('news'));
    }
    public function career()
    {
        $seo=MetaData::where(['id'=>4])->first();

        $Jobs=Jobs::where(['iStatus'=>1,'isDelete'=>0])->get();
        return view('frontview.career',compact('Jobs','seo'));
    }
    public function careerdetail(){
        return view('frontview.career-detail');
    }

    public function thankyou(){
        return view('frontview.thankyou');
    }
    public function contact_store(Request $request)
    {
             $inquiry = new Inquiry();
            $inquiry->firstname=$request->fname;
            $inquiry->lastname=$request->lname;
            $inquiry->email=$request->email;
            $inquiry->mobileNumber=$request->phone;
            $inquiry->message=$request->msg;
            $inquiry->strIp=$_SERVER['REMOTE_ADDR'];
            $inquiry->save();

            $SendEmailDetails = DB::table('sendemaildetails')
                ->where(['id' => 4])
                ->first();
            $name=$request->fname.' '.$request->lname;
            $root = $_SERVER['DOCUMENT_ROOT'];
            $file = file_get_contents($root . '/mailers/contactemail.html', 'r');
            $file = str_replace('#name', $name, $file);
            $file = str_replace('#email', $request->email, $file);
            $file = str_replace('#mobile', $request->phone, $file);
            $file = str_replace('#message', $request->msg, $file);
            
            $setting = DB::table("setting")->select('email')->first();
            $toMail = $setting->email;// "shahkrunal83@gmail.com";//
            
            $to = $toMail;
            $subject = 'Contact Inquiry';
            $message = $file;
            $header = "From:".$SendEmailDetails->strFromMail."\r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-type: text/html\r\n";
            
             $retval = mail($to,$subject,$message,$header);
            

            return redirect()->route('FrontContact')->with('success',"Inquiry Send Successfully");

    }
     public function submitCV(Request $request)
    {
            try
            {
                    $job=Jobs::select('created_at')->where(['jobId'=>$request->jobId])->first();
                    $pdfFile = "";
                    if ($request->hasFile('cv')) 
                    {
                        $root = $_SERVER['DOCUMENT_ROOT'];
                        $image = $request->file('cv');
                        $pdfFile = time() . '.' . $image->getClientOriginalExtension();
                        $date=date('Y/m/d',strtotime($job->created_at));
                        $destinationpath = $root.'/'.$date.'/';
                        if (!file_exists($destinationpath)) {
                            mkdir($destinationpath, 0755, true);
                        }
                        $image->move($destinationpath, $pdfFile);
                    }

                  $AppliedJob=new AppliedJob();
                  $AppliedJob->jobId=$request->jobId; 
                  $AppliedJob->name=$request->name; 
                  $AppliedJob->Email=$request->email; 
                  $AppliedJob->coverLetter=$request->coverLetter;
                  $AppliedJob->resume=$pdfFile;
                  $AppliedJob->save(); 

                 return view('frontview.thankyou');

            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
            }
    }
     public function otherservices(){
        return view('frontview.otherservices');
    }
    public function websitedevelopmentservices()
    {
        $seo=MetaData::where(['id'=>5])->first();
        return view('frontview.websitedevelopmentservices',compact('seo'));
    }
    public function mobileapplicationdevelopmentservices()
    {
        $seo=MetaData::where(['id'=>6])->first();
        return view('frontview.mobileapplicationdevelopmentservices',compact('seo'));
    }
    public function searchengineoptimizationservices()
    {
        $seo=MetaData::where(['id'=>7])->first();
        return view('frontview.searchengineoptimizationservices',compact('seo'));
    }
    public function socialmediamarketingservices()
    {
        $seo=MetaData::where(['id'=>8])->first();
        return view('frontview.socialmediamarketingservices',compact('seo'));
    }
    public function graphicdesignservices(){
        $seo=MetaData::where(['id'=>9])->first();
        return view('frontview.graphicdesignservices',compact('seo'));
    }

}