@extends('admin.layout')
@section('title', 'Visitors')
@section('content')
<h1>Visitors</h1>

<div class="grid">
  <div class="stat">
    <span style="font-size:18px">👁️</span>
    <b>{{ $total }}</b><span>Total visits</span>
  </div>
  <div class="stat">
    <span style="font-size:18px">🧑‍💻</span>
    <b>{{ $uniqueIps }}</b><span>Unique visitors (by IP)</span>
  </div>
  <div class="stat">
    <span style="font-size:18px">📅</span>
    <b>{{ $today }}</b><span>Visits today</span>
  </div>
</div>

<div class="card" style="margin-top:18px">
<table>
  <tr><th>When</th><th>IP address</th><th>Location</th><th>Page</th><th>Referrer</th><th>Browser / Device</th></tr>
  @forelse($rows as $v)
  <tr>
    <td>{{ $v->created_at->format('d M Y H:i') }}</td>
    <td>{{ $v->ip_address ?? '—' }}</td>
    <td>{{ $v->location }}</td>
    <td>{{ $v->path }}</td>
    <td style="max-width:220px">{{ $v->referrer ?: '—' }}</td>
    <td>{{ $v->device_label }}</td>
  </tr>
  @empty
  <tr><td colspan="6"><small class="hint">No visits recorded yet.</small></td></tr>
  @endforelse
</table>
{{ $rows->links() }}
</div>
@endsection
