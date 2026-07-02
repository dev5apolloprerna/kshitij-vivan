<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppliedJob extends Model
{
    public $table = 'applied_jobs';
    protected $fillable = [
        'candidateId', 'Jobid', 'firstName', 'lastName', 'Gender', 'DOB', 'Email', 'Mobile', 'stateId', 'cityId', 'Company', 'Designation', 'Experience', 'current_CTC', 'Education', 'Institute', 'Functions', 'Industry', 'resume', 'IStatus', 'isDelete', 'created_at', 'updated_at'
    ];
}


