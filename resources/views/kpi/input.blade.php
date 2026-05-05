@extends('layouts.app')
@section('title','Input Data Kinerja')
@section('page-title','Input Data Kinerja UP3')

@section('content')

{{-- Flash error --}}
@if($errors->any())
<div class="alert alert-danger mb-3">
  <strong>Terjadi kesalahan:</strong>
  <ul class="mb-0 mt-1">
    @foreach($errors->all() as $e)
    <li>{{ $e }}</li>
    @endforeach
  </ul>
</div>
@endif

<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
  <p class="text-muted mb-0" style="font-size:13px">
    Input nilai realisasi KPI UP3 Surakarta. Data dikirim ke admin untuk divalidasi.
  </p>
  <form method="GET" class="d-flex gap-2 flex-wrap">
    <select name="bulan" class="form-select form-select-sm" style="width:auto">
      @php $namaBulan = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']; @endphp
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
  Menampilkan data periode: <strong>{{ $namaBulan[$bulan] }} {{ $tahun }}</strong>
  &nbsp;|&nbsp; Login sebagai: <strong>{{ auth()->user()->name }}</strong>
  ({{ auth()->user()->role_label }})
</div>

@php $grouped = $indikators->groupBy(fn($i) => $i->kategori->nama); @endphp

@foreach($grouped as $namaKat => $inds)
@php $kat = $inds->first()->kategori; @endphp
<div class="card mb-4">
  <div class="card-header d-flex align-items-center gap-2"
       style="border-left:4px solid {{ $kat->warna }}">
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
          <th class="text-end">Target</th>
          <th class="text-center" style="width:160px">Realisasi</th>
          <th class="text-center">Status</th>
          <th class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($inds as $ind)
        @php
          $r        = $ind->realisasis->first();
          $terkunci = $r && $r->status === 'approved';
        @endphp
        <tr class="{{ $terkunci ? 'table-light' : '' }}">
          <td>
            <code style="font-size:10px;background:#F1F5F9;padding:2px 6px;border-radius:4px">
              {{ $ind->kode }}
            </code>
          </td>
          <td style="font-size:12.5px;font-weight:500;max-width:240px">{{ $ind->nama }}</td>
          <td>
            <span style="background:#EFF6FF;color:#1E40AF;padding:1px 7px;border-radius:8px;font-size:10px">
              {{ $ind->satuan }}
            </span>
          </td>
          <td class="text-end" style="font-weight:600;font-size:13px">
            {{ number_format($ind->target, 4) }}
          </td>
          <td class="text-center">
            @if($r)
              @if($r->status === 'approved')
                <span class="text-ok fw-bold">{{ number_format($r->nilai, 4) }}</span>
                <div style="font-size:10px;color:#059669">✅ {{ number_format($r->capaian, 1) }}% — Disetujui</div>
              @elseif($r->status === 'submitted')
                <span class="text-warn fw-bold">{{ number_format($r->nilai, 4) }}</span>
                <div style="font-size:10px;color:#D97706">⏳ {{ number_format($r->capaian, 1) }}% — Menunggu validasi</div>
              @elseif($r->status === 'rejected')
                <span class="text-bad fw-bold">{{ number_format($r->nilai, 4) }}</span>
                <div style="font-size:10px;color:#DC2626">❌ Ditolak — silakan input ulang</div>
              @else
                <span style="color:#94A3B8;font-size:12px">{{ number_format($r->nilai, 4) }}</span>
              @endif
            @else
              <span style="color:#94A3B8;font-size:12px">—</span>
            @endif
          </td>
          <td class="text-center">
            <span class="{{ $r?->status_badge ?? 'badge-gray' }}">
              {{ $r?->status_label ?? 'Belum Input' }}
            </span>
          </td>
          <td class="text-center">
            @if(!$terkunci)
              <button
                class="btn btn-sm btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#mdlInput"
                data-id="{{ $ind->id }}"
                data-kode="{{ $ind->kode }}"
                data-nama="{{ addslashes($ind->nama) }}"
                data-target="{{ $ind->target }}"
                data-satuan="{{ $ind->satuan }}"
                data-arah="{{ $ind->arah }}"
                data-nilai="{{ $r?->nilai ?? '' }}"
                data-ket="{{ addslashes($r?->keterangan ?? '') }}">
                <i class="bi bi-pencil"></i>
                {{ $r && $r->status !== 'approved' ? 'Edit' : 'Input' }}
              </button>
            @else
              <span style="font-size:11px;color:#94A3B8">Terkunci ✓</span>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endforeach

{{-- ═══ MODAL INPUT ═══ --}}
<div class="modal fade" id="mdlInput" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius:13px;border:none">
      <div class="modal-header"
           style="background:linear-gradient(135deg,#003B93,#0052CC);color:#fff;border-radius:13px 13px 0 0">
        <h5 class="modal-title fw-bold">
          <i class="bi bi-pencil-square me-2"></i>Input Realisasi KPI
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <form action="{{ route('input.store') }}" method="POST" id="formInput">
        @csrf
        <div class="modal-body p-4">

          {{-- Hidden fields --}}
          <input type="hidden" name="indikator_id" id="mi_id">
          <input type="hidden" name="bulan" value="{{ $bulan }}">
          <input type="hidden" name="tahun" value="{{ $tahun }}">

          {{-- Info indikator --}}
          <div class="p-3 rounded mb-3" style="background:#F0F4FF">
            <div style="font-size:10px;font-weight:700;color:#003B93;margin-bottom:2px">INDIKATOR</div>
            <div id="mi_kode" style="font-size:10px;color:#64748B;font-family:monospace"></div>
            <div id="mi_nama" style="font-size:13px;font-weight:600;color:#1E293B;margin-top:2px"></div>
            <div style="font-size:11px;color:#64748B;margin-top:4px">
              Target: <strong id="mi_target"></strong>
              <span id="mi_satuan" style="color:#003B93"></span>
              &nbsp;|&nbsp;
              Arah: <strong id="mi_arah"></strong>
            </div>
          </div>

          {{-- Input nilai --}}
          <div class="mb-3">
            <label class="form-label fw-bold" style="font-size:11px">
              NILAI REALISASI <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <input
                type="number"
                name="nilai"
                id="mi_nilai"
                class="form-control"
                step="any"
                required
                placeholder="Masukkan nilai realisasi">
              <span class="input-group-text bg-white" id="mi_sat2"></span>
            </div>
            <div id="preview_capaian" class="mt-2" style="font-size:12px;color:#64748B;display:none">
              Estimasi capaian: <strong id="est_capaian">—</strong>
            </div>
          </div>

          {{-- Keterangan --}}
          <div>
            <label class="form-label fw-bold" style="font-size:11px">KETERANGAN</label>
            <textarea
              name="keterangan"
              id="mi_ket"
              class="form-control"
              rows="2"
              placeholder="Keterangan tambahan (opsional)..."></textarea>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
            Batal
          </button>
          <button type="submit" class="btn btn-primary btn-sm" id="btnSubmit">
            <i class="bi bi-send-fill me-1"></i>Kirim untuk Divalidasi
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
// Data indikator dari modal trigger
let currentArah   = 'naik';
let currentTarget = 0;

document.getElementById('mdlInput').addEventListener('show.bs.modal', function(e) {
  const btn = e.relatedTarget;

  // Isi hidden + info
  document.getElementById('mi_id').value            = btn.dataset.id;
  document.getElementById('mi_kode').textContent    = btn.dataset.kode;
  document.getElementById('mi_nama').textContent    = btn.dataset.nama;
  document.getElementById('mi_target').textContent  = btn.dataset.target;
  document.getElementById('mi_satuan').textContent  = btn.dataset.satuan;
  document.getElementById('mi_sat2').textContent    = btn.dataset.satuan;
  document.getElementById('mi_nilai').value         = btn.dataset.nilai;
  document.getElementById('mi_ket').value           = btn.dataset.ket;

  currentArah   = btn.dataset.arah;
  currentTarget = parseFloat(btn.dataset.target) || 0;

  const arahLabel = currentArah === 'turun'
    ? '⬇ Turun (semakin kecil semakin baik)'
    : '⬆ Naik (semakin besar semakin baik)';
  document.getElementById('mi_arah').textContent = arahLabel;

  // Hitung estimasi jika nilai sudah ada
  hitungEstimasi();
  document.getElementById('mi_nilai').focus();
});

// Hitung estimasi capaian real-time
document.getElementById('mi_nilai').addEventListener('input', hitungEstimasi);

function hitungEstimasi() {
  const nilaiInput = document.getElementById('mi_nilai').value;
  const preview    = document.getElementById('preview_capaian');
  const estEl      = document.getElementById('est_capaian');

  if (!nilaiInput || isNaN(parseFloat(nilaiInput))) {
    preview.style.display = 'none';
    return;
  }

  const nilai = parseFloat(nilaiInput);
  let capaian = 0;

  if (currentTarget === 0) {
    capaian = nilai > 0 ? 110 : 100;
  } else if (currentArah === 'turun') {
    capaian = Math.min((currentTarget / Math.max(Math.abs(nilai), 0.0001)) * 100, 150);
  } else {
    capaian = Math.min((nilai / currentTarget) * 100, 150);
  }

  capaian = Math.round(capaian * 100) / 100;
  const warna = capaian >= 100 ? '#059669' : capaian >= 80 ? '#D97706' : '#DC2626';
  estEl.innerHTML = `<span style="color:${warna};font-size:13px">${capaian.toFixed(1)}%</span>`;
  preview.style.display = 'block';
}

// Reset modal saat ditutup
document.getElementById('mdlInput').addEventListener('hidden.bs.modal', function() {
  document.getElementById('formInput').reset();
  document.getElementById('mi_id').value = '';
  document.getElementById('preview_capaian').style.display = 'none';
});

// Cegah double submit
document.getElementById('formInput').addEventListener('submit', function() {
  const btn = document.getElementById('btnSubmit');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';
});
</script>
@endpush