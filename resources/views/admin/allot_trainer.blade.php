@extends('layouts.layout')

@section('title', 'Allot Trainer')

@section('content')
<div class="container-fluid mx-auto py-6" style="min-height:calc(100vh - 200px);">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 mb-4">
            <div>
                <h5 style="margin:0 0 12px;" class="font-semibold mb-1">Trainer Allotment</h5>     
            </div>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 mb-4">
            <div class="card mt-2 hide-scrollbar trainer_allot">
                <h5 style="margin:0 0 12px;" class="font-semibold mb-4">Members</h5>
                <div style="margin-top:6px;">
                    <select class="form-control" style="width:100%; display:inline-block; margin-right:8px;" id="member_id" name="member_id">
                        <option value="">--Select Member--</option>
                        @foreach($allMembers as $member)
                            <option value="{{ $member->id }}">{{ $member->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 mb-4">
            <div class="card mt-2 hide-scrollbar trainer_allot">
                <div>
                    <h5 style="margin:0 0 12px; float:left;" class="font-semibold mb-4">Trainers</h5>
                    <button class="allot_trainer" id="allot-trainer-btn">Allot Trainer</button>
                </div>
                <div>
                    @foreach($allTrainers as $trainer)
                        <div style="padding:10px 0; border-bottom:1px solid #eee;">
                            <input type="checkbox" id="trainer-{{ $trainer->id }}" name="trainers[]" value="{{ $trainer->id }}">&nbsp;&nbsp;
                            <label for="trainer-{{ $trainer->id }}"><strong>{{ $trainer->name }}</strong> ({{ $trainer->phone }})</label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- <div class="col-md-2 col-lg-2 col-sm-12 col-xs-12 mb-4">
            <h5 style="margin:0 0 12px;" class="font-semibold mb-4">&nbsp;</h5>
            <div style="margin-top:6px; float:right; margin-right:30px;">
                <button class="allot_trainer" id="allot-trainer-btn">Allot Trainer</button>
            </div>
        </div> -->
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 mb-4">
            <div class="bg-white p-2 rounded-lg shadow-md">
                <h5 class="font-semibold mb-4">No. of members alloted to the trainers</h5>
                <hr>
                <div style="height: 350px;">
                    <canvas id="trainerAllotmentChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection