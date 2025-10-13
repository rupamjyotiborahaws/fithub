<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class FeePayment extends Model
{
    protected $table = 'fee_payment';

    protected $fillable = [
        'member_id',
        'fee_type',
        'pay_for_month',
        'amount',
        'payment_date',
        'payment_method',
        'transaction_id',
        'notes',
    ];

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function paymentSchedules()
    {
        return $this->hasMany(FeePaymentSchedule::class, 'fee_payment_id');
    }
}
