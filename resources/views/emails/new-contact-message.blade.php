<!DOCTYPE html>
<html>
<body style="font-family: sans-serif; color:#111; line-height:1.6;">
    <h2 style="margin:0 0 12px;">New portfolio contact message</h2>
    @if($contactMessage->ai_category)
    <p>
        <span style="display:inline-block;background:#eef2ff;color:#3730a3;font-size:12px;font-weight:600;padding:3px 8px;border-radius:4px;text-transform:uppercase;">{{ $contactMessage->ai_category }}</span>
        &nbsp;{{ $contactMessage->ai_summary }}
    </p>
    @endif
    <p><strong>Name:</strong> {{ $contactMessage->name }}</p>
    <p><strong>Email:</strong> {{ $contactMessage->email }}</p>
    <p><strong>Message:</strong></p>
    <p style="white-space:pre-wrap;">{{ $contactMessage->message }}</p>
    <hr style="border:none;border-top:1px solid #ddd;margin:20px 0;">
    <p style="font-size:12px;color:#888;">Reply to this email to respond directly to {{ $contactMessage->name }}.</p>
</body>
</html>
