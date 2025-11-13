<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Completed - TREIS ADIUTOR</title>
</head>
<body style="margin: 0; padding: 0; background-color: #F8FAFC; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; line-height: 1.6; color: #334155;">
    
    <!-- Email Container -->
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #F8FAFC;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                
                <!-- Main Content Card -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="max-width: 600px; background-color: #FFFFFF; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); overflow: hidden;">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #10B981; padding: 48px 40px; text-align: center;">
                            <h1 style="margin: 0 0 8px; font-family: 'Inter', sans-serif; font-size: 24px; font-weight: 700; color: #FFFFFF; letter-spacing: -0.01em;">
                                TREIS ADIUTOR
                            </h1>
                        </td>
                    </tr>
                    
                    <!-- Success Icon -->
                    <tr>
                        <td style="padding: 40px 40px 0; text-align: center;">
                            <div style="display: inline-block; background-color: #D1FAE5; border-radius: 50%; width: 80px; height: 80px; line-height: 80px;">
                                <span style="font-size: 40px;">🎉</span>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Message -->
                    <tr>
                        <td style="padding: 24px 40px 32px; text-align: center;">
                            <h2 style="margin: 0 0 12px; font-family: 'Inter', sans-serif; font-size: 20px; font-weight: 600; color: #0F172A; letter-spacing: -0.01em;">
                                Payment Successfully Completed!
                            </h2>
                            <p style="margin: 0; font-size: 15px; color: #64748B; line-height: 1.6;">
                                Your payment has been processed and sent. You should receive it within the expected timeframe.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <div style="height: 1px; background-color: #E2E8F0;"></div>
                        </td>
                    </tr>
                    
                    <!-- Payment Details -->
                    <tr>
                        <td style="padding: 32px 40px;">
                            <h3 style="margin: 0 0 20px; font-size: 14px; font-weight: 600; color: #0F172A; text-transform: uppercase; letter-spacing: 0.05em;">
                                Payment Summary
                            </h3>
                            
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ECFDF5; border: 1px solid #A7F3D0; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px 24px;">
                                        <div style="margin-bottom: 16px;">
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Payout Number
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                {{ $payout->payout_number }}
                                            </div>
                                        </div>
                                        
                                        <div style="margin-bottom: 16px;">
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Amount Paid
                                            </label>
                                            <div style="font-size: 28px; color: #10B981; font-weight: 700;">
                                                ₱{{ number_format($payout->amount, 2) }}
                                            </div>
                                        </div>
                                        
                                        <div style="margin-bottom: 16px;">
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Period
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                {{ \Carbon\Carbon::parse($payout->period_start)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($payout->period_end)->format('M d, Y') }}
                                            </div>
                                        </div>
                                        
                                        <div style="margin-bottom: 16px;">
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Payment Method
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600; text-transform: capitalize;">
                                                {{ $payout->payment_method }}
                                            </div>
                                        </div>
                                        
                                        @if($payout->reference_number)
                                        <div style="margin-bottom: 16px;">
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Reference Number
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600; font-family: monospace;">
                                                {{ $payout->reference_number }}
                                            </div>
                                        </div>
                                        @endif
                                        
                                        <div style="margin-bottom: 16px;">
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Processed By
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                {{ $processedBy->fullName }}
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Payment Date
                                            </label>
                                            <div style="font-size: 15px; color: #0F172A; font-weight: 600;">
                                                {{ \Carbon\Carbon::parse($payout->paid_at)->format('M d, Y h:i A') }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Time Entries Breakdown -->
                            <div style="margin-top: 20px;">
                                <h4 style="margin: 0 0 12px; font-size: 13px; font-weight: 600; color: #0F172A; text-transform: uppercase; letter-spacing: 0.05em;">
                                    Earnings Breakdown
                                </h4>
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px;">
                                    <tr style="background-color: #F1F5F9;">
                                        <td style="padding: 10px 16px; font-size: 11px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em;">
                                            Description
                                        </td>
                                        <td style="padding: 10px 16px; font-size: 11px; font-weight: 600; color: #64748B; text-align: right; text-transform: uppercase; letter-spacing: 0.05em;">
                                            Hours
                                        </td>
                                        <td style="padding: 10px 16px; font-size: 11px; font-weight: 600; color: #64748B; text-align: right; text-transform: uppercase; letter-spacing: 0.05em;">
                                            Amount
                                        </td>
                                    </tr>
                                    @foreach($payout->items as $item)
                                    <tr style="border-top: 1px solid #E2E8F0;">
                                        <td style="padding: 12px 16px; font-size: 13px; color: #0F172A;">
                                            {{ $item->description }}
                                        </td>
                                        <td style="padding: 12px 16px; font-size: 13px; color: #64748B; text-align: right;">
                                            {{ number_format($item->hours, 2) }}
                                        </td>
                                        <td style="padding: 12px 16px; font-size: 13px; color: #0F172A; font-weight: 600; text-align: right;">
                                            ₱{{ number_format($item->amount, 2) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr style="border-top: 2px solid #E2E8F0; background-color: #F1F5F9;">
                                        <td style="padding: 12px 16px; font-size: 14px; font-weight: 600; color: #0F172A;">
                                            Total
                                        </td>
                                        <td style="padding: 12px 16px; font-size: 14px; font-weight: 600; color: #0F172A; text-align: right;">
                                            {{ number_format($payout->items->sum('hours'), 2) }}
                                        </td>
                                        <td style="padding: 12px 16px; font-size: 16px; font-weight: 700; color: #10B981; text-align: right;">
                                            ₱{{ number_format($payout->amount, 2) }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <div style="height: 1px; background-color: #E2E8F0;"></div>
                        </td>
                    </tr>
                    
                    <!-- CTA Button -->
                    <tr>
                        <td style="padding: 32px 40px; text-align: center;">
                            <a href="{{ route('adiutor.earnings.payouts.show', $payout->id) }}" style="display: inline-block; background-color: #10B981; color: #FFFFFF; text-decoration: none; padding: 14px 32px; border-radius: 6px; font-weight: 500; font-size: 14px; letter-spacing: 0.01em; transition: background-color 0.2s; margin-right: 8px;">
                                View Payment Receipt
                            </a>
                            <a href="{{ route('adiutor.earnings.index') }}" style="display: inline-block; background-color: #64748B; color: #FFFFFF; text-decoration: none; padding: 14px 32px; border-radius: 6px; font-weight: 500; font-size: 14px; letter-spacing: 0.01em; transition: background-color 0.2s;">
                                View Earnings Dashboard
                            </a>
                        </td>
                    </tr>
                    
                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <div style="height: 1px; background-color: #E2E8F0;"></div>
                        </td>
                    </tr>
                    
                    <!-- Important Information -->
                    <tr>
                        <td style="padding: 32px 40px 40px;">
                            <h3 style="margin: 0 0 20px; font-size: 14px; font-weight: 600; color: #0F172A; text-transform: uppercase; letter-spacing: 0.05em;">
                                Important Information
                            </h3>
                            
                            <div style="background-color: #FEF3C7; border-left: 3px solid #F59E0B; padding: 16px 20px; border-radius: 4px; margin-bottom: 16px;">
                                <p style="margin: 0; font-size: 13px; color: #92400E; line-height: 1.5;">
                                    <strong style="font-weight: 600;">Processing Time:</strong> Depending on your payment method, it may take 1-3 business days for the funds to appear in your account.
                                </p>
                            </div>
                            
                            <div style="background-color: #EEF2FF; border-left: 3px solid #3B82F6; padding: 16px 20px; border-radius: 4px;">
                                <p style="margin: 0; font-size: 13px; color: #1E40AF; line-height: 1.5;">
                                    <strong style="font-weight: 600;">Keep This Email:</strong> Save this confirmation email for your records. The reference number may be needed for any payment inquiries.
                                </p>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #F8FAFC; padding: 32px 40px; text-align: center; border-top: 1px solid #E2E8F0;">
                            <p style="margin: 0; font-size: 12px; color: #94A3B8; line-height: 1.6;">
                                Questions about your payment? Contact us at<br>
                                <a href="mailto:finance@treisadiutor.com" style="color: #10B981; text-decoration: none;">finance@treisadiutor.com</a>
                            </p>
                            
                            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #E2E8F0;">
                                <p style="margin: 0; font-size: 11px; color: #CBD5E1; letter-spacing: 0.05em;">
                                    © 2025 TREIS ADIUTOR. All rights reserved.
                                </p>
                            </div>
                        </td>
                    </tr>
                    
                </table>
                
            </td>
        </tr>
    </table>
    
</body>
</html>
