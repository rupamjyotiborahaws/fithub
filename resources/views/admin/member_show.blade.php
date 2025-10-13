@extends('layouts.layout')

@section('title', 'Member Details')

@section('content')
<div class="container-fluid mx-auto py-6" style="min-height: 75vh;">
    <div class="row">
        <div class="col-md-12">
            {{-- Membership Settings --}}
            <div class="card" style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:18px; max-height: 700px; overflow: scroll;">
                <h5 style="margin:0 0 12px;" class="font-semibold mb-4">Basic Details</h5>
                <table id="memberBasicTable" style="border-collapse:collapse; width:100%; margin-bottom:1.25rem; border:1px solid #ddd;">
                    <thead>
                        <tr style="background-color:#f8f9fa;">
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:20%; font-weight:600; background-color:#e9ecef;">Member ID</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:20%; font-weight:600; background-color:#e9ecef;">Full Name</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:10%; font-weight:600; background-color:#e9ecef;">Email</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:10%; font-weight:600; background-color:#e9ecef;">Address</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:25%; font-weight:600; background-color:#e9ecef;">Phone No</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="background-color:#ffffff;">
                            <td style="padding:12px; border:1px solid #ddd;">{{ $member->id }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ $member->name }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ $member->email }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ $member->address }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ $member->phone_no }}</td>
                        </tr>
                    </tbody>
                </table>

                <h5 style="margin:0 0 12px;" class="font-semibold mb-4">Trainer(s) Allotted</h5>
                @if(count($trainers) > 0)
                <table id="memberBasicTable" style="border-collapse:collapse; width:100%; margin-bottom:1.25rem; border:1px solid #ddd;">
                    <thead>
                        <tr style="background-color:#f8f9fa;">
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:20%; font-weight:600; background-color:#e9ecef;">Trainer ID</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:20%; font-weight:600; background-color:#e9ecef;">Trainer Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trainers as $trainer)
                        <tr style="background-color:#ffffff;">
                            <td style="padding:12px; border:1px solid #ddd;">{{ $trainer->id }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ $trainer->name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <h6 style="text-align:center; padding:12px; border:1px solid #ddd;">No records found</h6>    
                @endif

                <h5 style="margin:0 0 12px;" class="font-semibold mb-4">Personal Details</h5>
                <table id="memberPersonalTable" style="border-collapse:collapse; width:100%; margin-bottom:1.25rem; border:1px solid #ddd;">
                    <thead>
                        <tr style="background-color:#f8f9fa;">
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:20%; font-weight:600; background-color:#e9ecef;">Gender</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:20%; font-weight:600; background-color:#e9ecef;">Age</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:10%; font-weight:600; background-color:#e9ecef;">Fitness Goals</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:10%; font-weight:600; background-color:#e9ecef;">Medical Conditions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="background-color:#ffffff;">
                            <td style="padding:12px; border:1px solid #ddd;">{{ $memberDetail->gender == 'm' ? 'Male' : ($memberDetail->gender == 'f' ? 'Female' : 'Others') }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ $memberDetail->age }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ $memberDetail->fitness_goals == "" ? "NA" : $memberDetail->fitness_goals }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ $memberDetail->medical_conditions == "" ? "NA" : $memberDetail->medical_conditions }}</td>
                        </tr>
                    </tbody>
                </table>

                <h5 style="margin:0 0 12px;" class="font-semibold mb-4">Membership Details</h5>
                <table id="membershipTable" style="border-collapse:collapse; width:100%; margin-bottom:1.25rem; border:1px solid #ddd;">
                    <thead>
                        <tr style="background-color:#f8f9fa;">
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:20%; font-weight:600; background-color:#e9ecef;">Membership Plan</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:20%; font-weight:600; background-color:#e9ecef;">Membership Start Date</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:10%; font-weight:600; background-color:#e9ecef;">Membership End Date</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:10%; font-weight:600; background-color:#e9ecef;">Discount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="background-color:#ffffff;">
                            <td style="padding:12px; border:1px solid #ddd;">{{ $memberDetail->membership_type_name }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ date('d-m-Y', strtotime($memberDetail->membership_start_date)) }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ date('d-m-Y', strtotime($memberDetail->membership_end_date)) }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ $memberDetail->discount == "" ? "NA" : $memberDetail->discount }}</td>
                        </tr>
                    </tbody>
                </table>

                <h5 style="margin:0 0 12px;" class="font-semibold mb-4">Progress Tracking</h5>
                @if(count($healthTracks) >0)
                <table id="healthTrackingTable" style="border-collapse:collapse; width:100%; margin-bottom:1.25rem; border:1px solid #ddd;">
                    <thead>
                        <tr style="background-color:#f8f9fa;">
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:12%; font-weight:600; background-color:#e9ecef;">Date</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:8%; font-weight:600; background-color:#e9ecef;">Weight</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:8%; font-weight:600; background-color:#e9ecef;">Height</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:8%; font-weight:600; background-color:#e9ecef;">BMI</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:8%; font-weight:600; background-color:#e9ecef;">Body Fat %</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:8%; font-weight:600; background-color:#e9ecef;">Muscle Mass %</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:8%; font-weight:600; background-color:#e9ecef;">Waist</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:8%; font-weight:600; background-color:#e9ecef;">Hip</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:8%; font-weight:600; background-color:#e9ecef;">Chest</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:8%; font-weight:600; background-color:#e9ecef;">Thigh</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:8%; font-weight:600; background-color:#e9ecef;">Arm</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:8%; font-weight:600; background-color:#e9ecef;">Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($healthTracks as $track)
                        <tr style="background-color:#ffffff;">
                            <td style="padding:12px; border:1px solid #ddd;">{{ date('d-m-Y', strtotime($track->measure_date)) }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ $track->weight }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ $track->height }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ $track->bmi }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ $track->body_fat_percentage }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ $track->muscle_mass }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ $track->waist_circumference }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ $track->hip_circumference }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ $track->chest_circumference }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ $track->thigh_circumference }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ $track->arm_circumference }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ $track->notes }}</td>

                        </tr>
                        @endforeach
                        </tr>
                    </tbody>
                </table>
                @else
                <h6 style="text-align:center; padding:12px; border:1px solid #ddd;">No records found</h6>
                @endif

                <h5 style="margin:0 0 12px;" class="font-semibold mb-4">Fee payment schedule</h5>
                <table id="bodyCircumTable" style="border-collapse:collapse; width:100%; margin-bottom:1.25rem; border:1px solid #ddd;">
                    <thead>
                        <tr style="background-color:#f8f9fa;">
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:10%; font-weight:600; background-color:#e9ecef;">For the month</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:10%; font-weight:600; background-color:#e9ecef;">Due date</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:10%; font-weight:600; background-color:#e9ecef;">Amount</th>
                            <th style="text-align:left; padding:12px; border:1px solid #ddd; width:10%; font-weight:600; background-color:#e9ecef;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($feeSchedules as $fee_schedule)
                        @php
                            $color = '';
                            if($fee_schedule->is_paid) {
                                $color = 'green';
                            } else {
                                $color = 'red';
                            }
                        @endphp
                        <tr style="background-color:#ffffff;">
                            <td style="padding:12px; border:1px solid #ddd;">{{ $fee_schedule->for_month }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ date('d-m-Y', strtotime($fee_schedule->due_date)) }}</td>
                            <td style="padding:12px; border:1px solid #ddd;">{{ $fee_schedule->amount }}</td>
                            <td style="padding:12px; border:1px solid #ddd; font-weight: bold; color: {{ $color }};">{{ $fee_schedule->is_paid ? 'Paid' : 'Pending' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
@endsection