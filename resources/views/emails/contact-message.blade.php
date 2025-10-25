<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Contact Inquiry</title>
</head>
<body>
    <h2>New Contact Form Submission</h2>
    
    <p><strong>Name:</strong> {{ $inquiry->name }}</p>
    <p><strong>Email:</strong> {{ $inquiry->email }}</p>
    <p><strong>Subject:</strong> {{ $inquiry->subject }}</p>
    <p><strong>Message:</strong></p>
    <p>{{ $inquiry->message }}</p>
    
    <hr>
    <p>This message was sent from the contact form on your website.</p>
</body>
</html>