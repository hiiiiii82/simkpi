<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title','Dashboard') | SIMKPI PLN UP3 Surakarta</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
<style>
:root{--pln:#003B93;--pln2:#0052CC;--gold:#FFB800;--sb-bg:#0A1628;--sb-w:252px;--top-h:54px;--bg:#F1F5FB;--r:10px;--ok:#059669;--warn:#D97706;--bad:#DC2626}
*{box-sizing:border-box}
body{font-family:'Inter',sans-serif;background:var(--bg);color:#1E293B;margin:0}

/* ── SIDEBAR ── */
#sb{position:fixed;top:0;left:0;width:var(--sb-w);height:100vh;background:var(--sb-bg);display:flex;flex-direction:column;z-index:1000;overflow-y:auto;transition:transform .25s}
.sb-brand{padding:16px 18px;border-bottom:1px solid rgba(255,255,255,.06);display:flex;align-items:center;gap:10px}
.sb-icon{width:36px;height:36px;background:var(--gold);border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:17px;font-weight:800;color:#001A4D;flex-shrink:0}
.sb-name{font-size:12px;font-weight:700;color:var(--gold);letter-spacing:.5px}
.sb-sub{font-size:9.5px;color:rgba(255,255,255,.38);margin-top:1px}
.sb-sec{font-size:9px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:rgba(255,255,255,.28);padding:14px 18px 4px}
.sb-link{display:flex;align-items:center;gap:9px;padding:8px 18px;color:rgba(255,255,255,.6);font-size:13px;text-decoration:none;border-radius:0 20px 20px 0;margin-right:10px;transition:all .12s;position:relative}
.sb-link:hover{background:rgba(255,255,255,.06);color:#fff;text-decoration:none}
.sb-link.active{background:var(--gold);color:#001A4D!important;font-weight:700}
.sb-link.active i{color:#001A4D!important}
.sb-link i{font-size:14px;min-width:16px}
.sb-badge{margin-left:auto;background:var(--bad);color:#fff;font-size:9px;font-weight:700;padding:2px 6px;border-radius:8px}
.sb-foot{margin-top:auto;padding:13px 18px;border-top:1px solid rgba(255,255,255,.06);display:flex;align-items:center;gap:8px}
.sb-av{width:30px;height:30px;border-radius:50%;background:var(--gold);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#001A4D;flex-shrink:0}
.sb-uname{font-size:12px;color:#fff;font-weight:600}
.sb-urole{font-size:9.5px;color:rgba(255,255,255,.38)}

/* ── TOPBAR ── */
#tb{position:fixed;top:0;left:var(--sb-w);right:0;height:var(--top-h);background:#fff;border-bottom:1px solid #E2E8F0;display:flex;align-items:center;justify-content:space-between;padding:0 22px;z-index:999;box-shadow:0 1px 3px rgba(0,0,0,.05)}
.tb-title{font-size:14px;font-weight:700;color:var(--pln);display:flex;align-items:center;gap:7px}
.tb-right{display:flex;align-items:center;gap:10px}
.tb-clock{font-family:'JetBrains Mono',monospace;font-size:11px;color:#64748B;background:var(--bg);padding:3px 10px;border-radius:20px}
.tb-av{width:30px;height:30px;border-radius:50%;background:var(--pln);display:flex;align-items:center;justify-content:center;color:#fff;font-size:11px;font-weight:700;text-decoration:none}

/* ── MAIN ── */
#main{margin-left:var(--sb-w);padding-top:var(--top-h);min-height:100vh}
.pg{padding:20px}

/* ── CARDS ── */
.card{border:none;border-radius:var(--r);box-shadow:0 1px 3px rgba(0,0,0,.06),0 4px 12px rgba(0,0,0,.04)}
.card-header{background:transparent;border-bottom:1px solid #F1F5F9;padding:13px 16px;font-weight:700;font-size:13px;color:#1E293B}

/* ── STAT ── */
.stat{border-radius:var(--r);padding:16px;color:#fff;position:relative;overflow:hidden}
.stat::after{content:'';position:absolute;top:-20px;right:-20px;width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,.08)}
.stat-val{font-size:26px;font-weight:800;line-height:1}
.stat-lbl{font-size:11px;opacity:.8;margin-top:3px}
.stat-sub{font-size:10px;margin-top:7px;opacity:.85}
.s-blue  {background:linear-gradient(135deg,#1565C0,#003B93)}
.s-green {background:linear-gradient(135deg,#059669,#065F46)}
.s-orange{background:linear-gradient(135deg,#D97706,#92400E)}
.s-red   {background:linear-gradient(135deg,#DC2626,#7F1D1D)}
.s-purple{background:linear-gradient(135deg,#7C3AED,#4C1D95)}
.s-teal  {background:linear-gradient(135deg,#0891B2,#0E7490)}

/* ── PROGRESS ── */
.progress{height:6px;border-radius:6px;background:#E2E8F0}
.progress-bar{border-radius:6px}

/* ── BADGES ── */
.badge-ok  {background:#DCFCE7;color:#166534;padding:2px 9px;border-radius:20px;font-size:10px;font-weight:700}
.badge-wait{background:#FEF3C7;color:#92400E;padding:2px 9px;border-radius:20px;font-size:10px;font-weight:700}
.badge-bad {background:#FEE2E2;color:#991B1B;padding:2px 9px;border-radius:20px;font-size:10px;font-weight:700}
.badge-gray{background:#F1F5F9;color:#64748B;padding:2px 9px;border-radius:20px;font-size:10px;font-weight:700}
.text-ok  {color:var(--ok)!important;font-weight:700}
.text-warn{color:var(--warn)!important;font-weight:700}
.text-bad {color:var(--bad)!important;font-weight:700}

/* ── PREDIKAT ── */
.pred-sb{background:#DCFCE7;color:#166534;padding:3px 12px;border-radius:20px;font-size:12px;font-weight:700;display:inline-block}
.pred-b {background:#DBEAFE;color:#1E40AF;padding:3px 12px;border-radius:20px;font-size:12px;font-weight:700;display:inline-block}
.pred-c {background:#FEF3C7;color:#92400E;padding:3px 12px;border-radius:20px;font-size:12px;font-weight:700;display:inline-block}
.pred-k {background:#FEE2E2;color:#991B1B;padding:3px 12px;border-radius:20px;font-size:12px;font-weight:700;display:inline-block}
.pred-sk{background:#F1F5F9;color:#374151;padding:3px 12px;border-radius:20px;font-size:12px;font-weight:700;display:inline-block}

/* ── TABLE ── */
.table th{font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#64748B;background:#F8FAFC;border-color:#E2E8F0}
.table td{font-size:12.5px;vertical-align:middle;border-color:#F1F5F9}

/* ── FLASH ── */
.flash{position:fixed;top:64px;right:16px;z-index:9999;min-width:300px;animation:slideIn .3s ease}
@keyframes slideIn{from{opacity:0;transform:translateX(16px)}to{opacity:1;transform:translateX(0)}}

/* ── LIVE ── */
.live{display:inline-block;width:7px;height:7px;border-radius:50%;background:#22C55E;animation:pulse 1.5s infinite}
@keyframes pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.5;transform:scale(1.3)}}

/* ── RESP ── */
@media(max-width:768px){#sb{transform:translateX(-100%)}#sb.open{transform:translateX(0)}#main,#tb{margin-left:0;left:0}}
</style>
@stack('styles')
</head>
<body>

{{-- SIDEBAR --}}
<nav id="sb">
  <div class="sb-brand">
    <div class="sb-icon">⚡</div>
    <div><div class="sb-name">SIMKPI PLN</div><div class="sb-sub">UP3 Surakarta 2026</div></div>
  </div>

  <div style="flex:1">
    <div class="sb-sec">Utama</div>
    <a href="{{ route('dashboard') }}" class="sb-link {{ request()->routeIs('dashboard') ? 'active':'' }}">
      <i class="bi bi-grid-fill"></i> Dashboard
    </a>

    <div class="sb-sec">Data ULP</div>
    {{-- Link per ULP --}}
    @php $ulpList = \App\Models\Ulp::where('is_active',true)->get(); @endphp
    @foreach($ulpList as $u)
    <a href="{{ route('ulp.show', $u->id) }}" class="sb-link {{ request()->routeIs('ulp.show') && request()->route('id')==$u->id ? 'active':'' }}"
       style="font-size:12px;padding:6px 18px">
      <i class="bi bi-building"></i> {{ $u->nama }}
    </a>
    @endforeach

    @if(auth()->user()->isAdmin())
    <div class="sb-sec">Kelola KPI</div>
    <a href="{{ route('kpi.index') }}" class="sb-link {{ request()->routeIs('kpi.*') ? 'active':'' }}">
      <i class="bi bi-list-check"></i> Indikator KPI
    </a>
    <a href="{{ route('ulp.index') }}" class="sb-link {{ request()->routeIs('ulp.index') ? 'active':'' }}">
      <i class="bi bi-diagram-3-fill"></i> Kelola ULP
    </a>
    @php $nWait = \App\Models\Realisasi::where('status','submitted')->count(); @endphp
    <a href="{{ route('validasi.index') }}" class="sb-link {{ request()->routeIs('validasi.*') ? 'active':'' }}">
      <i class="bi bi-shield-check"></i> Validasi Data
      @if($nWait > 0)<span class="sb-badge">{{ $nWait }}</span>@endif
    </a>
    @endif

    <div class="sb-sec">Kinerja</div>
    <a href="{{ route('input.index') }}" class="sb-link {{ request()->routeIs('input.*') ? 'active':'' }}">
      <i class="bi bi-pencil-square"></i> Input Data
    </a>
    <a href="{{ route('monitoring.index') }}" class="sb-link {{ request()->routeIs('monitoring.*') ? 'active':'' }}">
      <i class="bi bi-activity"></i> Monitoring Real-time
    </a>
    <a href="{{ route('evaluasi.index') }}" class="sb-link {{ request()->routeIs('evaluasi.*') ? 'active':'' }}">
      <i class="bi bi-clipboard2-data-fill"></i> Evaluasi Kinerja
    </a>

    <div class="sb-sec">Laporan</div>
    <a href="{{ route('laporan.index') }}" class="sb-link {{ request()->routeIs('laporan.*') ? 'active':'' }}">
      <i class="bi bi-file-earmark-bar-graph-fill"></i> Laporan & Unduh
    </a>

    @if(auth()->user()->isAdmin())
    <div class="sb-sec">Admin</div>
    <a href="{{ route('pengguna.index') }}" class="sb-link {{ request()->routeIs('pengguna.*') ? 'active':'' }}">
      <i class="bi bi-people-fill"></i> Pengguna
    </a>
    @endif
  </div>

  <div class="sb-foot">
    <div class="sb-av">{{ auth()->user()->inisial }}</div>
    <div>
      <div class="sb-uname">{{ Str::limit(auth()->user()->name,16) }}</div>
      <div class="sb-urole">{{ auth()->user()->role_label }}</div>
    </div>
    <form action="{{ route('logout') }}" method="POST" class="ms-auto">
      @csrf
      <button type="submit" style="background:none;border:none;color:rgba(255,255,255,.4);cursor:pointer;padding:4px" title="Logout">
        <i class="bi bi-box-arrow-right" style="font-size:15px"></i>
      </button>
    </form>
  </div>
</nav>

{{-- TOPBAR --}}
<header id="tb">
  <div class="d-flex align-items-center gap-3">
    <button class="btn btn-sm d-md-none" id="btnSb" style="color:var(--pln)"><i class="bi bi-list" style="font-size:20px"></i></button>
    <div class="tb-title"><i class="bi bi-lightning-charge-fill" style="color:var(--gold)"></i> @yield('page-title','Dashboard')</div>
  </div>
  <div class="tb-right">
    <span class="live"></span>
    <span class="tb-clock" id="clk">—</span>
    <a href="{{ route('profil') }}" class="tb-av">{{ auth()->user()->inisial }}</a>
  </div>
</header>

{{-- MAIN --}}
<main id="main">
  @if(session('success'))
  <div class="flash alert alert-success alert-dismissible fade show shadow-sm" style="border-radius:10px">
    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif
  @if(session('error'))
  <div class="flash alert alert-danger alert-dismissible fade show shadow-sm" style="border-radius:10px">
    <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif

  <div class="pg">@yield('content')</div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function tick(){
  const n=new Date(),p=v=>String(v).padStart(2,'0');
  const D=['Min','Sen','Sel','Rab','Kam','Jum','Sab'],M=['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
  document.getElementById('clk').textContent=D[n.getDay()]+', '+p(n.getDate())+' '+M[n.getMonth()]+' '+n.getFullYear()+' '+p(n.getHours())+':'+p(n.getMinutes())+':'+p(n.getSeconds());
  setTimeout(tick,1000);
})();
document.getElementById('btnSb')?.addEventListener('click',()=>document.getElementById('sb').classList.toggle('open'));
setTimeout(()=>{document.querySelectorAll('.flash').forEach(el=>{el.style.transition='opacity .5s';el.style.opacity='0';setTimeout(()=>el.remove(),500)})},5000);
</script>
@stack('scripts')
</body>
</html>