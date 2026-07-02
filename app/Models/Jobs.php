<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{
    public $table = 'jobs';
    protected $fillable = [
        'jobId', 'jobTitle', 'jobDescription', 'experienceId', 'salaryId', 'industryId', 'functionId', 'stateId', 'cityId', 'job_end_date', 'enter_by', 'iStatus', 'isDelete', 'create_at', 'updated_at'
    ];
}


