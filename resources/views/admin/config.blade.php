@extends('layouts.layout')

@section('title', 'Config Settings')

@section('content')
<div class="container-fluid mx-auto py-6" style="min-height: 75vh;">
    <div class="row">
        <div class="col-12">
            <h5 style="margin:0 0 12px;" class="font-semibold mb-4">General Settings</h5>
            @php
                $config = $config ?? null;
            @endphp
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <label for="membership_renewal_reminder">Membership Renewal Reminder (in days)</label>    
            <input type="number" class="form-control" id="membership_renewal_reminder" name="membership_renewal_reminder" 
                   value="{{ $config->membership_renewal_reminder ?? 0 }}">
        </div>
        <div class="col-6">
            <label for="membership_transfer_limit">Membership Transfer Limit</label>    
            <input type="number" class="form-control" id="membership_transfer_limit" name="membership_transfer_limit" 
                   value="{{ $config->membership_transfer_limit ?? 0 }}">
        </div>
    </div>
    <div class="row">
        <div class="col-6"></div>
        <div class="col-6">
            <button class="save_settings mt-3" style="float: right;">Save Settings</button>
        </div>
    </div>
</div>
@endsection