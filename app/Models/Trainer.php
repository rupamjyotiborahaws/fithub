<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    protected $table = 'trainers';

    protected $fillable = [
        'name',
        'dob',
        'phone',
        'gender',
        'email',
        'address',
        'height',
        'weight',
        'specialization',
        'certificate_no',
    ];
}
