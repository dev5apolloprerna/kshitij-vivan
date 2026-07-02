<?php
// echo "<pre>";
// print_r($renewalreport);
// exit;
$filename = 'Today_Report_' . date('d-m-Y H:s:i') . '.xls';

header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=" . $filename);

ob_end_clean();

echo
"Sr.No"
."\t" ."Emp Id"
."\t" ."Emp Name"
."\t" ."Start Date & Time"
."\t" ."Start Latitude "
."\t" ."Start Longitude "
."\t" ."Start Location "
."\t" ."End Date & Time"
."\t" ."End Latitude"
."\t" ."End Longitude"
."\t" ."End Location"
."\t" ."Attendance Status"
."\t" ."Comment"


 . "\n";


$i = 1;
$total=0;

foreach ($today as $key=>$today_report)
{
    
    if($today_report->start_date_time != NULL )
    {  $date = date('d-m-Y h:i A',strtotime($today_report->start_date_time))?? '-'; }
    else
        { $date='-'; }
    
    if($today_report->end_date_time != NULL && $today_report->end_date_time != '30-11--0001')
    { $enddate=date('d-m-Y h:i A',strtotime($today_report->end_date_time)) ?? '-'; }
    else
     { $enddate='-'; }
    if($today_report->day == 1)
    { $attendance= 'P'; }
    elseif($today_report->day == 2)
    { $attendance= 'H'; }
    elseif($today_report->day == 3)
    { $attendance= 'A'; }
    elseif($today_report->day == 4)
    { $attendance= 'L'; }
    else
    { $attendance= '-'; }
    

echo
    $i
  . "\t" . $today_report->empId
  . "\t" . $today_report->name
  . "\t" . $date
  . "\t" . $today_report->start_latitude
  . "\t" . $today_report->start_longitude
  . "\t" . $today_report->start_address
  . "\t" . $enddate
  . "\t" . $today_report->end_latitude
  . "\t" . $today_report->end_longitude
  . "\t" . $today_report->end_address 
  . "\t" . $attendance
  . "\t" . $today_report->comment

    . "\n";

    $i++;
}