@extends('admin.layout')
@section('title', 'Dashboard')
@section('content')
@php
  $icons = ['skill-categories'=>'🗂️','skills'=>'⚙️','experiences'=>'🛰️','projects'=>'🚀','achievements'=>'🏆'];
@endphp
<h1>Welcome back 👋</h1>
<div class="grid">
  @foreach($counts as $key => $c)
    <a class="stat" href="{{ route('admin.res.index', $key) }}">
      <span style="font-size:18px">{{ $icons[$key] ?? '📁' }}</span>
      <b>{{ $c['count'] }}</b><span>{{ $c['title'] }}</span>
    </a>
  @endforeach
  <a class="stat" href="{{ route('admin.messages') }}">
    <span style="font-size:18px">✉️</span>
    <b>{{ $messages }}</b><span>Messages received</span>
  </a>
  <a class="stat" href="{{ route('admin.visitors') }}">
    <span style="font-size:18px">👁️</span>
    <b>{{ $visits }}</b><span>Website visits</span>
  </a>
  <div class="stat">
    <span style="font-size:18px">🖱️</span>
    <b>{{ $contactClicks }}</b><span>"Contact me" clicks</span>
  </div>
  <div class="stat">
    <span style="font-size:18px">📄</span>
    <b>{{ $resumeClicks }}</b><span>Resume downloads</span>
  </div>
  <div class="stat">
    <span style="font-size:18px">💬</span>
    <b>{{ $whatsappClicks }}</b><span>WhatsApp clicks</span>
  </div>
  <a class="stat" href="{{ route('admin.visitors') }}">
    <span style="font-size:18px">🔥</span>
    <b>{{ $hotLeadsTotal }}</b><span>Hot leads</span>
  </a>
</div>
<div class="card" style="margin-top:18px">
  <h3 style="margin:0 0 10px;font-size:14px">🔥 Hot leads</h3>
  @forelse($hotLeads as $v)
    <div style="display:flex;justify-content:space-between;gap:12px;align-items:center;padding:9px 0;border-bottom:1px solid var(--line)">
      <div>
        @if($v->company)
          <span style="background:#FFC56E;color:#03101A;font-size:10px;font-weight:700;padding:2px 7px;border-radius:4px;margin-right:6px">{{ $v->company }}</span>
        @endif
        <span>{{ $v->ip_address }}</span>
        <span class="hint">— {{ $v->location }}</span>
        <div class="hint" style="font-size:11px;margin-top:2px">
          @foreach($v->hot_lead_reasons as $reason)
            <span style="background:rgba(255,107,157,.14);color:var(--rose);border:1px solid rgba(255,107,157,.3);font-size:10px;font-weight:600;padding:1px 6px;border-radius:4px;margin-right:5px;display:inline-block">{{ $reason }}</span>
          @endforeach
        </div>
      </div>
      <span class="hint" style="white-space:nowrap">{{ $v->updated_at->diffForHumans() }}</span>
    </div>
  @empty
    <small class="hint">No hot leads yet — flagged once someone returns {{ \App\Models\Visit::RETURN_VISIT_THRESHOLD }}+ times, or views the projects section and lingers on Contact in one visit.</small>
  @endforelse
  @if($hotLeadsTotal > $hotLeads->count())
    <small class="hint" style="display:block;margin-top:10px">Showing the {{ $hotLeads->count() }} most recent of {{ $hotLeadsTotal }} — see <a href="{{ route('admin.visitors') }}">all visitors</a>.</small>
  @endif
</div>
<div class="card" style="margin-top:18px">
  <h3 style="margin:0 0 4px;font-size:14px">Conversion funnel</h3>
  <small class="hint" style="display:block;margin-bottom:18px">Visits → project views → contact clicks → submissions — where people actually drop off.</small>
  <div class="funnel">
    @foreach($funnel['stages'] as $i => $stage)
      @php $widthPct = $stage['value'] > 0 ? max($stage['pct_of_top'], 1) : 0; @endphp
      <div class="funnel-row">
        <div class="funnel-head">
          <span class="funnel-label">{{ $stage['label'] }}</span>
          <span class="funnel-value">{{ number_format($stage['value']) }}</span>
        </div>
        <div class="funnel-track"><div class="funnel-fill" style="width:{{ $widthPct }}%"></div></div>
        <div class="funnel-sub">
          @if($i === 0)
            Start of funnel
          @else
            {{ $stage['pct_of_prev'] }}% of {{ strtolower($funnel['stages'][$i - 1]['label']) }}
            @if($stage['drop_off_pct'] > 0)
              · <span class="drop">{{ $stage['drop_off_pct'] }}% drop-off</span>
            @endif
          @endif
        </div>
      </div>
    @endforeach
  </div>
  @if($funnel['worst_index'] !== null && $funnel['worst_drop_pct'] > 0)
    <div class="funnel-callout">
      ⚠️ Biggest drop-off: <b>{{ $funnel['stages'][$funnel['worst_index'] - 1]['label'] }} → {{ $funnel['stages'][$funnel['worst_index']]['label'] }}</b>
      — {{ $funnel['worst_drop_pct'] }}% never continue. That's the step worth fixing first.
    </div>
  @endif
</div>
<div class="card" style="margin-top:18px">
  <h3 style="margin:0 0 10px;font-size:14px">Proactive AI nudge (lead capture)</h3>
  <small class="hint">
    Shown to {{ $nudgeShown }} visitors who lingered on Contact or showed exit intent
    &rarr; {{ $nudgeResume }} asked for the resume, {{ $nudgeCall }} asked to set up a call, {{ $nudgeDismissed }} dismissed it
    ({{ $nudgeShown > 0 ? round(($nudgeResume + $nudgeCall) / $nudgeShown * 100) : 0 }}% engaged).
  </small>
</div>
<div class="card" style="margin-top:18px">
  <h3 style="margin:0 0 10px;font-size:14px">🏢 Notable visitors</h3>
  @forelse($notableVisitors as $v)
    <div style="display:flex;justify-content:space-between;gap:12px;align-items:center;padding:9px 0;border-bottom:1px solid var(--line)">
      <div>
        <span style="background:#FFC56E;color:#03101A;font-size:10px;font-weight:700;text-transform:uppercase;padding:2px 7px;border-radius:4px;margin-right:8px">{{ $v->company }}</span>
        <span class="hint">{{ $v->location }} · viewed <code>{{ $v->path }}</code></span>
      </div>
      <span class="hint" style="white-space:nowrap">{{ $v->updated_at->diffForHumans() }}</span>
    </div>
  @empty
    <small class="hint">No visits from an identifiable company yet — this fills in once a business/office IP visits the site. See <a href="{{ route('admin.visitors') }}">Visitors</a> for everyone.</small>
  @endforelse
  @if($notableVisitors->isNotEmpty())
    <small class="hint" style="display:block;margin-top:10px">Best-effort reverse-IP lookup — residential ISPs and cloud/hosting providers are filtered out. See <a href="{{ route('admin.visitors') }}">all visitors</a>.</small>
  @endif
</div>
<div class="card" style="margin-top:18px">
  <small class="hint">Tip: change name, email, LinkedIn, tagline and about text in <a href="{{ route('admin.settings') }}">Settings</a>. All other content is managed per section above. Changes appear on the site instantly.</small>
</div>
@endsection
