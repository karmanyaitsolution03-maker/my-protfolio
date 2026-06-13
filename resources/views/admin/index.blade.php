@extends('admin.layout')
@section('title', $cfg['title'])
@section('content')
<div style="display:flex;align-items:center;gap:12px;margin-bottom:18px;flex-wrap:wrap">
  <h1 style="margin:0">{{ $cfg['title'] }}</h1>
  <span class="hint" style="border:1px solid var(--line);border-radius:999px;padding:3px 11px;font-size:12px">{{ count($rows) }} {{ \Illuminate\Support\Str::plural('record', count($rows)) }}</span>
  <a class="btn primary" style="margin-left:auto" href="{{ route('admin.res.create', $resource) }}">+ Add new</a>
</div>
<div class="card">
<table>
  <tr>@foreach($cfg['list'] as $col)<th>{{ str_replace('_',' ',$col) }}</th>@endforeach<th></th></tr>
  @forelse($rows as $row)
  <tr>
    @foreach($cfg['list'] as $col)
      <td>{{ is_bool($row->$col) ? ($row->$col ? 'yes' : '—') : \Illuminate\Support\Str::limit(is_array($row->$col) ? json_encode($row->$col) : $row->$col, 60) }}</td>
    @endforeach
    <td class="actions">
      <a class="btn" href="{{ route('admin.res.edit', [$resource, $row->id]) }}">Edit</a>
      <form method="POST" action="{{ route('admin.res.destroy', [$resource, $row->id]) }}" style="display:inline" onsubmit="return confirm('Delete this record?')">
        @csrf @method('DELETE')
        <button class="btn danger">Delete</button>
      </form>
    </td>
  </tr>
  @empty
  <tr><td colspan="{{ count($cfg['list']) + 1 }}"><small class="hint">No records yet.</small></td></tr>
  @endforelse
</table>
</div>
@endsection
