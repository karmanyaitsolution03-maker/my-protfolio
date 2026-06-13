@extends('admin.layout')
@section('title', ($row ? 'Edit' : 'Create').' — '.$cfg['title'])
@section('content')
<h1>{{ $row ? 'Edit' : 'Create' }} — {{ $cfg['title'] }}</h1>
<div class="card" style="max-width:680px">
<form method="POST" action="{{ $row ? route('admin.res.update', [$resource, $row->id]) : route('admin.res.store', $resource) }}">
  @csrf
  @if($row) @method('PUT') @endif
  @foreach($cfg['fields'] as $name => [$type, $label])
    <label for="f_{{ $name }}">{{ $label }}</label>
    @php $val = old($name, $row?->$name); @endphp
    @if($type === 'textarea')
      <textarea id="f_{{ $name }}" name="{{ $name }}">{{ $val }}</textarea>
    @elseif($type === 'json')
      <textarea id="f_{{ $name }}" name="{{ $name }}" spellcheck="false">{{ is_array($val) ? json_encode($val, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) : $val }}</textarea>
    @elseif($type === 'checkbox')
      <input type="checkbox" id="f_{{ $name }}" name="{{ $name }}" value="1" @checked($val)>
    @elseif($type === 'number')
      <input type="number" id="f_{{ $name }}" name="{{ $name }}" value="{{ $val }}">
    @elseif($type === 'category')
      <select id="f_{{ $name }}" name="{{ $name }}">
        @foreach($categories as $c)
          <option value="{{ $c->id }}" @selected($val == $c->id)>{{ $c->name }}</option>
        @endforeach
      </select>
    @else
      <input type="text" id="f_{{ $name }}" name="{{ $name }}" value="{{ $val }}">
    @endif
  @endforeach
  <p style="margin-top:18px">
    <button class="btn primary">{{ $row ? 'Save changes' : 'Create' }}</button>
    <a class="btn" href="{{ route('admin.res.index', $resource) }}">Cancel</a>
  </p>
</form>
</div>
@endsection
