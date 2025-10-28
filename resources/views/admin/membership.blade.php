@extends('layouts.layout')

@section('title', 'Membership Settings')

@section('content')
<div class="container-fluid card container-card">
    <div class="row mb-4">
        <div class="col-md-12">
            <button class="add_new_membership" data-bs-toggle="modal" data-bs-target="#addMembershipModal" style="margin:0; font-size:15px; font-weight:600; float:right;">+ Add Membership</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card hide-scrollbar" style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:18px; min-height: 600px; overflow: scroll;">
                <h5 style="margin:0 0 12px;" class="font-semibold mb-2">Available Memberships</h5>
                <hr>
                <table id="membershipTable" style="border-collapse:collapse; width:100%; margin-bottom:1.25rem; border:1px solid #ddd;">
                    <thead>
                        <tr style="background-color:#f8f9fa;">
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:20%; font-weight:600; background-color:#e9ecef;">Membership Name</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:10%; font-weight:600; background-color:#e9ecef;">Duration (Months)</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:10%; font-weight:600; background-color:#e9ecef;">Fee Type</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:10%; font-weight:600; background-color:#e9ecef;">Admission Fee</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:10%; font-weight:600; background-color:#e9ecef;">Monthly Fee</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:10%; font-weight:600; background-color:#e9ecef;">One Time Fee</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:25%; font-weight:600; background-color:#e9ecef;">Benefits</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:25%; font-weight:600; background-color:#e9ecef;">Description</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:25%; font-weight:600; background-color:#e9ecef;">Status</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:25%; font-weight:600; background-color:#e9ecef;">Transferrable</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:10%; font-weight:600; background-color:#e9ecef; text-align:center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($memberships as $membership)
                        <tr style="background-color:{{ $loop->even ? '#f8f9fa' : '#ffffff' }};">
                            <td style="padding:12px; border:1px solid #ddd;">{{ $membership->type }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ $membership->duration_months }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ $membership->payment_type == 'single' ? 'One Time' : 'Recurring' }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ number_format($membership->admission_fee, 2) }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ number_format($membership->monthly_fee, 2) }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ number_format($membership->one_time_fee, 2) }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ $membership->benefits }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ $membership->description }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ $membership->is_active ? 'Active' : 'Inactive' }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ $membership->is_transferable ? 'Yes' : 'No' }}</td>
                            <td style="padding:12px; border:1px solid #ddd; text-align:center;">
                                <button class="membership_edit me-1" 
                                    data-id="{{ $membership->id }}"
                                    data-type="{{ $membership->type }}"
                                    data-duration="{{ $membership->duration_months }}"
                                    data-payment_type="{{ $membership->payment_type }}"
                                    data-admission_fee="{{ $membership->admission_fee }}"
                                    data-monthly_fee="{{ $membership->monthly_fee }}"
                                    data-one_time_fee="{{ $membership->one_time_fee }}"
                                    data-benefits="{{ $membership->benefits }}"
                                    data-description="{{ $membership->description }}"
                                    data-is_active="{{ $membership->is_active }}"
                                    data-is_transferable="{{ $membership->is_transferable }}"
                                    data-time_schedules="{{ $membership->time_schedules }}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editMembershipModal"
                                >
                                    Edit
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Membership Modal -->
<div class="modal fade" id="addMembershipModal" tabindex="-1" aria-labelledby="addMembershipModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" style="width:100%;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMembershipModalLabel">Add New Membership Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="membershipType" class="form-label">Membership</label>
                            <input type="text" class="form-control" id="membershipType" name="type" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="duration" class="form-label">Duration (Months)</label>
                            <input type="number" class="form-control" id="duration" name="duration_months" min="1" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3 addm">
                            <label for="adm_fee" class="form-label">Admission Fee</label>
                            <input type="number" class="form-control" id="adm_fee" name="adm_fee" step="0.01" min="0">
                        </div>
                        <div class="col-md-4 mb-3 monthly">
                            <label for="monthly_fee" class="form-label">Monthly Fee</label>
                            <input type="number" class="form-control" id="monthly_fee" name="monthly_fee" step="0.01" min="0">
                        </div>
                        <div class="col-md-8 mb-3 one_time d-none">
                            <label for="one_time_fee" class="form-label">One Time Fee</label>
                            <input type="number" class="form-control one_time_fee" name="one_time_fee" step="0.01" min="0">
                        </div>
                        <div class="col-md-4 mt-4">
                            <input type="radio" class="payment_type" name="payment_type" value="single"> Single Payment <br>
                            <input type="radio" class="payment_type" name="payment_type" value="recurring" checked> Regular Payment
                        </div>
                    </div>
                    <div class="row benefits_row">
                        <div class="col-md-12 mb-3">
                            <label for="benefits" class="form-label">Benefits</label>
                            <textarea class="form-control" id="benefits" name="benefits" rows="3" placeholder="List the benefits of this membership plan"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Detailed description of the membership plan"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mt-4">
                            <input type="checkbox" class="form-check-input" id="is_active" style="border: 2px solid #ccc;" name="is_active">&nbsp;&nbsp;&nbsp;Active
                        </div>
                        <div class="col-md-6 mt-4">
                            <input type="checkbox" class="form-check-input" id="enable_transfer" name="is_transferable">&nbsp;&nbsp;&nbsp;Enable Transfer
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mt-4">
                            <input type="checkbox" class="form-check-input" id="is_time_schedule_required" style="border: 2px solid #ccc;" name="is_time_schedule_required">&nbsp;&nbsp;&nbsp;Time Schedule Required
                        </div>
                        <div class="col-md-6 mt-4 time_schedule d-none" style="height:150px; overflow-y: scroll;">
                            <div class="row" id="time_schedule_div_1">
                                <div class="col-md-6">
                                    <input type="time" class="form-control input_schedule_time" id="time_schedule_1" name="time_schedules[]">
                                </div>
                                <div class="col-md-6">
                                    
                                </div>
                            </div>
                            <button class="mt-2 add_more_time_schedule">+ Add more</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="add_membership">Add Membership</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Membership Modal -->
<div class="modal fade" id="editMembershipModal" tabindex="-1" aria-labelledby="editMembershipModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" style="width:100%;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMembershipModalLabel">Edit Membership Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="editMembershipId" name="membership_id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editMembershipType" class="form-label">Membership</label>
                            <input type="text" class="form-control" id="editMembershipType" name="type" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editDuration" class="form-label">Duration (Months)</label>
                            <input type="number" class="form-control" id="editDuration" name="duration_months" min="1" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3 addm_edit">
                            <label for="adm_fee" class="form-label">Admission Fee</label>
                            <input type="number" class="form-control" id="edit_adm_fee" name="edit_adm_fee" step="0.01" min="0">
                        </div>
                        <div class="col-md-4 mb-3 monthly_edit">
                            <label for="monthly_fee" class="form-label">Monthly Fee</label>
                            <input type="number" class="form-control" id="edit_monthly_fee" name="edit_monthly_fee" step="0.01" min="0">
                        </div>
                        <div class="col-md-8 mb-3 one_time_edit">
                            <label for="one_time_fee" class="form-label">One Time Fee</label>
                            <input type="number" class="form-control one_time_fee" id="edit_one_time_fee" name="edit_one_time_fee" step="0.01" min="0">
                        </div>
                        <div class="col-md-4 mt-4">
                            <input type="radio" class="edit_payment_type" name="edit_payment_type" value="single"> Single Payment <br>
                            <input type="radio" class="edit_payment_type" name="edit_payment_type" value="recurring" checked> Regular Payment
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3 benefits_row">
                            <label for="editBenefits" class="form-label">Benefits</label>
                            <textarea class="form-control" id="editBenefits" name="benefits" rows="3" placeholder="List the benefits of this membership plan"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="editDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="editDescription" name="description" rows="3" placeholder="Detailed description of the membership plan"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mt-4">
                            <input type="checkbox" class="form-check-input" id="is_active" style="border: 2px solid #ccc;" name="is_active">&nbsp;&nbsp;&nbsp;Active
                        </div>
                        <div class="col-md-6 mt-4">
                            <input type="checkbox" class="form-check-input" id="enable_transfer" name="is_transferable">&nbsp;&nbsp;&nbsp;Enable Transfer
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mt-4">
                            <input type="checkbox" class="form-check-input" id="is_edit_time_schedule_required" style="border: 2px solid #ccc;" name="is_edit_time_schedule_required">&nbsp;&nbsp;&nbsp;Time Schedule
                        </div>
                        <div class="col-md-6 mt-4 time_schedule_edit d-none" style="height:150px; overflow-y: scroll;">
                            <div class="row" id="time_schedule_edit_div_1">
                                <div class="col-md-6">
                                    <input type="time" style="width:100%;" class="form-control" id="time_schedule_edit_1" name="time_schedules_edit[]">
                                </div>
                                <div class="col-md-6">
                                    
                                </div>
                            </div>
                            <button class="mt-2 add_more_time_schedule_edit">+ Add more</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="edit_membership">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection