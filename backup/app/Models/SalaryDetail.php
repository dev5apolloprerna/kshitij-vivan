<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryDetail extends Model
{
    public $table = 'salary_detail';
    protected $primaryKey = 'sDetailId'; // Set the correct primary key

    protected $fillable = [
        'sDetailId', 'salaryId', 'employeeId', 'month', 'center', 'net_pay', 'basic_salary', 'Incentive', 'Bonus', 'Others', 'Total_A', 'WDIM', 'HDIM', 'Leave_ded', 'PT', 'TDS', 'Loan_Advance', 'Total_B', 'Accumlated', 'Used', 'Leave_taken', 'Rem', 'half_day_leave', 'full_day_leave'
    ];
}


