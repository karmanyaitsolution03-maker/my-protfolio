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
</div>
<div class="card" style="margin-top:18px">
  <h3 style="margin:0 0 10px;font-size:14px">Conversion funnel</h3>
  <small class="hint">
    {{ $visits }} visits
    &rarr; {{ $contactClicks }} clicked "contact me" ({{ $visits > 0 ? round($contactClicks / $visits * 100) : 0 }}%)
    &rarr; {{ $messages }} messages sent ({{ $contactClicks > 0 ? round($messages / $contactClicks * 100) : 0 }}% of clicks)
  </small>
</div>
<div class="card" style="margin-top:18px">
  <small class="hint">Tip: change name, email, LinkedIn, tagline and about text in <a href="{{ route('admin.settings') }}">Settings</a>. All other content is managed per section above. Changes appear on the site instantly.</small>
</div>
@endsection
