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
</div>
<div class="card" style="margin-top:18px">
  <small class="hint">Tip: change name, email, LinkedIn, tagline and about text in <a href="{{ route('admin.settings') }}">Settings</a>. All other content is managed per section above. Changes appear on the site instantly.</small>
</div>
@endsection
