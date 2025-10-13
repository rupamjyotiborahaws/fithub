<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberHealthTrack extends Model
{
    protected $table = 'member_health_track';
    protected $fillable = [
        'user_id',
        'measure_date',
        'weight',
        'height',
        'bmi',
        'body_fat_percentage',
        'muscle_mass',
        'waist_circumference',
        'hip_circumference',
        'chest_circumference',
        'thigh_circumference',
        'arm_circumference',
        'notes'
    ];
}
