@extends('layouts.app')
@section('title','Kategori KPI')
@section('page-title','Kategori KPI')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <p class="text-muted mb-0" style="font-size:13px">Kelola kategori pengelompokan indikator KPI.</p>
  <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#mdlTambah">
    <i class="bi bi-plus-lg me-1"></i>Tambah Kategori
  </button>
</div>

<div class="card">
  <div class="table-responsive">
    <table class="table mb-0">
      <thead>
        <tr><th>#</th><th>Nama Kategori</th><th>Kode</th><th>Warna</th><th>Indikator</th><th class="text-center">Aksi</th></tr>
      </thead>
      <tbody>
        @forelse($kategoris as $i => $k)
        <tr>
          <td style="color:#94A3B8">{{ $i+1 }}</td>
          <td style="font-weight:600">{{ $k->nama }}</td>
          <td><code style="background:#F1F5F9;padding:2px 8px;border-radius:5px">{{ $k->kode }}</code></td>
          <td><span style="background:{{ $k->warna }};padding:4px 14px;border-radius:20px;color:#fff;font-size:11px;font-weight:700">{{ $k->warna }}</span></td>
          <td><span style="background:#DBEAFE;color:#1E40AF;padding:2px 10px;border-radius:20px;font-size:11px;font-weight:700">{{ $k->indikators_count }} indikator</span></td>
          <td class="text-center">
            <button class="btn btn-sm btn-outline-primary me-1"
              data-bs-toggle="modal" data-bs-target="#mdlEdit"
              data-id="{{ $k->id }}" data-nama="{{ $k->nama }}"
              data-kode="{{ $k->kode }}" data-warna="{{ $k->warna }}"
              data-deskripsi="{{ $k->deskripsi }}">
              <i class="bi bi-pencil"></i>
            </button>
            <form action="{{ route('kategori.destroy',$k->id) }}" method="POST" class="d-inline"
              onsubmit="return confirm('Hapus kategori ini?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada kategori.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="mdlTambah" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content" style="border-radius:14px;border:none">
    <div class="modal-header" style="background:linear-gradient(135deg,#003B93,#0052CC);color:#fff;border-radius:14px 14px 0 0">
      <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Tambah Kategori KPI</h5>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <form action="{{ route('kategori.store') }}" method="POST">
      @csrf
      <div class="modal-body p-4">
        <div class="mb-3">
          <label class="form-label fw-bold" style="font-size:11px">NAMA KATEGORI <span class="text-danger">*</span></label>
          <input type="text" name="nama" class="form-control" required placeholder="contoh: Keandalan Sistem">
        </div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-bold" style="font-size:11px">KODE <span class="text-danger">*</span></label>
            <input type="text" name="kode" class="form-control" required placeholder="contoh: KS" maxlength="10">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold" style="font-size:11px">WARNA <span class="text-danger">*</span></label>
            <input type="color" name="warna" class="form-control form-control-color w-100" value="#003B93">
          </div>
        </div>
        <div class="mt-3">
          <label class="form-label fw-bold" style="font-size:11px">DESKRIPSI</label>
          <textarea name="deskripsi" class="form-control" rows="2" placeholder="Opsional..."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-save me-1"></i>Simpan</button>
      </div>
    </form>
  </div></div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="mdlEdit" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content" style="border-radius:14px;border:none">
    <div class="modal-header" style="background:linear-gradient(135deg,#059669,#065F46);color:#fff;border-radius:14px 14px 0 0">
      <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Kategori KPI</h5>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <form id="fmEdit" method="POST">
      @csrf @method('PUT')
      <div class="modal-body p-4">
        <div class="mb-3">
          <label class="form-label fw-bold" style="font-size:11px">NAMA KATEGORI</label>
          <input type="text" name="nama" id="en" class="form-control" required>
        </div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-bold" style="font-size:11px">KODE</label>
            <input type="text" name="kode" id="ek" class="form-control" required maxlength="10">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold" style="font-size:11px">WARNA</label>
            <input type="color" name="warna" id="ew" class="form-control form-control-color w-100">
          </div>
        </div>
        <div class="mt-3">
          <label class="form-label fw-bold" style="font-size:11px">DESKRIPSI</label>
          <textarea name="deskripsi" id="ed" class="form-control" rows="2"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-save me-1"></i>Simpan</button>
      </div>
    </form>
  </div></div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('mdlEdit').addEventListener('show.bs.modal',function(e){
  const b=e.relatedTarget;
  document.getElementById('fmEdit').action='/kategori/'+b.dataset.id;
  document.getElementById('en').value=b.dataset.nama;
  document.getElementById('ek').value=b.dataset.kode;
  document.getElementById('ew').value=b.dataset.warna;
  document.getElementById('ed').value=b.dataset.deskripsi;
});
</script>
@endpush
