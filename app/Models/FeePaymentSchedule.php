<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeePaymentSchedule extends Model
{
    protected $table = 'fee_payment_schedule';

    protected $fillable = [
        'member_id',
        'membership_type',
        'for_month',
        'due_date',
        'is_paid',
        'paid_on',
        'amount',
        'fee_payment_id'
    ];

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function feePayment()
    {
        return $this->belongsTo(FeePayment::class, 'fee_payment_id');
    }
}
