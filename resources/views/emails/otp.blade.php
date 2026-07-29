<!DOCTYPE html>
<html>
<head>
    <title>MediCart Verification</title>
</head>
<body style="font-family: 'Inter', Arial, sans-serif; background-color: #F8FAFC; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 6px; border: 1px solid #E2E8F0;">
        <h2 style="color: #0F172A; text-align: center; font-weight: 700;">Welcome to MediCart!</h2>
        <p style="font-size: 16px; color: #334155;">Hello,</p>
        <p style="font-size: 16px; color: #334155;">Thank you for registering. Please use the following One-Time Password (OTP) to verify your email address:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <span style="display: inline-block; font-size: 24px; font-weight: 700; background: #F1F5F9; padding: 15px 30px; border-radius: 4px; letter-spacing: 5px; color: #0F172A; border: 1px solid #E2E8F0;">
                {{ $otp }}
            </span>
        </div>
        
        <p style="font-size: 14px; color: #64748B;">This OTP is valid for 10 minutes. Do not share this code with anyone.</p>
        <hr style="border: 0; border-top: 1px solid #E2E8F0; margin: 20px 0;">
        <p style="font-size: 12px; color: #94A3B8; text-align: center;">&copy; {{ date('Y') }} MediCart. All rights reserved.</p>
    </div>
</body>
</html>
