<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to TREIS ADIUTOR</title>
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
<style>
    @font-face {
        font-family: 'Stereofunk';
        src: url('../../fonts/Stereofunk.ttf') format('truetype');
        font-weight: normal;
        font-style: normal;
    }
    .heading-serif {
    font-family: 'Stereofunk', serif;
    letter-spacing: -0.02em;
    font-weight: 700;
    line-height: 1.2;
    font-size: 32px; font-weight: 700; color: #FFFFFF; letter-spacing: -0.02em; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
</style>
<body style="margin: 0; padding: 0; background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 50%, #BFDBFE 100%); font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; line-height: 1.6; color: #334155;">
    
    <!-- Email Container -->
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 50%, #BFDBFE 100%); min-height: 100vh;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                
                <!-- Main Content Card -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="max-width: 600px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border-radius: 24px; box-shadow: 0 20px 40px rgba(59, 130, 246, 0.08), 0 8px 32px rgba(0, 0, 0, 0.03); border: 1px solid rgba(241, 245, 249, 0.9); overflow: hidden;">
                    
                    <!-- Header with Brand Gradient -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%); padding: 40px 40px 60px; text-align: center; position: relative;">
                            <!-- Decorative Elements -->
                            <div style="position: absolute; top: -50px; right: -50px; width: 100px; height: 100px; background: rgba(255, 255, 255, 0.1); border-radius: 50%; filter: blur(40px);"></div>
                            <div style="position: absolute; bottom: -30px; left: -30px; width: 60px; height: 60px; background: rgba(6, 182, 212, 0.3); border-radius: 50%; filter: blur(30px);"></div>
                            
                            <!-- Logo/Brand -->
                            <h1 class="heading-serif" style="margin: 0 0 16px;">
                                TREIS ADIUTOR
                            </h1>
                            <div style="width: 60px; height: 3px; background: linear-gradient(90deg, #06B6D4, #22D3EE); margin: 0 auto; border-radius: 2px;"></div>
                        </td>
                    </tr>
                    
                    <!-- Welcome Message -->
                    <tr>
                        <td style="padding: 0 40px 32px; text-align: center;">
                            <div style="margin-top: -30px; background: #FFFFFF; border-radius: 16px; padding: 32px; box-shadow: 0 8px 25px rgba(59, 130, 246, 0.08); border: 1px solid #F1F5F9;">
                                <h2 style="margin: 0 0 16px; font-family: 'Playfair Display', Georgia, serif; font-size: 28px; font-weight: 700; color: #1E293B; letter-spacing: -0.02em;">
                                    Welcome, {{ $fullName }}!
                                </h2>
                                <p style="margin: 0; font-size: 16px; color: #64748B; line-height: 1.6;">
                                    Thank you for submitting your service request. We've automatically created an account for you so you can track your request and communicate with us.
                                </p>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Credentials Section -->
                    <tr>
                        <td style="padding: 0 40px 32px;">
                            <div style="background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%); border-radius: 16px; padding: 32px; border: 1px solid #E2E8F0; position: relative; overflow: hidden;">
                                <!-- Decorative accent -->
                                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, #3B82F6, #06B6D4);"></div>
                                
                                <h3 style="margin: 0 0 24px; font-family: 'Playfair Display', Georgia, serif; font-size: 22px; font-weight: 600; color: #1E293B; display: flex; align-items: center;">
                                    <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, #3B82F6, #2563EB); border-radius: 8px; margin-right: 12px; position: relative;">
                                        <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #FFFFFF; font-size: 16px;">🔐</span>
                                    </span>
                                    Your Login Credentials
                                </h3>
                                
                                <div style="background: #FFFFFF; border-radius: 12px; padding: 24px; border: 1px solid #E2E8F0; margin-bottom: 20px;">
                                    <div style="margin-bottom: 16px;">
                                        <label style="display: block; font-size: 14px; font-weight: 600; color: #475569; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Email Address</label>
                                        <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 12px 16px; font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace; font-size: 15px; color: #1E293B; word-break: break-all;">{{ $email }}</div>
                                    </div>
                                    <div>
                                        <label style="display: block; font-size: 14px; font-weight: 600; color: #475569; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Temporary Password</label>
                                        <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 12px 16px; font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace; font-size: 15px; color: #1E293B; word-break: break-all;">{{ $password }}</div>
                                    </div>
                                </div>
                                
                                <div style="background: #FEF3C7; border: 1px solid #FDE68A; border-radius: 8px; padding: 16px; margin-bottom: 24px;">
                                    <p style="margin: 0; font-size: 14px; color: #92400E; display: flex; align-items: flex-start;">
                                        <span style="display: inline-block; margin-right: 8px; margin-top: 2px;">⚠️</span>
                                        <span><strong>Security Notice:</strong> Please keep these credentials safe and change your password after your first login for enhanced security.</span>
                                    </p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- CTA Button -->
                    <tr>
                        <td style="padding: 0 40px 32px; text-align: center;">
                            <a href="{{ url('/login') }}" style="display: inline-block; background: linear-gradient(135deg, #3B82F6, #2563EB); color: #FFFFFF; text-decoration: none; padding: 16px 32px; border-radius: 12px; font-weight: 600; font-size: 16px; letter-spacing: 0.01em; box-shadow: 0 8px 20px rgba(59, 130, 246, 0.25), 0 4px 8px rgba(59, 130, 246, 0.1); transition: all 0.3s ease; border: none; cursor: pointer;">
                                <span style="display: inline-block; margin-right: 8px;">🚀</span>
                                Login to Your Account
                            </a>
                        </td>
                    </tr>
                    
                    <!-- Features List -->
                    <tr>
                        <td style="padding: 0 40px 40px;">
                            <div style="background: #FFFFFF; border-radius: 16px; padding: 32px; border: 1px solid #E2E8F0;">
                                <h3 style="margin: 0 0 24px; font-family: 'Playfair Display', Georgia, serif; font-size: 20px; font-weight: 600; color: #1E293B; text-align: center;">
                                    What You Can Do Now
                                </h3>
                                
                                <div style="display: table; width: 100%;">
                                    <div style="display: table-row;">
                                        <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 40px;">
                                            <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, #06B6D4, #22D3EE); border-radius: 8px; text-align: center; line-height: 32px; color: #FFFFFF; font-size: 16px;">📊</span>
                                        </div>
                                        <div style="display: table-cell; padding: 12px 0 12px 16px; vertical-align: top;">
                                            <strong style="color: #1E293B; font-weight: 600;">Track your service request status</strong>
                                            <br><span style="color: #64748B; font-size: 14px;">Monitor progress and receive real-time updates</span>
                                        </div>
                                    </div>
                                    
                                    <div style="display: table-row;">
                                        <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 40px;">
                                            <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, #10B981, #059669); border-radius: 8px; text-align: center; line-height: 32px; color: #FFFFFF; font-size: 16px;">📎</span>
                                        </div>
                                        <div style="display: table-cell; padding: 12px 0 12px 16px; vertical-align: top;">
                                            <strong style="color: #1E293B; font-weight: 600;">Upload additional documents</strong>
                                            <br><span style="color: #64748B; font-size: 14px;">Share files and resources securely</span>
                                        </div>
                                    </div>
                                    
                                    <div style="display: table-row;">
                                        <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 40px;">
                                            <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, #8B5CF6, #7C3AED); border-radius: 8px; text-align: center; line-height: 32px; color: #FFFFFF; font-size: 16px;">💬</span>
                                        </div>
                                        <div style="display: table-cell; padding: 12px 0 12px 16px; vertical-align: top;">
                                            <strong style="color: #1E293B; font-weight: 600;">Communicate with your assigned Adiutor</strong>
                                            <br><span style="color: #64748B; font-size: 14px;">Direct messaging and collaboration</span>
                                        </div>
                                    </div>
                                    
                                    <div style="display: table-row;">
                                        <div style="display: table-cell; padding: 12px 0; vertical-align: top; width: 40px;">
                                            <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, #F59E0B, #D97706); border-radius: 8px; text-align: center; line-height: 32px; color: #FFFFFF; font-size: 16px;">📈</span>
                                        </div>
                                        <div style="display: table-cell; padding: 12px 0 12px 16px; vertical-align: top;">
                                            <strong style="color: #1E293B; font-weight: 600;">View reports and updates</strong>
                                            <br><span style="color: #64748B; font-size: 14px;">Access detailed insights and analytics</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%); padding: 32px 40px; text-align: center; border-top: 1px solid #E2E8F0;">
                            <p style="margin: 0 0 16px; font-size: 16px; color: #475569;">
                                <strong>Thanks,</strong><br>
                                <span style="background: linear-gradient(135deg, #3B82F6, #2563EB); background-clip: text; -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 600;">{{ config('app.name') }} Team</span>
                            </p>
                            
                            <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #E2E8F0;">
                                <p style="margin: 0; font-size: 12px; color: #94A3B8; line-height: 1.5;">
                                    This email was sent to {{ $email }}. If you have any questions, please contact our support team.
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