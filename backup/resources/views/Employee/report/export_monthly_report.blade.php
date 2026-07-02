<?php
// echo "<pre>";
// print_r($renewalreport);
// exit;
$filename = 'monthly_Report_' . date('d-m-Y H:s:i') . '.xls';

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

foreach ($result as $key=>$monthly_report)
{
    
    if($monthly_report['start_date_time'] != NULL )
    {  $date = date('d-m-Y h:i A',strtotime($monthly_report['start_date_time']))?? '-'; }
    else
    { 
            $date=date('d-m-Y',strtotime($monthly_report['date'])); 
    }

    if($monthly_report['end_date_time'] != NULL && $monthly_report['end_date_time'] != '30-11--0001')
    { $enddate=date('d-m-Y h:i A',strtotime($monthly_report['end_date_time'])) ?? '-'; }
    else
     { $enddate='-'; }
     
        if ($monthly_report['day'] == 1 && $monthly_report['status'] === 'P')
        { $attendance="P"; }
    elseif ($monthly_report['day'] == 2)
        { $attendance="H"; }
    elseif ($monthly_report['status'] == 'Sunday')
        { $attendance="Sunday"; }
    elseif ($monthly_report['holiday_name'])
        { $attendance=$monthly_report['holiday_name']; }
    elseif ($monthly_report['status'] == 'A')
        { $attendance="A"; }
    else
        { $attendance="A";
    }

    
    

echo
    $i
  . "\t" . $monthly_report['empId']
  . "\t" . $monthly_report['employee_name']
  . "\t" . $date
  . "\t" . $monthly_report['start_latitude']
  . "\t" . $monthly_report['start_longitude']
  . "\t" . $monthly_report['start_address']
  . "\t" . $enddate
  . "\t" . $monthly_report['end_latitude']
  . "\t" . $monthly_report['end_longitude']
  . "\t" . $monthly_report['end_address'] 
  . "\t" . $attendance
  . "\t" . $monthly_report['comment']

    . "\n";

    $i++;
}