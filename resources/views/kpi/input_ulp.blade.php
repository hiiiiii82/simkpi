@extends('layouts.app')
@section('title','Input KPI – ' . $ulp->nama)
@section('page-title','Input Data KPI ' . $ulp->nama)

@section('content')

@if($errors->any())
<div class="alert alert-danger mb-3">
  <strong>Terjadi kesalahan:</strong>
  <ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

{{-- Header ULP ─────────────────────────────────────────────── --}}
<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
  <div class="d-flex align-items-center gap-2">
    <div style="background:linear-gradient(135deg,#003B93,#0052CC);color:#fff;padding:8px 16px;border-radius:8px;font-weight:700;font-size:13px">
      <i class="bi bi-building me-2"></i>{{ $ulp->kode }}
    </div>
    <span class="text-muted" style="font-size:13px">
      Input KPI Bulanan &nbsp;|&nbsp; Login: <strong>{{ auth()->user()->name }}</strong>
    </span>
  </div>
  <form method="GET" class="d-flex gap-2">
    @php $namaBulan=['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']; @endphp
    <select name="bulan" class="form-select form-select-sm" style="width:auto">
      @foreach(range(1,12) as $b)
      <option value="{{ $b }}" @selected($bulan==$b)>{{ $namaBulan[$b] }}</option>
      @endforeach
    </select>
    <select name="tahun" class="form-select form-select-sm" style="width:auto">
      @foreach(range(2025,2027) as $t)
      <option value="{{ $t }}" @selected($tahun==$t)>{{ $t }}</option>
      @endforeach
    </select>
    <button class="btn btn-sm btn-outline-primary">Tampilkan</button>
  </form>
</div>

{{-- Info periode --}}
<div class="alert alert-info py-2 mb-4" style="font-size:13px;border-radius:8px">
  <i class="bi bi-info-circle me-2"></i>
  Periode: <strong>{{ $namaBulan[$bulan] }} {{ $tahun }}</strong>
  &nbsp;|&nbsp;
  ULP: <strong>{{ $ulp->nama }}</strong>
  &nbsp;|&nbsp;
  @php
    $sudahInput = $indikators->filter(fn($i) => $i->realisasiUlps->isNotEmpty())->count();
    $total      = $indikators->count();
  @endphp
  Progress: <strong style="color:{{ $sudahInput==$total ? '#059669' : '#D97706' }}">{{ $sudahInput }}/{{ $total }}</strong> indikator
</div>

@php $grouped = $indikators->groupBy(fn($i) => $i->kategori->nama); @endphp

@foreach($grouped as $namaKat => $inds)
@php $kat = $inds->first()->kategori; @endphp
<div class="card mb-4">
  <div class="card-header d-flex align-items-center gap-2" style="border-left:4px solid {{ $kat->warna }}">
    <i class="bi bi-tag-fill" style="color:{{ $kat->warna }}"></i>
    <strong>{{ $namaKat }}</strong>
    <span style="font-size:11px;color:#64748B">({{ $inds->count() }} indikator)</span>
  </div>
  <div class="table-responsive">
    <table class="table mb-0">
      <thead>
        <tr>
          <th>Kode</th>
          <th>Indikator</th>
          <th>Satuan</th>
          <th class="text-end">Target ULP</th>
          <th class="text-center" style="width:180px">Realisasi</th>
          <th class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($inds as $ind)
        @php
          $r       = $ind->realisasiUlps->first();
          $indUlp  = $ind->indikatorUlps->first();
          $target  = $indUlp ? $indUlp->target : $ind->target;
        @endphp
        <tr>
          <td><code style="font-size:10px;background:#F1F5F9;padding:2px 6px;border-radius:4px">{{ $ind->kode }}</code></td>
          <td style="font-size:12.5px;font-weight:500;max-width:240px">{{ $ind->nama }}</td>
          <td><span style="background:#EFF6FF;color:#1E40AF;padding:1px 7px;border-radius:8px;font-size:10px">{{ $ind->satuan }}</span></td>
          <td class="text-end" style="font-weight:600;font-size:13px">{{ number_format($target, 4) }}</td>
          <td class="text-center">
            @if($r)
              <span style="font-weight:700;color:#0052CC">{{ number_format($r->nilai, 4) }}</span>
              <div style="font-size:10px;color:#059669">✅ {{ number_format($r->capaian, 1) }}%</div>
            @else
              <span style="color:#94A3B8;font-size:12px">—</span>
            @endif
          </td>
          <td class="text-center">
            <button class="btn btn-sm btn-primary"
              data-bs-toggle="modal" data-bs-target="#mdlInput"
              data-id="{{ $ind->id }}"
              data-kode="{{ $ind->kode }}"
              data-nama="{{ addslashes($ind->nama) }}"
              data-target="{{ $target }}"
              data-satuan="{{ $ind->satuan }}"
              data-arah="{{ $ind->arah }}"
              data-nilai="{{ $r?->nilai ?? '' }}"
              data-ket="{{ addslashes($r?->keterangan ?? '') }}"
              data-rid="{{ $r?->id ?? '' }}">
              <i class="bi bi-pencil"></i> {{ $r ? 'Edit' : 'Input' }}
            </button>
            @if($r)
            <form action="{{ route('input.destroy', $r->id) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('Hapus data ini?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </form>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endforeach

{{-- MODAL INPUT ──────────────────────────────────────────────── --}}
<div class="modal fade" id="mdlInput" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius:13px;border:none">
      <div class="modal-header" style="background:linear-gradient(135deg,#003B93,#0052CC);color:#fff;border-radius:13px 13px 0 0">
        <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Input Realisasi KPI – {{ $ulp->nama }}</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('input.store') }}" method="POST" id="formInput">
        @csrf
        <input type="hidden" name="ulp_id" value="{{ $ulp->id }}">
        <div class="modal-body p-4">
          <input type="hidden" name="indikator_id" id="mi_id">
          <input type="hidden" name="bulan" value="{{ $bulan }}">
          <input type="hidden" name="tahun" value="{{ $tahun }}">

          <div class="p-3 rounded mb-3" style="background:#F0F4FF">
            <div style="font-size:10px;font-weight:700;color:#003B93;margin-bottom:2px">INDIKATOR</div>
            <div id="mi_kode" style="font-size:10px;color:#64748B;font-family:monospace"></div>
            <div id="mi_nama" style="font-size:13px;font-weight:600;color:#1E293B;margin-top:2px"></div>
            <div style="font-size:11px;color:#64748B;margin-top:4px">
              Target ULP: <strong id="mi_target"></strong>
              <span id="mi_satuan" style="color:#003B93"></span>
              &nbsp;|&nbsp; Arah: <strong id="mi_arah"></strong>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold" style="font-size:11px">NILAI REALISASI <span class="text-danger">*</span></label>
            <div class="input-group">
              <input type="number" name="nilai" id="mi_nilai" class="form-control" step="any" required placeholder="Masukkan nilai realisasi">
              <span class="input-group-text bg-white" id="mi_sat2"></span>
            </div>
            <div id="preview_capaian" class="mt-2" style="font-size:12px;color:#64748B;display:none">
              Estimasi capaian: <strong id="est_capaian">—</strong>
            </div>
          </div>

          <div>
            <label class="form-label fw-bold" style="font-size:11px">KETERANGAN</label>
            <textarea name="keterangan" id="mi_ket" class="form-control" rows="2" placeholder="Keterangan tambahan (opsional)..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary btn-sm" id="btnSubmit">
            <i class="bi bi-floppy-fill me-1"></i>Simpan Data
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
let currentArah = 'naik', currentTarget = 0;

document.getElementById('mdlInput').addEventListener('show.bs.modal', function(e) {
  const btn = e.relatedTarget;
  document.getElementById('mi_id').value           = btn.dataset.id;
  document.getElementById('mi_kode').textContent   = btn.dataset.kode;
  document.getElementById('mi_nama').textContent   = btn.dataset.nama;
  document.getElementById('mi_target').textContent = parseFloat(btn.dataset.target).toLocaleString('id-ID', {minimumFractionDigits:2});
  document.getElementById('mi_satuan').textContent = ' ' + btn.dataset.satuan;
  document.getElementById('mi_sat2').textContent   = btn.dataset.satuan;
  document.getElementById('mi_nilai').value        = btn.dataset.nilai;
  document.getElementById('mi_ket').value          = btn.dataset.ket;
  currentArah   = btn.dataset.arah;
  currentTarget = parseFloat(btn.dataset.target) || 0;
  document.getElementById('mi_arah').textContent   = currentArah === 'turun' ? '⬇ Turun' : '⬆ Naik';
  hitungEstimasi();
  document.getElementById('mi_nilai').focus();
});

document.getElementById('mi_nilai').addEventListener('input', hitungEstimasi);

function hitungEstimasi() {
  const val = document.getElementById('mi_nilai').value;
  const preview = document.getElementById('preview_capaian');
  if (!val || isNaN(parseFloat(val))) { preview.style.display='none'; return; }
  const nilai = parseFloat(val);
  let capaian = 0;
  if (currentTarget === 0) { capaian = nilai > 0 ? 110 : 100; }
  else if (currentArah === 'turun') { capaian = Math.min((currentTarget / Math.max(Math.abs(nilai), 0.0001)) * 100, 150); }
  else { capaian = Math.min((nilai / currentTarget) * 100, 150); }
  capaian = Math.round(capaian * 100) / 100;
  const warna = capaian >= 100 ? '#059669' : capaian >= 80 ? '#D97706' : '#DC2626';
  document.getElementById('est_capaian').innerHTML = `<span style="color:${warna};font-size:13px">${capaian.toFixed(1)}%</span>`;
  preview.style.display = 'block';
}

document.getElementById('mdlInput').addEventListener('hidden.bs.modal', function() {
  document.getElementById('formInput').reset();
  document.getElementById('preview_capaian').style.display = 'none';
});

document.getElementById('formInput').addEventListener('submit', function() {
  const btn = document.getElementById('btnSubmit');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';
});
</script>
@endpush
