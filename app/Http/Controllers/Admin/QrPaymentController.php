<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\FeePaymentSchedule;
use App\Models\MemberDetail;
use App\Models\Client;

use Illuminate\Http\Request;

class QrPaymentController extends Controller
{
    public function show(User $member, Request $request) {
        $inputs = $request->all();
        $client = Client::first();
        $fee_schedule = FeePaymentSchedule::whereIn('id', $inputs['selectedPayments'])->get();
        $amount = 0;
        $pay_months = "";
        foreach ($fee_schedule as $key => $schedule) {
            $amount += $schedule->amount;
            if($key == 0) {
                $pay_months .= $schedule->for_month;
            } else {
                $pay_months .= ", ".$schedule->for_month;
            }
        }
        // Create a local "order" you can mark paid later (optional but recommended)
        $orderId = (string) Str::ulid(); // stable unique id for reconciliation
        // Build a UPI deep link (note: no server-side confirmation)
        // Required params: pa (VPA), pn (name), am (amount), cu (currency)
        $vpa = env('PAYMENTS_UPI_VPA');
        $payeeName = $client->name . ' (ICICI Bank)'; // Explicitly mention bank name
        $upiUrl = sprintf(
            'upi://pay?pa=%s&pn=%s&am=%s&cu=INR&tn=%s&tr=%s',
            rawurlencode($vpa),
            rawurlencode($payeeName),
            $amount,
            rawurlencode($member->name.' : '.$pay_months),
            rawurlencode($orderId) // your internal reference
        );
        return response()->json([
            'member' => $member,
            'amount' => $amount,
            'upi_url' => $upiUrl,
            'order_id' => $orderId
        ]);
    }

    public function show_member_wise(User $member, Request $request) {
        // Build a UPI deep link (note: no server-side confirmation)
        // Required params: pa (VPA), pn (name), am (amount), cu (currency)
        $client = Client::first();
        $vpa = env('PAYMENTS_UPI_VPA');
        $payeeName = $client->name . ' (ICICI Bank)';
        $memberdetails = MemberDetail::where('user_id', $member->id)->first();
        $membership_id = $memberdetails ? $memberdetails->membership_type : null;
        $fee_schedule = FeePaymentSchedule::where('member_id', $member->id)
                        ->where('membership_type', $membership_id)
                        ->where('is_paid', 0)
                        ->first();
        $amount = $fee_schedule ? $fee_schedule->amount : 0;
        $orderId = (string) Str::ulid();
        $upiUrl = sprintf(
            'upi://pay?pa=%s&pn=%s&am=%s&cu=INR&tn=%s',
            rawurlencode($vpa),
            rawurlencode($payeeName),
            $amount,
            rawurlencode($member->name),
            rawurlencode($orderId)
        );
        return response()->json([
            'member' => $member,
            'amount' => $amount,
            'upi_url' => $upiUrl,
            'order_id' => $orderId
        ]);
    }
}
