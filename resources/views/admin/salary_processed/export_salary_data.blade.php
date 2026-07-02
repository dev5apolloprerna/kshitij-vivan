<?php
$filename = 'Pay_slip_' . date('d-m-Y H:s:i') . '.xls';

header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=" . $filename);

ob_end_clean();

echo
"Sr No"
 . "\t" . "Name"
 . "\t" . "Month"
 . "\t" . "Basic Salary"
 . "\t" . "Incentive"
 . "\t" . "Bonus"
 . "\t" . "Others"
 . "\t" . "Total (A)"
 . "\t" . "WDIM"
 . "\t" . "HDIM"
 . "\t" . "Leave Ded"
 . "\t" . "PT"
 . "\t" . "TDS"
 . "\t" . "Loan/Advance"
 . "\t" . "Total (B)"
 . "\t" . "Accumlated"
 . "\t" . "Used"
 . "\t" . "Leave Taken"
 . "\t" . "Rem"
 . "\t" . "NET Pay (A-B)"

 . "\n";
$i = 1;
foreach ($attendance as $key => $row)  
{
        
    echo
    $i
    . "\t" . $row['employeeName']
    . "\t" . date("F Y", mktime(0, 0, 0, $row['salary_month'], 1, $row['salary_year']))
    . "\t" . $row['basic_salary']
    . "\t" . $row['Incentive']
    . "\t" . $row['Bonus']
    . "\t" . $row['Others']
    . "\t" . $row['Total_A']
    . "\t" . $row['WDIM']
    . "\t" . $row['HDIM']
    . "\t" . $row['Leave_ded']
    . "\t" . $row['PT']
    . "\t" . $row['TDS']
    . "\t" . $row['Loan_Advance']
    . "\t" . $row['Total_B']
    . "\t" . $row['Accumlated']
    . "\t" . $row['Used']
    . "\t" . $row['Leave_taken']
    . "\t" . $row['Rem']
    . "\t" . $row['net_pay']
    . "\n";
    $i++;
}