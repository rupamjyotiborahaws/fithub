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
        
    </div>
</div>
@endsection