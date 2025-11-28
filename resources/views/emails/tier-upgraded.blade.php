@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #3B82F6; padding: 40px 30px; text-align: center;">
        <div style="font-size: 48px; margin-bottom: 12px;">🏆</div>
        <h1 style="margin: 0 0 8px 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">Tier Upgraded!</h1>
        <p style="margin: 0; font-size: 15px; color: #DBEAFE;">Congratulations on your achievement, {{ $userName }}!</p>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        
        <!-- Tier Upgrade Box -->
        <div style="background: #EFF6FF; border: 2px solid #3B82F6; padding: 32px 24px; text-align: center; margin: 0 0 32px 0;">
            <p style="margin: 0 0 20px 0; font-size: 12px; font-weight: 600; color: #2563EB; text-transform: uppercase; letter-spacing: 1px;">You've Been Upgraded To</p>
            
            <div style="display: flex; align-items: center; justify-content: center; gap: 16px; margin: 0 0 20px 0;">
                <span style="font-size: 20px; font-weight: 600; color: #6B7280; padding: 12px 20px; background: white; border: 1px solid #E5E7EB;">{{ $oldTier }}</span>
                <span style="font-size: 24px; color: #3B82F6;">→</span>
                <span style="font-size: 20px; font-weight: 600; color: #1E40AF; padding: 12px 20px; background: white; border: 2px solid #3B82F6;">{{ $newTier }}</span>
            </div>
            
            <div style="display: inline-block; background: white; border: 1px solid #BFDBFE; padding: 10px 20px; font-size: 14px; color: #1E40AF;">
                <span style="margin-right: 6px;">🌟</span>{{ number_format($totalPoints) }} lifetime points earned
            </div>
        </div>
        
        <!-- Earning Rate Highlight -->
        <div style="background: #F9FAFB; border-left: 3px solid #3B82F6; padding: 24px; margin: 0 0 32px 0;">
            <p style="margin: 0 0 8px 0; font-size: 13px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Your New Earning Rate</p>
            <p style="margin: 0 0 8px 0; font-size: 36px; font-weight: 700; color: #3B82F6; line-height: 1;">{{ $earningRate }}</p>
            <p style="margin: 0; font-size: 14px; color: #4B5563;">Earn {{ $earningRate }} on every ₱100 you spend</p>
            
            @if($discount > 0)
            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #E5E7EB;">
                <p style="margin: 0 0 4px 0; font-size: 15px; color: #1F2937; font-weight: 600;">Plus automatic {{ $discount }}% discount</p>
                <p style="margin: 0; font-size: 13px; color: #6B7280;">Applied to all your services!</p>
            </div>
            @endif
        </div>
        
        <!-- Benefits Section -->
        <div style="margin: 0 0 32px 0;">
            <div style="background: #F9FAFB; border-bottom: 2px solid #3B82F6; padding: 16px 20px;">
                <p style="margin: 0; font-size: 14px; font-weight: 600; color: #1E40AF;">Your Exclusive {{ $newTier }} Benefits</p>
            </div>
            <div style="background: white; border: 1px solid #E5E7EB; border-top: none; padding: 24px 20px;">
                <table style="width: 100%; border-collapse: collapse;">
                    @foreach($benefits as $benefit)
                    <tr>
                        <td style="padding: 10px 0; font-size: 14px; color: #4B5563; vertical-align: top; border-bottom: 1px solid #F3F4F6;">
                            <span style="color: #3B82F6; margin-right: 12px; font-weight: bold;">✓</span>{{ $benefit }}
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
        
        <!-- CTA Button -->
        <div style="text-align: center; margin: 0 0 32px 0;">
            <a href="{{ route('client.loyalty.dashboard') }}" style="display: inline-block; background: #3B82F6; color: white; padding: 14px 32px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0;">Explore Your Benefits</a>
        </div>
        
        <!-- Social Share Section -->
        <div style="background: #F9FAFB; border: 1px solid #E5E7EB; padding: 24px; text-align: center; margin: 0 0 32px 0;">
            <p style="margin: 0 0 8px 0; font-size: 15px; font-weight: 600; color: #1F2937;">
                <span style="margin-right: 6px;">🎊</span>Share your achievement!
            </p>
            <p style="margin: 0; font-size: 13px; color: #6B7280;">Tell your friends about your {{ $newTier }} status and help them earn rewards too!</p>
        </div>
        
        <!-- Footer Message -->
        <div style="padding: 24px 0; border-top: 1px solid #E5E7EB; text-align: center;">
            <p style="margin: 0 0 12px 0; font-size: 15px; font-weight: 600; color: #1F2937;">Thank you for your continued trust!</p>
            <p style="margin: 0 0 20px 0; font-size: 14px; color: #6B7280; line-height: 1.6;">Your loyalty means everything to us. Keep up the great work and enjoy your new tier benefits!</p>
            <p style="margin: 0;">
                <a href="{{ route('client.loyalty.transactions') }}" style="color: #3B82F6; text-decoration: none; font-size: 14px; font-weight: 500;">View Transaction History</a>
            </p>
        </div>
    </div>
    
    <!-- Footer -->
    <div style="background: #F9FAFB; border-top: 1px solid #E5E7EB; padding: 24px 30px; text-align: center;">
        <p style="margin: 0; font-size: 12px; color: #9CA3AF;">This is an automated email. Please do not reply to this message.</p>
        <p style="margin: 8px 0 0 0; font-size: 13px; color: #6B7280;">&copy; {{ date('Y') }} Treis Adiutor. All rights reserved.</p>
    </div>
</div>
@endsection
