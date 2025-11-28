@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #10B981; padding: 40px 30px; text-align: center;">
        <h1 style="margin: 0 0 4px 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">Great News, {{ $referrer->fullName }}</h1>
        <p style="margin: 0; font-size: 14px; color: #D1FAE5; font-weight: 500;">Your referral just signed up</p>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        
        <!-- Referral Info Box -->
        <div style="background: #10B981; color: white; padding: 30px; text-align: center; margin: 0 0 24px 0;">
            <p style="margin: 0 0 4px 0; font-size: 24px; font-weight: 700; color: white; line-height: 1;">{{ $referred->fullName }}</p>
            <p style="margin: 0 0 12px 0; font-size: 15px; color: #D1FAE5;">{{ $referred->email }}</p>
            <span style="display: inline-block; background: #FEF3C7; color: #92400E; padding: 6px 16px; font-size: 12px; font-weight: 600; text-transform: uppercase;">Pending First Payment</span>
        </div>
        
        <!-- Info Grid -->
        <div style="margin: 0 0 24px 0;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 50%; padding: 0 6px 0 0; vertical-align: top;">
                        <div style="background: #F9FAFB; border: 1px solid #E5E7EB; padding: 20px; text-align: center;">
                            <p style="margin: 0 0 8px 0; font-size: 12px; color: #6B7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Your Referral Code</p>
                            <p style="margin: 0; font-size: 24px; color: #3B82F6; font-weight: 700; line-height: 1;">{{ $referralCode->code }}</p>
                        </div>
                    </td>
                    <td style="width: 50%; padding: 0 0 0 6px; vertical-align: top;">
                        <div style="background: #F9FAFB; border: 1px solid #E5E7EB; padding: 20px; text-align: center;">
                            <p style="margin: 0 0 8px 0; font-size: 12px; color: #6B7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Total Referrals</p>
                            <p style="margin: 0; font-size: 24px; color: #10B981; font-weight: 700; line-height: 1;">{{ $referralCode->total_referrals }}</p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Pending Reward Box -->
        <div style="background: #F59E0B; color: white; padding: 28px; text-align: center; margin: 0 0 24px 0;">
            <p style="margin: 0 0 12px 0; font-size: 16px; font-weight: 600; color: white;">Pending Reward</p>
            <p style="margin: 0; font-size: 48px; font-weight: 700; line-height: 1; color: white;">{{ number_format($pendingPoints) }} pts</p>
            <p style="margin: 12px 0 0 0; font-size: 14px; color: #FEF3C7;">+ 20% Discount Coupon</p>
        </div>
        
        <!-- What Happens Next Section -->
        <div style="background: #EFF6FF; border: 1px solid #BFDBFE; padding: 24px; margin: 0 0 24px 0;">
            <p style="margin: 0 0 20px 0; font-size: 14px; font-weight: 600; color: #1E40AF;">What Happens Next?</p>
            
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #3B82F6; color: white; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border-radius: 50%;">1</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0; font-size: 14px; color: #1E3A8A; line-height: 1.6;"><strong>{{ $referred->fullName }}</strong> explores our services and submits a request</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0 0 20px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #3B82F6; color: white; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border-radius: 50%;">2</span>
                    </td>
                    <td style="padding: 0 0 20px 12px; vertical-align: top;">
                        <p style="margin: 0; font-size: 14px; color: #1E3A8A; line-height: 1.6;">They complete their <strong>first payment</strong> for the service</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 36px; padding: 0; vertical-align: top;">
                        <span style="display: inline-block; width: 28px; height: 28px; background: #3B82F6; color: white; text-align: center; line-height: 28px; font-size: 13px; font-weight: 600; border-radius: 50%;">3</span>
                    </td>
                    <td style="padding: 0 0 0 12px; vertical-align: top;">
                        <p style="margin: 0; font-size: 14px; color: #1E3A8A; line-height: 1.6;">You receive <strong>{{ number_format($pendingPoints) }} points</strong> and a <strong>20% discount coupon</strong> instantly</p>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- CTA Button -->
        <div style="text-align: center; margin: 0 0 24px 0;">
            <a href="{{ route('client.referrals.dashboard') }}" style="display: inline-block; background: #3B82F6; color: white; padding: 14px 32px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0;">View Referral Dashboard</a>
        </div>
        
        <!-- Tip Box -->
        <div style="background: #ECFDF5; border-left: 3px solid #10B981; padding: 16px 20px; margin: 0 0 24px 0;">
            <p style="margin: 0; font-size: 14px; color: #065F46; line-height: 1.7;">
                <strong style="font-weight: 600;">Earn More Rewards:</strong> Share your referral code <strong>{{ $referralCode->code }}</strong> with friends! Each successful referral earns you {{ number_format($pendingPoints) }} points and a discount coupon.
            </p>
        </div>
        
        <!-- Referral Stats Section -->
        <div style="padding: 24px 0 0 0; border-top: 1px solid #E5E7EB; text-align: center;">
            <p style="margin: 0 0 12px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Your Referral Stats</p>
            <p style="margin: 0 0 16px 0; font-size: 13px; color: #6B7280; line-height: 1.7;">
                Total Referrals: {{ $referralCode->total_referrals }} • 
                Successful: {{ $referralCode->successful_referrals }} • 
                Pending: {{ $referralCode->pending_referrals }}<br>
                Lifetime Earnings: {{ number_format($referralCode->lifetime_earnings_points) }} points
            </p>
            <p style="margin: 0;">
                <a href="{{ route('client.referrals.share') }}" style="color: #3B82F6; text-decoration: none; font-weight: 500; font-size: 13px;">Share Your Referral Link</a>
            </p>
        </div>
    </div>
    
    <!-- Footer -->
    <div style="background: #F9FAFB; border-top: 1px solid #E5E7EB; padding: 24px 30px; text-align: center;">
        <p style="margin: 0; font-size: 12px; color: #9CA3AF;">This is an automated email. Please do not reply to this message.</p>
    </div>
</div>
@endsection
