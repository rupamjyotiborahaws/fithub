@extends('layouts.layout')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container mx-auto py-6">
    @if(session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="m-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h1 class="mb-4">Admin Dashboard</h1>

    <div class="grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(350px,1fr));gap:1.5rem;">
        {{-- New Member Registration --}}
        <div class="card" style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:18px;">
            <h2 style="margin:0 0 12px;">New Member Registration</h2>
            <form method="POST" action="" autocomplete="off">
                @csrf
                <div class="mb-2">
                    <label>Name</label>
                    <input name="name" value="{{ old('name') }}" required class="w-100">
                </div>
                <div class="mb-2">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-100">
                </div>
                <div class="mb-2">
                    <label>Phone</label>
                    <input name="phone" value="{{ old('phone') }}" class="w-100">
                </div>
                <div class="mb-2">
                    <label>Membership Plan</label>
                    <select name="membership_plan" required class="w-100">
                        <option value="">-- Select --</option>
                        <option value="monthly" {{ old('membership_plan')=='monthly'?'selected':'' }}>Monthly</option>
                        <option value="quarterly" {{ old('membership_plan')=='quarterly'?'selected':'' }}>Quarterly</option>
                        <option value="annual" {{ old('membership_plan')=='annual'?'selected':'' }}>Annual</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label>Start Date</label>
                    <input type="date" name="start_date" value="{{ old('start_date', now()->toDateString()) }}" required class="w-100">
                </div>
                <div class="mb-2">
                    <label>Initial Fee (Optional)</label>
                    <input type="number" step="0.01" name="initial_fee" value="{{ old('initial_fee') }}" class="w-100">
                </div>
                <button type="submit" class="btn-primary w-100">Register Member</button>
            </form>
        </div>

        {{-- Fee Collection --}}
        <div class="card" style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:18px;">
            <h2 style="margin:0 0 12px;">Fee Collection</h2>
            <form method="POST" action="">
                @csrf
                <div class="mb-2">
                    <label>Member</label>
                    <select name="member_id" required class="w-100">
                        <option value="">-- Select Member --</option>
                        @foreach($members ?? [] as $m)
                            <option value="{{ $m->id }}">{{ $m->name }} ({{ $m->membership_plan }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <label>Amount</label>
                    <input type="number" step="0.01" name="amount" required class="w-100">
                </div>
                <div class="mb-2">
                    <label>Payment Method</label>
                    <select name="method" required class="w-100">
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="bank">Bank Transfer</option>
                        <option value="upi">UPI</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label>Payment Date</label>
                    <input type="date" name="paid_at" value="{{ now()->toDateString() }}" required class="w-100">
                </div>
                <div class="mb-2">
                    <label>Reference (Optional)</label>
                    <input name="reference" class="w-100">
                </div>
                <button type="submit" class="btn-primary w-100">Record Payment</button>
            </form>
        </div>

        {{-- Recent Members --}}
        <div class="card" style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:18px;">
            <h2 style="margin:0 0 12px;">Recent Members</h2>
            <div style="max-height:300px;overflow:auto;">
                <table class="table" style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th style="text-align:left;padding:6px;border-bottom:1px solid #ccc;">Name</th>
                            <th style="text-align:left;padding:6px;border-bottom:1px solid #ccc;">Plan</th>
                            <th style="text-align:left;padding:6px;border-bottom:1px solid #ccc;">Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentMembers ?? [] as $rm)
                            <tr>
                                <td style="padding:6px;border-bottom:1px solid #eee;">{{ $rm->name }}</td>
                                <td style="padding:6px;border-bottom:1px solid #eee;text-transform:capitalize;">{{ $rm->membership_plan }}</td>
                                <td style="padding:6px;border-bottom:1px solid #eee;">{{ $rm->start_date->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" style="padding:6px;">No recent members.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Payments --}}
        <div class="card" style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:18px;">
            <h2 style="margin:0 0 12px;">Recent Payments</h2>
            <div style="max-height:300px;overflow:auto;">
                <table class="table" style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th style="text-align:left;padding:6px;border-bottom:1px solid #ccc;">Member</th>
                            <th style="text-align:left;padding:6px;border-bottom:1px solid #ccc;">Amount</th>
                            <th style="text-align:left;padding:6px;border-bottom:1px solid #ccc;">Method</th>
                            <th style="text-align:left;padding:6px;border-bottom:1px solid #ccc;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPayments ?? [] as $p)
                            <tr>
                                <td style="padding:6px;border-bottom:1px solid #eee;">{{ $p->member->name ?? 'N/A' }}</td>
                                <td style="padding:6px;border-bottom:1px solid #eee;">{{ number_format($p->amount,2) }}</td>
                                <td style="padding:6px;border-bottom:1px solid #eee;">{{ ucfirst($p->method) }}</td>
                                <td style="padding:6px;border-bottom:1px solid #eee;">{{ $p->paid_at->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" style="padding:6px;">No payments recorded.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Summary --}}
        <div class="card" style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:18px;">
            <h2 style="margin:0 0 12px;">Summary</h2>
            <ul style="list-style:none;padding:0;margin:0;">
                <li style="padding:4px 0;">Total Members: <strong>{{ $stats['total_members'] ?? 0 }}</strong></li>
                <li style="padding:4px 0;">Active This Month: <strong>{{ $stats['active_this_month'] ?? 0 }}</strong></li>
                <li style="padding:4px 0;">Fees Collected (Month): <strong>{{ number_format($stats['fees_month'] ?? 0,2) }}</strong></li>
                <li style="padding:4px 0;">Outstanding (Estimate): <strong>{{ number_format($stats['outstanding'] ?? 0,2) }}</strong></li>
            </ul>
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