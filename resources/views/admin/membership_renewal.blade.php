@extends('layouts.layout')

@section('title', 'Membership Renewal')

@section('content')

<div class="container-fluid mx-auto py-6" style="min-height: 75vh;">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card" style="padding: 10px; margin-bottom:10px;">
                <h5 style="margin:0 0 12px;" class="font-semibold mb-4">Membership Renewal</h5>
                <input type="text" id="memberSearchMembershipRenewal" placeholder="Search by Member ID, Name, or Phone No">
                <div class="matched_members_renewal hide-scrollbar"></div>
            </div>
            <div class="card membership_renewal_details">
                <table style="width:100%; border-collapse:collapse; margin-top:15px; overflow:scroll">
                    <thead>
                        <tr>
                            <th class="membership_transfer_head">ID</th>
                            <th class="membership_transfer_head">Name</th>
                            <th class="membership_transfer_head">Membership</th>
                            <th class="membership_transfer_head">Membership End Date</th>
                            <th class="membership_transfer_head">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($member_details as $member)
                            <tr>
                                <td class="membership_transfer_data">{{ $member['member_id'] }}</td>
                                <td class="membership_transfer_data">{{ $member['member_name'] }}</td>
                                <td class="membership_transfer_data">{{ $member['membership_name'] }}</td>
                                <td class="membership_transfer_data">{{ date('d-m-Y', strtotime($member['membership_end_date'])) }}</td>
                                <td class="membership_transfer_data">
                                    @if($member['renew_action'] == 0) 
                                        No Action 
                                    @else 
                                        <button class="renew_membership" data-member-id="{{ $member['member_id'] }}" data-membership-id="{{ $member['membership_id'] }}">Renew</button> 
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    const members_renewal = @json($member_details ?? []);
    console.log('All Members:', members_renewal);
</script>
@endsection