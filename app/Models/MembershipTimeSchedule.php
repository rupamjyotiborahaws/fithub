<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipTimeSchedule extends Model
{
    protected $table = 'membership_time_schedules';
    protected $fillable = ['membership_id', 'start_time'];
}
