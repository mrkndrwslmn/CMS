@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #F59E0B; padding: 40px 30px; text-align: center;">
        <h1 style="margin: 0 0 4px 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">Coupon Expiring Soon</h1>
        <p style="margin: 0; font-size: 14px; color: #FEF3C7; font-weight: 500;">Don't miss out on your discount, {{ $userName }}</p>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        
        <!-- Alert Box -->
        <div style="background: #FEF3C7; border: 2px dashed #F59E0B; padding: 24px; text-align: center; margin: 0 0 24px 0;">
            <p style="margin: 0 0 8px 0; font-size: 16px; font-weight: 600; color: #92400E;">Last Chance to Save</p>
            <p style="margin: 0; font-size: 14px; color: #78350F;">This exclusive coupon will expire soon. Use it before it's too late!</p>
        </div>
        
        <!-- Coupon Code Display -->
        <div style="background: white; border: 2px solid #E5E7EB; padding: 24px; text-align: center; margin: 0 0 24px 0;">
            <p style="margin: 0 0 8px 0; font-size: 12px; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Your Coupon Code</p>
            <p style="margin: 0; font-family: 'Courier New', monospace; font-size: 36px; font-weight: 700; color: #DC2626; letter-spacing: 2px; line-height: 1;">{{ $couponCode }}</p>
            <p style="margin: 8px 0 0 0; font-size: 15px; color: #6B7280;">{{ $couponName }}</p>
        </div>
        
        <!-- Expiry Countdown -->
        <div style="background: #FEF2F2; border: 1px solid #FCA5A5; padding: 24px; text-align: center; margin: 0 0 24px 0;">
            <p style="margin: 0; font-size: 56px; font-weight: 700; color: #DC2626; line-height: 1;">{{ $daysUntilExpiry }}</p>
            <p style="margin: 8px 0 0 0; font-size: 12px; color: #991B1B; text-transform: uppercase; letter-spacing: 0.5px;">Days Until Expiration</p>
            <p style="margin: 12px 0 0 0; font-size: 14px; color: #991B1B;">Expires: {{ $expiryDate->format('F d, Y g:i A') }}</p>
        </div>
        
        <!-- Discount Highlight -->
        <div style="background: #10B981; color: white; padding: 28px; text-align: center; margin: 0 0 24px 0;">
            <p style="margin: 0 0 8px 0; font-size: 14px; color: #D1FAE5;">Save Up To</p>
            <p style="margin: 0; font-size: 48px; font-weight: 700; line-height: 1;">
                @if($discountType === 'percentage')
                {{ $discountValue }}% OFF
                @else
                ₱{{ number_format($discountValue, 0) }} OFF
                @endif
            </p>
            <p style="margin: 8px 0 0 0; font-size: 14px; color: #D1FAE5;">On your next service request</p>
        </div>
        
        <!-- Details Grid -->
        <div style="margin: 0 0 24px 0;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    @if($minPurchase > 0)
                    <td style="width: 50%; padding: 0 6px 0 0; vertical-align: top;">
                        <div style="background: #F9FAFB; border: 1px solid #E5E7EB; padding: 16px; text-align: center;">
                            <p style="margin: 0 0 4px 0; font-size: 12px; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Minimum Purchase</p>
                            <p style="margin: 0; font-size: 18px; color: #1F2937; font-weight: 600;">₱{{ number_format($minPurchase, 0) }}</p>
                        </div>
                    </td>
                    @endif
                    
                    @if($usesRemaining !== null)
                    <td style="width: 50%; padding: 0 0 0 6px; vertical-align: top;">
                        <div style="background: #F9FAFB; border: 1px solid #E5E7EB; padding: 16px; text-align: center;">
                            <p style="margin: 0 0 4px 0; font-size: 12px; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Uses Remaining</p>
                            <p style="margin: 0; font-size: 18px; color: #1F2937; font-weight: 600;">{{ $usesRemaining }} {{ $usesRemaining === 1 ? 'use' : 'uses' }}</p>
                        </div>
                    </td>
                    @endif
                </tr>
            </table>
        </div>
        
        <!-- CTA Button -->
        <div style="text-align: center; margin: 0 0 24px 0;">
            <a href="{{ route('client.requests.index') }}" style="display: inline-block; background: #DC2626; color: white; padding: 14px 40px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0;">Use Coupon Now</a>
        </div>
        
        <!-- Why Act Now Box -->
        <div style="background: #FEF3C7; border-left: 3px solid #F59E0B; padding: 20px 24px; margin: 0 0 24px 0;">
            <p style="margin: 0 0 12px 0; font-size: 14px; font-weight: 600; color: #92400E;">Why Act Now</p>
            <div style="font-size: 14px; color: #78350F; line-height: 1.7;">
                <p style="margin: 0 0 8px 0;">This coupon cannot be extended or recovered after expiration</p>
                <p style="margin: 0 0 8px 0;">Apply it to any active or new service request</p>
                <p style="margin: 0 0 8px 0;">Combine with your loyalty points for extra savings</p>
                <p style="margin: 0;">Valid on all our services</p>
            </div>
        </div>
        
        <!-- Pro Tip Box -->
        <div style="background: #EFF6FF; border: 1px solid #BFDBFE; padding: 20px 24px; text-align: center; margin: 0 0 24px 0;">
            <p style="margin: 0 0 8px 0; font-size: 14px; font-weight: 600; color: #1E40AF;">Pro Tip</p>
            <p style="margin: 0; font-size: 14px; color: #1E3A8A; line-height: 1.7;">Apply this coupon during checkout and watch your total decrease instantly. The earlier you use it, the more time you have to enjoy the savings!</p>
        </div>
        
        <!-- Footer Links -->
        <div style="padding: 24px 0 0 0; border-top: 1px solid #E5E7EB; text-align: center;">
            <p style="margin: 0 0 12px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Need help using your coupon?</p>
            <p style="margin: 0 0 16px 0; font-size: 13px; color: #6B7280; line-height: 1.7;">Visit your coupons page to view all available offers and learn how to apply them.</p>
            <p style="margin: 0;">
                <a href="{{ route('client.coupons.index') }}" style="color: #DC2626; text-decoration: none; font-weight: 500; font-size: 13px;">View All Coupons</a> |
                <a href="{{ route('client.requests.create') }}" style="color: #DC2626; text-decoration: none; font-weight: 500; font-size: 13px;">Submit New Request</a>
            </p>
        </div>
    </div>
    
    <!-- Footer -->
    <div style="background: #F9FAFB; border-top: 1px solid #E5E7EB; padding: 24px 30px; text-align: center;">
        <p style="margin: 0; font-size: 12px; color: #9CA3AF;">This is an automated reminder. Please do not reply to this message.</p>
    </div>
</div>
@endsection
