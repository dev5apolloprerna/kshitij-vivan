<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryLedger extends Model
{
    public $table = 'salary_ledger';
    protected $dates = [
        'created_at',
        'updated_at',
     
    ];

    protected $fillable = [
        'id', 'empid', 'opening_balance', 'credit_balance', 'debit_balance', 'closing_balance', 'employee_salary', 'employee_leave'
    ];
}


