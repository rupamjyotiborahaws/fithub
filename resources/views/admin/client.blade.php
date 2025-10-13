@extends('layouts.layout')

@section('title', 'Client Settings')

@section('content')
<div class="container-fluid mx-auto py-6">
    <div class="row">
        <div class="col-md-9">
            {{-- Client Settings --}}
            <div class="card" style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:18px;">
                <h5 style="margin:0 0 12px;" class="font-semibold mb-4">Client Settings</h5>
                <form method="POST" action="" autocomplete="on">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label>Name</label>
                                <input name="name" value="{{ $client['name'] }}" required class="w-100">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label>Email</label>
                                <input type="email" name="email" value="{{ $client['email'] }}" required class="w-100">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-2">
                                <label>Address</label>
                                <input type="text" name="address" value="{{ $client['address'] }}" class="w-100">
                            </div>    
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label>Phone No.</label>
                                <input type="text" name="phone_no" value="{{ $client['phone_no'] }}" class="w-100">
                            </div>   
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label>Date of Incorporation</label>
                                <input type="date" name="date_of_icorporation" value="{{ date('Y-m-d', strtotime($client['date_of_icorporation'])) }}" required class="w-100">
                            </div>    
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label>Contact Person</label>
                                <input type="text" name="contact_person" value="{{ $client['contact_person'] }}" class="w-100">
                            </div>   
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label>GST No.</label>
                                <input type="text" name="gst_id" value="{{ $client['gst_id'] }}" class="w-100">
                            </div>   
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label>Business</label>
                                <input type="text" name="business_type" value="{{ $client['business_type'] }}" class="w-100" placeholder="Gym">
                            </div>   
                        </div>
                    </div>
                    <button type="submit" class="w-100 register_client">Add/Update Client</button>
                </form>
            </div>
        </div>
        <div class="col-md-3">
            
        </div>
    </div>
</div>

<style>
    .w-100 { width:100%; }
    .mb-2 { margin-bottom:.75rem; }
    .mb-4 { margin-bottom:1.25rem; }
    .btn-primary {
        background:#2563eb;
        color:#fff;
        border:none;
        padding:10px 14px;
        cursor:pointer;
        border-radius:4px;
        font-weight:600;
    }
    .btn-primary:hover { background:#1d4ed8; }
    input, select {
        padding:8px;
        border:1px solid #ccc;
        border-radius:4px;
        background:#fff;
    }
    input:focus, select:focus {
        outline:none;
        border-color:#2563eb;
        box-shadow:0 0 0 1px #2563eb33;
    }
    .alert { padding:10px 14px; border-radius:4px; }
    .alert-success { background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0; }
    .alert-danger { background:#fef2f2;color:#991b1b;border:1px solid #fecaca; }
</style>
@endsection