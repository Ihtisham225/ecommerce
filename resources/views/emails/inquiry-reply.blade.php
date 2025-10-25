<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Response to your inquiry</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #1B5388; color: white; padding: 15px; text-align: center; }
        .content { background-color: #f9f9f9; padding: 20px; border-radius: 5px; }
        .footer { margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>InfoTech Institute</h1>
        </div>
        
        <div class="content">
            <h2>Response to Your Inquiry</h2>
            
            <p>Dear {{ $inquiry->name }},</p>
            
            <p>Thank you for contacting InfoTech Institute. Here is our response to your inquiry:</p>
            
            <div style="background-color: #fff; padding: 15px; border-left: 4px solid #1B5388; margin: 15px 0;">
                {!! nl2br(e($replyMessage)) !!}
            </div>
            
            <p><strong>Your original message:</strong></p>
            <blockquote style="background-color: #f5f5f5; padding: 10px; border-radius: 3px;">
                {!! nl2br(e($inquiry->message)) !!}
            </blockquote>
            
            <p>If you have any further questions, please don't hesitate to contact us.</p>
            
            <p>Best regards,<br>
            InfoTech Institute Team</p>
        </div>
        
        <div class="footer">
            <p>This is an automated response. Please do not reply directly to this email.</p>
            <p>InfoTech Institute &copy; {{ date('Y') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>