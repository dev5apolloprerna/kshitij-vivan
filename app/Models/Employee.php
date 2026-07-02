<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;


class Employee extends Authenticatable implements JWTSubject 
{
        use HasFactory, Notifiable;
    protected $primaryKey = 'empid';

    public $table = 'employee_master';
    protected $fillable = [
        'empid', 'name', 'email', 'mobile', 'location', 'in_time', 'out_time', 'grace_period','salary','balance_cl', 'morning_half_day_in_time', 'morning_half_day_out_time', 'evening_half_day_in_time', 'evening_half_day_out_time', 'username', 'password', 'device_token', 'role_id', 'report_to'
    ];
    protected $hidden = [
        'password',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getAuthPassword()
    {
        return $this->password;
    }
}


