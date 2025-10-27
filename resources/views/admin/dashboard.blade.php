@extends('layouts.layout')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid mx-auto py-6">
    <div class="row mb-6">
        <div class="col-12">
            <div class="card hide-scrollbar" style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:15px; margin-bottom:2px;">
                <input type="text" id="memberSearch" placeholder="Search Members..." style="border:1px solid #ccc; border-radius:4px; padding:8px;" />
                <div class="matched_members" style="max-height:200px; overflow:auto;"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-lg-8 col-sm-12 col-sx-12 mb-4">
            <div class="bg-white p-2 rounded-lg shadow-md">
                <h5 class="font-semibold mb-4">Member Registrations</h5>
                <hr>
                <div style="height: 350px;">
                    <canvas id="registrationChart"></canvas>
                </div>
                <div style="text-align: center; margin-top: 20px;">
                    <input type="radio" class="mb-2 recent_registrations" name="recent_registrations" value="3" checked>&nbsp; last 3 months &nbsp;&nbsp;&nbsp;
                    <input type="radio" class="mb-2 recent_registrations" name="recent_registrations" value="6">&nbsp; last 6 months &nbsp;&nbsp;&nbsp;
                    <input type="radio" class="mb-2 recent_registrations" name="recent_registrations" value="12">&nbsp; last 12 months &nbsp;&nbsp;&nbsp;
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-4 col-sm-12 col-sx-12 mb-4">
            <div class="bg-white p-2 rounded-lg shadow-md">
                <h5 class="font-semibold mb-4">Members with Pending Fees</h5>
                <hr>
                <div style="height: 350px; overflow:auto;" class="pending_fee_members hide-scrollbar">
                    
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const allMembers = @json($allMembers ?? []);
    console.log('All Members:', allMembers);
</script>
@endsection