@extends('layouts.app')
@section('title','ULP '.$ulp->nama)
@section('page-title','Kinerja ULP '.$ulp->nama)

@section('content')

{{-- ULP Selector --}}
<div class="d-flex flex-wrap align-items-center gap-2 mb-4">
  @foreach($ulps as $u)
  <a href="{{ route('ulp.show',$u->id) }}"
     class="btn btn-sm {{ $u->id == $ulp->id ? 'btn-primary' : 'btn-outline-primary' }}"
     style="font-size:12px;font-weight:600">
    {{ $u->nama }}
  </a>
  @endforeach
  <div class="ms-auto d-flex gap-2">
    <form method="GET" class="d-flex gap-2">
      <select name="bulan" class="form-select form-select-sm" style="width:auto">
        @foreach(range(1,12) as $b)
        <option value="{{ $b }}" @selected($bulan==$b)>{{ ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$b] }}</option>
        @endforeach
      </select>
      <select name="tahun" class="form-select form-select-sm" style="width:auto">
        @foreach(range(2025,2027) as $t)<option value="{{ $t }}" @selected($tahun==$t)>{{ $t }}</option>@endforeach
      </select>
      <button class="btn btn-sm btn-outline-secondary">Tampilkan</button>
    </form>
  </div>
</div>

{{-- Summary --}}
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div class="stat s-blue" style="padding:14px">
      <div class="stat-val">{{ $avgCapaian }}%</div>
      <div class="stat-lbl">Rata-rata Capaian</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat s-green" style="padding:14px">
      <div class="stat-val">{{ $realisasis->count() }}</div>
      <div class="stat-lbl">Indikator Terlaporkan</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat s-orange" style="padding:14px">
      <div class="stat-val">{{ $realisasis->where('capaian','>=',100)->count() }}</div>
      <div class="stat-lbl">KPI Tercapai (≥100%)</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat s-red" style="padding:14px">
      <div class="stat-val">{{ $realisasis->where('capaian','<',80)->count() }}</div>
      <div class="stat-lbl">KPI Kritis (&lt;80%)</div>
    </div>
  </div>
</div>

{{-- Per Kategori --}}
@foreach($perKategori as $namaKat => $reals)
<div class="card mb-4">
  <div class="card-header d-flex align-items-center justify-content-between"
       style="border-left:4px solid {{ $reals->first()->indikator->kategori->warna }}">
    <span style="font-weight:700;color:{{ $reals->first()->indikator->kategori->warna }}">{{ $namaKat }}</span>
    <span style="font-size:11px;color:#64748B">{{ $reals->count() }} indikator &nbsp;|&nbsp; Avg: <strong>{{ round($reals->avg('capaian'),1) }}%</strong></span>
  </div>
  <div class="table-responsive">
    <table class="table mb-0">
      <thead>
        <tr><th>Kode</th><th>Indikator KPI</th><th>Satuan</th><th class="text-end">Target</th><th class="text-end">Realisasi</th><th style="width:180px">Capaian</th></tr>
      </thead>
      <tbody>
        @foreach($reals as $r)
        @php $c = $r->capaian ?? 0; $cc = $c>=100?'#059669':($c>=80?'#D97706':'#DC2626'); @endphp
        <tr>
          <td><code style="font-size:10px;background:#F1F5F9;padding:2px 6px;border-radius:4px">{{ $r->indikator->kode }}</code></td>
          <td style="font-size:13px;font-weight:500;max-width:250px">{{ $r->indikator->nama }}</td>
          <td><span style="background:#EFF6FF;color:#1E40AF;padding:2px 8px;border-radius:8px;font-size:10px">{{ $r->indikator->satuan }}</span></td>
          <td class="text-end" style="font-size:12px;color:#64748B">{{ number_format($r->target_snapshot,4) }}</td>
          <td class="text-end" style="font-weight:600;font-size:13px">{{ number_format($r->nilai,4) }}</td>
          <td>
            <div class="d-flex align-items-center gap-2">
              <div class="progress flex-grow-1"><div class="progress-bar" style="width:{{ min($c,100) }}%;background:{{ $cc }}"></div></div>
              <span style="font-size:12px;font-weight:700;color:{{ $cc }};min-width:46px;text-align:right">{{ number_format($c,1) }}%</span>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endforeach

@if($realisasis->isEmpty())
<div class="card">
  <div class="card-body text-center py-5">
    <div style="font-size:36px">📊</div>
    <p class="text-muted mt-2 mb-0">Belum ada data realisasi ULP {{ $ulp->nama }} untuk periode ini.</p>
  </div>
</div>
@endif

@endsection