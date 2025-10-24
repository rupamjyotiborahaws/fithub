<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipTransferLog extends Model
{
    protected $table = 'membership_transfer_log';

    protected $fillable = [
        'member_id',
        'from_membership_id',
        'to_membership_id',
        'transfer_date',
    ];
}
