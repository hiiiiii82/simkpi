@extends('layouts.app')
@section('title','Validasi Data KPI')
@section('page-title','Validasi Data KPI')
@section('content')
<div class="d-flex justify-content-between mb-3">
  <p class="text-muted mb-0" style="font-size:13px">Data KPI yang dikirim pegawai dan menunggu persetujuan.</p>
  <span class="badge-wait">{{ $realisasis->count() }} menunggu</span>
</div>
<div class="card"><div class="table-responsive"><table class="table mb-0">
  <thead><tr><th>#</th><th>Indikator</th><th>Diinput Oleh</th><th class="text-center">Periode</th><th class="text-end">Target</th><th class="text-end">Realisasi</th><th class="text-center">Capaian</th><th class="text-center">Aksi</th></tr></thead>
  <tbody>
    @forelse($realisasis as $i => $r)
    <tr>
      <td style="color:#94A3B8">{{ $i+1 }}</td>
      <td><div style="font-size:12.5px;font-weight:600">{{ $r->indikator->nama }}</div><span style="font-size:10px;background:{{ $r->indikator->kategori->warna }}20;color:{{ $r->indikator->kategori->warna }};padding:1px 7px;border-radius:8px;font-weight:600">{{ $r->indikator->kode }}</span></td>
      <td><div style="font-size:12.5px">{{ $r->user->name }}</div><div style="font-size:10px;color:#94A3B8">{{ $r->user->unit_kerja }}</div></td>
      <td class="text-center" style="font-size:12px">{{ ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$r->bulan] }} {{ $r->tahun }}</td>
      <td class="text-end">{{ number_format($r->target_snapshot,4) }} <small style="color:#94A3B8">{{ $r->indikator->satuan }}</small></td>
      <td class="text-end" style="font-weight:600">{{ number_format($r->nilai,4) }}</td>
      <td class="text-center"><span class="{{ $r->capaian_warn }}" style="font-size:13px">{{ number_format($r->capaian,1) }}%</span></td>
      <td class="text-center">
        <div class="d-flex gap-1 justify-content-center">
          <form action="{{ route('validasi.approve',$r->id) }}" method="POST">@csrf @method('PATCH')
            <button class="btn btn-sm btn-success" onclick="return confirm('Setujui data ini?')"><i class="bi bi-check-lg"></i></button>
          </form>
          <form action="{{ route('validasi.reject',$r->id) }}" method="POST">@csrf @method('PATCH')
            <button class="btn btn-sm btn-danger" onclick="return confirm('Tolak data ini?')"><i class="bi bi-x-lg"></i></button>
          </form>
        </div>
      </td>
    </tr>
    @empty
    <tr><td colspan="8" class="text-center py-5"><div style="font-size:28px">✅</div><p class="text-muted mb-0 mt-1">Tidak ada data yang menunggu validasi.</p></td></tr>
    @endforelse
  </tbody>
</table></div></div>
@endsection