@extends('layouts.layout')

@section('title', 'Salary Settings')

@section('content')
<div class="container-fluid card container-card">
    <div class="row">
        <div class="col-12">
            <h5 style="margin:0 0 12px;" class="font-semibold mb-2">Staff Salary Settings</h5>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <button class="add_new_salary" data-bs-toggle="modal" data-bs-target="#addStaffSalaryModal" 
            style="margin:0; font-size:15px; font-weight:600; float:right;">+ Add Staff Salary</button>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            @if(count($salary) > 0)
                <table style="width:100%; border-collapse:collapse; margin-top:12px; overflow:scroll; border:1px solid #ddd;">
                    <thead>
                        <tr>
                            <th class="salary_table_head">Staff Name</th>
                            <th class="salary_table_head">Staff Type</th>
                            <th class="salary_table_head">Salary</th>
                            <th class="salary_table_head">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($salary as $sal)
                        <tr>
                            <td class="salary_table_data">{{ $sal->staff_name }}</td>
                            <td class="salary_table_data">{{ $sal->staff_type }}</td>
                            <td class="salary_table_data">{{ $sal->amount }}</td>
                            <td class="salary_table_data">
                                <button class="edit_salary" 
                                    data-id="{{ $sal->id }}"
                                    data-staff_name="{{ $sal->staff_name }}"
                                    data-staff_type="{{ $sal->staff_type }}"
                                    data-amount="{{ $sal->amount }}"
                                    data-bs-toggle="modal" data-bs-target="#editStaffSalaryModal">
                                    Edit
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <p>No salary settings found.</p>
            @endif
        </div>
    </div>
</div>
<!-- Add Staff Salary Modal -->
<div class="modal fade" id="addStaffSalaryModal" tabindex="-1" aria-labelledby="addStaffSalaryModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" style="width:100%; margin-top:30px;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStaffSalaryModalLabel">Add Staff Salary</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="staffName" class="form-label">Staff Name</label>
                            <input type="text" class="form-control" id="staffName" name="staff_name" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="staffType" class="form-label">Staff Type</label>
                            <select class="form-select" id="staffType" name="staff_type" required>
                                <option value="" selected disabled>Select Staff Type</option>
                                <option value="Trainer">Trainer</option>
                                <option value="Receptionist">Receptionist</option>
                                <option value="Cleaner">Cleaner</option>
                                <option value="Manager">Manager</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="row salary_row">
                        <div class="col-md-12 mb-3">
                            <label for="salary" class="form-label">Salary</label>
                            <input type="number" class="form-control" id="salary" name="salary" placeholder="Enter the salary for this staff member">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="add_salary">Add Salary</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Staff Salary Modal -->
<div class="modal fade" id="editStaffSalaryModal" tabindex="-1" aria-labelledby="editStaffSalaryModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" style="width:100%; margin-top:30px;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editStaffSalaryModalLabel">Add Staff Salary</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="salaryId" name="salary_id">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="staffName" class="form-label">Staff Name</label>
                            <input type="text" class="form-control" id="staffName" name="staff_name" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="staffType" class="form-label">Staff Type</label>
                            <input type="text" class="form-control" id="staffType" name="staff_type" required readonly>
                        </div>
                    </div>
                    <div class="row salary_row">
                        <div class="col-md-12 mb-3">
                            <label for="salary" class="form-label">Salary</label>
                            <input type="number" class="form-control" id="salary" name="salary" placeholder="Enter the salary for this staff member">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="edit_save">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection