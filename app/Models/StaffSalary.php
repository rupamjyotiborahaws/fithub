<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffSalary extends Model
{
    protected $table = 'staff_salary';

    protected $fillable = [
        'staff_name',
        'staff_type',
        'amount',
    ];
}
