<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendence extends Model
{
    use HasFactory;
    public $table = 'attendence_master';
       protected $primaryKey = 'attendenceId'; // Define the primary key

    protected $fillable = [
       'attendenceId', 'empId', 'start_date_time', 'end_date_time', 'start_location', 'end_location', 'working_hrs', 'day', 'comment'
    ];
}
