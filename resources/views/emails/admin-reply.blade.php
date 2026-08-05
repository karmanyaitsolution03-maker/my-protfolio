<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $settings['name'] ?? '' }}</title>
</head>
<body style="margin:0;padding:0;background:#F1F4FA;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F1F4FA;padding:32px 16px;">
<tr><td align="center">

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;background:#FFFFFF;border-radius:14px;border:1px solid #E7EBF3;">
  <tr>
    <td style="height:4px;line-height:4px;font-size:0;background:#3DE8FF;background:linear-gradient(90deg,#3DE8FF,#54F0A8);border-radius:14px 14px 0 0;">&nbsp;</td>
  </tr>

  <tr>
    <td style="padding:30px 36px 6px;">
      <table role="presentation" cellpadding="0" cellspacing="0">
        <tr>
          <td style="width:44px;height:44px;min-width:44px;border-radius:50%;background:#3DE8FF;background:linear-gradient(135deg,#3DE8FF,#54F0A8);color:#03101A;font-weight:700;font-size:17px;text-align:center;vertical-align:middle;font-family:ui-monospace,Menlo,Consolas,monospace;">
            {{ strtoupper(substr(trim((string) ($settings['name'] ?? '')), 0, 1)) ?: 'A' }}
          </td>
          <td style="padding-left:13px;">
            <div style="font-size:15px;font-weight:700;color:#12172A;">{{ $settings['name'] ?? '' }}</div>
            @if(! empty($settings['designation']))
            <div style="font-size:12px;color:#8A92A8;letter-spacing:.02em;margin-top:1px;">{{ $settings['designation'] }}</div>
            @endif
          </td>
        </tr>
      </table>
    </td>
  </tr>

  <tr>
    <td style="padding:18px 36px 4px;">
      <p style="margin:0 0 14px;font-size:15px;color:#12172A;">Hi {{ $contactMessage->name }},</p>
      <div style="font-size:15px;line-height:1.7;color:#333B52;">
        {!! nl2br(e($body)) !!}
      </div>
    </td>
  </tr>

  <tr>
    <td style="padding:22px 36px 26px;">
      <p style="margin:0 0 14px;font-size:15px;color:#12172A;">— {{ $settings['name'] ?? '' }}</p>
      @if(! empty($settings['email']) || ! empty($settings['linkedin']) || ! empty($settings['whatsapp_number']))
      <div>
        @if(! empty($settings['email']))
        <a href="mailto:{{ $settings['email'] }}" style="display:inline-block;margin:0 8px 8px 0;padding:7px 14px;border-radius:999px;background:#EAF6FF;color:#0B6FA8;font-size:12.5px;font-weight:600;text-decoration:none;">✉️ Email</a>
        @endif
        @if(! empty($settings['linkedin']))
        <a href="{{ $settings['linkedin'] }}" style="display:inline-block;margin:0 8px 8px 0;padding:7px 14px;border-radius:999px;background:#EAF6FF;color:#0B6FA8;font-size:12.5px;font-weight:600;text-decoration:none;">💼 LinkedIn</a>
        @endif
        @if(! empty($settings['whatsapp_number']))
        <a href="https://wa.me/{{ preg_replace('/\D/', '', $settings['whatsapp_number']) }}" style="display:inline-block;margin:0 8px 8px 0;padding:7px 14px;border-radius:999px;background:#EAFBF1;color:#1C8A56;font-size:12.5px;font-weight:600;text-decoration:none;">💬 WhatsApp</a>
        @endif
      </div>
      @endif
    </td>
  </tr>

  <tr>
    <td style="padding:0 36px 30px;">
      <div style="border-top:1px solid #EDEFF5;padding-top:16px;">
        <div style="font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#B0B7C9;margin-bottom:7px;">Your original message</div>
        <div style="font-size:13px;line-height:1.6;color:#7A8296;background:#F7F8FC;border-radius:10px;padding:12px 14px;">{!! nl2br(e($contactMessage->message)) !!}</div>
      </div>
    </td>
  </tr>
</table>

<p style="max-width:560px;margin:16px auto 0;font-size:11.5px;color:#A6ADBE;text-align:center;">
  Sent via {{ $settings['name'] ?? '' }}'s portfolio
</p>

</td></tr>
</table>
</body>
</html>
