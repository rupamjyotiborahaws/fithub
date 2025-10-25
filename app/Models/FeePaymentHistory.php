<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeePaymentHistory extends Model
{
    protected $table = 'fee_payment_history';

    protected $fillable = [
        'member_id',
        'fee_type',
        'pay_for_month',
        'amount',
        'payment_date',
        'payment_method',
        'transaction_id',
        'notes',
        'id_from_fee_payments'
    ];
}
