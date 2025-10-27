<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RenewalHistory extends Model
{
    protected $table = 'renewal_history';

    protected $fillable = [
        'member_id',
        'old_membership_id',
        'new_membership_id',
        'renewal_date',
    ];
}
