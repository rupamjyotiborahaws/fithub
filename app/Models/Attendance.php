<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendance';

    protected $fillable = [
        'member_id',
        'attendance_date',
        'check_in_time',
        'check_out_time',
        'status',
        'shift'
    ];
}
