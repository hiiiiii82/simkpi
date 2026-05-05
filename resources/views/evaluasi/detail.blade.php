@extends('layouts.app')
@section('title','Detail Evaluasi')
@section('page-title','Detail Evaluasi Kinerja')
@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
  <a href="{{ route('evaluasi.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
  <h5 class="mb-0 ms-2" style="font-weight:700">{{ ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$bulan] }} {{ $tahun }}</h5>
</div>
<div class="row g-3 mb-4">
  <div class="col-12 col-md-3"><div class="stat s-blue text-center" style="padding:16px">
    <div style="font-size:30px;font-weight:800;line-height:1">{{ number_format($evaluasi->total_skor,2) }}</div>
    <div style="font-size:11px;opacity:.8;margin-top:3px">Total Skor KPI</div>
    <div class="mt-2"><span class="{{ $evaluasi->pred_kelas }}">{{ $evaluasi->predikat }}</span></div>
    <div style="font-size:11px;opacity:.8;margin-top:5px">Proporsional: {{ number_format($evaluasi->total_proporsional,1) }}%</div>
  </div></div>
  @foreach($perKategori as $nm => $pk)
  <div class="col"><div class="card h-100 text-center"><div class="card-body py-3">
    <div style="font-size:10px;font-weight:600;color:#64748B;margin-bottom:4px">{{ Str::limit($nm,22) }}</div>
    <div style="font-size:19px;font-weight:800;color:{{ $pk['warna'] }}">{{ $pk['skor'] }}</div>
    <div style="font-size:11px;color:#64748B">pts ({{ $pk['capaian'] }}%)</div>
  </div></div></div>
  @endforeach
</div>
<div class="card"><div class="card-header"><i class="bi bi-list-check me-2" style="color:var(--pln)"></i>Rincian Realisasi KPI</div>
  <div class="table-responsive"><table class="table mb-0">
    <thead><tr><th>#</th><th>Kode</th><th>Indikator KPI</th><th class="text-end">Target</th><th class="text-end">Realisasi</th><th class="text-center">Capaian</th><th class="text-center">Bobot</th><th class="text-end">Skor</th></tr></thead>
    <tbody>
      @foreach($realisasis as $i => $r)
      <tr>
        <td style="color:#94A3B8">{{ $i+1 }}</td>
        <td><code style="font-size:10px;background:#F1F5F9;padding:2px 6px;border-radius:4px">{{ $r->indikator->kode }}</code></td>
        <td style="font-size:12.5px;font-weight:500;max-width:240px">{{ $r->indikator->nama }}</td>
        <td class="text-end">{{ number_format($r->target_snapshot,4) }} <small style="color:#94A3B8">{{ $r->indikator->satuan }}</small></td>
        <td class="text-end" style="font-weight:600">{{ number_format($r->nilai,4) }}</td>
        <td class="text-center"><div class="d-flex align-items-center justify-content-center gap-2"><div class="progress" style="width:55px"><div class="progress-bar {{ $r->capaian>=100?'bg-success':($r->capaian>=80?'bg-warning':'bg-danger') }}" style="width:{{ min($r->capaian,100) }}%"></div></div><span class="{{ $r->capaian_warn }}">{{ number_format($r->capaian,1) }}%</span></div></td>
        <td class="text-center"><span class="badge bg-primary">{{ $r->indikator->bobot }}%</span></td>
        <td class="text-end" style="font-weight:700;color:var(--pln)">{{ number_format($r->skor,2) }}</td>
      </tr>
      @endforeach
    </tbody>
    <tfoot><tr style="background:#F0F4FF"><td colspan="7" class="text-end fw-bold" style="font-size:12px">TOTAL SKOR:</td><td class="text-end" style="font-size:15px;font-weight:800;color:var(--pln)">{{ number_format($evaluasi->total_skor,2) }}</td></tr></tfoot>
  </table></div>
</div>
@endsection