<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background-color: #f8f9fa;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table role="presentation" style="width: 100%; max-width: 600px; border-collapse: collapse; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="padding: 40px 40px 30px; text-align: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px 8px 0 0;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700;">Reset Your Password</h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <p style="margin: 0 0 20px; color: #2d3748; font-size: 16px; line-height: 1.6;">
                                Hello {{ $userName }},
                            </p>
                            
                            <p style="margin: 0 0 20px; color: #2d3748; font-size: 16px; line-height: 1.6;">
                                We received a request to reset your password for your Treis Adiutor account. Click the button below to create a new password:
                            </p>
                            
                            <!-- Button -->
                            <table role="presentation" style="width: 100%; border-collapse: collapse; margin: 30px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $resetUrl }}" style="display: inline-block; padding: 16px 36px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 16px;">Reset Password</a>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="margin: 20px 0; color: #718096; font-size: 14px; line-height: 1.6;">
                                Or copy and paste this link into your browser:
                            </p>
                            
                            <p style="margin: 0 0 20px; padding: 12px; background-color: #f7fafc; border-radius: 4px; color: #4a5568; font-size: 13px; word-break: break-all;">
                                {{ $resetUrl }}
                            </p>
                            
                            <p style="margin: 20px 0 0; color: #718096; font-size: 14px; line-height: 1.6;">
                                This password reset link will expire in 60 minutes.
                            </p>
                            
                            <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 30px 0;">
                            
                            <p style="margin: 0; color: #a0aec0; font-size: 13px; line-height: 1.6;">
                                If you didn't request a password reset, you can safely ignore this email. Your password will remain unchanged.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="padding: 30px 40px; background-color: #f7fafc; border-radius: 0 0 8px 8px; text-align: center;">
                            <p style="margin: 0 0 10px; color: #718096; font-size: 13px;">
                                © {{ date('Y') }} Treis Adiutor. All rights reserved.
                            </p>
                            <p style="margin: 0; color: #a0aec0; font-size: 12px;">
                                This is an automated message, please do not reply to this email.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
