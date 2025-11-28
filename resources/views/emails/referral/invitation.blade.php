@extends('layouts.email')

@section('content')
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1F2937; max-width: 600px; margin: 0 auto; background: white;">
    
    <!-- Header -->
    <div style="background: #3B82F6; padding: 40px 30px; text-align: center;">
        <h1 style="margin: 0 0 4px 0; font-size: 24px; font-weight: 600; color: white; letter-spacing: -0.5px;">You're Invited</h1>
        <p style="margin: 0; font-size: 14px; color: #DBEAFE; font-weight: 500;">Someone special wants you to join TREIS ADIUTOR</p>
    </div>
    
    <!-- Main Content -->
    <div style="padding: 40px 30px;">
        
        <!-- Invitation From Box -->
        <div style="background: #3B82F6; color: white; padding: 28px; text-align: center; margin: 0 0 24px 0;">
            <p style="margin: 0 0 8px 0; font-size: 12px; color: #DBEAFE; text-transform: uppercase; letter-spacing: 0.5px;">Invitation From</p>
            <p style="margin: 0; font-size: 28px; font-weight: 700; color: white; line-height: 1;">{{ $referrer->fullName }}</p>
            <p style="margin: 8px 0 0 0; font-size: 14px; color: #DBEAFE;">{{ $referrer->email }}</p>
        </div>
        
        @if($personalMessage)
        <!-- Personal Message -->
        <div style="background: #F9FAFB; border-left: 3px solid #3B82F6; padding: 20px 24px; margin: 0 0 24px 0;">
            <p style="margin: 0 0 8px 0; font-size: 13px; font-weight: 600; color: #1F2937;">Personal Message</p>
            <p style="margin: 0; font-size: 14px; color: #4B5563; line-height: 1.7; font-style: italic;">{{ $personalMessage }}</p>
        </div>
        @endif
        
        <!-- Welcome Rewards Section -->
        <div style="margin: 0 0 24px 0;">
            <p style="margin: 0 0 16px 0; font-size: 18px; font-weight: 600; color: #1F2937; text-align: center;">Your Welcome Rewards</p>
            
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 50%; padding: 0 6px 0 0; vertical-align: top;">
                        <div style="background: #10B981; color: white; padding: 28px; text-align: center;">
                            <p style="margin: 0 0 12px 0; font-size: 32px; font-weight: 700; line-height: 1;">{{ number_format($referredBonus) }}</p>
                            <p style="margin: 0; font-size: 14px; color: #D1FAE5;">Bonus Points</p>
                        </div>
                    </td>
                    <td style="width: 50%; padding: 0 0 0 6px; vertical-align: top;">
                        <div style="background: #F59E0B; color: white; padding: 28px; text-align: center;">
                            <p style="margin: 0 0 12px 0; font-size: 32px; font-weight: 700; line-height: 1;">{{ $referredDiscount }}%</p>
                            <p style="margin: 0; font-size: 14px; color: #FEF3C7;">Discount Coupon</p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Why Join Section -->
        <div style="background: #EFF6FF; border: 1px solid #BFDBFE; padding: 24px; margin: 0 0 24px 0;">
            <p style="margin: 0 0 16px 0; font-size: 16px; font-weight: 600; color: #1E40AF;">Why Join TREIS ADIUTOR?</p>
            
            <div style="font-size: 14px; color: #1F2937; line-height: 1.7;">
                <p style="margin: 0 0 12px 0;"><strong style="color: #1E40AF;">Professional Services:</strong> Access expert adiutors for your projects</p>
                <p style="margin: 0 0 12px 0;"><strong style="color: #1E40AF;">Loyalty Rewards:</strong> Earn points with every transaction</p>
                <p style="margin: 0 0 12px 0;"><strong style="color: #1E40AF;">Quality Guaranteed:</strong> All work is reviewed and verified</p>
                <p style="margin: 0;"><strong style="color: #1E40AF;">Secure Payments:</strong> Safe and reliable payment processing</p>
            </div>
        </div>
        
        <!-- CTA Button -->
        <div style="text-align: center; margin: 0 0 12px 0;">
            <a href="{{ $referralUrl }}" style="display: inline-block; background: #3B82F6; color: white; padding: 14px 40px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 0;">Join Now & Claim Your Rewards</a>
        </div>
        <p style="margin: 0 0 24px 0; text-align: center; font-size: 13px; color: #6B7280;">Click the button above to sign up with {{ $referrer->firstName }}'s referral code</p>
        
        <!-- Referral Code Box -->
        <div style="background: #F3F4F6; border: 2px dashed #9CA3AF; padding: 24px; text-align: center; margin: 0 0 24px 0;">
            <p style="margin: 0 0 8px 0; font-size: 12px; color: #6B7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Your Referral Code</p>
            <p style="margin: 0 0 12px 0; font-family: 'Courier New', monospace; font-size: 28px; font-weight: 700; color: #1F2937; letter-spacing: 2px; line-height: 1;">{{ $referralCode->code }}</p>
            <p style="margin: 0; font-size: 12px; color: #6B7280;">Use this code during registration to claim your rewards</p>
        </div>
        
        <!-- How It Works Section -->
        <div style="background: #FEF3C7; border-left: 3px solid #F59E0B; padding: 20px 24px; margin: 0 0 24px 0;">
            <p style="margin: 0 0 16px 0; font-size: 14px; font-weight: 600; color: #92400E;">How It Works</p>
            
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 28px; padding: 0 0 12px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 24px; height: 24px; background: #F59E0B; color: white; text-align: center; line-height: 24px; font-size: 12px; font-weight: 600; border-radius: 50%;">1</span>
                    </td>
                    <td style="padding: 0 0 12px 12px; vertical-align: top;">
                        <p style="margin: 0; font-size: 14px; color: #78350F; line-height: 1.6;">Click the button above to sign up with code <strong>{{ $referralCode->code }}</strong></p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 28px; padding: 0 0 12px 0; vertical-align: top;">
                        <span style="display: inline-block; width: 24px; height: 24px; background: #F59E0B; color: white; text-align: center; line-height: 24px; font-size: 12px; font-weight: 600; border-radius: 50%;">2</span>
                    </td>
                    <td style="padding: 0 0 12px 12px; vertical-align: top;">
                        <p style="margin: 0; font-size: 14px; color: #78350F; line-height: 1.6;">Your <strong>{{ number_format($referredBonus) }} bonus points</strong> and <strong>{{ $referredDiscount }}% discount coupon</strong> are instantly added</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 28px; padding: 0; vertical-align: top;">
                        <span style="display: inline-block; width: 24px; height: 24px; background: #F59E0B; color: white; text-align: center; line-height: 24px; font-size: 12px; font-weight: 600; border-radius: 50%;">3</span>
                    </td>
                    <td style="padding: 0 0 0 12px; vertical-align: top;">
                        <p style="margin: 0; font-size: 14px; color: #78350F; line-height: 1.6;">Start using our services and enjoy your rewards</p>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Limited Time Offer Box -->
        <div style="background: #ECFDF5; border: 1px solid #10B981; padding: 24px; text-align: center; margin: 0 0 24px 0;">
            <p style="margin: 0 0 8px 0; font-size: 16px; font-weight: 600; color: #065F46;">Limited Time Offer</p>
            <p style="margin: 0; font-size: 14px; color: #047857; line-height: 1.7;">Sign up today to claim your welcome rewards. Your friend {{ $referrer->firstName }} will also earn points when you complete your first payment!</p>
        </div>
        
        <!-- Footer Links -->
        <div style="padding: 24px 0 0 0; border-top: 1px solid #E5E7EB; text-align: center;">
            <p style="margin: 0 0 12px 0;">
                <a href="{{ $referralUrl }}" style="color: #3B82F6; text-decoration: none; font-weight: 600; font-size: 13px;">Join TREIS ADIUTOR Now</a>
            </p>
            <p style="margin: 0; font-size: 12px; color: #9CA3AF; line-height: 1.7;">
                This invitation was sent by {{ $referrer->fullName }}. If you don't want to receive invitations,<br>
                you can safely ignore this email.
            </p>
        </div>
    </div>
    
    <!-- Footer -->
    <div style="background: #F9FAFB; border-top: 1px solid #E5E7EB; padding: 24px 30px; text-align: center;">
        <p style="margin: 0; font-size: 13px; color: #6B7280;">&copy; {{ date('Y') }} TREIS ADIUTOR. All rights reserved.</p>
    </div>
</div>
@endsection
