@extends('layouts.layout')

@section('title', 'Fee Collections')

@section('content')
<div class="container-fluid card container-card">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12 col-sx-12">
            <h5 style="margin:0 0 12px;" class="font-semibold mb-2">Fee Collections</h5>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 col-lg-3 col-sm-12 col-xs-12">
            <div class="card hide-scrollbar mt-2" style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:18px; overflow: scroll;">
                <label for="fee_month">Month:</label>
                <select name="fee_month" id="fee_month" class="form-select" style="width:300px; padding:6px;">
                    <option value="0">--Select Month--</option>
                    @foreach($fee_months as $month)
                        <option value="{{ $month }}">{{ $month }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3 col-lg-3 col-sm-12 col-xs-12">
            <div class="card hide-scrollbar mt-2" style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:18px; overflow: scroll;">
                <label for="memberships">Membership:</label>
                <select name="memberships" id="memberships" class="form-select" style="width:300px; padding:6px;">
                    <option value="0">--Select Membership--</option>
                    <option value="-1">All</option>
                    @foreach($memberships as $membership)
                        <option value="{{ $membership->id }}">{{ $membership->type }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
            <div id="display_section" class="mt-6 fee_collection_display_section" style="margin-top:24px; height:600px; width:100%; overflow:auto; padding:10px; border:1px solid #ccc; border-radius:8px;">
                <canvas id="feeCollectionsDisplayChartCanvasId" style="height:550px; width:80%;"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection