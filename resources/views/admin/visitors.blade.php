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
<div class="table-wrap">
<table>
  <tr><th></th><th>Last seen</th><th>First seen</th><th>IP address</th><th>Visits</th><th>Company</th><th>Location</th><th>Page</th><th>Referrer</th><th>Browser / Device</th></tr>
  @forelse($rows as $v)
  <tr>
    <td>
      @if($v->is_hot_lead)
        <span title="{{ implode(' · ', $v->hot_lead_reasons) }}">🔥</span>
      @endif
    </td>
    <td>{{ $v->updated_at->format('d M Y H:i') }}</td>
    <td>{{ $v->created_at->format('d M Y H:i') }}</td>
    <td>{{ $v->ip_address ?? '—' }}</td>
    <td>{{ $v->visit_count }}</td>
    <td>
      @if($v->company)
        <span style="background:#FFC56E;color:#03101A;font-size:10px;font-weight:700;padding:2px 7px;border-radius:4px;display:inline-block">{{ $v->company }}</span>
      @else
        <span class="hint">—</span>
      @endif
    </td>
    <td>{{ $v->location }}</td>
    <td>{{ $v->path }}</td>
    <td style="max-width:220px">{{ $v->referrer ?: '—' }}</td>
    <td>{{ $v->device_label }}</td>
  </tr>
  @empty
  <tr><td colspan="10"><small class="hint">No visits recorded yet.</small></td></tr>
  @endforelse
</table>
</div>
{{ $rows->links() }}
</div>
@endsection
