@extends('layouts.app')
@section('title','Evaluasi Kinerja')
@section('page-title','Evaluasi Kinerja UP3')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
  <form method="GET" class="d-flex gap-2"><select name="tahun" class="form-select form-select-sm" style="width:auto">@foreach(range(2025,2027) as $t)<option value="{{ $t }}" @selected($tahun==$t)>{{ $t }}</option>@endforeach</select><button class="btn btn-sm btn-outline-primary">Tampilkan</button></form>
  @if(auth()->user()->canValidate())<button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#mdlGen"><i class="bi bi-magic me-1"></i>Generate Evaluasi</button>@endif
</div>
<div class="row g-3 mb-4">
  <div class="col-12 col-lg-8"><div class="card"><div class="card-header"><i class="bi bi-bar-chart-fill me-2" style="color:var(--pln)"></i>Grafik Skor Proporsional {{ $tahun }}</div><div class="card-body"><canvas id="evalChart" height="220"></canvas></div></div></div>
  <div class="col-12 col-lg-4"><div class="card h-100"><div class="card-header">Ringkasan</div>
    <div class="card-body text-center">
      <div style="font-size:40px;font-weight:800;color:var(--pln);line-height:1">{{ number_format($avgSkor,1) }}</div>
      <div style="font-size:12px;color:#64748B;margin:4px 0 10px">Rata-rata Skor Proporsional</div>
      @php $p=\App\Models\Evaluasi::predikatDari($avgSkor);$k=\App\Models\Evaluasi::kelasPredikat($p); @endphp
      <span class="{{ $k }}">{{ $p }}</span>
      <div class="row g-2 mt-3">
        <div class="col-6"><div style="background:#F0F4FF;border-radius:8px;padding:10px"><div style="font-size:18px;font-weight:700;color:#003B93">{{ $daftar->count() }}</div><div style="font-size:10px;color:#64748B">Evaluasi Selesai</div></div></div>
        <div class="col-6"><div style="background:#F0FDF4;border-radius:8px;padding:10px"><div style="font-size:18px;font-weight:700;color:#059669">{{ number_format($daftar->max('total_proporsional')??0,1) }}</div><div style="font-size:10px;color:#64748B">Skor Tertinggi</div></div></div>
      </div>
    </div>
  </div></div>
</div>
<div class="card"><div class="card-header"><i class="bi bi-table me-2" style="color:var(--pln)"></i>Detail per Bulan</div>
  <div class="table-responsive"><table class="table mb-0">
    <thead><tr><th>Periode</th><th class="text-center">Skor Total</th><th class="text-center">Skor Proporsional</th><th class="text-center">Predikat</th><th class="text-center">Status</th><th class="text-center">Aksi</th></tr></thead>
    <tbody>
      @forelse($daftar as $ev)
      <tr>
        <td style="font-weight:600">{{ ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$ev->bulan] }} {{ $ev->tahun }}</td>
        <td class="text-center"><span style="font-size:16px;font-weight:800;color:var(--pln)">{{ number_format($ev->total_skor,2) }}</span></td>
        <td class="text-center"><div class="progress mx-auto" style="width:80px"><div class="progress-bar {{ $ev->total_proporsional>=90?'bg-success':($ev->total_proporsional>=80?'bg-primary':($ev->total_proporsional>=70?'bg-warning':'bg-danger')) }}" style="width:{{ min($ev->total_proporsional,100) }}%"></div></div><small style="font-size:10px;color:#64748B">{{ number_format($ev->total_proporsional,1) }}%</small></td>
        <td class="text-center"><span class="{{ $ev->pred_kelas }}">{{ $ev->predikat }}</span></td>
        <td class="text-center"><span class="{{ $ev->status=='selesai'?'badge-ok':'badge-gray' }}">{{ $ev->status=='selesai'?'Selesai':'Proses' }}</span></td>
        <td class="text-center"><a href="{{ route('evaluasi.detail',[$ev->bulan,$ev->tahun]) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Detail</a></td>
      </tr>
      @empty<tr><td colspan="6" class="text-center py-4 text-muted">Belum ada evaluasi untuk tahun {{ $tahun }}</td></tr>
      @endforelse
    </tbody>
  </table></div>
</div>
<div class="modal fade" id="mdlGen" tabindex="-1"><div class="modal-dialog modal-sm"><div class="modal-content" style="border-radius:13px;border:none">
  <div class="modal-header" style="background:linear-gradient(135deg,#059669,#065F46);color:#fff;border-radius:13px 13px 0 0"><h5 class="modal-title fw-bold"><i class="bi bi-magic me-2"></i>Generate Evaluasi</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
  <form action="{{ route('evaluasi.generate') }}" method="POST">@csrf
    <div class="modal-body p-4">
      <div class="mb-3"><label class="form-label fw-bold" style="font-size:11px">BULAN</label><select name="bulan" class="form-select">@foreach(range(1,12) as $b)<option value="{{ $b }}" @selected(1==$b)>{{ ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$b] }}</option>@endforeach</select></div>
      <div><label class="form-label fw-bold" style="font-size:11px">TAHUN</label><select name="tahun" class="form-select">@foreach(range(2025,2027) as $t)<option value="{{ $t }}" @selected(2026==$t)>{{ $t }}</option>@endforeach</select></div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-success btn-sm"><i class="bi bi-magic me-1"></i>Generate</button></div>
  </form>
</div></div></div>
@endsection
@push('scripts')
<script>
const d=@json($chartSkor);const l=['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
new Chart(document.getElementById('evalChart'),{type:'bar',data:{labels:l,datasets:[{label:'Skor Proporsional (%)',data:d,backgroundColor:d.map(v=>v>=90?'#05966980':v>=80?'#2563EB80':v>0?'#DC262680':'#F1F5F9'),borderColor:d.map(v=>v>=90?'#059669':v>=80?'#2563EB':v>0?'#DC2626':'#E2E8F0'),borderWidth:2,borderRadius:6}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,max:115,grid:{color:'#F1F5F9'},ticks:{font:{size:11},callback:v=>v+'%'}},x:{grid:{display:false},ticks:{font:{size:11}}}}}});
</script>
@endpush