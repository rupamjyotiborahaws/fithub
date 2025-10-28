@extends('layouts.layout')

@section('title', 'Progress Tracker')

@section('content')
<div class="container-fluid card container-card">
    <div class="row">
        <div class="col-md-9 col-lg-9 col-sm-12 col-xs-12">
            <div class="card mt-2 hide-scrollbar instructions_mobile" style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:18px; overflow: scroll;">
                <div>
                    <h5 style="margin:0 0 12px;" class="font-semibold mb-2">Instructions</h5>
                    <ul class="instructions_list_fee_collection">
                        <li style="list-style-type: disc;" id="instructions_list_progress_record_1">Click on "Add Record" button to add health records of member</li>
                        <li style="list-style-type: disc;" id="instructions_list_progress_record_2">Click on the member name to see the progress chart of the member</li>
                        <li style="list-style-type: disc;" id="instructions_list_progress_record_3">Use Search to filter members and get the detailed fee payment data</li>
                    </ul>
                </div>
            </div>
            <div class="card member_search_card member_search_card_mobile">
                    <input type="text" class="memberSearchforkealthrec" placeholder="Search Members..." />
                <div class="matched_members_health_rec" style="max-height:200px; overflow:auto;"></div>
            </div>
            <div class="card hide-scrollbar" style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:18px; max-height: 700px; overflow: scroll;">
                <h5 style="margin:0 0 12px;" class="font-semibold mb-2">Progress Record Entry for Members</h5>
                <hr>
                <table id="health_rec_basic_table" style="border-collapse:collapse; width:100%; margin-bottom:1.25rem; border:1px solid #ddd;">
                    <thead>
                        <tr style="background-color:#f8f9fa;">
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:20%; font-weight:600; background-color:#e9ecef;">Member ID</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:20%; font-weight:600; background-color:#e9ecef;">Full Name</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:25%; font-weight:600; background-color:#e9ecef;">Phone No</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:25%; font-weight:600; background-color:#e9ecef;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="health_rec_basic_table_body">
                        @foreach($allMembers as $member)
                        <tr style="background-color:#ffffff;">
                            <td style="padding:12px; border:1px solid #ddd;">{{ $member->id }}</td>
                            <td style="padding:12px; border:1px solid #ddd;"><a href="#" style="text-decoration:none;" 
                                data-bs-toggle="modal" data-bs-target="#progressChartDisplayModal" data-member_id="{{ $member->id }}" data-member_name="{{ $member->name }}">{{ $member->name }}</a></td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ $member->phone_no }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">
                                <button class="add_health_record_btn"
                                    data-id="{{ $member->id }}"
                                    data-name="{{ $member->name }}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#addHealthRecord"
                                >
                                Add Record</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-3 col-lg-3 col-sm-12 col-xs-12">
            <div class="card member_search_card member_search_card_desktop">
                    <input type="text" class="memberSearchforkealthrec" placeholder="Search Members..." />
                <div class="matched_members_health_rec" style="max-height:300px; overflow:auto;">
                    
                </div>
            </div>
            <div class="card mt-2 hide-scrollbar instructions_desktop" style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:18px; overflow: scroll;">
                <div>
                    <h5 style="margin:0 0 12px;" class="font-semibold mb-4">Instructions</h5>
                    <ul class="instructions_list_fee_collection">
                        <li style="list-style-type: disc;" id="instructions_list_progress_record_1">Click on "Add Record" button to add health records of member</li>
                        <li style="list-style-type: disc;" id="instructions_list_progress_record_2">Click on the member name to see the progress chart of the member</li>
                        <li style="list-style-type: disc;" id="instructions_list_progress_record_3">Use Search to filter members and get the detailed fee payment data</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Membership Modal -->
<div class="modal fade" id="addHealthRecord" tabindex="-1" aria-labelledby="addHealthRecordLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addHealthRecordLabel">Add Progress Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="addHealthRecMemId" name="membership_id">
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 mb-3">
                            <h5 class="form-label member_name"></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 mb-3">
                            <label for="addWeight" class="form-label">Weight</label>
                            <input type="number" class="form-control" id="addWeight" name="weight" min="20.00" required>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 mb-3">
                            <label for="addHeight" class="form-label">Height</label>
                            <input type="number" class="form-control" id="addHeight" name="height" min="1" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 mb-3">
                            <label for="addBmi" class="form-label">BMI</label>
                            <input type="number" class="form-control" id="addBmi" name="bmi" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 mb-3">
                            <label for="addBodyFat" class="form-label">Body Fat %</label>
                            <input type="number" class="form-control" id="addBodyFat" name="body_fat" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 mb-3">
                            <label for="muscleMass" class="form-label">Muscle Mass</label>
                            <input type="number" class="form-control" id="muscleMass" name="muscle_mass" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 mb-3">
                            <label for="addWaistCir" class="form-label">Waist Circumference</label>
                            <input type="number" class="form-control" id="addWaistCir" name="west_cir" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 mb-3">
                            <label for="addHipCir" class="form-label">Hip Circumference</label>
                            <input type="number" class="form-control" id="addHipCir" name="hip_cir" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 mb-3">
                            <label for="chestCir" class="form-label">Chest Circumference</label>
                            <input type="number" class="form-control" id="chestCir" name="chest_cir" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 mb-3">
                            <label for="addThighCir" class="form-label">Thigh Circumference</label>
                            <input type="number" class="form-control" id="addThighCir" name="thigh_cir" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 mb-3">
                            <label for="addArmCir" class="form-label">Arm Circumference</label>
                            <input type="number" class="form-control" id="addArmCir" name="arm_cir" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 mb-3">
                            <label for="addNote" class="form-label">Note</label>
                            <textarea class="form-control" id="addNote" name="note"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="add_health_record_btn add_health_record">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Member Progress Chart Display Modal -->
<div class="modal fade" id="progressChartDisplayModal" tabindex="-1" aria-labelledby="progressChartDisplayModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content hide-scrollbar">
            <div class="modal-header">
                <h5 class="modal-title" id="progressChartDisplayModalLabel">Progress of <button id="memberName" class="progress_chart_member_name"></button>
                    <strong><span id="memberName" style="font-weight: bold;"></span></strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="progressChartContainer" class="hide-scrollbar">
                    <!-- Progress chart content will be loaded here via AJAX -->
                     <canvas id="progressChartCanvasId"></canvas>
                </div>
                <div id="progressMatricContainer" class="hide-scrollbar">
                    <input type="hidden" name="member_id" value="" />
                    <input type="radio" class="progress_matric" name="progress_matric" value="Weight" checked>&nbsp;Weight&nbsp;&nbsp;
                    <input type="radio" class="progress_matric" name="progress_matric" value="Height">&nbsp;Height&nbsp;&nbsp;
                    <input type="radio" class="progress_matric" name="progress_matric" value="BMI">&nbsp;BMI&nbsp;&nbsp;
                    <input type="radio" class="progress_matric" name="progress_matric" value="Body Fat Percentage">&nbsp;Body Fat Percentage&nbsp;&nbsp;
                    <input type="radio" class="progress_matric" name="progress_matric" value="Muscle Mass">&nbsp;Muscle Mass&nbsp;&nbsp;
                    <input type="radio" class="progress_matric" name="progress_matric" value="Waist Circumference">&nbsp;Waist&nbsp;&nbsp;
                    <input type="radio" class="progress_matric" name="progress_matric" value="Hip Circumference">&nbsp;Hip&nbsp;&nbsp;
                    <input type="radio" class="progress_matric" name="progress_matric" value="Chest Circumference">&nbsp;Chest&nbsp;&nbsp;
                    <input type="radio" class="progress_matric" name="progress_matric" value="Thigh Circumference">&nbsp;Thigh&nbsp;&nbsp;
                    <input type="radio" class="progress_matric" name="progress_matric" value="Arm Circumference">&nbsp;Arm
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const total_members_health = @json($allMembers ?? []);
    console.log('All Members:', total_members_health);
</script>
@endsection