@extends('layouts.app')
@section('title','Dashboard')
@section('page-title','Dashboard — KPI Januari 2026')

@section('content')

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
  <div class="col-6 col-xl-3">
    <div class="stat s-blue">
      <div style="font-size:20px;margin-bottom:8px">📈</div>
      <div class="stat-val">{{ number_format($totalSkor,1) }}</div>
      <div class="stat-lbl">Total Skor KPI</div>
      <div class="stat-sub"><span class="pred-{{ Str::slug($predikat) }}" style="font-size:9px">{{ $predikat }}</span></div>
    </div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="stat s-green">
      <div style="font-size:20px;margin-bottom:8px">🎯</div>
      <div class="stat-val">{{ number_format($proporsional,1) }}%</div>
      <div class="stat-lbl">Skor Proporsional</div>
      <div class="stat-sub">dari 100% target</div>
    </div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="stat s-orange">
      <div style="font-size:20px;margin-bottom:8px">✏️</div>
      <div class="stat-val">{{ $sudahInput }}/{{ $totalInd }}</div>
      <div class="stat-lbl">KPI Diinput</div>
      <div class="stat-sub">
        <div class="progress mt-1" style="background:rgba(255,255,255,.2)">
          <div class="progress-bar bg-white" style="width:{{ $totalInd>0?round($sudahInput/$totalInd*100):0 }}%"></div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="stat {{ $menunggu>0?'s-red':'s-purple' }}">
      <div style="font-size:20px;margin-bottom:8px">🛡️</div>
      <div class="stat-val">{{ $menunggu }}</div>
      <div class="stat-lbl">Menunggu Validasi</div>
      <div class="stat-sub">
        @if($menunggu>0 && auth()->user()->isAdmin())
        <a href="{{ route('validasi.index') }}" style="color:rgba(255,255,255,.8);font-size:10px">→ Validasi sekarang</a>
        @else Semua tervalidasi ✓ @endif
      </div>
    </div>
  </div>
</div>

{{-- REKAP ULP --}}
<div class="card mb-4">
  <div class="card-header d-flex align-items-center justify-content-between">
    <span><i class="bi bi-diagram-3-fill me-2" style="color:var(--pln)"></i>Rekap Capaian per ULP — Januari 2026</span>
    <span class="live"></span>
  </div>
  <div class="card-body p-0">
    <div class="row g-0">
      @foreach($rekapUlp as $ulp)
      @php
        $w = $ulp['avg_capaian'] >= 100 ? '#059669' : ($ulp['avg_capaian'] >= 80 ? '#D97706' : '#DC2626');
        $bg= $ulp['avg_capaian'] >= 100 ? '#DCFCE7' : ($ulp['avg_capaian'] >= 80 ? '#FEF3C7' : '#FEE2E2');
      @endphp
      <div class="col-6 col-md-4 col-xl-2" style="border-right:1px solid #F1F5F9;border-bottom:1px solid #F1F5F9">
        <div class="p-3 text-center">
          <div style="font-size:10.5px;font-weight:700;color:#64748B;margin-bottom:6px;text-transform:uppercase;letter-spacing:.5px">{{ $ulp['kode'] }}</div>
          <div style="font-size:9.5px;color:#94A3B8;margin-bottom:8px">{{ $ulp['nama'] }}</div>
          <div style="font-size:22px;font-weight:800;color:{{ $w }}">{{ $ulp['avg_capaian'] }}%</div>
          <div style="font-size:9px;color:#94A3B8;margin-top:3px">Rata-rata capaian</div>
          <div class="progress mt-2" style="height:4px">
            <div class="progress-bar" style="width:{{ min($ulp['avg_capaian'],100) }}%;background:{{ $w }}"></div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</div>

{{-- CHARTS --}}
<div class="row g-3 mb-4">
  <div class="col-12 col-xl-8">
    <div class="card h-100">
      <div class="card-header"><i class="bi bi-bar-chart-line-fill me-2" style="color:var(--pln)"></i>Tren Skor KPI UP3</div>
      <div class="card-body p-3"><canvas id="trenChart" height="200"></canvas></div>
    </div>
  </div>
  <div class="col-12 col-xl-4">
    <div class="card h-100">
      <div class="card-header"><i class="bi bi-pie-chart-fill me-2" style="color:var(--pln)"></i>Per Kategori</div>
      <div class="card-body p-3">
        <canvas id="katChart" height="160"></canvas>
        <div class="mt-3" style="font-size:11.5px">
          @foreach($perKategori as $k)
          <div class="d-flex align-items-center justify-content-between mb-2">
            <div class="d-flex align-items-center gap-2">
              <span style="width:9px;height:9px;border-radius:50%;background:{{ $k['warna'] }};display:inline-block"></span>
              <span style="color:#334155;font-weight:500">{{ $k['kode'] }}</span>
            </div>
            <span style="font-weight:700;color:{{ $k['capaian']>=100?'#059669':($k['capaian']>=80?'#D97706':'#DC2626') }}">{{ $k['capaian'] }}%</span>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>

{{-- KPI KRITIS & TERBAIK --}}
<div class="row g-3">
  <div class="col-12 col-lg-6">
    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-exclamation-triangle-fill me-2" style="color:#DC2626"></i>KPI Perlu Perhatian</span>
        <span class="badge-bad">{{ $kritis->count() }} KPI</span>
      </div>
      <div class="card-body p-0">
        @forelse($kritis as $r)
        <div style="padding:11px 15px;border-bottom:1px solid #F8FAFC">
          <div class="d-flex justify-content-between align-items-start mb-1">
            <div>
              <div style="font-size:12.5px;font-weight:600">{{ $r->indikator->nama }}</div>
              <span style="font-size:10px;background:{{ $r->indikator->kategori->warna }}20;color:{{ $r->indikator->kategori->warna }};padding:1px 7px;border-radius:8px;font-weight:600">{{ $r->indikator->kode }}</span>
            </div>
            <span class="text-bad" style="font-size:13px">{{ number_format($r->capaian,1) }}%</span>
          </div>
          <div class="progress"><div class="progress-bar bg-danger" style="width:{{ min($r->capaian,100) }}%"></div></div>
          <div class="d-flex justify-content-between mt-1" style="font-size:10px;color:#94A3B8">
            <span>Real: {{ number_format($r->nilai,4) }} {{ $r->indikator->satuan }}</span>
            <span>Target: {{ number_format($r->target_snapshot,4) }}</span>
          </div>
        </div>
        @empty
        <div class="text-center py-4"><div style="font-size:28px">🎉</div><p class="text-muted mb-0 mt-1" style="font-size:13px">Semua KPI dalam kondisi baik!</p></div>
        @endforelse
      </div>
    </div>
  </div>
  <div class="col-12 col-lg-6">
    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-trophy-fill me-2" style="color:#D97706"></i>KPI Terbaik</span>
        <span class="badge-ok">{{ $terbaik->count() }} KPI</span>
      </div>
      <div class="card-body p-0">
        @forelse($terbaik as $r)
        <div style="padding:11px 15px;border-bottom:1px solid #F8FAFC">
          <div class="d-flex justify-content-between align-items-start mb-1">
            <div>
              <div style="font-size:12.5px;font-weight:600">{{ $r->indikator->nama }}</div>
              <span style="font-size:10px;background:{{ $r->indikator->kategori->warna }}20;color:{{ $r->indikator->kategori->warna }};padding:1px 7px;border-radius:8px;font-weight:600">{{ $r->indikator->kode }}</span>
            </div>
            <span class="text-ok" style="font-size:13px">{{ number_format($r->capaian,1) }}%</span>
          </div>
          <div class="progress"><div class="progress-bar bg-success" style="width:{{ min($r->capaian,100) }}%"></div></div>
        </div>
        @empty
        <div class="text-center py-4 text-muted" style="font-size:13px">Belum ada data.</div>
        @endforelse
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
const trenLabels = @json(array_column($tren,'label'));
const trenSkor   = @json(array_column($tren,'skor'));

new Chart(document.getElementById('trenChart'),{type:'bar',data:{labels:trenLabels,datasets:[
  {label:'Skor KPI',data:trenSkor,backgroundColor:trenSkor.map(v=>v>=90?'#05966970':v>=80?'#2563EB70':'#DC262670'),borderColor:trenSkor.map(v=>v>=90?'#059669':v>=80?'#2563EB':'#DC2626'),borderWidth:2,borderRadius:6},
  {type:'line',label:'Tren',data:trenSkor,borderColor:'#003B93',borderWidth:2,tension:0.4,pointRadius:4,pointBackgroundColor:'#003B93',fill:false}
]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,max:110,grid:{color:'#F1F5F9'},ticks:{font:{size:11},callback:v=>v+' pts'}},x:{grid:{display:false},ticks:{font:{size:11}}}}}});

const katData = @json($perKategori);
new Chart(document.getElementById('katChart'),{type:'doughnut',data:{labels:katData.map(k=>k.nama),datasets:[{data:katData.map(k=>k.capaian),backgroundColor:katData.map(k=>k.warna+'CC'),borderColor:katData.map(k=>k.warna),borderWidth:2,hoverOffset:5}]},options:{responsive:true,cutout:'65%',plugins:{legend:{display:false},tooltip:{callbacks:{label:ctx=>' '+ctx.label+': '+ctx.parsed+'%'}}}}});
</script>
@endpush