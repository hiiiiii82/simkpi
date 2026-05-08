@extends('layouts.app')
@section('title','Dashboard – ' . $ulp->nama)
@section('page-title','Dashboard ULP ' . $ulp->nama)

@section('content')
<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="stat s-blue">
      <div class="stat-val">{{ $sudahInput }}</div>
      <div class="stat-lbl">Indikator Sudah Diinput</div>
      <div class="stat-sub">dari {{ $totalInd }} total indikator</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat {{ $avgCapaian >= 100 ? 's-green' : ($avgCapaian >= 80 ? 's-orange' : 's-red') }}">
      <div class="stat-val">{{ number_format($avgCapaian, 1) }}%</div>
      <div class="stat-lbl">Rata-rata Capaian</div>
      <div class="stat-sub">{{ $ulp->nama }}</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat s-teal">
      <div class="stat-val">{{ $totalInd - $sudahInput }}</div>
      <div class="stat-lbl">Belum Diinput</div>
      <div class="stat-sub">Perlu diselesaikan</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat s-purple">
      <div class="stat-val">{{ $reals->filter(fn($r) => $r->capaian >= 100)->count() }}</div>
      <div class="stat-lbl">Indikator ≥ 100%</div>
      <div class="stat-sub">Mencapai / melampaui target</div>
    </div>
  </div>
</div>

@php $namaBulan=['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']; @endphp
<div class="card mb-4">
  <div class="card-header d-flex align-items-center justify-content-between">
    <span><i class="bi bi-building me-2"></i>Status KPI {{ $ulp->nama }} — {{ $namaBulan[$bulan] }} {{ $tahun }}</span>
    <a href="{{ route('input.index') }}" class="btn btn-sm btn-primary">
      <i class="bi bi-pencil-square me-1"></i>Input / Edit KPI
    </a>
  </div>
  <div class="table-responsive">
    <table class="table mb-0">
      <thead>
        <tr>
          <th>Kode</th><th>Indikator</th><th class="text-end">Target</th>
          <th class="text-end">Realisasi</th><th class="text-center">Capaian</th>
        </tr>
      </thead>
      <tbody>
        @php
          $indikators = \App\Models\Indikator::with(['kategori','indikatorUlps' => fn($q)=>$q->where('ulp_id',$ulp->id)])
            ->where('is_active',true)->orderBy('kode')->get();
        @endphp
        @foreach($indikators as $ind)
        @php
          $r      = $reals->where('indikator_id', $ind->id)->first();
          $iUlp   = $ind->indikatorUlps->first();
          $target = $iUlp ? $iUlp->target : $ind->target;
        @endphp
        <tr>
          <td><code style="font-size:10px;background:#F1F5F9;padding:2px 6px;border-radius:4px">{{ $ind->kode }}</code></td>
          <td style="font-size:12px">{{ $ind->nama }}</td>
          <td class="text-end" style="font-size:12px">{{ number_format($target,4) }} <span style="font-size:10px;color:#94A3B8">{{ $ind->satuan }}</span></td>
          <td class="text-end" style="font-size:12px">
            @if($r) <strong>{{ number_format($r->nilai,4) }}</strong>
            @else <span style="color:#94A3B8">—</span> @endif
          </td>
          <td class="text-center">
            @if($r)
              @php $c = $r->capaian; @endphp
              <div class="d-flex align-items-center gap-2">
                <div class="progress flex-grow-1" style="height:6px">
                  <div class="progress-bar" style="width:{{ min($c,100) }}%;background:{{ $c>=100?'#059669':($c>=80?'#D97706':'#DC2626') }}"></div>
                </div>
                <span style="font-size:11px;font-weight:700;color:{{ $c>=100?'#059669':($c>=80?'#D97706':'#DC2626') }};min-width:38px">
                  {{ number_format($c,1) }}%
                </span>
              </div>
            @else
              <span class="badge-gray">Belum Input</span>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
