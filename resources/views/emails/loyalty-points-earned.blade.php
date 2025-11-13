<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loyalty Points Earned</title>
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
        .points-earned-box {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 40px;
            border-radius: 12px;
            text-align: center;
            margin: 30px 0;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        .points-label {
            font-size: 14px;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .points-value {
            font-size: 56px;
            font-weight: bold;
            margin: 10px 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        .points-reason {
            font-size: 16px;
            opacity: 0.95;
            margin-top: 10px;
        }
        .balance-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 30px 0;
        }
        .balance-card {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            border: 2px solid #e5e7eb;
        }
        .balance-label {
            color: #6b7280;
            font-size: 13px;
            font-weight: 500;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .balance-value {
            color: #111827;
            font-size: 32px;
            font-weight: bold;
        }
        .tier-box {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: white;
            padding: 25px;
            border-radius: 12px;
            margin: 20px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .tier-info {
            flex: 1;
        }
        .tier-badge {
            font-size: 48px;
        }
        .tier-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .tier-benefit {
            font-size: 14px;
            opacity: 0.95;
        }
        .progress-box {
            background-color: #f0f9ff;
            border: 2px solid #3b82f6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .progress-label {
            color: #1e40af;
            font-weight: 600;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .progress-bar-container {
            background-color: #dbeafe;
            height: 12px;
            border-radius: 6px;
            overflow: hidden;
            margin: 10px 0;
        }
        .progress-bar {
            background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
            height: 100%;
            border-radius: 6px;
            transition: width 0.3s ease;
        }
        .progress-text {
            color: #6b7280;
            font-size: 13px;
            text-align: center;
            margin-top: 8px;
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
            .balance-grid {
                grid-template-columns: 1fr;
            }
            .points-value {
                font-size: 42px;
            }
            .tier-box {
                flex-direction: column;
                text-align: center;
            }
            .tier-badge {
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="celebration-icon">🎉</div>
            <h1>Congratulations, {{ $userName }}!</h1>
            <p>You've just earned loyalty points</p>
        </div>

        <div class="points-earned-box">
            <div class="points-label">Points Earned</div>
            <div class="points-value">+{{ number_format($pointsEarned) }}</div>
            <div class="points-reason">{{ $reason }}</div>
        </div>

        <div class="balance-grid">
            <div class="balance-card">
                <div class="balance-label">New Balance</div>
                <div class="balance-value">{{ number_format($newBalance) }}</div>
                <div style="color: #6b7280; font-size: 12px; margin-top: 5px;">points</div>
            </div>
            
            <div class="balance-card">
                <div class="balance-label">Points Value</div>
                <div class="balance-value" style="color: #10b981;">₱{{ number_format($newBalance) }}</div>
                <div style="color: #6b7280; font-size: 12px; margin-top: 5px;">1 point = ₱1</div>
            </div>
        </div>

        <div class="tier-box">
            <div class="tier-info">
                <div class="tier-name">{{ $currentTier }} Tier</div>
                <div class="tier-benefit">
                    @if($tierDiscount > 0)
                        {{ $tierDiscount }}% automatic discount on all services
                    @else
                        Standard benefits
                    @endif
                </div>
            </div>
            <div class="tier-badge">🏅</div>
        </div>

        @if($pointsToNextTier !== null && $nextTier)
        <div class="progress-box">
            <div class="progress-label">
                <span>Progress to {{ $nextTier }} Tier</span>
                <span style="font-size: 13px;">{{ number_format($pointsToNextTier) }} points to go</span>
            </div>
            <div class="progress-bar-container">
                <div class="progress-bar" style="width: {{ min(100, (($newBalance / ($newBalance + $pointsToNextTier)) * 100)) }}%"></div>
            </div>
            <div class="progress-text">
                Keep earning to unlock {{ $nextTier }} benefits! 🚀
            </div>
        </div>
        @endif

        <div style="text-align: center;">
            <a href="{{ route('client.loyalty.dashboard') }}" class="cta-button">
                View Loyalty Dashboard
            </a>
        </div>

        <div class="tip-box">
            <strong>💡 Redeem Your Points:</strong> Use your points to get discounts on future services. 
            100 points = ₱100 off your next payment!
        </div>

        <div class="footer">
            <p><strong>Ways to Earn More Points:</strong></p>
            <p style="margin: 10px 0; font-size: 13px; color: #6b7280;">
                Complete payments • Reach milestones • Finish projects<br>
                Submit feedback • Refer friends • Stay active
            </p>
            <p style="margin-top: 20px;">
                <a href="{{ route('client.loyalty.transactions') }}" style="color: #3b82f6; text-decoration: none;">View All Transactions</a>
            </p>
            <p style="margin-top: 20px; font-size: 12px; color: #9ca3af;">
                This is an automated email. Please do not reply to this message.
            </p>
        </div>
    </div>
</body>
</html>
