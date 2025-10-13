@extends('layouts.layout')

@section('title', 'Fee Collection')

@section('content')

<style>
    /* Custom styles for the fee collection page */
    .matched_members {
        max-height: 200px;
        overflow-y: auto;
        margin-bottom: 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 8px;
    }
    .matched_members div {
        padding: 8px 12px;
        border-bottom: 1px solid #eee;
        cursor: pointer;
    }
    .matched_members div:hover {
        background-color: #f5f5f5;
    }
    .fee_schedule {
        height: 900px;
        overflow-y: auto;
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 12px;
    }
</style>
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
            {{-- Fee Collection --}}
            <div class="card" style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:18px; height: 800px; overflow-x: auto;">
                <h5 style="margin:0 0 12px;" class="font-semibold mb-4">Fee Collection</h5>
                <input type="text" id="memberSearchFeeCollection" placeholder="Search by Member ID, Name, or Phone No" 
                       style="width:100%; padding:8px 12px; margin-bottom:12px; border:1px solid #ccc; border-radius:4px;">
                <div class="matched_members">

                </div>
                <div class="fee_schedule">

                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-white p-2 rounded-lg shadow-md">
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
<script>
    const total_members = @json($allMembers ?? []);
    console.log('All Members:', total_members);
    
    // Backup chart creation function
    function createSimpleChart() {
        const canvas = document.getElementById('currentMonthFeeCollectionChart');
        if (!canvas) {
            console.error('Canvas not found');
            return;
        }
        
        console.log('Creating simple test chart...');
        try {
            // Test with simple hardcoded data first
            const testChart = new Chart(canvas, {
                type: 'pie',
                data: {
                    labels: ['Paid', 'Unpaid'],
                    datasets: [{
                        data: [1000, 500],
                        backgroundColor: ['#28a745', '#dc3545']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
            console.log('Test chart created successfully:', testChart);
        } catch (error) {
            console.error('Error creating test chart:', error);
        }
    }
    
    // Initialize fee collection chart when page loads
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Fee collection page loaded, checking for chart element...');
        const chartElement = document.getElementById('currentMonthFeeCollectionChart');
        console.log('Chart element:', chartElement);
        
        // First try to load with our function
        if (chartElement && typeof window.loadCurrentMonthFeeCollectionData === 'function') {
            console.log('Trying main chart function...');
            setTimeout(function() {
                window.loadCurrentMonthFeeCollectionData();
            }, 1000);
        } else {
            console.log('Main function not available, trying backup...');
            // Fallback to simple chart
            setTimeout(createSimpleChart, 1500);
        }
    });
</script>
@endsection