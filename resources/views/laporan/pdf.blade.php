<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan KPI PLN UP3 Surakarta</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'DejaVu Sans',Arial,sans-serif;font-size:10px;color:#1E293B}
.header{background:#001A4D;color:#fff;padding:12px 20px;display:flex;align-items:center;gap:12px}
.header-icon{font-size:24px}
.header-text h1{font-size:13px;font-weight:700;color:#FFB800;margin-bottom:2px}
.header-text p{font-size:9px;color:rgba(255,255,255,.6)}
.header-right{margin-left:auto;text-align:right;font-size:9px;color:rgba(255,255,255,.55)}
.summary{background:#F0F4FF;padding:8px 20px;display:flex;gap:20px;align-items:center;border-bottom:2px solid #C7D7FF}
.s-item{text-align:center}
.s-val{font-size:16px;font-weight:800;color:#003B93}
.s-lbl{font-size:8px;text-transform:uppercase;letter-spacing:.5px;color:#64748B}
.pred{display:inline-block;padding:2px 10px;border-radius:20px;font-size:10px;font-weight:700}
.pred-sangat-baik{background:#DCFCE7;color:#166534}
.pred-baik{background:#DBEAFE;color:#1E40AF}
.pred-cukup{background:#FEF3C7;color:#92400E}
.pred-kurang{background:#FEE2E2;color:#991B1B}
.pred-sangat-kurang{background:#F1F5F9;color:#374151}
.section{padding:12px 20px 0}
.sec-title{font-size:11px;font-weight:700;color:#003B93;border-bottom:2px solid #003B93;padding-bottom:3px;margin-bottom:8px}
table{width:100%;border-collapse:collapse;margin-bottom:12px}
th{background:#001A4D;color:#fff;padding:5px 6px;font-size:8.5px;text-align:left}
td{padding:4px 6px;border-bottom:1px solid #F1F5F9;font-size:9px}
tr:nth-child(even) td{background:#F8FAFC}
.pct-ok{color:#059669;font-weight:700}
.pct-warn{color:#D97706;font-weight:700}
.pct-bad{color:#DC2626;font-weight:700}
.pb{width:55px;height:4px;background:#E2E8F0;border-radius:4px;display:inline-block;vertical-align:middle}
.pbf{height:100%;border-radius:4px}
.footer{padding:8px 20px;border-top:1px solid #E2E8F0;display:flex;justify-content:space-between;font-size:8px;color:#94A3B8;margin-top:6px}
</style>
</head>
<body>
<div class="header">
  <div class="header-icon">⚡</div>
  <div class="header-text">
    <h1>LAPORAN KINERJA BERBASIS KPI</h1>
    <p>PT PLN (Persero) — Unit Pelaksana Pelayanan Pelanggan (UP3) Surakarta</p>
    <p>Periode: {{ $namaBulan }} {{ $tahun }}</p>
  </div>
  <div class="header-right">Dicetak: {{ $tglCetak }}<br>Oleh: {{ $dicetak }}</div>
</div>

<div class="summary">
  <div class="s-item"><div class="s-val">{{ number_format($totalSkor,2) }}</div><div class="s-lbl">Total Skor</div></div>
  <div class="s-item"><div class="s-val">{{ number_format($proporsional,1) }}%</div><div class="s-lbl">Proporsional</div></div>
  <div class="s-item"><div class="s-val">{{ $realisasis->count() }}</div><div class="s-lbl">Jumlah KPI</div></div>
  <div class="s-item"><span class="pred pred-{{ Str::slug($predikat) }}">{{ $predikat }}</span></div>
</div>

@foreach($perKategori as $namaKat => $reals)
<div class="section">
  <div class="sec-title">{{ $namaKat }}</div>
  <table>
    <thead>
      <tr><th style="width:20px">#</th><th style="width:55px">Kode</th><th>Indikator KPI</th><th style="width:50px">Satuan</th><th style="width:60px;text-align:right">Target</th><th style="width:65px;text-align:right">Realisasi</th><th style="width:90px;text-align:center">Capaian</th><th style="width:38px;text-align:center">Bobot</th><th style="width:38px;text-align:right">Skor</th></tr>
    </thead>
    <tbody>
      @foreach($reals as $i => $r)
      @php $c=$r->capaian; $cls=$c>=100?'pct-ok':($c>=80?'pct-warn':'pct-bad'); $bg=$c>=100?'#059669':($c>=80?'#D97706':'#DC2626'); @endphp
      <tr>
        <td style="color:#94A3B8">{{ $i+1 }}</td>
        <td><code style="font-size:8px">{{ $r->indikator->kode }}</code></td>
        <td>{{ $r->indikator->nama }}</td>
        <td style="color:#64748B">{{ $r->indikator->satuan }}</td>
        <td style="text-align:right">{{ number_format($r->target_snapshot,4) }}</td>
        <td style="text-align:right;font-weight:700">{{ number_format($r->nilai,4) }}</td>
        <td style="text-align:center"><span class="{{ $cls }}">{{ number_format($c,1) }}%</span> <span class="pb"><span class="pbf" style="width:{{ min($c,100) }}%;background:{{ $bg }}"></span></span></td>
        <td style="text-align:center;color:#64748B">{{ $r->indikator->bobot }}%</td>
        <td style="text-align:right;font-weight:700;color:#003B93">{{ number_format($r->skor,2) }}</td>
      </tr>
      @endforeach
      <tr style="background:#F0F4FF"><td colspan="8" style="text-align:right;font-weight:700;color:#003B93">Sub-total:</td><td style="text-align:right;font-weight:800;color:#003B93;font-size:11px">{{ number_format($reals->sum('skor'),2) }}</td></tr>
    </tbody>
  </table>
</div>
@endforeach

<div class="footer">
  <span>SIMKPI — Sistem Monitoring & Evaluasi KPI | PLN UP3 Surakarta</span>
  <span>Digenerate otomatis oleh sistem. {{ $tglCetak }}</span>
</div>
</body>
</html>