@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #10B981; padding: 40px 30px; text-align: center;">
        <h1 style="margin: 0 0 4px 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">Reward Unlocked</h1>
        <p style="margin: 0; font-size: 14px; color: #D1FAE5; font-weight: 500;">Congratulations, {{ $referrer->fullName }}</p>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        
        <!-- Success Banner -->
        <div style="background: #ECFDF5; border: 1px solid #10B981; padding: 20px 24px; text-align: center; margin: 0 0 24px 0;">
            <p style="margin: 0 0 4px 0; font-size: 16px; font-weight: 600; color: #065F46;">Referral Completed Successfully</p>
            <p style="margin: 0; font-size: 14px; color: #047857;">{{ $referred->fullName }} just completed their first payment</p>
        </div>
        
        <!-- Reward Earned Box -->
        <div style="background: #10B981; color: white; padding: 40px; text-align: center; margin: 0 0 24px 0;">
            <p style="margin: 0 0 8px 0; font-size: 12px; color: #D1FAE5; text-transform: uppercase; letter-spacing: 0.5px;">Points Credited</p>
            <p style="margin: 0; font-size: 56px; font-weight: 700; line-height: 1; color: white;">+{{ number_format($pointsEarned) }}</p>
            <p style="margin: 12px 0 0 0; font-size: 15px; color: #D1FAE5;">Added to your loyalty account</p>
        </div>
        
        @if($coupon)
        <!-- Coupon Box -->
        <div style="background: #8B5CF6; color: white; padding: 30px; margin: 0 0 24px 0;">
            <p style="margin: 0 0 16px 0; font-size: 12px; color: #EDE9FE; text-transform: uppercase; letter-spacing: 0.5px;">Your Exclusive Discount Coupon</p>
            <div style="background: white; color: #7C3AED; font-size: 32px; font-weight: 700; padding: 16px; text-align: center; letter-spacing: 2px; margin: 0 0 16px 0;">{{ $coupon->code }}</div>
            <div style="font-size: 14px; color: #EDE9FE; line-height: 1.7;">
                <p style="margin: 0 0 4px 0;"><strong>{{ $coupon->discount_value }}% OFF</strong> your next service</p>
                <p style="margin: 0 0 4px 0;">Valid until {{ $coupon->valid_until->format('F d, Y') }}</p>
                <p style="margin: 0;">Min. purchase: ₱{{ number_format($coupon->min_purchase_amount) }}</p>
            </div>
        </div>
        @endif
        
        <!-- Referrer Box -->
        <div style="background: #EFF6FF; border: 1px solid #BFDBFE; padding: 20px 24px; margin: 0 0 24px 0;">
            <p style="margin: 0 0 4px 0; font-size: 16px; color: #1E40AF; font-weight: 600;">{{ $referred->fullName }}</p>
            <p style="margin: 0; font-size: 14px; color: #6B7280;">Your referral is now an active client</p>
        </div>
        
        <!-- Rewards Grid -->
        <div style="margin: 0 0 24px 0;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 50%; padding: 0 6px 0 0; vertical-align: top;">
                        <div style="background: #F9FAFB; border: 1px solid #E5E7EB; padding: 20px; text-align: center;">
                            <p style="margin: 0 0 8px 0; font-size: 12px; color: #6B7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Points Value</p>
                            <p style="margin: 0; font-size: 24px; color: #10B981; font-weight: 700; line-height: 1;">₱{{ number_format($pointsEarned) }}</p>
                            <p style="margin: 4px 0 0 0; font-size: 12px; color: #6B7280;">1 point = ₱1</p>
                        </div>
                    </td>
                    <td style="width: 50%; padding: 0 0 0 6px; vertical-align: top;">
                        <div style="background: #F9FAFB; border: 1px solid #E5E7EB; padding: 20px; text-align: center;">
                            <p style="margin: 0 0 8px 0; font-size: 12px; color: #6B7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Coupon Savings</p>
                            <p style="margin: 0; font-size: 24px; color: #8B5CF6; font-weight: 700; line-height: 1;">
                                @if($coupon)
                                {{ $coupon->discount_value }}%
                                @else
                                N/A
                                @endif
                            </p>
                            <p style="margin: 4px 0 0 0; font-size: 12px; color: #6B7280;">discount off</p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- CTA Buttons -->
        <div style="text-align: center; margin: 0 0 24px 0;">
            <a href="{{ route('client.loyalty.dashboard') }}" style="display: inline-block; background: #10B981; color: white; padding: 14px 32px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0; margin: 0 6px 12px 6px;">View Points Balance</a>
            @if($coupon)
            <a href="{{ route('client.coupons.index') }}" style="display: inline-block; background: #3B82F6; color: white; padding: 14px 32px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0; margin: 0 6px 12px 6px;">View My Coupons</a>
            @endif
        </div>
        
        <!-- Tip Box -->
        <div style="background: #FEF3C7; border-left: 3px solid #F59E0B; padding: 16px 20px; margin: 0 0 24px 0;">
            <p style="margin: 0; font-size: 14px; color: #78350F; line-height: 1.7;">
                <strong style="font-weight: 600;">Keep Referring & Earning:</strong> Share your referral code with more friends! Each successful referral earns you {{ number_format(config('referral.rewards.referrer.completion_points', 1000)) }} points and a {{ config('referral.rewards.referrer.coupon_discount', 20) }}% discount coupon.
            </p>
        </div>
        
        <!-- How to Use Section -->
        <div style="padding: 24px 0 0 0; border-top: 1px solid #E5E7EB;">
            <p style="margin: 0 0 12px 0; font-size: 14px; font-weight: 600; color: #1F2937; text-align: center;">How to Use Your Rewards</p>
            <div style="font-size: 13px; color: #6B7280; line-height: 1.7; text-align: center;">
                <p style="margin: 0 0 4px 0;">Use points as payment (100 points = ₱100 off)</p>
                <p style="margin: 0 0 4px 0;">Apply your coupon code at checkout</p>
                <p style="margin: 0;">Rewards can be stacked for maximum savings</p>
            </div>
            <p style="margin: 16px 0 0 0; text-align: center;">
                <a href="{{ route('client.referrals.dashboard') }}" style="color: #10B981; text-decoration: none; font-weight: 500; font-size: 13px;">View Referral Dashboard</a> • 
                <a href="{{ route('client.referrals.share') }}" style="color: #3B82F6; text-decoration: none; font-weight: 500; font-size: 13px;">Share Your Code</a>
            </p>
        </div>
    </div>
    
    <!-- Footer -->
    <div style="background: #F9FAFB; border-top: 1px solid #E5E7EB; padding: 24px 30px; text-align: center;">
        <p style="margin: 0; font-size: 12px; color: #9CA3AF;">This is an automated email. Please do not reply to this message.</p>
    </div>
</div>
@endsection
