<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Change Decision - TREIS ADIUTOR</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
</head>
<body style="margin: 0; padding: 0; background: linear-gradient(135deg, #F0F9FF 0%, #E0F2FE 50%, #BAE6FD 100%); font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; line-height: 1.6; color: #334155;">
    
    <!-- Email Container -->
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background: linear-gradient(135deg, #F0F9FF 0%, #E0F2FE 50%, #BAE6FD 100%); min-height: 100vh;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                
                <!-- Main Content Card -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="max-width: 600px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border-radius: 24px; box-shadow: 0 20px 40px rgba(59, 130, 246, 0.08), 0 8px 32px rgba(0, 0, 0, 0.03); border: 1px solid rgba(241, 245, 249, 0.9); overflow: hidden;">
                    
                    <!-- Header with Dynamic Gradient -->
                    <tr>
                        <td style="background: linear-gradient(135deg, {{ $request->status === 'approved' ? '#10B981, #059669' : '#EF4444, #DC2626' }}); padding: 40px 40px 60px; text-align: center; position: relative;">
                            <!-- Decorative Elements -->
                            <div style="position: absolute; top: -50px; right: -50px; width: 100px; height: 100px; background: rgba(255, 255, 255, 0.1); border-radius: 50%; filter: blur(40px);"></div>
                            <div style="position: absolute; bottom: -30px; left: -30px; width: 60px; height: 60px; background: rgba(6, 182, 212, 0.3); border-radius: 50%; filter: blur(30px);"></div>
                            
                            <!-- Status Icon -->
                            <div style="display: inline-block; width: 80px; height: 80px; background: rgba(255, 255, 255, 0.2); border-radius: 50%; margin-bottom: 24px; line-height: 80px; font-size: 40px;">
                                {{ $request->status === 'approved' ? '✅' : '❌' }}
                            </div>
                            
                            <!-- Logo/Brand -->
                            <h1 style="margin: 0 0 8px; font-family: 'Playfair Display', Georgia, serif; font-size: 32px; font-weight: 700; color: #FFFFFF; letter-spacing: -0.02em; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                TREIS ADIUTOR
                            </h1>
                            <p style="margin: 0; color: rgba(255, 255, 255, 0.9); font-size: 16px; font-weight: 500;">Billing Department</p>
                        </td>
                    </tr>
                    
                    <!-- Decision Message -->
                    <tr>
                        <td style="padding: 0 40px 32px; text-align: center;">
                            <div style="margin-top: -30px; background: #FFFFFF; border-radius: 16px; padding: 32px; box-shadow: 0 8px 25px rgba({{ $request->status === 'approved' ? '16, 185, 129' : '239, 68, 68' }}, 0.08); border: 1px solid #F1F5F9;">
                                <h2 style="margin: 0 0 16px; font-family: 'Playfair Display', Georgia, serif; font-size: 28px; font-weight: 700; color: #1E293B; letter-spacing: -0.02em;">
                                    Budget Change {{ $request->status === 'approved' ? 'Approved' : 'Rejected' }}
                                </h2>
                                <p style="margin: 0; font-size: 16px; color: #64748B; line-height: 1.6;">
                                    Your budget change request for <strong style="color: #1E293B;">"{{ $request->project->title ?? $request->project->serviceRequest->project_name }}"</strong> has been 
                                    <strong style="color: {{ $request->status === 'approved' ? '#059669' : '#DC2626' }};">{{ $request->status === 'approved' ? 'approved' : 'rejected' }}</strong>.
                                </p>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Decision Details Section -->
                    <tr>
                        <td style="padding: 0 40px 32px;">
                            <div style="background: linear-gradient(135deg, {{ $request->status === 'approved' ? '#ECFDF5 0%, #D1FAE5 100%' : '#FEF2F2 0%, #FECACA 100%' }}); border-radius: 16px; padding: 32px; border: 1px solid {{ $request->status === 'approved' ? '#BBF7D0' : '#FECACA' }}; position: relative; overflow: hidden;">
                                <!-- Decorative accent -->
                                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, {{ $request->status === 'approved' ? '#10B981, #059669' : '#EF4444, #DC2626' }});"></div>
                                
                                <h3 style="margin: 0 0 24px; font-family: 'Playfair Display', Georgia, serif; font-size: 22px; font-weight: 600; color: #1E293B; display: flex; align-items: center;">
                                    <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, {{ $request->status === 'approved' ? '#10B981, #059669' : '#EF4444, #DC2626' }}); border-radius: 8px; margin-right: 12px; position: relative;">
                                        <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #FFFFFF; font-size: 16px;">💰</span>
                                    </span>
                                    Decision Summary
                                </h3>
                                
                                <div style="background: #FFFFFF; border-radius: 12px; padding: 24px; border: 1px solid #E2E8F0; margin-bottom: 20px;">
                                    <div style="display: table; width: 100%;">
                                        <div style="display: table-row;">
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 140px;">
                                                <label style="font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Project</label>
                                            </div>
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top;">
                                                <span style="font-size: 18px; font-weight: 700; color: #1E293B;">{{ $request->project->title ?? $request->project->serviceRequest->project_name }}</span>
                                            </div>
                                        </div>
                                        
                                        <div style="display: table-row;">
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 140px;">
                                                <label style="font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Decision</label>
                                            </div>
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top;">
                                                @if($request->status === 'approved')
                                                    <span style="background: linear-gradient(135deg, #DCFCE7, #BBF7D0); color: #047857; padding: 6px 12px; border-radius: 20px; font-size: 14px; font-weight: 500; border: 1px solid #BBF7D0;">✅ Approved</span>
                                                @else
                                                    <span style="background: linear-gradient(135deg, #FEE2E2, #FECACA); color: #B91C1C; padding: 6px 12px; border-radius: 20px; font-size: 14px; font-weight: 500; border: 1px solid #FECACA;">❌ Rejected</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div style="display: table-row;">
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 140px;">
                                                <label style="font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">{{ $request->status === 'approved' ? 'New Budget' : 'Original Budget' }}</label>
                                            </div>
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top;">
                                                <span style="font-size: 18px; color: #1E293B; font-weight: 700;">₱{{ number_format($request->status === 'approved' ? $request->requestedBudget : $request->currentBudget, 2) }}</span>
                                            </div>
                                        </div>
                                        
                                        @if($request->status === 'approved')
                                        <div style="display: table-row;">
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 140px;">
                                                <label style="font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Change Amount</label>
                                            </div>
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top;">
                                                @php
                                                    $changeAmount = $request->requestedBudget - $request->currentBudget;
                                                    $isIncrease = $changeAmount > 0;
                                                @endphp
                                                <span style="font-size: 16px; color: {{ $isIncrease ? '#DC2626' : '#059669' }}; font-weight: 600;">
                                                    {{ $isIncrease ? '+' : '' }}₱{{ number_format($changeAmount, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        <div style="display: table-row;">
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 140px;">
                                                <label style="font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Reviewed By</label>
                                            </div>
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top;">
                                                <span style="font-size: 16px; color: #1E293B; font-weight: 500;">{{ $reviewedBy->name }}</span>
                                            </div>
                                        </div>
                                        
                                        <div style="display: table-row;">
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 140px;">
                                                <label style="font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Decision Date</label>
                                            </div>
                                            <div style="display: table-cell; padding: 12px 0; vertical-align: top;">
                                                <span style="font-size: 16px; color: #1E293B; font-weight: 500;">{{ $request->updated_at->format('M d, Y H:i') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($request->review_notes)
                                <div style="background: #FFFFFF; border-radius: 12px; padding: 20px; border: 1px solid #E2E8F0;">
                                    <h4 style="margin: 0 0 12px; font-size: 16px; font-weight: 600; color: #1E293B;">{{ $request->status === 'approved' ? 'Approval' : 'Rejection' }} Notes:</h4>
                                    <p style="margin: 0; font-size: 14px; color: #475569; line-height: 1.6; white-space: pre-line;">{{ $request->review_notes }}</p>
                                </div>
                                @endif
                                
                                <div style="background: {{ $request->status === 'approved' ? '#DCFCE7' : '#FEE2E2' }}; border: 1px solid {{ $request->status === 'approved' ? '#BBF7D0' : '#FECACA' }}; border-radius: 8px; padding: 16px; margin-top: 16px;">
                                    <p style="margin: 0; font-size: 14px; color: {{ $request->status === 'approved' ? '#065F46' : '#B91C1C' }}; display: flex; align-items: flex-start;">
                                        <span style="display: inline-block; margin-right: 8px; margin-top: 2px;">{{ $request->status === 'approved' ? '🎉' : '📞' }}</span>
                                        <span>
                                            <strong>{{ $request->status === 'approved' ? 'Great News!' : 'Need to Discuss?' }}</strong> 
                                            {{ $request->status === 'approved' 
                                                ? 'Your budget change has been approved and will be reflected in your next invoice.' 
                                                : 'Please contact our team to discuss alternative solutions for your project needs.' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- CTA Button -->
                    <tr>
                        <td style="padding: 0 40px 32px; text-align: center;">
                            @if($request->status === 'approved')
                                <a href="{{ route('client.billing') }}" style="display: inline-block; background: linear-gradient(135deg, #10B981, #059669); color: #FFFFFF; text-decoration: none; padding: 16px 32px; border-radius: 12px; font-weight: 600; font-size: 16px; letter-spacing: 0.01em; box-shadow: 0 8px 20px rgba(16, 185, 129, 0.25), 0 4px 8px rgba(16, 185, 129, 0.1); transition: all 0.3s ease; border: none; cursor: pointer;">
                                    <span style="display: inline-block; margin-right: 8px;">💳</span>
                                    View Updated Billing
                                </a>
                            @else
                                <a href="{{ route('client.contact') }}" style="display: inline-block; background: linear-gradient(135deg, #3B82F6, #2563EB); color: #FFFFFF; text-decoration: none; padding: 16px 32px; border-radius: 12px; font-weight: 600; font-size: 16px; letter-spacing: 0.01em; box-shadow: 0 8px 20px rgba(59, 130, 246, 0.25), 0 4px 8px rgba(59, 130, 246, 0.1); transition: all 0.3s ease; border: none; cursor: pointer;">
                                    <span style="display: inline-block; margin-right: 8px;">💬</span>
                                    Contact Our Team
                                </a>
                            @endif
                        </td>
                    </tr>
                    
                    <!-- Next Steps Section -->
                    <tr>
                        <td style="padding: 0 40px 40px;">
                            <div style="background: #FFFFFF; border-radius: 16px; padding: 32px; border: 1px solid #E2E8F0;">
                                <h3 style="margin: 0 0 24px; font-family: 'Playfair Display', Georgia, serif; font-size: 20px; font-weight: 600; color: #1E293B; text-align: center;">
                                    {{ $request->status === 'approved' ? 'What Happens Next?' : 'Alternative Options' }}
                                </h3>
                                
                                @if($request->status === 'approved')
                                <div style="display: table; width: 100%;">
                                    <div style="display: table-row;">
                                        <div style="display: table-cell; padding: 16px 0; vertical-align: top; width: 40px;">
                                            <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, #3B82F6, #2563EB); border-radius: 8px; text-align: center; line-height: 32px; color: #FFFFFF; font-size: 16px;">📋</span>
                                        </div>
                                        <div style="display: table-cell; padding: 16px 0 16px 16px; vertical-align: top;">
                                            <strong style="color: #1E293B; font-weight: 600;">Contract Update</strong>
                                            <br><span style="color: #64748B; font-size: 14px;">We'll update your project contract to reflect the new budget allocation</span>
                                        </div>
                                    </div>
                                    
                                    <div style="display: table-row;">
                                        <div style="display: table-cell; padding: 16px 0; vertical-align: top; width: 40px;">
                                            <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, #10B981, #059669); border-radius: 8px; text-align: center; line-height: 32px; color: #FFFFFF; font-size: 16px;">💳</span>
                                        </div>
                                        <div style="display: table-cell; padding: 16px 0 16px 16px; vertical-align: top;">
                                            <strong style="color: #1E293B; font-weight: 600;">Billing Adjustment</strong>
                                            <br><span style="color: #64748B; font-size: 14px;">The budget change will be reflected in your next invoice</span>
                                        </div>
                                    </div>
                                    
                                    <div style="display: table-row;">
                                        <div style="display: table-cell; padding: 16px 0; vertical-align: top; width: 40px;">
                                            <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, #8B5CF6, #7C3AED); border-radius: 8px; text-align: center; line-height: 32px; color: #FFFFFF; font-size: 16px;">🚀</span>
                                        </div>
                                        <div style="display: table-cell; padding: 16px 0 16px 16px; vertical-align: top;">
                                            <strong style="color: #1E293B; font-weight: 600;">Project Enhancement</strong>
                                            <br><span style="color: #64748B; font-size: 14px;">We can now proceed with the additional features or improvements</span>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div style="display: table; width: 100%;">
                                    <div style="display: table-row;">
                                        <div style="display: table-cell; padding: 16px 0; vertical-align: top; width: 40px;">
                                            <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, #3B82F6, #2563EB); border-radius: 8px; text-align: center; line-height: 32px; color: #FFFFFF; font-size: 16px;">💬</span>
                                        </div>
                                        <div style="display: table-cell; padding: 16px 0 16px 16px; vertical-align: top;">
                                            <strong style="color: #1E293B; font-weight: 600;">Discuss Alternatives</strong>
                                            <br><span style="color: #64748B; font-size: 14px;">Let's explore alternative approaches that fit within your budget</span>
                                        </div>
                                    </div>
                                    
                                    <div style="display: table-row;">
                                        <div style="display: table-cell; padding: 16px 0; vertical-align: top; width: 40px;">
                                            <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, #10B981, #059669); border-radius: 8px; text-align: center; line-height: 32px; color: #FFFFFF; font-size: 16px;">🔄</span>
                                        </div>
                                        <div style="display: table-cell; padding: 16px 0 16px 16px; vertical-align: top;">
                                            <strong style="color: #1E293B; font-weight: 600;">Revise Scope</strong>
                                            <br><span style="color: #64748B; font-size: 14px;">We can adjust the project scope to match your approved budget</span>
                                        </div>
                                    </div>
                                    
                                    <div style="display: table-row;">
                                        <div style="display: table-cell; padding: 16px 0; vertical-align: top; width: 40px;">
                                            <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, #8B5CF6, #7C3AED); border-radius: 8px; text-align: center; line-height: 32px; color: #FFFFFF; font-size: 16px;">📅</span>
                                        </div>
                                        <div style="display: table-cell; padding: 16px 0 16px 16px; vertical-align: top;">
                                            <strong style="color: #1E293B; font-weight: 600;">Phased Approach</strong>
                                            <br><span style="color: #64748B; font-size: 14px;">Consider breaking the project into phases to spread costs over time</span>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                                <div style="background: {{ $request->status === 'approved' ? '#EFF6FF' : '#FEF7F0' }}; border: 1px solid {{ $request->status === 'approved' ? '#BFDBFE' : '#FED7AA' }}; border-radius: 8px; padding: 16px; margin-top: 24px;">
                                    <p style="margin: 0; font-size: 14px; color: {{ $request->status === 'approved' ? '#1D4ED8' : '#9A3412' }}; display: flex; align-items: flex-start;">
                                        <span style="display: inline-block; margin-right: 8px; margin-top: 2px;">{{ $request->status === 'approved' ? '🎯' : '🤝' }}</span>
                                        <span>
                                            <strong>{{ $request->status === 'approved' ? 'Moving Forward!' : 'Let\'s Find Solutions!' }}</strong> 
                                            {{ $request->status === 'approved' 
                                                ? 'We\'re excited to deliver even better results with your approved budget enhancement.' 
                                                : 'Our team is committed to finding the best solution that meets your needs and budget.' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%); padding: 32px 40px; text-align: center; border-top: 1px solid #E2E8F0;">
                            <p style="margin: 0 0 16px; font-size: 16px; color: #475569;">
                                <strong>{{ $request->status === 'approved' ? 'Celebrating progress with you,' : 'Working together toward solutions,' }}</strong><br>
                                <span style="background: linear-gradient(135deg, {{ $request->status === 'approved' ? '#10B981, #059669' : '#3B82F6, #2563EB' }}); background-clip: text; -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 600;">{{ config('app.name') }} Billing Team</span>
                            </p>
                            
                            <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #E2E8F0;">
                                <p style="margin: 0; font-size: 12px; color: #94A3B8; line-height: 1.5;">
                                    Budget change decision for "{{ $request->project->title ?? $request->project->serviceRequest->project_name }}". 
                                    <br>{{ $request->status === 'approved' ? 'Thank you for your trust in our services!' : 'We appreciate your understanding and look forward to finding the perfect solution.' }}
                                    <br><br>
                                    <span style="color: #64748B;">Digital Innovation • Intelligence • Excellence</span>
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