<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberDetail extends Model
{
    protected $table = 'memberdetails';
    protected $fillable = [
        'user_id',
        'height',
        'weight',
        'age',
        'gender',
        'fitness_goals',
        'medical_conditions',
        'emergency_contact_name',
        'emergency_contact_phone',
        'membership_type',
        'membership_start_date',
        'membership_end_date',
        'discount',
        'profile_picture',
        'dob'
    ];
}
