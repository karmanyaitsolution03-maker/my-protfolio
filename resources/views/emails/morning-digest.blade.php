<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Morning digest</title>
</head>
<body style="margin:0;padding:0;background:#F1F4FA;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F1F4FA;padding:32px 16px;">
<tr><td align="center">

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;background:#FFFFFF;border-radius:14px;border:1px solid #E7EBF3;">
  <tr>
    <td style="height:4px;line-height:4px;font-size:0;background:#3DE8FF;background:linear-gradient(90deg,#3DE8FF,#54F0A8);border-radius:14px 14px 0 0;">&nbsp;</td>
  </tr>

  {{-- header --}}
  <tr>
    <td style="padding:30px 36px 4px;">
      <div style="font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#8A92A8;margin-bottom:6px;">Morning digest</div>
      <h1 style="margin:0;font-size:21px;color:#12172A;">Here's what happened overnight</h1>
      <p style="margin:6px 0 0;font-size:13px;color:#8A92A8;">Since {{ $data['since']->format('D, d M · H:i') }}</p>
    </td>
  </tr>

  {{-- visitor stats --}}
  <tr>
    <td style="padding:22px 36px 6px;">
      <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td width="33%" style="background:#F7F9FD;border-radius:10px;padding:14px 0;text-align:center;">
            <div style="font-family:ui-monospace,Menlo,Consolas,monospace;font-size:22px;font-weight:700;color:#12172A;">{{ $data['visitors']['total'] }}</div>
            <div style="font-size:11px;color:#8A92A8;letter-spacing:.03em;margin-top:2px;">VISITORS</div>
          </td>
          <td width="2%"></td>
          <td width="33%" style="background:#F7F9FD;border-radius:10px;padding:14px 0;text-align:center;">
            <div style="font-family:ui-monospace,Menlo,Consolas,monospace;font-size:22px;font-weight:700;color:#12172A;">{{ $data['visitors']['new'] }}</div>
            <div style="font-size:11px;color:#8A92A8;letter-spacing:.03em;margin-top:2px;">NEW</div>
          </td>
          <td width="2%"></td>
          <td width="30%" style="background:#F7F9FD;border-radius:10px;padding:14px 0;text-align:center;">
            <div style="font-family:ui-monospace,Menlo,Consolas,monospace;font-size:22px;font-weight:700;color:#12172A;">{{ $data['visitors']['returning'] }}</div>
            <div style="font-size:11px;color:#8A92A8;letter-spacing:.03em;margin-top:2px;">RETURNING</div>
          </td>
        </tr>
      </table>
    </td>
  </tr>

  {{-- companies --}}
  <tr>
    <td style="padding:22px 36px 4px;">
      <div style="font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#B0B7C9;margin-bottom:9px;">🏢 Companies that visited</div>
      @if($data['companies']->isEmpty())
        <p style="margin:0;font-size:13px;color:#8A92A8;">No identifiable companies — just individual visitors, or none at all.</p>
      @else
        <div>
          @foreach($data['companies'] as $company)
            <span style="display:inline-block;margin:0 6px 6px 0;padding:6px 12px;border-radius:999px;background:#FFF6E5;color:#8A5B00;font-size:12.5px;font-weight:600;">{{ $company }}</span>
          @endforeach
        </div>
      @endif
    </td>
  </tr>

  {{-- hot leads --}}
  <tr>
    <td style="padding:22px 36px 4px;">
      <div style="font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#B0B7C9;margin-bottom:9px;">🔥 Hot leads</div>
      @if($data['hotLeads']->isEmpty())
        <p style="margin:0;font-size:13px;color:#8A92A8;">No hot leads in this window.</p>
      @else
        @foreach($data['hotLeads'] as $lead)
          <div style="padding:10px 0;border-top:1px solid #EDEFF5;">
            <div style="font-size:13.5px;color:#12172A;">
              @if($lead->company)
                <span style="font-weight:700;">{{ $lead->company }}</span> —
              @endif
              {{ $lead->ip_address }} <span style="color:#8A92A8;">({{ $lead->location }})</span>
            </div>
            <div style="margin-top:4px;">
              @foreach($lead->hot_lead_reasons as $reason)
                <span style="display:inline-block;margin:2px 6px 0 0;padding:3px 9px;border-radius:999px;background:#FFEAF1;color:#B23662;font-size:11px;font-weight:600;">{{ $reason }}</span>
              @endforeach
            </div>
          </div>
        @endforeach
      @endif
    </td>
  </tr>

  {{-- messages --}}
  <tr>
    <td style="padding:22px 36px 4px;">
      <div style="font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#B0B7C9;margin-bottom:9px;">✉️ New messages ({{ $data['messages']['total'] }})</div>
      @if($data['messages']['total'] === 0)
        <p style="margin:0;font-size:13px;color:#8A92A8;">No new messages since last time.</p>
      @else
        @foreach($data['messages']['items'] as $m)
          @php
            $catColor = match($m->ai_category) {
              'recruiter' => ['#EAF6FF', '#0B6FA8'],
              'client'    => ['#EAFBF1', '#1C8A56'],
              'spam'      => ['#F1F2F6', '#7A8296'],
              default     => ['#FFF6E5', '#8A5B00'],
            };
          @endphp
          <div style="padding:10px 0;border-top:1px solid #EDEFF5;">
            <div style="font-size:13.5px;color:#12172A;">
              <span style="font-weight:700;">{{ $m->name }}</span>
              @if($m->ai_category)
                <span style="display:inline-block;margin-left:6px;padding:2px 8px;border-radius:999px;background:{{ $catColor[0] }};color:{{ $catColor[1] }};font-size:10.5px;font-weight:700;text-transform:uppercase;">{{ $m->ai_category }}</span>
              @endif
            </div>
            <div style="font-size:13px;color:#7A8296;margin-top:3px;">{{ $m->ai_summary ?: \Illuminate\Support\Str::limit($m->message, 100) }}</div>
          </div>
        @endforeach
        @if($data['messages']['total'] > $data['messages']['items']->count())
          <p style="margin:8px 0 0;font-size:12px;color:#8A92A8;">+ {{ $data['messages']['total'] - $data['messages']['items']->count() }} more in the inbox.</p>
        @endif
      @endif
    </td>
  </tr>

  {{-- cta --}}
  <tr>
    <td style="padding:26px 36px 32px;">
      <a href="{{ route('admin.dashboard') }}" style="display:inline-block;padding:11px 22px;border-radius:10px;background:#12172A;color:#ffffff;font-size:13.5px;font-weight:700;text-decoration:none;">Open dashboard →</a>
    </td>
  </tr>
</table>

<p style="max-width:600px;margin:16px auto 0;font-size:11.5px;color:#A6ADBE;text-align:center;">
  Sent automatically every morning by {{ $settings['name'] ?? '' }}'s portfolio
</p>

</td></tr>
</table>
</body>
</html>
