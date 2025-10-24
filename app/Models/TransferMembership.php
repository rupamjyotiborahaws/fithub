<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferMembership extends Model
{
    protected $table = 'membership_transfer_history';

    protected $fillable = [
        'member_id',
        'old_membership_id',
        'new_membership_id',
        'old_membership_start_date',
        'old_membership_end_date',
        'new_membership_start_date',
        'new_membership_end_date',
        'transfer_date',
    ];
}
