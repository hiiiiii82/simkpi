@extends('layouts.app')
@section('title','Laporan')
@section('page-title','Laporan & Unduh')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
  <form method="GET" class="d-flex gap-2">
    <select name="tahun" class="form-select form-select-sm" style="width:auto">@foreach(range(2025,2027) as $t)<option value="{{ $t }}" @selected($tahun==$t)>{{ $t }}</option>@endforeach</select>
    <button class="btn btn-sm btn-outline-primary">Tampilkan</button>
  </form>
  <div class="d-flex gap-2">
    <a href="{{ route('laporan.pdf',['bulan'=>$bulan,'tahun'=>$tahun]) }}" class="btn btn-sm btn-danger"><i class="bi bi-file-pdf me-1"></i>PDF Bulan Ini</a>
    <a href="{{ route('laporan.excel',['bulan'=>$bulan,'tahun'=>$tahun]) }}" class="btn btn-sm btn-success"><i class="bi bi-file-spreadsheet me-1"></i>Excel</a>
  </div>
</div>
<div class="row g-3 mb-4">
  <div class="col-12 col-lg-8"><div class="card"><div class="card-header"><i class="bi bi-graph-up me-2" style="color:var(--pln)"></i>Skor Proporsional Bulanan {{ $tahun }}</div>
    <div class="card-body"><canvas id="lChart" height="220"></canvas></div></div></div>
  <div class="col-12 col-lg-4"><div class="card h-100"><div class="card-header">Per Kategori</div>
    <div class="card-body">@foreach($perKategori as $pk)
      <div class="mb-3"><div class="d-flex justify-content-between mb-1"><span style="font-size:12px;font-weight:600">{{ $pk['nama'] }}</span><span style="font-size:12px;font-weight:700;color:{{ $pk['warna'] }}">{{ $pk['capaian'] }}%</span></div>
      <div class="progress"><div class="progress-bar" style="width:{{ min($pk['capaian'],100) }}%;background:{{ $pk['warna'] }}"></div></div></div>
    @endforeach</div></div></div>
</div>
<div class="card"><div class="card-header"><i class="bi bi-table me-2" style="color:var(--pln)"></i>Rekap Bulanan {{ $tahun }}</div>
  <div class="table-responsive"><table class="table mb-0">
    <thead><tr><th>Bulan</th><th class="text-center">Jml KPI</th><th class="text-center">Total Skor</th><th class="text-center">Proporsional</th><th class="text-center">Predikat</th><th class="text-center">Unduh</th></tr></thead>
    <tbody>@foreach($rekap as $r)
      <tr>
        <td style="font-weight:600">{{ $r['nama'] }}</td>
        <td class="text-center">{{ $r['jumlah'] }}</td>
        <td class="text-center">@if($r['skor']>0)<span style="font-weight:700;font-size:14px;color:var(--pln)">{{ $r['skor'] }}</span>@else<span class="text-muted">—</span>@endif</td>
        <td class="text-center">@if($r['proporsional']>0)<div class="d-flex align-items-center justify-content-center gap-2"><div class="progress" style="width:50px"><div class="progress-bar {{ $r['proporsional']>=90?'bg-success':($r['proporsional']>=80?'bg-primary':'bg-danger') }}" style="width:{{ min($r['proporsional'],100) }}%"></div></div><span style="font-size:12px;font-weight:600">{{ $r['proporsional'] }}%</span></div>@else<span class="text-muted">—</span>@endif</td>
        <td class="text-center">@if($r['predikat']!='-')@php $k=\App\Models\Evaluasi::kelasPredikat($r['predikat']); @endphp<span class="{{ $k }}">{{ $r['predikat'] }}</span>@else<span class="text-muted" style="font-size:12px">Belum ada data</span>@endif</td>
        <td class="text-center">@if($r['jumlah']>0)<div class="d-flex gap-1 justify-content-center">
          <a href="{{ route('laporan.pdf',['bulan'=>$r['bulan'],'tahun'=>$tahun]) }}" class="btn btn-sm btn-outline-danger" style="font-size:11px;padding:2px 8px"><i class="bi bi-file-pdf"></i> PDF</a>
          <a href="{{ route('laporan.excel',['bulan'=>$r['bulan'],'tahun'=>$tahun]) }}" class="btn btn-sm btn-outline-success" style="font-size:11px;padding:2px 8px"><i class="bi bi-file-spreadsheet"></i> XLS</a>
        </div>@else<span class="text-muted" style="font-size:11px">—</span>@endif</td>
      </tr>
    @endforeach</tbody>
  </table></div>
</div>
@endsection
@push('scripts')
<script>
const lb=@json($rekap->pluck('nama')); const sk=@json($rekap->pluck('skor')); const pr=@json($rekap->pluck('proporsional'));
new Chart(document.getElementById('lChart'),{type:'line',data:{labels:lb,datasets:[{label:'Total Skor',data:sk,borderColor:'#003B93',backgroundColor:'#003B9315',borderWidth:2.5,pointRadius:4,tension:0.4,fill:true,yAxisID:'y'},{label:'Proporsional (%)',data:pr,borderColor:'#F5A623',backgroundColor:'transparent',borderWidth:2,pointRadius:4,borderDash:[6,3],tension:0.4,yAxisID:'y1'}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{labels:{font:{size:11}}}},scales:{y:{position:'left',beginAtZero:true,max:110,grid:{color:'#F1F5F9'},ticks:{font:{size:11}}},y1:{position:'right',beginAtZero:true,max:120,grid:{display:false},ticks:{font:{size:11},callback:v=>v+'%'}},x:{grid:{display:false},ticks:{font:{size:11}}}}}});
</script>
@endpush