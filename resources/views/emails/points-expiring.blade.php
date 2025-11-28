@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #F59E0B; padding: 40px 30px; text-align: center;">
        <h1 style="margin: 0 0 8px 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">Points Expiring Soon</h1>
        <p style="margin: 0; font-size: 14px; color: #FEF3C7; font-weight: 500;">Don't lose your hard-earned loyalty points, {{ $userName }}!</p>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        
        <!-- Expiring Points Alert -->
        <div style="background: #FEF3C7; border-left: 3px solid #F59E0B; padding: 24px; margin: 0 0 24px 0; text-align: center;">
            <p style="margin: 0 0 8px 0; font-size: 12px; font-weight: 600; color: #92400E; text-transform: uppercase; letter-spacing: 0.5px;">Expiring Soon</p>
            <p style="margin: 0; font-size: 48px; font-weight: 700; color: #EA580C; line-height: 1.2;">{{ number_format($totalExpiringPoints) }}</p>
            <p style="margin: 8px 0 0 0; font-size: 14px; font-weight: 600; color: #92400E;">Loyalty Points</p>
            <p style="margin: 8px 0 0 0; font-size: 13px; color: #78350F;">Worth ₱{{ number_format($pointsValue) }}</p>
            <p style="margin: 16px 0 0 0; font-size: 15px; font-weight: 600; color: #92400E;">Expires on {{ $expiryDate->format('F d, Y') }}</p>
        </div>
        
        <!-- Countdown -->
        <div style="background: #FFF7ED; border: 1px solid #FB923C; padding: 20px; text-align: center; margin: 0 0 24px 0;">
            <p style="margin: 0; font-size: 42px; font-weight: 700; color: #EA580C; line-height: 1;">{{ $daysUntilExpiry }}</p>
            <p style="margin: 8px 0 0 0; font-size: 12px; font-weight: 600; color: #9A3412; text-transform: uppercase; letter-spacing: 0.5px;">Days Remaining</p>
        </div>
        
        <!-- Balance Information -->
        <div style="margin: 0 0 24px 0;">
            <p style="margin: 0 0 16px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Balance Information</p>
            
            <div style="background: #F0F9FF; border-left: 3px solid #3B82F6; padding: 20px 24px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; font-size: 14px; color: #1E40AF; font-weight: 500;">
                            Current Total Balance:
                        </td>
                        <td style="padding: 8px 0; font-size: 14px; color: #1E3A8A; font-weight: 600; text-align: right;">
                            {{ number_format($currentBalance) }} points
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-size: 14px; color: #1E40AF; font-weight: 500;">
                            Points Expiring:
                        </td>
                        <td style="padding: 8px 0; font-size: 14px; color: #EA580C; font-weight: 600; text-align: right;">
                            {{ number_format($totalExpiringPoints) }} points
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 16px 0 8px 0; font-size: 14px; color: #1E40AF; font-weight: 600; border-top: 1px solid #BFDBFE;">
                            After Expiry:
                        </td>
                        <td style="padding: 16px 0 8px 0; font-size: 14px; color: #1E3A8A; font-weight: 700; text-align: right; border-top: 1px solid #BFDBFE;">
                            {{ number_format($currentBalance - $totalExpiringPoints) }} points
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        @if($expiringBatches->count() > 1)
        <!-- Expiring Points Breakdown -->
        <div style="margin: 0 0 24px 0; padding: 24px 0 0 0; border-top: 1px solid #E5E7EB;">
            <p style="margin: 0 0 16px 0; font-size: 13px; font-weight: 600; color: #1F2937; text-transform: uppercase; letter-spacing: 0.5px;">Expiring Points Breakdown</p>
            
            @foreach($expiringBatches as $date => $batch)
            <div style="background: #FEF3C7; border-left: 3px solid #F59E0B; padding: 16px 20px; margin: 0 0 12px 0;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="font-size: 20px; font-weight: 700; color: #92400E;">
                            {{ number_format($batch->sum('points')) }} pts
                        </td>
                        <td style="font-size: 13px; color: #78350F; text-align: right;">
                            Expires: {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}
                        </td>
                    </tr>
                </table>
            </div>
            @endforeach
        </div>
        @endif
        
        <!-- Ways to Use Points -->
        <div style="background: #ECFDF5; border-left: 3px solid #10B981; padding: 20px 24px; margin: 0 0 24px 0;">
            <p style="margin: 0 0 16px 0; font-size: 14px; font-weight: 600; color: #065F46;">Ways to Use Your Points Before They Expire</p>
            
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 24px; padding: 0 0 12px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 18px; height: 18px; background: #10B981; color: white; text-align: center; line-height: 18px; font-size: 12px; font-weight: 700; border-radius: 50%;">✓</span>
                    </td>
                    <td style="padding: 0 0 12px 12px; font-size: 14px; color: #065F46; line-height: 1.6;">
                        Redeem points for instant discounts on active service requests
                    </td>
                </tr>
                <tr>
                    <td style="width: 24px; padding: 0 0 12px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 18px; height: 18px; background: #10B981; color: white; text-align: center; line-height: 18px; font-size: 12px; font-weight: 700; border-radius: 50%;">✓</span>
                    </td>
                    <td style="padding: 0 0 12px 12px; font-size: 14px; color: #065F46; line-height: 1.6;">
                        Apply points to upcoming payments (minimum 100 points)
                    </td>
                </tr>
                <tr>
                    <td style="width: 24px; padding: 0 0 12px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 18px; height: 18px; background: #10B981; color: white; text-align: center; line-height: 18px; font-size: 12px; font-weight: 700; border-radius: 50%;">✓</span>
                    </td>
                    <td style="padding: 0 0 12px 12px; font-size: 14px; color: #065F46; line-height: 1.6;">
                        Convert to discount coupons for future use
                    </td>
                </tr>
                <tr>
                    <td style="width: 24px; padding: 0; vertical-align: top;">
                        <span style="display: inline-block; width: 18px; height: 18px; background: #10B981; color: white; text-align: center; line-height: 18px; font-size: 12px; font-weight: 700; border-radius: 50%;">✓</span>
                    </td>
                    <td style="padding: 0 0 0 12px; font-size: 14px; color: #065F46; line-height: 1.6;">
                        Save up to 50% of your service costs with points
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- CTA Button -->
        <div style="text-align: center; margin: 0 0 24px 0;">
            <a href="{{ route('client.loyalty.dashboard') }}" style="display: inline-block; background: #EA580C; color: white; padding: 14px 32px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0;">Redeem Points Now</a>
        </div>
        
        <!-- Important Warning -->
        <div style="background: #FEF2F2; border-left: 3px solid #EF4444; padding: 16px 20px; margin: 0 0 24px 0;">
            <p style="margin: 0; font-size: 14px; color: #7F1D1D; line-height: 1.7;">
                <strong style="color: #991B1B;">Important:</strong> Once points expire, they cannot be recovered. Make sure to use them before {{ $expiryDate->format('F d, Y') }}!
            </p>
        </div>
        
        <!-- Support Section -->
        <div style="padding: 24px 0 0 0; border-top: 1px solid #E5E7EB; text-align: center;">
            <p style="margin: 0 0 12px 0; font-size: 14px; font-weight: 600; color: #1F2937;">Have questions about your points?</p>
            <p style="margin: 0 0 16px 0; font-size: 13px; color: #6B7280; line-height: 1.6;">Visit your loyalty dashboard to view all your points, transactions, and redemption options.</p>
            <p style="margin: 0; font-size: 13px;">
                <a href="{{ route('client.loyalty.transactions') }}" style="color: #EA580C; text-decoration: none; font-weight: 500;">View Transaction History</a> |
                <a href="{{ route('client.loyalty.dashboard') }}" style="color: #EA580C; text-decoration: none; font-weight: 500;">Loyalty Dashboard</a>
            </p>
        </div>
    </div>
    
    <!-- Footer -->
    <div style="background: #F9FAFB; border-top: 1px solid #E5E7EB; padding: 24px 30px; text-align: center;">
        <p style="margin: 0 0 8px 0; font-size: 13px; color: #6B7280;">&copy; {{ date('Y') }} TREIS ADIUTOR. All rights reserved.</p>
        <p style="margin: 0; font-size: 12px; color: #9CA3AF;">This is an automated reminder. Please do not reply to this message.</p>
    </div>
</div>
@endsection
