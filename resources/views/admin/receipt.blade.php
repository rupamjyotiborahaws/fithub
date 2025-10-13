<!DOCTYPE html>
<html lang="en">
{{-- Security Meta Tags --}}
<meta http-equiv="X-Content-Type-Options" content="nosniff">
<meta http-equiv="X-Frame-Options" content="DENY">
<meta http-equiv="X-XSS-Protection" content="1; mode=block">
<meta http-equiv="Referrer-Policy" content="strict-origin-when-cross-origin">
<meta http-equiv="Permissions-Policy" content="camera=(), microphone=(), geolocation=(), payment=()">
<meta name="robots" content="noindex, nofollow" />

{{-- Cache Control Meta Tags --}}
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Payment Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .receipt-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .receipt-details {
            margin-bottom: 30px;
        }
        .receipt-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .receipt-row:last-child {
            border-bottom: none;
        }
        .receipt-label {
            font-weight: bold;
            color: #333;
        }
        .receipt-value {
            color: #666;
        }
        .amount-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .amount-section .amount {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
        .print-button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 20px 0;
        }
        .print-button:hover {
            background-color: #0056b3;
        }
        @media print {
            body {
                background-color: white;
            }
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="header">
            <h1>{{ $client_settings->name ?? 'FitHub' }}</h1>
            <p>{{ $client_settings->address ?? '' }}</p>
            @if($client_settings && $client_settings->phone_no)
            <p>Phone: {{ $client_settings->phone_no }}</p>
            @endif
            @if($client_settings && $client_settings->email)
            <p>Email: {{ $client_settings->email }}</p>
            @endif
            <p><strong>Fee Payment Receipt</strong></p>
        </div>

        <div class="receipt-details">
            <div class="receipt-row">
                <span class="receipt-label">Member Name:</span>
                <span class="receipt-value">{{ $receiptData['member_name'] }}</span>
            </div>
            <div class="receipt-row">
                <span class="receipt-label">Member ID:</span>
                <span class="receipt-value">{{ $receiptData['member_id'] }}</span>
            </div>
            @if($receiptData['membership_plan'])
            <div class="receipt-row">
                <span class="receipt-label">Membership Plan:</span>
                <span class="receipt-value">{{ ucfirst($receiptData['membership_plan']) }}</span>
            </div>
            @endif
            <div class="receipt-row">
                <span class="receipt-label">Payment Date:</span>
                <span class="receipt-value">{{ \Carbon\Carbon::parse($receiptData['payment_date'])->format('d M Y') }}</span>
            </div>
            <div class="receipt-row">
                <span class="receipt-label">For Month:</span>
                <span class="receipt-value">{{ $receiptData['for_month'] }}</span>
            </div>
            @if($receiptData['payment_method'])
            <div class="receipt-row">
                <span class="receipt-label">Payment Method:</span>
                <span class="receipt-value">{{ ucfirst($receiptData['payment_method']) }}</span>
            </div>
            @endif
            @if($receiptData['transaction_id'])
            <div class="receipt-row">
                <span class="receipt-label">Transaction ID:</span>
                <span class="receipt-value">{{ $receiptData['transaction_id'] }}</span>
            </div>
            @endif
        </div>

        <div class="amount-section">
            <div class="receipt-row">
                <span class="receipt-label">Amount Paid:</span>
                <span class="receipt-value amount">₹{{ number_format($receiptData['amount'], 2) }}</span>
            </div>
        </div>

        <div class="footer">
            <p>Thank you for your payment!</p>
            <p>This is a computer generated receipt.</p>
            <p>Generated on: {{ \Carbon\Carbon::now()->format('d M Y H:i:s') }}</p>
        </div>

        <div style="text-align: center;">
            <button class="print-button" onclick="window.print()">Print Receipt</button>
            <button class="print-button" onclick="window.close()" style="background-color: #6c757d;">Close</button>
        </div>
    </div>
</body>
</html>