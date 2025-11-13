<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Referral Reward Unlocked</title>
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
        .header h1 {
            color: #10b981;
            margin: 0 0 10px 0;
            font-size: 28px;
        }
        .celebration-icon {
            font-size: 64px;
            margin-bottom: 10px;
        }
        .reward-earned-box {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 40px;
            border-radius: 12px;
            text-align: center;
            margin: 30px 0;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        .reward-label {
            font-size: 14px;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .reward-points {
            font-size: 56px;
            font-weight: bold;
            margin: 10px 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        .reward-subtitle {
            font-size: 16px;
            opacity: 0.95;
            margin-top: 10px;
        }
        .success-banner {
            background-color: #ecfdf5;
            border: 2px solid #10b981;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .success-title {
            color: #065f46;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .success-text {
            color: #047857;
            font-size: 14px;
        }
        .coupon-box {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin: 20px 0;
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
        }
        .coupon-header {
            font-size: 14px;
            opacity: 0.9;
            text-transform: uppercase;
            margin-bottom: 15px;
        }
        .coupon-code {
            background-color: white;
            color: #7c3aed;
            font-size: 32px;
            font-weight: bold;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            letter-spacing: 2px;
        }
        .coupon-details {
            font-size: 14px;
            opacity: 0.95;
            margin-top: 15px;
        }
        .coupon-detail-item {
            margin: 5px 0;
        }
        .rewards-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 30px 0;
        }
        .reward-card {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            border: 2px solid #e5e7eb;
        }
        .reward-card-label {
            color: #6b7280;
            font-size: 13px;
            font-weight: 500;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .reward-card-value {
            color: #111827;
            font-size: 24px;
            font-weight: bold;
        }
        .referrer-box {
            background-color: #f0f9ff;
            border: 2px solid #3b82f6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .referrer-name {
            color: #1e40af;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .referrer-subtitle {
            color: #6b7280;
            font-size: 14px;
        }
        .cta-button {
            display: inline-block;
            background-color: #10b981;
            color: white;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 10px;
            text-align: center;
        }
        .cta-secondary {
            background-color: #3b82f6;
        }
        .tip-box {
            background-color: #fef3c7;
            border-left: 4px solid #fbbf24;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
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
            .rewards-grid {
                grid-template-columns: 1fr;
            }
            .reward-points {
                font-size: 42px;
            }
            .coupon-code {
                font-size: 24px;
            }
            .cta-button {
                display: block;
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="celebration-icon">🎁</div>
            <h1>Reward Unlocked!</h1>
            <p>Congratulations, {{ $referrer->fullName }}</p>
        </div>

        <div class="success-banner">
            <div class="success-title">✅ Referral Completed Successfully!</div>
            <div class="success-text">
                {{ $referred->fullName }} just completed their first payment
            </div>
        </div>

        <div class="reward-earned-box">
            <div class="reward-label">Points Credited</div>
            <div class="reward-points">+{{ number_format($pointsEarned) }}</div>
            <div class="reward-subtitle">Added to your loyalty account</div>
        </div>

        @if($coupon)
        <div class="coupon-box">
            <div class="coupon-header">🎟️ Your Exclusive Discount Coupon</div>
            <div class="coupon-code">{{ $coupon->code }}</div>
            <div class="coupon-details">
                <div class="coupon-detail-item">💸 <strong>{{ $coupon->discount_value }}% OFF</strong> your next service</div>
                <div class="coupon-detail-item">📅 Valid until {{ $coupon->valid_until->format('F d, Y') }}</div>
                <div class="coupon-detail-item">💰 Min. purchase: ₱{{ number_format($coupon->min_purchase_amount) }}</div>
            </div>
        </div>
        @endif

        <div class="referrer-box">
            <div class="referrer-name">{{ $referred->fullName }}</div>
            <div class="referrer-subtitle">
                Your referral is now an active client! 🎉
            </div>
        </div>

        <div class="rewards-grid">
            <div class="reward-card">
                <div class="reward-card-label">Points Value</div>
                <div class="reward-card-value" style="color: #10b981;">₱{{ number_format($pointsEarned) }}</div>
                <div style="color: #6b7280; font-size: 12px; margin-top: 5px;">1 point = ₱1</div>
            </div>
            
            <div class="reward-card">
                <div class="reward-card-label">Coupon Savings</div>
                <div class="reward-card-value" style="color: #8b5cf6;">
                    @if($coupon)
                        {{ $coupon->discount_value }}%
                    @else
                        N/A
                    @endif
                </div>
                <div style="color: #6b7280; font-size: 12px; margin-top: 5px;">discount off</div>
            </div>
        </div>

        <div style="text-align: center;">
            <a href="{{ route('client.loyalty.dashboard') }}" class="cta-button">
                View Points Balance
            </a>
            @if($coupon)
            <a href="{{ route('client.coupons.index') }}" class="cta-button cta-secondary">
                View My Coupons
            </a>
            @endif
        </div>

        <div class="tip-box">
            <strong>💡 Keep Referring & Earning:</strong> Share your referral code with more friends! 
            Each successful referral earns you {{ number_format(config('referral.rewards.referrer.completion_points', 1000)) }} points 
            and a {{ config('referral.rewards.referrer.coupon_discount', 20) }}% discount coupon.
        </div>

        <div class="footer">
            <p><strong>How to Use Your Rewards:</strong></p>
            <p style="margin: 10px 0; font-size: 13px; color: #6b7280;">
                • Use points as payment (100 points = ₱100 off)<br>
                • Apply your coupon code at checkout<br>
                • Rewards can be stacked for maximum savings!
            </p>
            <p style="margin-top: 20px;">
                <a href="{{ route('client.referrals.dashboard') }}" style="color: #10b981; text-decoration: none;">View Referral Dashboard</a> • 
                <a href="{{ route('client.referrals.share') }}" style="color: #3b82f6; text-decoration: none;">Share Your Code</a>
            </p>
            <p style="margin-top: 20px; font-size: 12px; color: #9ca3af;">
                This is an automated email. Please do not reply to this message.
            </p>
        </div>
    </div>
</body>
</html>
