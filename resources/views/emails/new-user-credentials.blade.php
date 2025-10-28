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
    font-size: 24px; 
    color: #FFFFFF; 
    letter-spacing: -0.01em;
}
</style>

<body style="margin: 0; padding: 0; background-color: #F8FAFC; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; line-height: 1.6; color: #334155;">
    
    <!-- Email Container -->
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #F8FAFC;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                
                <!-- Main Content Card -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="max-width: 600px; background-color: #FFFFFF; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); overflow: hidden;">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #3B82F6; padding: 48px 40px; text-align: center;">
                            <h1 class="heading-serif">
                                TREIS ADIUTOR
                            </h1>
                            <p style="margin: 8px 0 0; font-size: 13px; color: #FFFFFF; letter-spacing: 0.05em;">
                                DIGITAL INNOVATION • INTELLIGENCE • EXCELLENCE
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Welcome Message -->
                    <tr>
                        <td style="padding: 40px 40px 32px;">
                            <h2 style="margin: 0 0 12px; font-family: 'Inter', sans-serif; font-size: 20px; font-weight: 600; color: #0F172A; letter-spacing: -0.01em;">
                                Welcome, {{ $fullName }}
                            </h2>
                            <p style="margin: 0; font-size: 15px; color: #64748B; line-height: 1.6;">
                                Your account has been successfully created. You can now track your service request and access our platform using the credentials below.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <div style="height: 1px; background-color: #E2E8F0;"></div>
                        </td>
                    </tr>
                    
                    <!-- Credentials Section -->
                    <tr>
                        <td style="padding: 32px 40px;">
                            <h3 style="margin: 0 0 20px; font-size: 14px; font-weight: 600; color: #0F172A; text-transform: uppercase; letter-spacing: 0.05em;">
                                Login Credentials
                            </h3>
                            
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px 24px;">
                                        <div style="margin-bottom: 16px;">
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Email Address
                                            </label>
                                            <div style="font-family: 'Monaco', 'Menlo', 'Courier New', monospace; font-size: 14px; color: #0F172A;">
                                                {{ $email }}
                                            </div>
                                        </div>
                                        <div>
                                            <label style="display: block; font-size: 12px; font-weight: 500; color: #64748B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                                                Temporary Password
                                            </label>
                                            <div style="font-family: 'Monaco', 'Menlo', 'Courier New', monospace; font-size: 14px; color: #0F172A;">
                                                {{ $password }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Security Notice -->
                            <div style="margin-top: 16px; padding: 12px 16px; background-color: #EEF2FF; border-left: 3px solid #00D4FF; border-radius: 4px;">
                                <p style="margin: 0; font-size: 13px; color: #334155; line-height: 1.5;">
                                    <strong style="color: #0F172A;">Security Notice:</strong> Please change your password after your first login.
                                </p>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- CTA Button -->
                    <tr>
                        <td style="padding: 0 40px 32px; text-align: center;">
                            <a href="{{ url('/login') }}" style="display: inline-block; background-color: #3B82F6; color: #FFFFFF; text-decoration: none; padding: 14px 32px; border-radius: 6px; font-weight: 500; font-size: 14px; letter-spacing: 0.01em; transition: background-color 0.2s;">
                                Login to Your Account
                            </a>
                        </td>
                    </tr>
                    
                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <div style="height: 1px; background-color: #E2E8F0;"></div>
                        </td>
                    </tr>
                    
                    <!-- Features List -->
                    <tr>
                        <td style="padding: 32px 40px 40px;">
                            <h3 style="margin: 0 0 20px; font-size: 14px; font-weight: 600; color: #0F172A; text-transform: uppercase; letter-spacing: 0.05em;">
                                What You Can Do
                            </h3>
                            
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="padding: 0 0 16px 0; vertical-align: top; width: 24px;">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-top: 2px;">
                                            <rect width="16" height="16" rx="3" fill="#00D4FF" opacity="0.15"/>
                                            <path d="M5 8L7 10L11 6" stroke="#00D4FF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </td>
                                    <td style="padding: 0 0 16px 12px; vertical-align: top;">
                                        <div style="font-size: 14px; font-weight: 500; color: #0F172A; margin-bottom: 2px;">Track Service Request</div>
                                        <div style="font-size: 13px; color: #64748B; line-height: 1.5;">Monitor progress and receive updates</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 0 0 16px 0; vertical-align: top; width: 24px;">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-top: 2px;">
                                            <rect width="16" height="16" rx="3" fill="#00D4FF" opacity="0.15"/>
                                            <path d="M5 8L7 10L11 6" stroke="#00D4FF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </td>
                                    <td style="padding: 0 0 16px 12px; vertical-align: top;">
                                        <div style="font-size: 14px; font-weight: 500; color: #0F172A; margin-bottom: 2px;">Upload Documents</div>
                                        <div style="font-size: 13px; color: #64748B; line-height: 1.5;">Share files and resources securely</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 0 0 16px 0; vertical-align: top; width: 24px;">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-top: 2px;">
                                            <rect width="16" height="16" rx="3" fill="#00D4FF" opacity="0.15"/>
                                            <path d="M5 8L7 10L11 6" stroke="#00D4FF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </td>
                                    <td style="padding: 0 0 16px 12px; vertical-align: top;">
                                        <div style="font-size: 14px; font-weight: 500; color: #0F172A; margin-bottom: 2px;">Communicate with Adiutor</div>
                                        <div style="font-size: 13px; color: #64748B; line-height: 1.5;">Direct messaging and collaboration</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 0; vertical-align: top; width: 24px;">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-top: 2px;">
                                            <rect width="16" height="16" rx="3" fill="#00D4FF" opacity="0.15"/>
                                            <path d="M5 8L7 10L11 6" stroke="#00D4FF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </td>
                                    <td style="padding: 0 0 0 12px; vertical-align: top;">
                                        <div style="font-size: 14px; font-weight: 500; color: #0F172A; margin-bottom: 2px;">View Reports</div>
                                        <div style="font-size: 13px; color: #64748B; line-height: 1.5;">Access detailed insights and analytics</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #F8FAFC; padding: 32px 40px; text-align: center; border-top: 1px solid #E2E8F0;">
                                                        
                            <p style="margin: 0; font-size: 12px; color: #94A3B8; line-height: 1.6;">
                                This email was sent to {{ $email }}<br>
                                If you have any questions, please contact our support team.
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