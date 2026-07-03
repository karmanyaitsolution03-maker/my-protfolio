<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Dashboard') — Command Center</title>
<style>
:root{
  --bg:#05070D;--panel:#0B101D;--panel-2:#0E1426;--line:rgba(120,160,255,.16);
  --text:#E9EEFB;--muted:#8C97B4;--cyan:#3DE8FF;--green:#54F0A8;--rose:#FF6B9D;
  --sidebar:264px;
}
*{margin:0;padding:0;box-sizing:border-box}
html,body{height:100%}
body{background:var(--bg);color:var(--text);font:14px/1.6 system-ui,-apple-system,Segoe UI,sans-serif}
a{color:var(--cyan);text-decoration:none}
::selection{background:rgba(61,232,255,.25)}

/* ---------- layout shell ---------- */
.shell{display:flex;min-height:100vh}

/* ---------- sidebar ---------- */
.side{
  width:var(--sidebar);flex:0 0 var(--sidebar);
  background:linear-gradient(180deg,#0A0F1D 0%,#070B15 100%);
  border-right:1px solid var(--line);
  display:flex;flex-direction:column;
  position:sticky;top:0;height:100vh;overflow-y:auto;
}
.brand{display:flex;align-items:center;gap:11px;padding:20px 20px 18px;border-bottom:1px solid var(--line)}
.brand .logo{
  width:38px;height:38px;border-radius:10px;flex:0 0 38px;
  background:linear-gradient(135deg,var(--cyan),var(--green));
  display:grid;place-items:center;color:#03101A;font-weight:800;font-size:17px;
  box-shadow:0 0 18px rgba(61,232,255,.35);
}
.brand .who b{display:block;font-family:ui-monospace,monospace;letter-spacing:.1em;color:var(--text);font-size:13px}
.brand .who span{display:block;color:var(--muted);font-size:11px;letter-spacing:.06em}

.nav{padding:14px 12px;flex:1}
.nav .grp{color:var(--muted);font-size:10px;letter-spacing:.16em;text-transform:uppercase;margin:16px 12px 7px;font-weight:700}
.nav .grp:first-child{margin-top:4px}
.nav a.item{
  display:flex;align-items:center;gap:12px;
  padding:10px 12px;border-radius:9px;color:var(--muted);
  font-size:13.5px;font-weight:500;margin:2px 0;position:relative;transition:.15s;
}
.nav a.item .ic{width:20px;text-align:center;font-size:15px;filter:grayscale(.2)}
.nav a.item:hover{background:rgba(120,160,255,.06);color:var(--text)}
.nav a.item.active{
  background:linear-gradient(90deg,rgba(61,232,255,.14),rgba(61,232,255,.02));
  color:#fff;
}
.nav a.item.active::before{
  content:"";position:absolute;left:0;top:7px;bottom:7px;width:3px;border-radius:0 3px 3px 0;
  background:linear-gradient(var(--cyan),var(--green));
}
.side .foot{padding:14px 16px;border-top:1px solid var(--line)}
.side .foot a{display:flex;align-items:center;gap:8px;color:var(--muted);font-size:12.5px;padding:6px 0}
.side .foot a:hover{color:var(--cyan)}

/* ---------- main ---------- */
.main{flex:1;min-width:0;display:flex;flex-direction:column}
.topbar{
  display:flex;align-items:center;gap:16px;
  padding:14px 26px;border-bottom:1px solid var(--line);
  background:rgba(11,16,29,.7);backdrop-filter:blur(8px);
  position:sticky;top:0;z-index:20;
}
.topbar .ptitle{font-size:15px;font-weight:600;letter-spacing:.02em}
.topbar .ptitle small{display:block;color:var(--muted);font-size:11px;font-weight:400;letter-spacing:.08em;text-transform:uppercase}
.topbar .spacer{margin-left:auto}
.topbar .pill{display:inline-flex;align-items:center;gap:7px;color:var(--green);font-size:12px;border:1px solid rgba(84,240,168,.3);background:rgba(84,240,168,.06);padding:5px 11px;border-radius:999px}
.topbar .pill .dot{width:7px;height:7px;border-radius:50%;background:var(--green);box-shadow:0 0 8px var(--green);animation:pulse 1.8s infinite}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.35}}
.burger{display:none;background:none;border:1px solid var(--line);color:var(--text);border-radius:8px;width:38px;height:38px;font-size:18px;cursor:pointer;align-items:center;justify-content:center}

.wrap{max-width:1120px;width:100%;margin:0 auto;padding:26px 26px 50px}
h1{font-size:22px;margin-bottom:18px;letter-spacing:.01em}

/* ---------- components (shared with other views) ---------- */
.card{background:var(--panel);border:1px solid var(--line);border-radius:14px;padding:20px;margin-bottom:18px}
table{width:100%;border-collapse:collapse;font-size:13px}
th,td{text-align:left;padding:11px 12px;border-bottom:1px solid var(--line);vertical-align:top}
tr:last-child td{border-bottom:none}
table tr:hover td{background:rgba(120,160,255,.03)}
th{color:var(--muted);font-weight:700;font-size:10.5px;letter-spacing:.1em;text-transform:uppercase}
.btn{display:inline-block;padding:8px 16px;border:1px solid var(--line);border-radius:9px;background:rgba(61,232,255,.08);color:var(--cyan);cursor:pointer;font:600 13px system-ui;transition:.15s}
.btn:hover{background:rgba(61,232,255,.16);transform:translateY(-1px)}
.btn.danger{color:var(--rose);background:rgba(255,107,157,.07);border-color:rgba(255,107,157,.25)}
.btn.danger:hover{background:rgba(255,107,157,.16)}
.btn.primary{background:linear-gradient(135deg,var(--cyan),var(--green));color:#03101A;border-color:transparent;box-shadow:0 4px 14px rgba(61,232,255,.2)}
.btn.primary:hover{filter:brightness(1.08)}
label{display:block;margin:16px 0 6px;color:var(--muted);font-size:12px;letter-spacing:.04em;font-weight:600}
input[type=text],input[type=number],input[type=password],textarea,select{width:100%;background:#070C16;border:1px solid var(--line);border-radius:9px;padding:10px 12px;color:var(--text);font:13px/1.5 ui-monospace,monospace;transition:.15s}
input:focus,textarea:focus,select:focus{outline:none;border-color:var(--cyan);box-shadow:0 0 0 3px rgba(61,232,255,.12)}
input[type=checkbox]{width:auto;accent-color:var(--cyan);transform:scale(1.2);margin-top:6px}
textarea{min-height:90px;resize:vertical}
.ok{background:rgba(84,240,168,.1);border:1px solid rgba(84,240,168,.35);color:var(--green);padding:11px 15px;border-radius:10px;margin-bottom:16px;font-size:13px;display:flex;gap:9px;align-items:center}
.err{background:rgba(255,107,157,.08);border:1px solid rgba(255,107,157,.35);color:var(--rose);padding:11px 15px;border-radius:10px;margin-bottom:16px;font-size:13px;display:flex;gap:9px;align-items:center}
.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(210px,1fr));gap:15px}
.stat{background:linear-gradient(160deg,var(--panel-2),var(--panel));border:1px solid var(--line);border-radius:14px;padding:18px;transition:.18s;display:block}
.stat:hover{border-color:rgba(61,232,255,.4);transform:translateY(-2px)}
.stat b{font-size:28px;color:var(--cyan);font-family:ui-monospace,monospace}
.stat span{display:block;color:var(--muted);font-size:12px;margin-top:5px;letter-spacing:.03em}
.actions{white-space:nowrap}
small.hint{color:var(--muted)}

/* ---------- responsive ---------- */
#navtoggle{display:none}
@media(max-width:860px){
  .burger{display:flex}
  .side{position:fixed;z-index:50;left:0;top:0;transform:translateX(-100%);transition:transform .25s;box-shadow:0 0 40px rgba(0,0,0,.6)}
  #navtoggle:checked ~ .shell .side{transform:translateX(0)}
  #navtoggle:checked ~ .scrim{display:block}
  .scrim{display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:40}
}
</style>
</head>
<body>
<input type="checkbox" id="navtoggle">
<label for="navtoggle" class="scrim"></label>
<div class="shell">

  @php
    $items = [
      'Overview' => [
        ['Dashboard', '📊', route('admin.dashboard'), request()->routeIs('admin.dashboard')],
      ],
      'Content' => [
        ['Skill Categories', '🗂️', route('admin.res.index','skill-categories'), request()->is('admin/skill-categories*')],
        ['Skills',           '⚙️', route('admin.res.index','skills'),           request()->is('admin/skills*')],
        ['Experience',       '🛰️', route('admin.res.index','experiences'),      request()->is('admin/experiences*')],
        ['Projects',         '🚀', route('admin.res.index','projects'),         request()->is('admin/projects*')],
        ['Achievements',     '🏆', route('admin.res.index','achievements'),     request()->is('admin/achievements*')],
      ],
      'Inbox' => [
        ['Messages', '✉️', route('admin.messages'), request()->is('admin/messages*')],
      ],
      'Analytics' => [
        ['Visitors', '👁️', route('admin.visitors'), request()->is('admin/visitors*')],
      ],
      'Config' => [
        ['Settings', '🛠️', route('admin.settings'), request()->is('admin/settings*')],
      ],
    ];
  @endphp

  <aside class="side">
    <div class="brand">
      <div class="logo">A</div>
      <div class="who"><b>A.R.I.A.</b><span>ADMIN CONSOLE</span></div>
    </div>
    <nav class="nav">
      @foreach($items as $group => $links)
        <div class="grp">{{ $group }}</div>
        @foreach($links as [$label, $icon, $url, $active])
          <a class="item {{ $active ? 'active' : '' }}" href="{{ $url }}">
            <span class="ic">{{ $icon }}</span>{{ $label }}
          </a>
        @endforeach
      @endforeach
    </nav>
    <div class="foot">
      <a href="{{ route('home') }}" target="_blank">🌐 View live site ↗</a>
    </div>
  </aside>

  <div class="main">
    <div class="topbar">
      <label for="navtoggle" class="burger">☰</label>
      <div class="ptitle">@yield('title', 'Dashboard')<small>Command Center</small></div>
      <div class="spacer"></div>
      <span class="pill"><span class="dot"></span> ONLINE</span>
      <form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="btn danger">Logout</button></form>
    </div>

    <div class="wrap">
      @if(session('ok'))<div class="ok">✓ {{ session('ok') }}</div>@endif
      @if($errors->any())<div class="err">⚠ {{ $errors->first() }}</div>@endif
      @yield('content')
    </div>
  </div>

</div>
</body>
</html>
