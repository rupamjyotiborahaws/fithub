@extends('layouts.layout')

@section('title', 'New Registration')

@section('content')
<style>
    .w-100 { width:100%; }
    .mb-2 { margin-bottom:.75rem; }
    .mb-4 { margin-bottom:1.25rem; }
    .btn-primary {
        background:#2563eb;
        color:#fff;
        border:none;
        padding:10px 14px;
        cursor:pointer;
        border-radius:4px;
        font-weight:600;
    }
    .btn-primary:hover { background:#1d4ed8; }
    input, select {
        padding:8px;
        border:1px solid #ccc;
        border-radius:4px;
        background:#fff;
    }
    input:focus, select:focus {
        outline:none;
        border-color:#2563eb;
        box-shadow:0 0 0 1px #2563eb33;
    }
    .alert { padding:10px 14px; border-radius:4px; }
    .alert-success { background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0; }
    .alert-danger { background:#fef2f2;color:#991b1b;border:1px solid #fecaca; }
</style>
<div class="container-fluid mx-auto py-6">
    <div class="row">
        <div class="col-md-9">
            {{-- New Member Registration --}}
            <div class="card hide-scrollbar" style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:18px;">
                <h5 style="margin:0 0 12px;" class="font-semibold mb-4">New Member Registration</h5>
                <form method="POST" action="" autocomplete="on">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-sm-12 col-sx-12">
                            <div class="mb-2">
                                <label>Name</label>
                                <input name="name" value="{{ old('name') }}" required class="w-100">
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 col-sx-12">
                            <div class="mb-2">
                                <label>Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" required class="w-100">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12 col-sx-12">
                            <div class="mb-2">
                                <label>Address</label>
                                <input type="text" name="address" value="{{ old('address') }}" class="w-100">
                            </div>    
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-12 col-sx-12">
                            <div class="mb-2">
                                <label>Date of Birth</label>
                                <input type="date" name="dob" value="{{ old('dob') }}" class="w-100">
                            </div>   
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-12 col-sx-12">
                            <div class="mb-2">
                                <label>Gender</label>
                                <select name="gender" class="w-100">
                                    <option value="">-- Select --</option>
                                    <option value="m" {{ old('gender')=='m'?'selected':'' }}>Male</option>
                                    <option value="f" {{ old('gender')=='f'?'selected':'' }}>Female</option>
                                    <option value="o" {{ old('gender')=='o'?'selected':'' }}>Other</option>
                                </select>
                            </div>       
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-12 col-sx-12">
                            <div class="mb-2">
                                <label>Phone</label>
                                <input name="phone" value="{{ old('phone') }}" class="w-100">
                            </div>    
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-sm-12 col-sx-12">
                            <div class="mb-2">
                                <label>Fitness Goals</label>
                                <input type="text" name="fitness_goals" value="{{ old('fitness_goals') }}" class="w-100">
                            </div>   
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 col-sx-12">
                            <div class="mb-2">
                                <label>Medical Conditions</label>
                                <input type="text" name="medical_conditions" value="{{ old('medical_conditions') }}" class="w-100">
                            </div>   
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-sm-12 col-sx-12">
                            <div class="mb-2">
                                <label>Emergency Contact Name</label>
                                <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" class="w-100">
                            </div>   
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 col-sx-12">
                            <div class="mb-2">
                                <label>Emergency Contact Phone</label>
                                <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" class="w-100">
                            </div>   
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-sm-12 col-sx-12">
                            <div class="mb-2">
                                <label>Membership Plan</label>
                                <select name="membership_type" class="form-control membership_type" required class="w-100">
                                    <option value="">-- Select --</option>
                                    @foreach($memberships ?? [] as $m)
                                        <option value="{{ $m->id }}" {{ old('membership_type')==$m->id?'selected':'' }}>{{ $m->type }} - {{ ucfirst($m->duration_months) }} {{ $m->duration_months > 1 ? 'Months' : 'Month' }}</option>
                                    @endforeach
                                </select>
                            </div>   
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 col-sx-12">
                            <div class="mb-2">
                                <label>Start Date</label>
                                <input type="date" name="membership_start_date" value="{{ old('membership_start_date', now()->toDateString()) }}" required class="w-100">
                            </div>  
                        </div>
                    </div>
                    <div class="row mt-3 mb-3">
                        <div class="col-md-12 col-lg-12 col-sm-12 col-sx-12">
                            <div class="mb-2 time_schedules_container_display d-none">
                                <label id="one_time_amount_label">Time Schedules</label>
                                <div class="time_schedules_container">
                                      
                                </div>    
                            </div>   
                        </div>
                    </div>
                    <div class="row mt-3 mb-3">
                        <div class="col-md-12 col-lg-12 col-sm-12 col-sx-12">
                            <div class="mb-2 one_time_single d-none">
                                <div class="row">
                                    <div class="col-md-6 col-lg-6 col-sm-12 col-sx-12">
                                        <label id="one_time_amount_label">Amount to be paid (At a time)</label>
                                        <input type="text" id="one_time_amount_label" class="one_time_amount" name="one_time_amount" value="" required class="w-100">&nbsp;&nbsp;&nbsp;
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-12 col-sx-12 payment_con">
                                        <input type="checkbox" id="confirm_one_time_fee_payment" name="confirm_one_time_fee_payment"> I confirm that the fee amount has been collected.
                                    </div>
                                </div>
                            </div>   
                        </div>
                    </div>

                    <div class="row mt-3 mb-3 payment_type_details d-none">
                        <div class="col-md-6 col-lg-6 col-sm-12 col-sx-12">
                            <label for="paymentMethod" class="form-label">Payment Method</label>
                            <select class="form-select" id="paymentMethod" name="payment_method" required>
                                <option value="">Select Payment Method</option>
                                <option value="cash">Cash</option>
                                <option value="upi">UPI</option>
                            </select>      
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 col-sx-12 upi_transaction">
                            <label for="transactionId" class="form-label">UPI Transaction ID</label>
                            <input type="text" class="form-control" id="transactionId" name="transaction_id">      
                        </div>
                    </div>
                    <button type="submit" class="w-100 register_member">Register Member</button>
                </form>
            </div>
        </div>
        <div class="col-md-3">
            {{-- Search Members --}}
            <div class="card hide-scrollbar member_search_card">
                <div style="height:50px;">
                    <input type="text" id="memberSearch" placeholder="Search Members..." class="w-100 mb-4">
                </div>
                <div class="matched_members" style="max-height:300px; overflow:auto;">
                    
                </div>
            </div>
            {{-- Recent Members --}}
            <div class="card hide-scrollbar" style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:18px;">
                <h5 style="margin:0 0 12px;">Recent Members</h5>
                <div style="max-height:300px;overflow:auto;">
                    <table class="table" style="width:100%;border-collapse:collapse;">
                        <thead>
                            <tr>
                                <th style="text-align:left;padding:6px;border-bottom:1px solid #ccc;">Name</th>
                                <th style="text-align:left;padding:6px;border-bottom:1px solid #ccc;">Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentMembers ?? [] as $rm)
                                <tr>
                                    <td style="padding:6px;border-bottom:1px solid #eee;">{{ $rm->name }}</td>
                                    <td style="padding:6px;border-bottom:1px solid #eee;">{{ date('d-m-Y', strtotime($rm->membership_start_date)) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" style="padding:6px;">No recent members.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const allMembers = @json($allMembers ?? []);
    const membershipData = @json($memberships ?? []);
    const membershipTimeSchedule = @json($MembershipTimeSchedule ?? []);
</script>
@endsection