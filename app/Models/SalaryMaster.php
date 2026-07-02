<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryMaster extends Model
{
    public $table = 'salary_master';
    protected $dates = [
        'created_at',
        'updated_at',
     
    ];
    protected $primaryKey = 'sid'; // Set the correct primary key

    protected $fillable = [
        'sid', 'salary_month', 'salary_year', 'is_process', 'is_attendace'
    ];
}


