@extends('layouts.app')
@section('title','Kelola ULP')
@section('page-title','Kelola Unit Layanan Pelanggan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <p class="text-muted mb-0" style="font-size:13px">Kelola daftar ULP di bawah UP3 Surakarta.</p>
  <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#mdlTambah">
    <i class="bi bi-plus-lg me-1"></i>Tambah ULP
  </button>
</div>

<div class="row g-3">
  @foreach($ulps as $u)
  <div class="col-12 col-md-6 col-xl-4">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div class="d-flex align-items-center gap-2">
            <div style="width:36px;height:36px;background:var(--pln);border-radius:9px;display:flex;align-items:center;justify-content:center;color:var(--gold);font-size:11px;font-weight:700">{{ $u->kode }}</div>
            <div>
              <div style="font-size:14px;font-weight:700">{{ $u->nama }}</div>
              <div style="font-size:11px;color:#64748B">{{ $u->is_active ? 'Aktif' : 'Nonaktif' }}</div>
            </div>
          </div>
          <a href="{{ route('ulp.show',$u->id) }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-eye"></i> Lihat
          </a>
        </div>
      </div>
    </div>
  </div>
  @endforeach
</div>

<div class="modal fade" id="mdlTambah" tabindex="-1">
  <div class="modal-dialog modal-sm"><div class="modal-content" style="border-radius:12px;border:none">
    <div class="modal-header" style="background:linear-gradient(135deg,#003B93,#0052CC);color:#fff;border-radius:12px 12px 0 0">
      <h5 class="modal-title fw-bold">Tambah ULP</h5>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <form action="{{ route('ulp.store') }}" method="POST">@csrf
      <div class="modal-body p-4">
        <div class="mb-3"><label class="form-label fw-bold" style="font-size:11px">NAMA ULP</label><input type="text" name="nama" class="form-control" required></div>
        <div><label class="form-label fw-bold" style="font-size:11px">KODE</label><input type="text" name="kode" class="form-control" required maxlength="10"></div>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary btn-sm">Simpan</button></div>
    </form>
  </div></div>
</div>
@endsection