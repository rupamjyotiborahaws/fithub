@extends('layouts.layout')

@section('title', 'Transfer Membership')

@section('content')

<div class="container-fluid card container-card">
    <div class="row">
        <div class="col-md-12 mb-2">
            {{-- Membership Transfer --}}
            <h5 style="margin:0 0 12px;" class="font-semibold mb-2">Membership Transfer</h5>
            <hr>
            <input type="text" id="memberSearchMembershipTransfer" placeholder="Search by Member ID, Name, or Phone No">
            <div class="matched_members_transfer hide-scrollbar"></div>
            <div class="card membership_transfer_details"></div>
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
    const members_t = @json($members ?? []);
    const transferable_memberships = @json($transferable_memberships ?? []);
    console.log('All Members:', members_t);
    console.log('Transferable Memberships:', transferable_memberships);
</script>
@endsection