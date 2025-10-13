<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberTrainer extends Model
{
    protected $table = 'member_trainer';
    protected $fillable = ['member_id', 'trainer_id', 'allotment_date'];
}
