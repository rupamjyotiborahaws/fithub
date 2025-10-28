@extends('layouts.layout')

@section('title', 'Attendance Report')

@section('content')
<div class="container-fluid card container-card">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12 col-sx-12">
            <h5 style="margin:0 0 12px;" class="font-semibold mb-2">Attendance Report</h5>
            <hr>
        </div>
    </div>
    <div class="row" style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:8px; margin-bottom:10px;">
        <div class="col-md-12 col-lg-12 col-sm-12 col-sx-12">
            <div class="flex flex-wrap gap-3">
                <div class="">
                    <input type="text" id="memberSearchforAttendanceReport" class="mb-4 p-2 border rounded" style="border:1px solid #ccc; border-radius:4px; padding:4px; width:100%;" placeholder="Search Members..." />
                    <div class="matched_members_attendance_report" style="max-height:300px; overflow:auto;"></div>
                    <input type="hidden" id="all_members" value="" />
                </div>
                <div class="">
                    <label for="from_date" class="block mb-2 font-medium text-gray-700" style="width:300px;">From Date:</label>
                    <input type="date" name="from_date" id="from_date" />
                </div>
                <div class="">
                    <label for="to_date" class="block mb-2 font-medium text-gray-700" style="width:300px;">To Date:</label>
                    <input type="date" name="to_date" id="to_date" />
                </div>
                <div class="">
                    <button id="report_btn" class="attn_report_btn">Generate Report</button>
                </div>
                <div class="">
                    <a href="#" class="download_attn_report" target="_blank" style="text-decoration:none; color:blue; cursor:pointer;">
                        <button class="attn_download_report_btn">📄 Download</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
            <div id="report_section" class="mt-6 attendance_report" style="margin-top:24px; height:600px; width:100%; overflow:auto; padding:10px;">
                <!-- Report will be displayed here -->
            </div>
        </div>
    </div>
</div>
<script>
    const total_members_attn_report = @json($allMembers ?? []);
    console.log('All Members:', total_members_attn_report);
</script>
@endsection