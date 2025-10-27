<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeePaymentScheduleArchive extends Model
{
    protected $table = 'fee_payment_schedule_archive';

    protected $fillable = [
        'member_id',
        'membership_type',
        'for_month',
        'due_date',
        'amount',
        'paid_on',
        'is_paid',
        'fee_payment_id',
    ];
}
