@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #3B82F6; padding: 40px 30px; text-align: center;">
        <h1 style="margin: 0 0 4px 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">Congratulations, {{ $userName }}</h1>
        <p style="margin: 0; font-size: 14px; color: #DBEAFE; font-weight: 500;">You've just earned loyalty points</p>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        
        <!-- Points Earned Box -->
        <div style="background: #3B82F6; color: white; padding: 40px; text-align: center; margin: 0 0 24px 0;">
            <p style="margin: 0 0 8px 0; font-size: 12px; color: #DBEAFE; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Points Earned</p>
            <p style="margin: 0; font-size: 56px; font-weight: 700; line-height: 1; color: white;">+{{ number_format($pointsEarned) }}</p>
            <p style="margin: 12px 0 0 0; font-size: 15px; color: #DBEAFE;">{{ $reason }}</p>
        </div>
        
        <!-- Balance Cards -->
        <div style="margin: 0 0 24px 0;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 50%; padding: 0 6px 0 0; vertical-align: top;">
                        <div style="background: #F9FAFB; border: 1px solid #E5E7EB; padding: 20px; text-align: center;">
                            <p style="margin: 0 0 8px 0; font-size: 12px; color: #6B7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">New Balance</p>
                            <p style="margin: 0; font-size: 32px; color: #1F2937; font-weight: 700; line-height: 1;">{{ number_format($newBalance) }}</p>
                            <p style="margin: 4px 0 0 0; font-size: 12px; color: #6B7280;">points</p>
                        </div>
                    </td>
                    <td style="width: 50%; padding: 0 0 0 6px; vertical-align: top;">
                        <div style="background: #F9FAFB; border: 1px solid #E5E7EB; padding: 20px; text-align: center;">
                            <p style="margin: 0 0 8px 0; font-size: 12px; color: #6B7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Points Value</p>
                            <p style="margin: 0; font-size: 32px; color: #10B981; font-weight: 700; line-height: 1;">₱{{ number_format($newBalance) }}</p>
                            <p style="margin: 4px 0 0 0; font-size: 12px; color: #6B7280;">1 point = ₱1</p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Tier Box -->
        <div style="background: #FEF3C7; border-left: 3px solid #F59E0B; padding: 24px; margin: 0 0 24px 0;">
            <p style="margin: 0 0 4px 0; font-size: 20px; color: #78350F; font-weight: 700;">{{ $currentTier }} Tier</p>
            <p style="margin: 0; font-size: 14px; color: #92400E;">
                @if($tierDiscount > 0)
                {{ $tierDiscount }}% automatic discount on all services
                @else
                Standard benefits
                @endif
            </p>
        </div>
        
        @if($pointsToNextTier !== null && $nextTier)
        <!-- Progress to Next Tier -->
        <div style="background: #EFF6FF; border: 1px solid #BFDBFE; padding: 20px; margin: 0 0 24px 0;">
            <div style="margin: 0 0 10px 0;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="font-size: 14px; color: #1E40AF; font-weight: 600;">Progress to {{ $nextTier }} Tier</td>
                        <td style="font-size: 13px; color: #1E40AF; text-align: right;">{{ number_format($pointsToNextTier) }} points to go</td>
                    </tr>
                </table>
            </div>
            <div style="background: #DBEAFE; height: 12px; overflow: hidden; margin: 10px 0;">
                <div style="background: #3B82F6; height: 100%; width: {{ min(100, (($newBalance / ($newBalance + $pointsToNextTier)) * 100)) }}%;"></div>
            </div>
            <p style="margin: 8px 0 0 0; font-size: 13px; color: #6B7280; text-align: center;">Keep earning to unlock {{ $nextTier }} benefits</p>
        </div>
        @endif
        
        <!-- CTA Button -->
        <div style="text-align: center; margin: 0 0 24px 0;">
            <a href="{{ route('client.loyalty.dashboard') }}" style="display: inline-block; background: #3B82F6; color: white; padding: 14px 32px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0;">View Loyalty Dashboard</a>
        </div>
        
        <!-- Redemption Tip -->
        <div style="background: #FEF3C7; border-left: 3px solid #F59E0B; padding: 16px 20px; margin: 0 0 24px 0;">
            <p style="margin: 0; font-size: 14px; color: #78350F; line-height: 1.7;">
                <strong style="color: #92400E;">Redeem Your Points:</strong> Use your points to get discounts on future services. 100 points = ₱100 off your next payment!
            </p>
        </div>
        
        <!-- Ways to Earn More -->
        <div style="padding: 24px 0 0 0; border-top: 1px solid #E5E7EB; text-align: center;">
            <p style="margin: 0 0 8px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Ways to Earn More Points</p>
            <p style="margin: 0 0 16px 0; font-size: 13px; color: #6B7280; line-height: 1.7;">
                Complete payments • Reach milestones • Finish projects<br>
                Submit feedback • Refer friends • Stay active
            </p>
            <p style="margin: 0;">
                <a href="{{ route('client.loyalty.transactions') }}" style="color: #3B82F6; text-decoration: none; font-weight: 500; font-size: 13px;">View All Transactions</a>
            </p>
        </div>
    </div>
    
    <!-- Footer -->
    <div style="background: #F9FAFB; border-top: 1px solid #E5E7EB; padding: 24px 30px; text-align: center;">
        <p style="margin: 0; font-size: 12px; color: #9CA3AF;">This is an automated email. Please do not reply to this message.</p>
    </div>
</div>
@endsection
