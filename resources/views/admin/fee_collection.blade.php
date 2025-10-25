@extends('layouts.layout')

@section('title', 'Fee Collection')

@section('content')

@php
    use Carbon\Carbon;
    $currentMonth = Carbon::now()->format('M Y'); // Changed from 'Y-m' to
    $months = collect();
    for ($i = 0; $i < 3; $i++) {
        $months->push(Carbon::now()->subMonths($i)->format('M Y'));
    }
@endphp
<div class="container-fluid mx-auto py-6" style="min-height: 75vh;">
    <div class="row">
        <div class="col-md-9">
            <div class="card hide-scrollbar instructions_mobile mt-2" style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:18px; overflow: scroll;">
                <div>
                    <h5 style="margin:0 0 12px;" class="font-semibold mb-4">Instructions</h5>
                    <ul class="instructions_list_fee_collection">
                        <li style="list-style-type: disc;" id="instructions_list_fee_collection_1">Select checkboxes to pay the fee for selected members for the Pending month</li>
                        <li style="list-style-type: disc;" id="instructions_list_fee_collection_2">Use Search to filter members and get the detailed fee payment data</li>
                    </ul>
                </div>
            </div>
            <div class="card hide-scrollbar member_search_card_mobile">
                <input type="text" class="memberSearchFeeCollection" placeholder="Search by Member ID, Name, or Phone No.">
                <div class="matched_members_for_fee_collection">

                </div>
            </div>
            <div class="card hide-scrollbar fee_collection_data_div" style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:18px; max-height: 800px; overflow-x: auto;">
                <h5 style="margin:0 0 12px;" class="font-semibold mb-4">Fee Collection</h5>
                <div class="fee_schedule">
                    <table class="fee_collection_table hide-scrollbar">
                        <thead id="fee_collection_table_head">
                            <tr style="background-color:#f8f9fa;">
                                <th class="fee_collection_thead_data">ID</th>
                                <th class="fee_collection_thead_data">Full Name</th>
                                <th class="fee_collection_thead_data">Phone No</th>
                                <th class="fee_collection_thead_data">Pending</th>
                                <th class="fee_collection_thead_data" style="text-align:center;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="fee_collection_table_body">
                            @foreach($members_fee as $member)
                            <tr style="background-color:#ffffff;">
                                <td class="fee_collection_tbody_data">{{ $member->id }}</td>
                                <td class="fee_collection_tbody_data">{{ $member->name }}</td>
                                <td class="fee_collection_tbody_data">{{ $member->phone_no }}</td>
                                <td class="fee_collection_tbody_data" id="fee_status_{{ $member->id }}">
                                    <button class="member_fee_month">{{ $member->for_month }}</button> (₹{{ number_format($member->amount, 2) }})
                                </td>
                                <td class="fee_collection_tbody_data" style="text-align:center;" id="fee_action_{{ $member->id }}">
                                    @if($member->is_paid)
                                        <span style="color: green; font-weight: bold;">No Action Needed</span>
                                    @else
                                        <input type="checkbox" class="check_pay_for_multiple_member" name="multi_payment[]" value="{{ $member->id }}">
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="text-align: right; margin-top: 12px;">
                    <button class="pay_for_multiple_members" id="pay_for_multiple_members_btn" data-bs-toggle="modal" data-bs-target="#multipleFeePaymentModal">Pay for Selected Members</button>
                </div>    
            </div>
        </div>
        <div class="col-md-3">
            <div class="card hide-scrollbar member_search_card_desktop">
                <input type="text" class="memberSearchFeeCollection" placeholder="Search by Member ID, Name, or Phone No.">
                <div class="matched_members_for_fee_collection">

                </div>
            </div>
            <div class="card hide-scrollbar instructions_desktop mt-2" style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:18px; overflow: scroll;">
                <div>
                    <h5 style="margin:0 0 12px;" class="font-semibold mb-4">Instructions</h5>
                    <ul class="instructions_list_fee_collection">
                        <li style="list-style-type: disc;" id="instructions_list_fee_collection_1">Select checkboxes to pay the fee for selected members for the Pending month</li>
                        <li style="list-style-type: disc;" id="instructions_list_fee_collection_2">Use Search to filter members and get the detailed fee payment data</li>
                    </ul>
                </div>
            </div>
            <div class="card hide-scrollbar mt-2" style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:18px; overflow: scroll;">
                <h6 class="mb-4" id="chartHeader">Fee Collection status for the month of {{ \Carbon\Carbon::now()->format('M Y') }}</h6>
                <div style="height: 350px;">
                    <canvas id="currentMonthFeeCollectionChart"></canvas>
                </div>
                <div style="text-align: center; margin-top: 10px;">
                    @foreach ($months as $key=>$month)
                        <input type="radio" class="mb-2 recent_fee_collection" name="recent_fee_collection" value="{{ $month }}" @if($key == 0) checked @endif>&nbsp;{{ $month }} &nbsp;&nbsp;&nbsp;
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Fee Payment Modal -->
<div class="modal fade" id="feePaymentModal" tabindex="-1" aria-labelledby="feePaymentModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="feePaymentModalLabel">Process Fee Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="payment_ids" value="">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="feeType" class="form-label">Fee Types</label>
                            <select class="form-select" id="feeType" name="type" required>
                                <option value="">Select Fee Type</option>
                                <option value="monthly">Monthly</option>
                                <option value="annual">Annual</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="paymentMethod" class="form-label">Payment Method</label>
                            <select class="form-select" id="paymentMethod" name="payment_method" required>
                                <option value="">Select Payment Method</option>
                                <option value="cash">Cash</option>
                                <option value="upi">UPI</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="transactionId" class="form-label">UPI Transaction ID</label>
                            <input type="text" class="form-control" id="transactionId" name="transaction_id" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="note" class="form-label">Note</label>
                            <textarea class="form-control" id="note" name="note" rows="3" placeholder="Additional notes about the payment"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary proceed_payment">Proceed Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Multiple Member Fee Payment Modal -->
<div class="modal fade" id="multipleFeePaymentModal" tabindex="-1" aria-labelledby="multipleFeePaymentModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="multipleFeePaymentModalLabel">Process Fee Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="members_payment_info">
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="process_multiple_payment proceed_payment_multiple d-none">Proceed Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    const total_members = @json($allMembers ?? []);
    console.log('Total Members in Fee Collection:', total_members);
    // Backup chart creation function
    // function createSimpleChart() {
    //     const canvas = document.getElementById('currentMonthFeeCollectionChart');
    //     if (!canvas) {
    //         console.error('Canvas not found');
    //         return;
    //     }
    //     try {
    //         // Test with simple hardcoded data first
    //         const testChart = new Chart(canvas, {
    //             type: 'pie',
    //             data: {
    //                 labels: ['Paid', 'Unpaid'],
    //                 datasets: [{
    //                     data: [1000, 500],
    //                     backgroundColor: ['#28a745', '#dc3545']
    //                 }]
    //             },
    //             options: {
    //                 responsive: true,
    //                 maintainAspectRatio: false
    //             }
    //         });
    //     } catch (error) {
    //         console.error('Error creating test chart:', error);
    //     }
    // }
    
    // // Initialize fee collection chart when page loads
    // document.addEventListener('DOMContentLoaded', function() {
    //     $('.check_pay_for_multiple_member:checked').each(function() {
    //         $(this).prop('checked', false);
    //     });
    //     const chartElement = document.getElementById('currentMonthFeeCollectionChart');
    //     // First try to load with our function
    //     if (chartElement && typeof window.loadCurrentMonthFeeCollectionData === 'function') {
    //         setTimeout(function() {
    //             window.loadCurrentMonthFeeCollectionData();
    //         }, 1000);
    //     } else {
    //         // Fallback to simple chart
    //         setTimeout(createSimpleChart, 1500);
    //     }
    // });
</script>
@endsection