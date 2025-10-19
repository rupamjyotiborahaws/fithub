@extends('layouts.layout')

@section('title', 'Fee Collections')

@section('content')
<div class="container-fluid mx-auto py-6" style="min-height: 75vh;">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12 col-sx-12">
            <h5 style="margin:0 0 12px;" class="font-semibold mb-4">Fee Collections</h5>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 col-lg-3 col-sm-12 col-xs-12">
            <label for="fee_month">Month:</label>
            <select name="fee_month" id="fee_month" class="form-select" style="width:300px; padding:6px;">
                <option value="0">--Select Month--</option>
                @foreach($fee_months as $month)
                    <option value="{{ $month }}">{{ $month }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 col-lg-3 col-sm-12 col-xs-12">
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
    <div class="row">
        <div class="col-md-8 col-lg-8 col-sm-12 col-xs-12">
            <div id="display_section" class="mt-6 fee_collection_display_section" style="margin-top:24px; height:600px; width:100%; overflow:auto; padding:10px; border:1px solid #ccc; border-radius:8px;">
                <canvas id="feeCollectionsDisplayChartCanvasId" style="height:550px; width:80%;"></canvas>
            </div>
        </div>
        <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
            <div id="text_display_section" class="mt-6 fee_collection_txt_display_section" style="margin-top:24px; height:600px; width:100%; border:1px solid #ccc; border-radius:8px; padding:10px;">
                <h6 class="font-semibold mb-2">Fee Collection Summary</h6>
                <div class="row hide-scrollbar mt-3" style="overflow: scroll;">
                    <div class="col-md-4 col-lg-4 col-sm-4 col-xs-4"><strong>Membership</strong></div>
                    <div class="col-md-4 col-lg-4 col-sm-4 col-xs-4"><strong>Month</strong></div>
                    <div class="col-md-4 col-lg-4 col-sm-4 col-xs-4"><strong>Fee Collected</strong></div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-4 col-lg-4 col-sm-4 col-xs-4">
                        @foreach($data['membership'] as $d)
                            <p>{{ $d }}</p>
                        @endforeach
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4 col-xs-4">
                        @foreach($data['month'] as $d)
                            <p>{{ $d }}</p>
                        @endforeach
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4 col-xs-4">
                        @foreach($data['total_amount'] as $d)
                            <p>{{ $d }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const data = @json($data);
    console.log(data);
</script>
@endsection