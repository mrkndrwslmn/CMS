<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tier Upgraded</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .container {
            background-color: #ffffff;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .trophy-icon {
            font-size: 96px;
            margin-bottom: 10px;
            animation: bounce 1s ease infinite;
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .header h1 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0 0 10px 0;
            font-size: 32px;
            font-weight: bold;
        }
        .tier-upgrade-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            border-radius: 12px;
            text-align: center;
            margin: 30px 0;
            position: relative;
            overflow: hidden;
        }
        .tier-upgrade-box::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 10s linear infinite;
        }
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .tier-transition {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            font-size: 32px;
            font-weight: bold;
            margin: 20px 0;
        }
        .tier-name {
            padding: 15px 30px;
            background-color: rgba(255,255,255,0.2);
            border-radius: 8px;
            backdrop-filter: blur(10px);
        }
        .arrow {
            font-size: 36px;
        }
        .achievement-label {
            position: relative;
            z-index: 1;
            font-size: 16px;
            opacity: 0.95;
            margin-top: 15px;
        }
        .points-badge {
            position: relative;
            z-index: 1;
            background-color: rgba(255,255,255,0.2);
            display: inline-block;
            padding: 10px 20px;
            border-radius: 20px;
            margin-top: 15px;
            font-size: 14px;
            backdrop-filter: blur(10px);
        }
        .benefits-section {
            margin: 30px 0;
        }
        .benefits-header {
            background-color: #f0f9ff;
            padding: 15px 20px;
            border-radius: 8px 8px 0 0;
            font-weight: 600;
            color: #1e40af;
            border-bottom: 3px solid #3b82f6;
        }
        .benefits-list {
            background-color: #f9fafb;
            padding: 25px;
            border-radius: 0 0 8px 8px;
            list-style: none;
            margin: 0;
        }
        .benefits-list li {
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: flex-start;
        }
        .benefits-list li:last-child {
            border-bottom: none;
        }
        .benefit-icon {
            color: #10b981;
            margin-right: 12px;
            font-size: 20px;
            flex-shrink: 0;
        }
        .highlight-box {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 2px solid #fbbf24;
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            text-align: center;
        }
        .highlight-title {
            font-size: 18px;
            font-weight: bold;
            color: #92400e;
            margin-bottom: 10px;
        }
        .highlight-value {
            font-size: 42px;
            font-weight: bold;
            color: #b45309;
            margin: 10px 0;
        }
        .highlight-desc {
            color: #78350f;
            font-size: 14px;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 16px 40px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            text-align: center;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        .social-share {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background-color: #f9fafb;
            border-radius: 8px;
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
            .trophy-icon {
                font-size: 64px;
            }
            .tier-transition {
                flex-direction: column;
                gap: 10px;
            }
            .arrow {
                transform: rotate(90deg);
            }
            .highlight-value {
                font-size: 32px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="trophy-icon">🏆</div>
            <h1>Tier Upgraded!</h1>
            <p>Congratulations on your achievement, {{ $userName }}!</p>
        </div>

        <div class="tier-upgrade-box">
            <div class="achievement-label">YOU'VE BEEN UPGRADED TO</div>
            <div class="tier-transition">
                <span class="tier-name">{{ $oldTier }}</span>
                <span class="arrow">→</span>
                <span class="tier-name">{{ $newTier }}</span>
            </div>
            <div class="points-badge">
                🌟 {{ number_format($totalPoints) }} lifetime points earned
            </div>
        </div>

        <div class="highlight-box">
            <div class="highlight-title">Your New Earning Rate</div>
            <div class="highlight-value">{{ $earningRate }}</div>
            <div class="highlight-desc">Earn {{ $earningRate }} on every ₱100 you spend</div>
            @if($discount > 0)
            <div style="margin-top: 20px; padding-top: 20px; border-top: 2px dashed #fbbf24;">
                <div style="font-size: 16px; color: #92400e; font-weight: 600;">Plus automatic {{ $discount }}% discount</div>
                <div style="font-size: 13px; color: #78350f; margin-top: 5px;">Applied to all your services!</div>
            </div>
            @endif
        </div>

        <div class="benefits-section">
            <div class="benefits-header">
                ✨ Your Exclusive {{ $newTier }} Benefits
            </div>
            <ul class="benefits-list">
                @foreach($benefits as $benefit)
                <li>
                    <span class="benefit-icon">✓</span>
                    <span>{{ $benefit }}</span>
                </li>
                @endforeach
            </ul>
        </div>

        <div style="text-align: center;">
            <a href="{{ route('client.loyalty.dashboard') }}" class="cta-button">
                Explore Your Benefits
            </a>
        </div>

        <div class="social-share">
            <p style="font-weight: 600; color: #111827; margin-bottom: 10px;">
                🎊 Share your achievement!
            </p>
            <p style="font-size: 13px; color: #6b7280;">
                Tell your friends about your {{ $newTier }} status and help them earn rewards too!
            </p>
        </div>

        <div class="footer">
            <p><strong>Thank you for your continued trust!</strong></p>
            <p style="margin: 15px 0; font-size: 13px; color: #6b7280;">
                Your loyalty means everything to us. Keep up the great work and enjoy your new tier benefits!
            </p>
            <p style="margin-top: 20px;">
                <a href="{{ route('client.loyalty.transactions') }}" style="color: #667eea; text-decoration: none;">View Transaction History</a>
            </p>
            <p style="margin-top: 20px; font-size: 12px; color: #9ca3af;">
                This is an automated email. Please do not reply to this message.
            </p>
        </div>
    </div>
</body>
</html>
