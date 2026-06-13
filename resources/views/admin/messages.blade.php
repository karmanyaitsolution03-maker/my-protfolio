@extends('admin.layout')
@section('title', 'Messages')
@section('content')
<h1>Messages</h1>
<div class="card">
<table>
  <tr><th>When</th><th>Name</th><th>Email</th><th>Message</th><th></th></tr>
  @forelse($rows as $m)
  <tr>
    <td>{{ $m->created_at->format('d M Y H:i') }}</td>
    <td>{{ $m->name }}</td>
    <td><a href="mailto:{{ $m->email }}">{{ $m->email }}</a></td>
    <td style="max-width:420px">{{ $m->message }}</td>
    <td>
      <form method="POST" action="{{ route('admin.messages.delete', $m) }}" onsubmit="return confirm('Delete?')">
        @csrf @method('DELETE')
        <button class="btn danger">Delete</button>
      </form>
    </td>
  </tr>
  @empty
  <tr><td colspan="5"><small class="hint">No messages yet.</small></td></tr>
  @endforelse
</table>
{{ $rows->links() }}
</div>
@endsection
