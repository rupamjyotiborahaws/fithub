<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeePaymentScheduleHistory extends Model
{
    protected $table = 'fee_payment_schedule_history';

    protected $fillable = [
        'member_id',
        'membership_type',
        'for_month',
        'due_date',
        'amount',
        'paid_on',
        'is_paid',
        'fee_payment_id',
        'latest'
    ];
}
