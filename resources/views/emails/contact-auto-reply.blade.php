<!DOCTYPE html>
<html>
<body style="font-family: sans-serif; color:#111; line-height:1.6;">
    <p>Hi {{ $contactMessage->name }},</p>
    <p>Thanks for reaching out to {{ $settings['name'] ?? 'me' }}. Your message has been received:</p>
    <p style="white-space:pre-wrap; padding:12px; background:#f5f5f5; border-radius:6px;">{{ $contactMessage->message }}</p>
    <p>Typical response time is {{ $settings['response_time'] ?? '24 hours' }}. Talk soon.</p>
    <p>— {{ $settings['name'] ?? '' }}</p>
</body>
</html>
