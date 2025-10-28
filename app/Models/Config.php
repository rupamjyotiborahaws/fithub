<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table = 'config';

    protected $fillable = [
        'membership_renewal_reminder',
        'membership_transfer_limit', 
        'attendance_opening_time',
        'attendance_last_time',
    ];
}
