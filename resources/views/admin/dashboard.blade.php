@extends('layouts.layout')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid card container-card">
    <div class="row mb-6">
        <div class="col-12">
            <div class="card hide-scrollbar" style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:10px; margin-bottom:2px;">
                <input type="text" id="memberSearch" placeholder="Search Members..." style="border:1px solid #ccc; border-radius:4px; padding:8px;" />
                <div class="matched_members" style="max-height:200px; overflow:auto;"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12 col-sx-12 mb-2">
            <div class="card calender_card">
                <div class="calender_header">
                    <p><button class="prev_month">&lt;</button><label style="font-weight: bold;">Oct 2025</label><button class="next_month">&gt;</button></p>
                </div>
                <div class="calender_container">
                    <div class="row calender_row">
                        @php $i=2; @endphp
                        @foreach($days as $day)
                            @php 
                                if($day['day'] == 'Sun') {
                                    $bg_color = '#ffddddff';
                                } else if($i%2==0) {
                                    $bg_color = '#eee4e4ff';
                                } else {
                                    $bg_color = '#f8f5f5ff';
                                }
                            @endphp
                            <div class="col-md-2 col-lg-2 col-sm-4 col-xs-6 {{ $day['is_today'] ? 'day_cell_today' : 'day_cell' }}" 
                                 style="background-color: {{ $bg_color }}">
                                <div class="calender_date">{{ $day['date']->format('j') }}</div>
                                <div class="calender_day">{{ $day['day'] }}</div>
                                <div class="calender_events">
                                    @php $events_array = []; @endphp
                                    <div class="calender_event">
                                    @foreach($day['events'] as $event)
                                        @if(!in_array($event['membership'], $events_array))
                                            @php $events_array[] = $event['membership']; @endphp
                                            {{ $event['membership'] }} : 
                                        @endif
                                        {{ $event['start_time'] }}
                                    @endforeach
                                    </div>
                                </div>
                            </div>
                            @php $i++; @endphp
                        @endforeach
                    </div>
                </div>    
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-lg-6 col-sm-12 col-sx-12 mb-2">
            <div class="card" style="padding: 15px; height:300px;">
                <h5 class="dashboard_titles">Members with Pending Fees</h5>
                <hr>
                <div style="height: 300px; overflow:auto;" class="pending_fee_members hide-scrollbar"></div>
            </div>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-12 col-sx-12 mb-2">
            <div class="card" style="padding: 15px; height:300px;">
                <h5 class="dashboard_titles">Membership Renewal Alert</h5>
                <hr>
                <div style="height: 300px; overflow:auto;" class="membership_renewal_alert hide-scrollbar"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-lg-6 col-sm-12 col-sx-12 mb-2">
            <div class="card" style="padding: 15px;">
                <h5 class="dashboard_titles">Member Registrations</h5>
                <hr>
                <div style="height: 400px;">
                    <canvas id="registrationChart"></canvas>
                </div>
                <div style="text-align: center; margin-top: 20px;">
                    <input type="radio" class="mb-2 recent_registrations" name="recent_registrations" value="3" checked>&nbsp; last 3 months &nbsp;&nbsp;&nbsp;
                    <input type="radio" class="mb-2 recent_registrations" name="recent_registrations" value="6">&nbsp; last 6 months &nbsp;&nbsp;&nbsp;
                    <input type="radio" class="mb-2 recent_registrations" name="recent_registrations" value="12">&nbsp; last 12 months &nbsp;&nbsp;&nbsp;
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-12 col-sx-12 mb-2">
            <div class="card" style="padding: 15px;">
                <h5 class="dashboard_titles">Payout/Income Statistics for the month of {{ now()->format('F Y') }}</h5>
                <hr>
                <div style="height: 400px;">
                    <canvas id="payoutStatisticsChartCanvasId"></canvas>
                    <div style="text-align: center; margin-top: 20px;">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const allMembers = @json($allMembers ?? []);
</script>
@endsection