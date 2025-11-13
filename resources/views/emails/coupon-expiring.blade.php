<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coupon Expiring Soon</title>
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
        .clock-icon {
            font-size: 64px;
            margin-bottom: 10px;
        }
        .header h1 {
            color: #dc2626;
            margin: 0 0 10px 0;
            font-size: 28px;
        }
        .coupon-alert-box {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border: 3px dashed #dc2626;
            color: #7f1d1d;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            margin: 30px 0;
        }
        .coupon-code-display {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .code-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        .code-value {
            font-size: 36px;
            font-weight: bold;
            font-family: 'Courier New', monospace;
            color: #dc2626;
            letter-spacing: 2px;
            margin: 10px 0;
        }
        .coupon-name {
            font-size: 16px;
            color: #4b5563;
            margin-top: 5px;
        }
        .expiry-countdown {
            background-color: #fef2f2;
            border: 2px solid #f87171;
            border-radius: 8px;
            padding: 25px;
            text-align: center;
            margin: 20px 0;
        }
        .countdown-value {
            font-size: 56px;
            font-weight: bold;
            color: #dc2626;
            margin-bottom: 5px;
        }
        .countdown-label {
            color: #991b1b;
            font-size: 14px;
            text-transform: uppercase;
        }
        .discount-highlight {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 25px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        .discount-label {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 5px;
        }
        .discount-value {
            font-size: 48px;
            font-weight: bold;
            margin: 10px 0;
        }
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 25px 0;
        }
        .detail-card {
            background-color: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        .detail-label {
            color: #6b7280;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .detail-value {
            color: #111827;
            font-size: 18px;
            font-weight: 600;
        }
        .cta-button {
            display: inline-block;
            background-color: #dc2626;
            color: white;
            padding: 16px 40px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            text-align: center;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }
        .urgency-box {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .urgency-title {
            color: #92400e;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 10px;
        }
        .urgency-text {
            color: #78350f;
            font-size: 14px;
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
            .code-value {
                font-size: 24px;
            }
            .countdown-value {
                font-size: 42px;
            }
            .discount-value {
                font-size: 36px;
            }
            .details-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="clock-icon">⏰</div>
            <h1>Your Coupon is Expiring Soon!</h1>
            <p>Don't miss out on your discount, {{ $userName }}!</p>
        </div>

        <div class="coupon-alert-box">
            <div style="font-size: 18px; font-weight: 600; margin-bottom: 10px;">
                ⚠️ Last Chance to Save!
            </div>
            <div style="font-size: 14px;">
                This exclusive coupon will expire soon. Use it before it's too late!
            </div>
        </div>

        <div class="coupon-code-display">
            <div class="code-label">Your Coupon Code</div>
            <div class="code-value">{{ $couponCode }}</div>
            <div class="coupon-name">{{ $couponName }}</div>
        </div>

        <div class="expiry-countdown">
            <div class="countdown-value">{{ $daysUntilExpiry }}</div>
            <div class="countdown-label">Days Until Expiration</div>
            <div style="margin-top: 10px; font-size: 14px; color: #991b1b;">
                Expires: {{ $expiryDate->format('F d, Y g:i A') }}
            </div>
        </div>

        <div class="discount-highlight">
            <div class="discount-label">Save Up To</div>
            <div class="discount-value">
                @if($discountType === 'percentage')
                    {{ $discountValue }}% OFF
                @else
                    ₱{{ number_format($discountValue, 0) }} OFF
                @endif
            </div>
            <div style="font-size: 14px; opacity: 0.95;">
                On your next service request
            </div>
        </div>

        <div class="details-grid">
            @if($minPurchase > 0)
            <div class="detail-card">
                <div class="detail-label">Minimum Purchase</div>
                <div class="detail-value">₱{{ number_format($minPurchase, 0) }}</div>
            </div>
            @endif
            
            @if($usesRemaining !== null)
            <div class="detail-card">
                <div class="detail-label">Uses Remaining</div>
                <div class="detail-value">{{ $usesRemaining }} {{ $usesRemaining === 1 ? 'use' : 'uses' }}</div>
            </div>
            @endif
        </div>

        <div style="text-align: center;">
            <a href="{{ route('client.requests.index') }}" class="cta-button">
                Use Coupon Now
            </a>
        </div>

        <div class="urgency-box">
            <div class="urgency-title">🔥 Why Act Now?</div>
            <div class="urgency-text">
                <p style="margin: 8px 0;">
                    ✓ This coupon cannot be extended or recovered after expiration
                </p>
                <p style="margin: 8px 0;">
                    ✓ Apply it to any active or new service request
                </p>
                <p style="margin: 8px 0;">
                    ✓ Combine with your loyalty points for extra savings
                </p>
                <p style="margin: 8px 0;">
                    ✓ Valid on all our services
                </p>
            </div>
        </div>

        <div style="background-color: #f0f9ff; border: 2px solid #3b82f6; border-radius: 8px; padding: 20px; margin: 25px 0; text-align: center;">
            <div style="color: #1e40af; font-weight: 600; margin-bottom: 10px;">
                💡 Pro Tip
            </div>
            <div style="color: #1e3a8a; font-size: 14px;">
                Apply this coupon during checkout and watch your total decrease instantly. 
                The earlier you use it, the more time you have to enjoy the savings!
            </div>
        </div>

        <div class="footer">
            <p><strong>Need help using your coupon?</strong></p>
            <p style="margin: 15px 0; font-size: 13px; color: #6b7280;">
                Visit your coupons page to view all available offers and learn how to apply them.
            </p>
            <p style="margin-top: 20px;">
                <a href="{{ route('client.coupons.index') }}" style="color: #dc2626; text-decoration: none;">View All Coupons</a> |
                <a href="{{ route('client.requests.create') }}" style="color: #dc2626; text-decoration: none;">Submit New Request</a>
            </p>
            <p style="margin-top: 20px; font-size: 12px; color: #9ca3af;">
                This is an automated reminder. Please do not reply to this message.
            </p>
        </div>
    </div>
</body>
</html>
