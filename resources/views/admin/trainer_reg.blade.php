@extends('layouts.layout')

@section('title', 'Trainer Registration')

@section('content')
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
<div class="container-fluid mx-auto py-6">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12 col-sx-12 mb-4">
            {{-- Trainer Registration --}}
            <div class="card" style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:18px;">
                <h5 style="margin:0 0 12px;" class="font-semibold mb-4">Trainer Registration</h5>
                <form method="POST" action="" autocomplete="on">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <div class="mb-2">
                                <label>Name</label>
                                <input name="name" value="{{ old('name') }}" required class="w-100">
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <div class="mb-2">
                                <label>Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" required class="w-100">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                            <div class="mb-2">
                                <label>Address</label>
                                <input type="text" name="address" value="{{ old('address') }}" class="w-100">
                            </div>    
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                            <div class="mb-2">
                                <label>Date of Birth</label>
                                <input type="date" name="dob" value="{{ old('dob') }}" class="w-100">
                            </div>   
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                            <div class="mb-2">
                                <label>Phone</label>
                                <input name="phone" value="{{ old('phone') }}" class="w-100">
                            </div>    
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                            <div class="mb-2">
                                <label>Gender</label>
                                <select name="gender" class="w-100">
                                    <option value="">-- Select --</option>
                                    <option value="m" {{ old('gender')=='m'?'selected':'' }}>Male</option>
                                    <option value="f" {{ old('gender')=='f'?'selected':'' }}>Female</option>
                                    <option value="o" {{ old('gender')=='o'?'selected':'' }}>Other</option>
                                </select>
                            </div>       
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <div class="mb-2">
                                <label>Height</label>
                                <input type="text" name="height" value="{{ old('height') }}" class="w-100">
                            </div>   
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <div class="mb-2">
                                <label>Weight</label>
                                <input type="text" name="weight" value="{{ old('weight') }}" class="w-100">
                            </div>   
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <div class="mb-2">
                                <label>Specialization</label>
                                <input type="text" name="specialization" value="{{ old('specialization') }}" class="w-100" placeholder="e.g. Yoga, Pilates">
                            </div>   
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
                            <div class="mb-2">
                                <label>Certificate No. (You can enter multiple numbers separated by commas)</label>
                                <input type="text" name="certificate_no" value="{{ old('certificate_no') }}" class="w-100" placeholder="ABCD1234, XYZ5678">
                            </div>   
                        </div>
                    </div>
                    <button type="submit" class="w-100 register_trainer">Register Trainer</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    const allMembers = @json($allMembers ?? []);
    console.log('All Members:', allMembers);
</script>
@endsection