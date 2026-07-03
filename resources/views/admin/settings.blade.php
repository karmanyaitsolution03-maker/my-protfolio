@extends('admin.layout')
@section('title', 'Settings')
@section('content')
<h1>Settings</h1>
<p class="hint" style="margin:-6px 0 18px">
  Everything you see on the public site is editable here. Tokens you can use in any field:
  <code>:name</code> <code>:first</code> <code>:last</code> <code>:exp</code> <code>:proj</code> <code>:ach</code>.
  HTML like <code>&lt;b&gt;…&lt;/b&gt;</code> is allowed.
</p>

<form method="POST" action="{{ route('admin.settings.save') }}" enctype="multipart/form-data">
  @csrf

  {{-- quick jump --}}
  <div class="card" style="position:sticky;top:64px;z-index:10;display:flex;flex-wrap:wrap;gap:8px;padding:14px 16px">
    @foreach($groups as $group => $fields)
      <a class="btn" style="padding:5px 12px;font-size:12px" href="#g-{{ \Illuminate\Support\Str::slug($group) }}">{{ $group }}</a>
    @endforeach
  </div>

  @foreach($groups as $group => $fields)
  <div class="card" id="g-{{ \Illuminate\Support\Str::slug($group) }}">
    <h3 style="margin-bottom:14px;font-size:15px;color:var(--cyan)">{{ $group }}</h3>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:6px 24px">
      @foreach($fields as $key => $def)
        @php
          [$label, $default, $type] = $def;
          $options = $def[3] ?? [];
          $wide = $type === 'area';
        @endphp
        <div style="{{ $wide ? 'grid-column:1/-1' : '' }}">
          <label for="s_{{ $key }}">{{ $label }} <small class="hint">· {{ $key }}</small></label>
          @if($type === 'file')
            @if(!empty($values[$key]))
              <p class="hint" style="margin:4px 0">
                Current file: <a href="{{ route('resume.download') }}" target="_blank">{{ basename($values[$key]) }}</a>
              </p>
            @endif
            <input type="file" id="s_{{ $key }}" name="settings[{{ $key }}]" accept="application/pdf">
          @elseif($type === 'select')
            <select id="s_{{ $key }}" name="settings[{{ $key }}]">
              @foreach($options as $opt)
                <option value="{{ $opt }}" @selected(($values[$key] ?? $default) === $opt)>{{ $opt }}</option>
              @endforeach
            </select>
          @elseif($wide)
            <textarea id="s_{{ $key }}" name="settings[{{ $key }}]" spellcheck="false" style="min-height:{{ str_contains($key,'diagnostics')||str_contains($key,'lines')||str_contains($key,'sectors')||str_contains($key,'waypoints')||str_contains($key,'chips')||str_contains($key,'idle') ? '150px' : '80px' }}">{{ $values[$key] ?? $default }}</textarea>
          @else
            <input type="text" id="s_{{ $key }}" name="settings[{{ $key }}]" value="{{ $values[$key] ?? $default }}">
          @endif
        </div>
      @endforeach
    </div>
  </div>
  @endforeach

  <div class="card" style="position:sticky;bottom:0;display:flex;align-items:center;gap:14px">
    <button class="btn primary">Save all settings</button>
    <a class="btn" href="{{ route('home') }}" target="_blank">Preview site ↗</a>
  </div>
</form>

<form method="POST" action="{{ route('admin.migrate') }}" class="card" style="display:flex;align-items:center;gap:14px" onsubmit="return confirm('Run pending database migrations now?')">
  @csrf
  <button class="btn">Run database migrations</button>
  <small class="hint">Applies any new migration files to the database — use after deploying code changes.</small>
</form>
@endsection
