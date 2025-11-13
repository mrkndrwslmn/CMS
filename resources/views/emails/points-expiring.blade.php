<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Points Expiring Soon</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .warning-icon {
            font-size: 64px;
            margin-bottom: 10px;
        }
        .header h1 {
            color: #ea580c;
            margin: 0 0 10px 0;
            font-size: 28px;
        }
        .alert-box {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 3px solid #f59e0b;
            color: #78350f;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            margin: 30px 0;
        }
        .expiring-amount {
            font-size: 56px;
            font-weight: bold;
            color: #ea580c;
            margin: 15px 0;
        }
        .expiry-date {
            font-size: 20px;
            font-weight: 600;
            color: #92400e;
            margin-top: 10px;
        }
        .countdown {
            background-color: #fff7ed;
            border: 2px solid #fb923c;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .countdown-value {
            font-size: 48px;
            font-weight: bold;
            color: #ea580c;
        }
        .countdown-label {
            color: #9a3412;
            font-size: 14px;
            text-transform: uppercase;
            margin-top: 5px;
        }
        .balance-info {
            background-color: #f0f9ff;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .balance-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
        }
        .balance-label {
            color: #1e40af;
            font-weight: 500;
        }
        .balance-value {
            font-weight: 600;
            color: #1e3a8a;
        }
        .expiring-batches {
            margin: 25px 0;
        }
        .batch-item {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 10px 0;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .batch-points {
            font-size: 24px;
            font-weight: bold;
            color: #92400e;
        }
        .batch-date {
            color: #78350f;
            font-size: 14px;
        }
        .cta-button {
            display: inline-block;
            background-color: #ea580c;
            color: white;
            padding: 16px 40px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            text-align: center;
            box-shadow: 0 4px 12px rgba(234, 88, 12, 0.3);
        }
        .suggestions-box {
            background-color: #ecfdf5;
            border: 2px solid #10b981;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        .suggestions-title {
            color: #065f46;
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .suggestion-item {
            padding: 10px 0;
            display: flex;
            align-items: flex-start;
        }
        .suggestion-icon {
            color: #10b981;
            margin-right: 10px;
            font-size: 18px;
            flex-shrink: 0;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        @media only screen and (max-width: 600px) {
            body {
                padding: 10px;
            }
            .container {
                padding: 20px;
            }
            .expiring-amount {
                font-size: 42px;
            }
            .countdown-value {
                font-size: 36px;
            }
            .batch-item {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="warning-icon">⏰</div>
            <h1>Reminder: Points Expiring Soon!</h1>
            <p>Don't lose your hard-earned loyalty points, {{ $userName }}!</p>
        </div>

        <div class="alert-box">
            <div style="font-size: 14px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px;">
                Expiring Soon
            </div>
            <div class="expiring-amount">{{ number_format($totalExpiringPoints) }}</div>
            <div style="font-size: 16px; margin-top: 5px;">Loyalty Points</div>
            <div style="font-size: 14px; margin-top: 10px; opacity: 0.9;">
                Worth ₱{{ number_format($pointsValue) }}
            </div>
            <div class="expiry-date">
                Expires on {{ $expiryDate->format('F d, Y') }}
            </div>
        </div>

        <div class="countdown">
            <div class="countdown-value">{{ $daysUntilExpiry }}</div>
            <div class="countdown-label">Days Remaining</div>
        </div>

        <div class="balance-info">
            <div class="balance-row">
                <span class="balance-label">Current Total Balance:</span>
                <span class="balance-value">{{ number_format($currentBalance) }} points</span>
            </div>
            <div class="balance-row">
                <span class="balance-label">Points Expiring:</span>
                <span class="balance-value" style="color: #ea580c;">{{ number_format($totalExpiringPoints) }} points</span>
            </div>
            <div class="balance-row" style="border-top: 1px solid #bfdbfe; padding-top: 10px; margin-top: 10px;">
                <span class="balance-label" style="font-weight: 600;">After Expiry:</span>
                <span class="balance-value" style="font-weight: 700;">{{ number_format($currentBalance - $totalExpiringPoints) }} points</span>
            </div>
        </div>

        @if($expiringBatches->count() > 1)
        <div class="expiring-batches">
            <h3 style="color: #111827; margin-bottom: 15px;">Expiring Points Breakdown:</h3>
            @foreach($expiringBatches as $date => $batch)
            <div class="batch-item">
                <div>
                    <div class="batch-points">{{ number_format($batch->sum('points')) }} pts</div>
                </div>
                <div class="batch-date">
                    Expires: {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <div class="suggestions-box">
            <div class="suggestions-title">💡 Ways to Use Your Points Before They Expire:</div>
            <div class="suggestion-item">
                <span class="suggestion-icon">✓</span>
                <span>Redeem points for instant discounts on active service requests</span>
            </div>
            <div class="suggestion-item">
                <span class="suggestion-icon">✓</span>
                <span>Apply points to upcoming payments (minimum 100 points)</span>
            </div>
            <div class="suggestion-item">
                <span class="suggestion-icon">✓</span>
                <span>Convert to discount coupons for future use</span>
            </div>
            <div class="suggestion-item">
                <span class="suggestion-icon">✓</span>
                <span>Save up to 50% of your service costs with points</span>
            </div>
        </div>

        <div style="text-align: center;">
            <a href="{{ route('client.loyalty.dashboard') }}" class="cta-button">
                Redeem Points Now
            </a>
        </div>

        <div style="background-color: #fef2f2; border-left: 4px solid #ef4444; padding: 15px; border-radius: 4px; margin: 20px 0; font-size: 14px;">
            <strong style="color: #991b1b;">⚠️ Important:</strong>
            <span style="color: #7f1d1d;"> Once points expire, they cannot be recovered. Make sure to use them before {{ $expiryDate->format('F d, Y') }}!</span>
        </div>

        <div class="footer">
            <p><strong>Have questions about your points?</strong></p>
            <p style="margin: 15px 0; font-size: 13px; color: #6b7280;">
                Visit your loyalty dashboard to view all your points, transactions, and redemption options.
            </p>
            <p style="margin-top: 20px;">
                <a href="{{ route('client.loyalty.transactions') }}" style="color: #ea580c; text-decoration: none;">View Transaction History</a> |
                <a href="{{ route('client.loyalty.dashboard') }}" style="color: #ea580c; text-decoration: none;">Loyalty Dashboard</a>
            </p>
            <p style="margin-top: 20px; font-size: 12px; color: #9ca3af;">
                This is an automated reminder. Please do not reply to this message.
            </p>
        </div>
    </div>
</body>
</html>
