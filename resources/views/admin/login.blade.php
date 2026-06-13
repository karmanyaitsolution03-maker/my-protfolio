<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login</title>
<style>
:root{--cyan:#3DE8FF;--green:#54F0A8;--line:rgba(120,160,255,.16);--rose:#FF6B9D}
*{box-sizing:border-box}
body{background:#05070D;color:#E9EEFB;font:14px system-ui,sans-serif;display:grid;place-items:center;min-height:100vh;margin:0;
  background-image:radial-gradient(circle at 20% 10%,rgba(61,232,255,.08),transparent 40%),radial-gradient(circle at 85% 90%,rgba(84,240,168,.07),transparent 40%)}
form{background:linear-gradient(160deg,#0E1426,#0B101D);border:1px solid var(--line);border-radius:16px;padding:34px 30px;width:min(360px,90vw);box-shadow:0 24px 60px rgba(0,0,0,.5)}
.logo{width:46px;height:46px;border-radius:12px;background:linear-gradient(135deg,var(--cyan),var(--green));display:grid;place-items:center;color:#03101A;font-weight:800;font-size:20px;margin-bottom:16px;box-shadow:0 0 22px rgba(61,232,255,.4)}
h1{font-size:15px;letter-spacing:.14em;color:var(--cyan);font-family:ui-monospace,monospace;margin:0 0 4px}
p.sub{color:#8C97B4;font-size:12.5px;margin:0 0 20px}
input{width:100%;background:#070C16;border:1px solid var(--line);border-radius:9px;padding:12px 13px;color:#E9EEFB;box-sizing:border-box;transition:.15s}
input:focus{outline:none;border-color:var(--cyan);box-shadow:0 0 0 3px rgba(61,232,255,.12)}
button{margin-top:16px;width:100%;padding:12px;border:none;border-radius:9px;background:linear-gradient(135deg,var(--cyan),var(--green));color:#03101A;font-weight:700;cursor:pointer;font-size:14px;transition:.15s}
button:hover{filter:brightness(1.08)}
.err{color:var(--rose);font-size:12.5px;margin-top:12px;background:rgba(255,107,157,.08);border:1px solid rgba(255,107,157,.3);padding:9px 12px;border-radius:8px}
</style></head><body>
<form method="POST" action="{{ route('admin.login.post') }}">
  @csrf
  <div class="logo">A</div>
  <h1>// ADMIN ACCESS</h1>
  <p class="sub">Enter your email and password to enter the command center.</p>
  <input type="email" name="email" placeholder="Admin email" value="{{ old('email') }}" autofocus required>
  <input type="password" name="password" placeholder="Admin password" required style="margin-top:12px">
  <button>Authenticate →</button>
  @error('email')<div class="err">⚠ {{ $message }}</div>@enderror
  @error('password')<div class="err">⚠ {{ $message }}</div>@enderror
</form>
</body></html>
