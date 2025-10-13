@extends('layouts.layout')

@section('title', 'Config Settings')

@section('content')
<div class="container-fluid mx-auto py-6" style="min-height: 75vh;">
    <div class="row">
        <div class="col-md-12">
            <h5 style="margin:0 0 12px;" class="font-semibold mb-4">Config Settings</h5>
            @php
                $config = $config ?? null;
            @endphp
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <label for="registrationFee" style="font-weight: bold; display:block; margin-bottom:6px;">Registration Fee</label>
            <input type="text" id="registrationFee" placeholder="Registration Fee" value="{{ isset($config->registration_fee) ? $config->registration_fee : '' }}" style="width:100%; padding:8px 12px; margin-bottom:12px; border:1px solid #ccc; border-radius:4px;">
        </div>
        <div class="col-md-6">
            <label for="monthlyFee" style="font-weight: bold; display:block; margin-bottom:6px;">Monthly Fee</label>
            <input type="text" id="monthlyFee" placeholder="Monthly Fee" value="{{ isset($config->monthly_fee) ? $config->monthly_fee : '' }}" style="width:100%; padding:8px 12px; margin-bottom:12px; border:1px solid #ccc; border-radius:4px;">
        </div>
        <div class="col-md-12">
            <button class="btn btn-primary save_config" id="saveChanges">Save Config</button>
        </div>
    </div>
</div>
@endsection