<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Login | SIMKPI PLN UP3 Surakarta</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Inter',sans-serif;min-height:100vh;background:#0F172A;display:flex;align-items:center;justify-content:center;padding:20px}
.wrap{display:flex;width:100%;max-width:900px;border-radius:20px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.5)}
.left{flex:1;background:linear-gradient(135deg,#003B93 0%,#0052CC 40%,#1B6FD8 100%);padding:48px 40px;display:flex;flex-direction:column;justify-content:center;position:relative;overflow:hidden}
.left::before{content:'';position:absolute;top:-100px;right:-100px;width:350px;height:350px;border-radius:50%;background:rgba(255,255,255,.05)}
.left::after{content:'';position:absolute;bottom:-60px;left:-60px;width:250px;height:250px;border-radius:50%;background:rgba(255,255,255,.04)}
.logo-ring{width:72px;height:72px;border-radius:18px;background:rgba(255,184,0,.2);border:1.5px solid rgba(255,184,0,.4);display:flex;align-items:center;justify-content:center;font-size:32px;margin-bottom:28px;animation:float 3s ease-in-out infinite}
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-6px)}}
.left-tag{font-size:11px;font-weight:600;letter-spacing:2px;text-transform:uppercase;color:rgba(255,184,0,.9);margin-bottom:8px}
.left-title{font-size:22px;font-weight:800;color:#fff;line-height:1.35;margin-bottom:10px}
.left-desc{font-size:13px;color:rgba(255,255,255,.55);line-height:1.75;margin-bottom:32px}
.feat-list{display:flex;flex-direction:column;gap:10px}
.feat-item{display:flex;align-items:center;gap:10px;font-size:12.5px;color:rgba(255,255,255,.65)}
.feat-dot{width:6px;height:6px;border-radius:50%;background:#FFB800;flex-shrink:0}
.right{width:400px;flex-shrink:0;background:#fff;padding:44px 40px;display:flex;flex-direction:column;justify-content:center}
.right-logo{display:flex;align-items:center;gap:10px;margin-bottom:28px}
.right-logo .icon{width:36px;height:36px;background:#003B93;border-radius:9px;display:flex;align-items:center;justify-content:center;color:#FFB800;font-size:18px;font-weight:800}
.right-logo .brand{font-size:13px;font-weight:700;color:#003B93;line-height:1.2}
.right-logo .brand small{display:block;font-size:10px;font-weight:400;color:#94A3B8}
h2{font-size:20px;font-weight:800;color:#0F172A;margin-bottom:4px}
.sub{font-size:12.5px;color:#64748B;margin-bottom:22px}
.alert-err{background:#FEF2F2;border:1px solid #FECACA;border-radius:8px;padding:10px 13px;font-size:12.5px;color:#B91C1C;margin-bottom:16px}
.alert-ok{background:#F0FDF4;border:1px solid #BBF7D0;border-radius:8px;padding:10px 13px;font-size:12.5px;color:#166534;margin-bottom:16px}
.form-group{margin-bottom:14px}
.form-group label{display:block;font-size:11px;font-weight:600;color:#374151;letter-spacing:.4px;text-transform:uppercase;margin-bottom:5px}
.form-group input{width:100%;border:1.5px solid #E2E8F0;border-radius:9px;padding:10px 13px;font-size:13.5px;font-family:inherit;outline:none;transition:border-color .15s,box-shadow .15s;color:#1E293B}
.form-group input:focus{border-color:#003B93;box-shadow:0 0 0 3px rgba(0,59,147,.1)}
.pw-wrap{position:relative}
.pw-wrap input{padding-right:42px}
.pw-toggle{position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94A3B8;font-size:15px;padding:2px}
.row-check{display:flex;align-items:center;margin-bottom:18px}
.row-check label{display:flex;align-items:center;gap:6px;font-size:12px;color:#64748B;cursor:pointer}
.row-check input[type=checkbox]{accent-color:#003B93;width:14px;height:14px}
.btn-masuk{width:100%;background:#003B93;color:#fff;border:none;border-radius:9px;padding:11px;font-size:13.5px;font-weight:700;font-family:inherit;cursor:pointer;transition:background .15s,transform .1s}
.btn-masuk:hover{background:#002D72;transform:translateY(-1px)}
.btn-masuk:disabled{background:#94A3B8;cursor:not-allowed;transform:none}
.demo-box{background:#F0F4FF;border:1.5px solid #C7D7FF;border-radius:9px;padding:11px 13px;margin-top:18px;font-size:11.5px;color:#3B5BDB}
.demo-box strong{display:block;font-size:12px;font-weight:700;margin-bottom:5px;color:#1E40AF}
.footer-note{font-size:10px;color:#94A3B8;text-align:center;margin-top:18px;line-height:1.7}
@media(max-width:640px){.left{display:none}.right{width:100%;border-radius:20px}}
</style>
</head>
<body>
<div class="wrap">
  <!-- LEFT -->
  <div class="left">
    <div class="logo-ring">⚡</div>
    <div class="left-tag">PLN UP3 Surakarta</div>
    <div class="left-title">Sistem Monitoring &amp;<br>Evaluasi Kinerja KPI</div>
    <div class="left-desc">Platform terintegrasi untuk memantau dan mengevaluasi Key Performance Indicator secara real-time, mencakup data UP3 dan 6 ULP di wilayah Surakarta.</div>
    <div class="feat-list">
      <div class="feat-item"><span class="feat-dot"></span>Monitoring KPI UP3 &amp; 6 ULP real-time</div>
      <div class="feat-item"><span class="feat-dot"></span>Data KPI sesuai Kinerja Januari 2026</div>
      <div class="feat-item"><span class="feat-dot"></span>Input per ULP: Surakarta Kota, Manahan, Kartosura, Palur, Sragen, Sumberlawang</div>
      <div class="feat-item"><span class="feat-dot"></span>Laporan otomatis &amp; ekspor PDF / Excel</div>
    </div>
  </div>

  <!-- RIGHT -->
  <div class="right">
    <div class="right-logo">
      <div class="icon">⚡</div>
      <div class="brand">SIMKPI<br><small>PLN UP3 Surakarta 2026</small></div>
    </div>

    <h2>Selamat Datang</h2>
    <p class="sub">Masuk untuk mengakses sistem monitoring KPI</p>

    @if($errors->any())
    <div class="alert-err">⚠ {{ $errors->first() }}</div>
    @endif

    @if(session('success'))
    <div class="alert-ok">✓ {{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div class="alert-err">⚠ {{ session('error') }}</div>
    @endif

    {{-- Form dengan @csrf yang selalu fresh --}}
    <form action="{{ route('login.post') }}" method="POST" id="loginForm">
      @csrf

      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email"
               value="{{ old('email') }}"
               placeholder="email@pln.local"
               required
               autofocus
               autocomplete="email">
      </div>

      <div class="form-group">
        <label>Password</label>
        <div class="pw-wrap">
          <input type="password" name="password" id="pwField"
                 placeholder="••••••••"
                 required
                 autocomplete="current-password">
          <button type="button" class="pw-toggle" id="pwToggle" title="Tampilkan password">👁</button>
        </div>
      </div>

      <div class="row-check">
        <label>
          <input type="checkbox" name="remember">
          Ingat saya selama 30 hari
        </label>
      </div>

      <button type="submit" class="btn-masuk" id="btnLogin">
        Masuk ke Sistem
      </button>
    </form>

    <div class="demo-box">
      <strong>🔑 Akun Demo</strong>
      Admin &nbsp;&nbsp;: admin@pln.local / password123<br>
      Manajer &nbsp;: manajer@pln.local / password123<br>
      Supervisor: sup.teknik@pln.local / password123
    </div>

    <div class="footer-note">
      © 2026 PT PLN (Persero) UP3 Surakarta<br>
      SIMKPI v2.0 — Sistem Monitoring &amp; Evaluasi KPI
    </div>
  </div>
</div>

<script>
// Toggle password visibility
document.getElementById('pwToggle').addEventListener('click', function() {
  var pw = document.getElementById('pwField');
  if (pw.type === 'password') {
    pw.type = 'text';
    this.textContent = '🙈';
  } else {
    pw.type = 'password';
    this.textContent = '👁';
  }
});

// Anti double submit
document.getElementById('loginForm').addEventListener('submit', function() {
  var btn = document.getElementById('btnLogin');
  btn.disabled = true;
  btn.textContent = 'Memproses...';
});
</script>
</body>
</html>