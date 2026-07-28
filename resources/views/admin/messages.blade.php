@extends('admin.layout')
@section('title', 'Messages')
@section('content')
<h1>Messages</h1>
<div class="card" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
  <form method="GET" action="{{ route('admin.messages') }}" style="display:flex;gap:8px;flex:1">
    <input type="text" name="q" value="{{ $q }}" placeholder="Search name, email, or message…" style="flex:1;min-width:200px">
    <button class="btn">Search</button>
    @if($q !== '')
      <a class="btn" href="{{ route('admin.messages') }}">Clear</a>
    @endif
  </form>
  <a class="btn" href="{{ route('admin.messages.export', $q !== '' ? ['q' => $q] : []) }}">⬇ Export CSV</a>
</div>
<div class="card">
<table>
  <tr><th>When</th><th>Name</th><th>Type</th><th>Email</th><th>Message</th><th></th></tr>
  @forelse($rows as $m)
  <tr>
    <td>{{ $m->created_at->format('d M Y H:i') }}</td>
    <td>
      @if(in_array($m->id, $unreadIds))
        <span style="background:#ff6b9d;color:#03101A;font-size:10px;font-weight:700;letter-spacing:.06em;padding:2px 6px;border-radius:4px;margin-right:6px">NEW</span>
      @endif
      {{ $m->name }}
    </td>
    <td>
      @if($m->ai_category)
        @php
          $catColor = match($m->ai_category) {
            'recruiter' => '#3DE8FF',
            'client' => '#54F0A8',
            'spam' => '#8C97B4',
            default => '#FFC56E',
          };
        @endphp
        <span style="background:{{ $catColor }};color:#03101A;font-size:10px;font-weight:700;text-transform:uppercase;padding:2px 7px;border-radius:4px;display:inline-block;margin-bottom:3px">{{ $m->ai_category }}</span>
        <div class="hint" style="font-size:11px;max-width:180px">{{ $m->ai_summary }}</div>
      @endif
    </td>
    <td><a href="mailto:{{ $m->email }}">{{ $m->email }}</a></td>
    <td style="max-width:380px">{{ $m->message }}</td>
    <td>
      <form method="POST" action="{{ route('admin.messages.delete', $m) }}" onsubmit="return confirm('Delete?')">
        @csrf @method('DELETE')
        <button class="btn danger">Delete</button>
      </form>
    </td>
  </tr>
  @empty
  <tr><td colspan="6"><small class="hint">No messages yet.</small></td></tr>
  @endforelse
</table>
{{ $rows->links() }}
</div>
@endsection
