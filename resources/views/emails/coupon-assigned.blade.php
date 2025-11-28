@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #10B981; padding: 40px 30px; text-align: center;">
        <h1 style="margin: 0 0 4px 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">Great News, {{ $userName }}</h1>
        <p style="margin: 0; font-size: 14px; color: #D1FAE5; font-weight: 500;">A special discount coupon has been assigned to your service request</p>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        
        <!-- Coupon Box -->
        <div style="background: #10B981; border: 2px dashed #D1FAE5; color: white; padding: 30px; text-align: center; margin: 0 0 24px 0;">
            <p style="margin: 0 0 8px 0; font-size: 12px; color: #D1FAE5; text-transform: uppercase; letter-spacing: 0.5px;">Your Coupon Code</p>
            <p style="margin: 0; font-family: 'Courier New', monospace; font-size: 36px; font-weight: 700; color: white; letter-spacing: 3px; line-height: 1;">{{ $couponCode }}</p>
            <p style="margin: 12px 0 0 0; font-size: 16px; color: #D1FAE5;">{{ $couponName }}</p>
            
            <p style="margin: 20px 0 0 0; font-size: 48px; font-weight: 700; line-height: 1;">
                @if($discountType === 'percentage')
                {{ $discountValue }}% OFF
                @else
                ₱{{ number_format($discountValue, 0) }} OFF
                @endif
            </p>
        </div>
        
        <!-- Payment Breakdown Section -->
        <div style="margin: 0 0 24px 0;">
            <p style="margin: 0 0 16px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Payment Breakdown</p>
            
            <div style="background: #F9FAFB; border-left: 3px solid #10B981; padding: 24px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; font-size: 14px; color: #6B7280; border-bottom: 1px solid #E5E7EB;">
                            <strong style="color: #1F2937;">Original Budget:</strong>
                        </td>
                        <td style="padding: 8px 0; font-size: 14px; color: #1F2937; text-align: right; border-bottom: 1px solid #E5E7EB; font-weight: 600;">
                            ₱{{ number_format($originalBudget, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-size: 14px; color: #6B7280; border-bottom: 1px solid #E5E7EB;">
                            <strong style="color: #1F2937;">Coupon Discount:</strong>
                        </td>
                        <td style="padding: 8px 0; font-size: 14px; color: #10B981; text-align: right; border-bottom: 1px solid #E5E7EB; font-weight: 600;">
                            -₱{{ number_format($discountAmount, 2) }}
                        </td>
                    </tr>
                    @if($minPurchase > 0)
                    <tr>
                        <td style="padding: 8px 0; font-size: 14px; color: #6B7280; border-bottom: 1px solid #E5E7EB;">
                            <strong style="color: #1F2937;">Minimum Purchase:</strong>
                        </td>
                        <td style="padding: 8px 0; font-size: 14px; color: #1F2937; text-align: right; border-bottom: 1px solid #E5E7EB; font-weight: 600;">
                            ₱{{ number_format($minPurchase, 2) }}
                        </td>
                    </tr>
                    @endif
                    @if($validUntil)
                    <tr>
                        <td style="padding: 8px 0 0 0; font-size: 14px; color: #6B7280;">
                            <strong style="color: #1F2937;">Valid Until:</strong>
                        </td>
                        <td style="padding: 8px 0 0 0; font-size: 14px; color: #1F2937; text-align: right; font-weight: 600;">
                            {{ $validUntil->format('F d, Y') }}
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
        
        <!-- Final Amount Box -->
        <div style="background: #ECFDF5; border: 1px solid #A7F3D0; padding: 24px; text-align: center; margin: 0 0 24px 0;">
            <p style="margin: 0 0 8px 0; font-size: 14px; color: #6B7280;">Your Final Amount</p>
            <p style="margin: 0; font-size: 42px; color: #10B981; font-weight: 700; line-height: 1;">₱{{ number_format($finalAmount, 2) }}</p>
            <p style="margin: 12px 0 0 0; font-size: 16px; color: #059669; font-weight: 600;">You save ₱{{ number_format($discountAmount, 2) }}</p>
        </div>
        
        <!-- CTA Button -->
        <div style="text-align: center; margin: 0 0 24px 0;">
            <a href="{{ route('client.requests.show', $requestId) }}" style="display: inline-block; background: #10B981; color: white; padding: 14px 32px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0;">View Request Details</a>
        </div>
        
        <!-- Important Info Box -->
        <div style="background: #EFF6FF; border-left: 3px solid #3B82F6; padding: 16px 20px; margin: 0 0 24px 0;">
            <p style="margin: 0; font-size: 14px; color: #1E40AF; line-height: 1.7;">
                <strong style="font-weight: 600;">Important:</strong> This coupon has been automatically applied to your request. When you proceed to payment, the discounted amount will be reflected in your invoice.
            </p>
        </div>
        
        <!-- Footer Links -->
        <div style="padding: 24px 0 0 0; border-top: 1px solid #E5E7EB; text-align: center;">
            <p style="margin: 0 0 12px 0; font-size: 14px; color: #6B7280;">Thank you for choosing our services!</p>
            <p style="margin: 0;">
                <a href="{{ config('app.url') }}" style="color: #10B981; text-decoration: none; font-weight: 500; font-size: 13px;">Visit Dashboard</a> |
                <a href="{{ route('client.loyalty.dashboard') }}" style="color: #10B981; text-decoration: none; font-weight: 500; font-size: 13px;">View Loyalty Points</a>
            </p>
        </div>
    </div>
    
    <!-- Footer -->
    <div style="background: #F9FAFB; border-top: 1px solid #E5E7EB; padding: 24px 30px; text-align: center;">
        <p style="margin: 0; font-size: 12px; color: #9CA3AF;">This is an automated email. Please do not reply to this message.</p>
    </div>
</div>
@endsection
