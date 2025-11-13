<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Referral Signup</title>
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
            color: #3b82f6;
            margin: 0 0 10px 0;
            font-size: 28px;
        }
        .celebration-icon {
            font-size: 64px;
            margin-bottom: 10px;
        }
        .referral-info-box {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            margin: 30px 0;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        .referral-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .referral-email {
            font-size: 16px;
            opacity: 0.95;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 30px 0;
        }
        .info-card {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            border: 2px solid #e5e7eb;
        }
        .info-label {
            color: #6b7280;
            font-size: 13px;
            font-weight: 500;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .info-value {
            color: #111827;
            font-size: 24px;
            font-weight: bold;
        }
        .reward-box {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: white;
            padding: 25px;
            border-radius: 12px;
            margin: 20px 0;
            text-align: center;
        }
        .reward-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .reward-points {
            font-size: 48px;
            font-weight: bold;
            margin: 10px 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        .reward-note {
            font-size: 14px;
            opacity: 0.95;
        }
        .status-badge {
            background-color: #fef3c7;
            color: #92400e;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            display: inline-block;
            margin: 10px 0;
        }
        .steps-box {
            background-color: #f0f9ff;
            border: 2px solid #3b82f6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .step-item {
            display: flex;
            align-items: flex-start;
            margin: 15px 0;
        }
        .step-number {
            background-color: #3b82f6;
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            margin-right: 15px;
            flex-shrink: 0;
        }
        .step-text {
            flex: 1;
            font-size: 14px;
            padding-top: 2px;
        }
        .cta-button {
            display: inline-block;
            background-color: #3b82f6;
            color: white;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }
        .tip-box {
            background-color: #ecfdf5;
            border-left: 4px solid #10b981;
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
            .info-grid {
                grid-template-columns: 1fr;
            }
            .reward-points {
                font-size: 36px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="celebration-icon">🎉</div>
            <h1>Great News, {{ $referrer->fullName }}!</h1>
            <p>Your referral just signed up</p>
        </div>

        <div class="referral-info-box">
            <div class="referral-name">{{ $referred->fullName }}</div>
            <div class="referral-email">{{ $referred->email }}</div>
            <div class="status-badge">⏳ Pending First Payment</div>
        </div>

        <div class="info-grid">
            <div class="info-card">
                <div class="info-label">Your Referral Code</div>
                <div class="info-value" style="color: #3b82f6;">{{ $referralCode->code }}</div>
            </div>
            
            <div class="info-card">
                <div class="info-label">Total Referrals</div>
                <div class="info-value" style="color: #10b981;">{{ $referralCode->total_referrals }}</div>
            </div>
        </div>

        <div class="reward-box">
            <div class="reward-title">💰 Pending Reward</div>
            <div class="reward-points">{{ number_format($pendingPoints) }} pts</div>
            <div class="reward-note">+ 20% Discount Coupon</div>
        </div>

        <div class="steps-box">
            <h3 style="color: #1e40af; margin-top: 0;">What Happens Next?</h3>
            
            <div class="step-item">
                <div class="step-number">1</div>
                <div class="step-text">
                    <strong>{{ $referred->fullName }}</strong> explores our services and submits a request
                </div>
            </div>
            
            <div class="step-item">
                <div class="step-number">2</div>
                <div class="step-text">
                    They complete their <strong>first payment</strong> for the service
                </div>
            </div>
            
            <div class="step-item">
                <div class="step-number">3</div>
                <div class="step-text">
                    You receive <strong>{{ number_format($pendingPoints) }} points</strong> and a <strong>20% discount coupon</strong> instantly!
                </div>
            </div>
        </div>

        <div style="text-align: center;">
            <a href="{{ route('client.referrals.dashboard') }}" class="cta-button">
                View Referral Dashboard
            </a>
        </div>

        <div class="tip-box">
            <strong>💡 Earn More Rewards:</strong> Share your referral code <strong>{{ $referralCode->code }}</strong> with friends! 
            Each successful referral earns you {{ number_format($pendingPoints) }} points and a discount coupon.
        </div>

        <div class="footer">
            <p><strong>Your Referral Stats:</strong></p>
            <p style="margin: 10px 0; font-size: 13px; color: #6b7280;">
                Total Referrals: {{ $referralCode->total_referrals }} • 
                Successful: {{ $referralCode->successful_referrals }} • 
                Pending: {{ $referralCode->pending_referrals }}<br>
                Lifetime Earnings: {{ number_format($referralCode->lifetime_earnings_points) }} points
            </p>
            <p style="margin-top: 20px;">
                <a href="{{ route('client.referrals.share') }}" style="color: #3b82f6; text-decoration: none;">Share Your Referral Link</a>
            </p>
            <p style="margin-top: 20px; font-size: 12px; color: #9ca3af;">
                This is an automated email. Please do not reply to this message.
            </p>
        </div>
    </div>
</body>
</html>
