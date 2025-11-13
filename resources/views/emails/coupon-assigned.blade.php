<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coupon Assigned</title>
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
        .gift-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .coupon-box {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            margin: 30px 0;
            border: 3px dashed rgba(255,255,255,0.3);
        }
        .coupon-code {
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 3px;
            font-family: 'Courier New', monospace;
            margin: 10px 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        .coupon-name {
            font-size: 18px;
            margin-top: 10px;
            opacity: 0.95;
        }
        .discount-value {
            font-size: 48px;
            font-weight: bold;
            margin: 20px 0;
        }
        .details-box {
            background-color: #f9fafb;
            border-left: 4px solid #10b981;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .details-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .details-row:last-child {
            border-bottom: none;
        }
        .details-label {
            color: #6b7280;
            font-weight: 500;
        }
        .details-value {
            font-weight: 600;
            color: #111827;
        }
        .final-amount {
            background-color: #ecfdf5;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
        }
        .final-amount-label {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .final-amount-value {
            color: #10b981;
            font-size: 42px;
            font-weight: bold;
        }
        .savings {
            color: #059669;
            font-size: 18px;
            font-weight: 600;
            margin-top: 10px;
        }
        .cta-button {
            display: inline-block;
            background-color: #10b981;
            color: white;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }
        .cta-button:hover {
            background-color: #059669;
        }
        .info-box {
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
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
            .coupon-code {
                font-size: 24px;
                letter-spacing: 2px;
            }
            .discount-value {
                font-size: 36px;
            }
            .final-amount-value {
                font-size: 32px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="gift-icon">🎁</div>
            <h1>Great News, {{ $userName }}!</h1>
            <p>A special discount coupon has been assigned to your service request.</p>
        </div>

        <div class="coupon-box">
            <div style="font-size: 14px; opacity: 0.9; margin-bottom: 5px;">YOUR COUPON CODE</div>
            <div class="coupon-code">{{ $couponCode }}</div>
            <div class="coupon-name">{{ $couponName }}</div>
            
            <div class="discount-value">
                @if($discountType === 'percentage')
                    {{ $discountValue }}% OFF
                @else
                    ₱{{ number_format($discountValue, 0) }} OFF
                @endif
            </div>
        </div>

        <div class="details-box">
            <h3 style="margin-top: 0; color: #111827;">Payment Breakdown</h3>
            
            <div class="details-row">
                <span class="details-label">Original Budget:</span>
                <span class="details-value">₱{{ number_format($originalBudget, 2) }}</span>
            </div>
            
            <div class="details-row">
                <span class="details-label">Coupon Discount:</span>
                <span class="details-value" style="color: #10b981;">-₱{{ number_format($discountAmount, 2) }}</span>
            </div>
            
            @if($minPurchase > 0)
            <div class="details-row">
                <span class="details-label">Minimum Purchase:</span>
                <span class="details-value">₱{{ number_format($minPurchase, 2) }}</span>
            </div>
            @endif
            
            @if($validUntil)
            <div class="details-row">
                <span class="details-label">Valid Until:</span>
                <span class="details-value">{{ $validUntil->format('F d, Y') }}</span>
            </div>
            @endif
        </div>

        <div class="final-amount">
            <div class="final-amount-label">Your Final Amount</div>
            <div class="final-amount-value">₱{{ number_format($finalAmount, 2) }}</div>
            <div class="savings">You save ₱{{ number_format($discountAmount, 2) }}! 🎉</div>
        </div>

        <div style="text-align: center;">
            <a href="{{ route('client.requests.show', $requestId) }}" class="cta-button">
                View Request Details
            </a>
        </div>

        <div class="info-box">
            <strong>📌 Important:</strong> This coupon has been automatically applied to your request. 
            When you proceed to payment, the discounted amount will be reflected in your invoice.
        </div>

        <div class="footer">
            <p>Thank you for choosing our services!</p>
            <p style="margin-top: 10px;">
                <a href="{{ config('app.url') }}" style="color: #10b981; text-decoration: none;">Visit Dashboard</a> |
                <a href="{{ route('client.loyalty.dashboard') }}" style="color: #10b981; text-decoration: none;">View Loyalty Points</a>
            </p>
            <p style="margin-top: 20px; font-size: 12px; color: #9ca3af;">
                This is an automated email. Please do not reply to this message.
            </p>
        </div>
    </div>
</body>
</html>
