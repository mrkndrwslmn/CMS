<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You're Invited - TREIS ADIUTOR</title>
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
        .invitation-icon {
            font-size: 72px;
            margin-bottom: 15px;
        }
        .from-box {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            margin: 30px 0;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        .from-label {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .from-name {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .personal-message {
            background-color: #f9fafb;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
            font-style: italic;
            color: #374151;
        }
        .rewards-section {
            margin: 30px 0;
        }
        .rewards-title {
            text-align: center;
            color: #111827;
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .rewards-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
        }
        .reward-card {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        .reward-card.discount {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }
        .reward-icon {
            font-size: 36px;
            margin-bottom: 10px;
        }
        .reward-value {
            font-size: 32px;
            font-weight: bold;
            margin: 10px 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        .reward-label {
            font-size: 14px;
            opacity: 0.95;
        }
        .benefits-box {
            background-color: #f0f9ff;
            border: 2px solid #3b82f6;
            border-radius: 8px;
            padding: 25px;
            margin: 25px 0;
        }
        .benefits-title {
            color: #1e40af;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .benefit-item {
            display: flex;
            align-items: flex-start;
            margin: 12px 0;
        }
        .benefit-icon {
            color: #10b981;
            font-size: 20px;
            margin-right: 12px;
            flex-shrink: 0;
        }
        .benefit-text {
            flex: 1;
            font-size: 14px;
            color: #1f2937;
        }
        .cta-section {
            text-align: center;
            margin: 35px 0;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 16px 40px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 10px 0;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
            transition: transform 0.2s;
        }
        .referral-code-box {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            border: 2px dashed #9ca3af;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
        }
        .code-label {
            font-size: 13px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .code-value {
            font-size: 28px;
            font-weight: bold;
            color: #1f2937;
            letter-spacing: 2px;
            font-family: 'Courier New', monospace;
        }
        .how-it-works {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .how-it-works-title {
            color: #92400e;
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .step-item {
            display: flex;
            align-items: flex-start;
            margin: 12px 0;
        }
        .step-number {
            background-color: #f59e0b;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 12px;
            margin-right: 12px;
            flex-shrink: 0;
        }
        .step-text {
            flex: 1;
            font-size: 14px;
            color: #78350f;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 13px;
        }
        @media only screen and (max-width: 600px) {
            body {
                padding: 10px;
            }
            .container {
                padding: 25px;
            }
            .rewards-grid {
                grid-template-columns: 1fr;
            }
            .invitation-icon {
                font-size: 56px;
            }
            .from-name {
                font-size: 22px;
            }
            .reward-value {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="invitation-icon">✉️</div>
            <h1>You're Invited!</h1>
            <p style="color: #6b7280; font-size: 15px;">Someone special wants you to join TREIS ADIUTOR</p>
        </div>

        <div class="from-box">
            <div class="from-label">Invitation From</div>
            <div class="from-name">{{ $referrer->fullName }}</div>
            <div style="font-size: 14px; opacity: 0.9; margin-top: 5px;">{{ $referrer->email }}</div>
        </div>

        @if($personalMessage)
        <div class="personal-message">
            <strong style="color: #1f2937; font-size: 14px; display: block; margin-bottom: 8px;">📝 Personal Message:</strong>
            {{ $personalMessage }}
        </div>
        @endif

        <div class="rewards-section">
            <div class="rewards-title">🎁 Your Welcome Rewards</div>
            
            <div class="rewards-grid">
                <div class="reward-card">
                    <div class="reward-icon">💰</div>
                    <div class="reward-value">{{ number_format($referredBonus) }}</div>
                    <div class="reward-label">Bonus Points</div>
                </div>
                
                <div class="reward-card discount">
                    <div class="reward-icon">🎟️</div>
                    <div class="reward-value">{{ $referredDiscount }}%</div>
                    <div class="reward-label">Discount Coupon</div>
                </div>
            </div>
        </div>

        <div class="benefits-box">
            <div class="benefits-title">Why Join TREIS ADIUTOR?</div>
            
            <div class="benefit-item">
                <div class="benefit-icon">✓</div>
                <div class="benefit-text">
                    <strong>Professional Services:</strong> Access expert adiutors for your projects
                </div>
            </div>
            
            <div class="benefit-item">
                <div class="benefit-icon">✓</div>
                <div class="benefit-text">
                    <strong>Loyalty Rewards:</strong> Earn points with every transaction
                </div>
            </div>
            
            <div class="benefit-item">
                <div class="benefit-icon">✓</div>
                <div class="benefit-text">
                    <strong>Quality Guaranteed:</strong> All work is reviewed and verified
                </div>
            </div>
            
            <div class="benefit-item">
                <div class="benefit-icon">✓</div>
                <div class="benefit-text">
                    <strong>Secure Payments:</strong> Safe and reliable payment processing
                </div>
            </div>
        </div>

        <div class="cta-section">
            <a href="{{ $referralUrl }}" class="cta-button">
                🚀 Join Now & Claim Your Rewards
            </a>
            <p style="color: #6b7280; font-size: 13px; margin-top: 15px;">
                Click the button above to sign up with {{ $referrer->firstName }}'s referral code
            </p>
        </div>

        <div class="referral-code-box">
            <div class="code-label">Your Referral Code</div>
            <div class="code-value">{{ $referralCode->code }}</div>
            <p style="margin: 10px 0 0 0; font-size: 12px; color: #6b7280;">
                Use this code during registration to claim your rewards
            </p>
        </div>

        <div class="how-it-works">
            <div class="how-it-works-title">⚡ How It Works</div>
            
            <div class="step-item">
                <div class="step-number">1</div>
                <div class="step-text">
                    Click the button above to sign up with code <strong>{{ $referralCode->code }}</strong>
                </div>
            </div>
            
            <div class="step-item">
                <div class="step-number">2</div>
                <div class="step-text">
                    Your <strong>{{ number_format($referredBonus) }} bonus points</strong> and <strong>{{ $referredDiscount }}% discount coupon</strong> are instantly added
                </div>
            </div>
            
            <div class="step-item">
                <div class="step-number">3</div>
                <div class="step-text">
                    Start using our services and enjoy your rewards!
                </div>
            </div>
        </div>

        <div style="background-color: #ecfdf5; border: 1px solid #10b981; border-radius: 8px; padding: 20px; margin: 25px 0; text-align: center;">
            <div style="font-size: 18px; font-weight: 600; color: #065f46; margin-bottom: 10px;">
                🎊 Limited Time Offer!
            </div>
            <p style="margin: 0; font-size: 14px; color: #047857;">
                Sign up today to claim your welcome rewards. Your friend {{ $referrer->firstName }} will also earn points when you complete your first payment!
            </p>
        </div>

        <div class="footer">
            <p style="margin-bottom: 15px;">
                <a href="{{ $referralUrl }}" style="color: #3b82f6; text-decoration: none; font-weight: 600;">
                    Join TREIS ADIUTOR Now →
                </a>
            </p>
            <p style="font-size: 12px; color: #9ca3af; margin-top: 20px;">
                This invitation was sent by {{ $referrer->fullName }}. If you don't want to receive invitations,<br>
                you can safely ignore this email.
            </p>
            <p style="font-size: 11px; color: #d1d5db; margin-top: 15px;">
                © {{ date('Y') }} TREIS ADIUTOR. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
