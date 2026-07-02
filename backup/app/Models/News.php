<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    public $table = 'news';
    protected $dates = [
        'created_at',
        'updated_at',
     
    ];

    protected $fillable = [
       'id', 'title', 'photo', 'description'
    ];
}


