@extends('layouts.layout')

@section('title', 'Attendance Marker')

@section('content')
<div class="container-fluid mx-auto py-6" style="min-height: 75vh;">
    <div class="row">
        <div class="col-md-9 col-lg-9 col-sm-12 col-xs-12">
            {{-- Membership Settings --}}
            <div class="card hide-scrollbar" style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:18px; max-height: 725px; overflow: scroll;">
                <h5 style="margin:0 0 12px;" class="font-semibold mb-4">Basic Details</h5>
                <table id="attn_basic_table" style="border-collapse:collapse; width:100%; margin-bottom:1.25rem; border:1px solid #ddd;">
                    <thead>
                        <tr style="background-color:#f8f9fa;">
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:20%; font-weight:600; background-color:#e9ecef;">Member ID</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:20%; font-weight:600; background-color:#e9ecef;">Full Name</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:25%; font-weight:600; background-color:#e9ecef;">Phone No</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:25%; font-weight:600; background-color:#e9ecef;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="attn_basic_table_body">
                        @foreach($member_attendances as $member)
                        <tr style="background-color:#ffffff;">
                            <td style="padding:12px; border:1px solid #ddd;">{{ $member->id }}</td>
                            <td style="padding:12px; border:1px solid #ddd;"><a href="#" style="text-decoration:none;" 
                                data-bs-toggle="modal" data-bs-target="#attendanceReportModal" data-member_id="{{ $member->id }}" data-member_name="{{ $member->name }}">{{ $member->name }}</a>
                            </td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ $member->phone_no }}</td>
                            <td style="padding:12px; border:1px solid #ddd;" id="attendance_action_{{ $member->id }}">
                                @if($member->status == 'present')
                                    <span style="color: green; font-weight: bold;">Checked In at {{ date('h:i A', strtotime($member->check_in_time)) }} ({{ ucfirst($member->shift) }})</span>
                                @else
                                    <button class="mark_attendance" data-id="{{ $member->id }}">Check In</button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-3 col-lg-3 col-sm-12 col-xs-12">
            <div class="card hide-scrollbar" style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:8px; margin-bottom:10px;">
                <div>
                    <input type="text" id="memberSearchforAttendance" placeholder="Search Members..." 
                           class="w-100 mb-2 p-1 border rounded" style="border:1px solid #ccc; border-radius:4px; padding:4px;" />
                </div>
                <div class="matched_members_attendance" style="max-height:300px; overflow:auto;">
                    
                </div>
            </div>
            <div class="card hide-scrollbar" style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:8px; margin-bottom:10px;">
                <div>
                    <h5 style="margin:0 0 12px;" class="font-semibold mb-4">Instructions</h5>
                    <ul>
                        <li style="list-style-type: disc;">Click on "Check In" to mark attendance for a member.</li>
                        <li style="list-style-type: disc;">Click on a member's name to view their attendance report for the month.</li>
                        <li style="list-style-type: disc;">Use the search box to quickly find members by name or ID.</li>
                    </ul>
                </div>
            </div>
            <div class="card hide-scrollbar" style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:8px; margin-bottom:10px;">
                <div>
                    <h5 style="margin:0 0 12px;" class="font-semibold mb-4">Attendance Overview for today</h5>
                    <div id="attendance_overview" style="height: 350px;">
                        <canvas id="todayAttendanceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Attendance Report Modal -->
<div class="modal fade" id="attendanceReportModal" tabindex="-1" aria-labelledby="attendanceReportModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" style="width:100%;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="width: 1000px; height: 700px; overflow: scroll;">
            <div class="modal-header">
                <h5 class="modal-title" id="attendanceReportModalLabel">Attendance of <strong><span id="memberName" style="font-weight: bold;"></span></strong> for the month of <span id="attendanceMonth"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="attendanceReportContent">
                    <!-- Attendance report content will be loaded here via AJAX -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary print_attn_report">Print</button>
            </div>
        </div>
    </div>
</div>
<script>
    const total_members_attn = @json($member_attendances ?? []);
    console.log('All Members:', total_members_attn);
</script>
@endsection