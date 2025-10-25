<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $subject ?? 'Notification' }}</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .email-container { max-width: 600px; margin: 20px auto; background-color: #fff; padding: 20px; border-radius: 8px; }
        .header { background-color: #1B5388; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .footer { background-color: #f4f4f4; color: #555; padding: 10px; text-align: center; font-size: 12px; margin-top: 20px; border-radius: 0 0 8px 8px; }
        .content { padding: 20px; font-size: 16px; color: #333; line-height: 1.5; }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h2>{{ config('app.name') }}</h2>
        </div>

        <div class="content">
            {!! $body !!}
        </div>

        <p>
            Mariam AlThaidi<br>
            CEO - InfoTech<br>
            <img src="http://infotechq8.test/storage/documents/KY7S5TDhq5R5xDMiLyKLaHS5VUPg7QtEtfgg939X.png" alt="InfoTech Logo" style="max-width:100px; margin: 10px 0;"><br>
        </p>

        <table style="width: 100%; font-size: 14px; line-height: 1.5; color: #333; margin-bottom: 10px;">
            <tr>
                <td style="width: 80px; font-weight: bold;">Tel:</td>
                <td>‪+96522273890‬</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Mob:</td>
                <td>‪+96597365237‬</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Email:</td>
                <td>admin@infotechkw.co, support@infotechq8.com</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Website:</td>
                <td><a href="https://www.infotechq8.com/" target="_blank">https://www.infotechq8.com/</a></td>
            </tr>
        </table>


        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.<br>
            If you did not expect this email, please ignore it.
        </div>
    </div>
</body>
</html>
