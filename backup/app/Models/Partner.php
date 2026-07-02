<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    public $table = 'partner';
    protected $fillable = [
        'partnerId', 'name', 'photo'
    ];
}


