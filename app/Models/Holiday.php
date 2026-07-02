<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    public $table = 'holiday_master';
    protected $dates = [
        'created_at',
        'updated_at',
     
    ];

    protected $fillable = [
       'id', 'name', 'date'
    ];
}


