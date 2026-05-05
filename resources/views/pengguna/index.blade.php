@extends('layouts.app')
@section('title','Manajemen Pengguna')
@section('page-title','Manajemen Pengguna')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <p class="text-muted mb-0" style="font-size:13px">Kelola akun pengguna sistem SIMKPI.</p>
  <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#mdlTambah"><i class="bi bi-plus-lg me-1"></i>Tambah Pengguna</button>
</div>
<div class="card"><div class="table-responsive"><table class="table mb-0">
  <thead><tr><th>#</th><th>Nama</th><th>Email</th><th>NIP</th><th>Jabatan / Unit</th><th class="text-center">Role</th><th class="text-center">Status</th><th class="text-center">Aksi</th></tr></thead>
  <tbody>@forelse($users as $i => $u)
    <tr>
      <td style="color:#94A3B8">{{ $i+1 }}</td>
      <td><div class="d-flex align-items-center gap-2"><div style="width:30px;height:30px;border-radius:50%;background:var(--pln);display:flex;align-items:center;justify-content:center;color:#fff;font-size:10px;font-weight:700;flex-shrink:0">{{ $u->inisial }}</div><span style="font-weight:600;font-size:13px">{{ $u->name }}</span></div></td>
      <td style="font-size:12px;color:#64748B">{{ $u->email }}</td>
      <td><code style="font-size:11px">{{ $u->nip ?? '—' }}</code></td>
      <td style="font-size:12px">{{ $u->jabatan ?? '—' }}<br><span style="color:#94A3B8;font-size:10px">{{ $u->unit_kerja }}</span></td>
      <td class="text-center"><span style="background:{{ match($u->role){'admin'=>'#FEF3C7','manajer'=>'#DBEAFE','supervisor'=>'#F3E8FF',default=>'#F1F5F9'} }};color:{{ match($u->role){'admin'=>'#92400E','manajer'=>'#1E40AF','supervisor'=>'#5B21B6',default=>'#64748B'} }};padding:2px 10px;border-radius:20px;font-size:11px;font-weight:700">{{ $u->role_label }}</span></td>
      <td class="text-center"><span class="{{ $u->is_active?'badge-ok':'badge-bad' }}">{{ $u->is_active?'Aktif':'Nonaktif' }}</span></td>
      <td class="text-center"><div class="d-flex gap-1 justify-content-center">
        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#mdlEdit" data-id="{{ $u->id }}" data-name="{{ $u->name }}" data-role="{{ $u->role }}" data-jabatan="{{ $u->jabatan }}" data-unit="{{ $u->unit_kerja }}"><i class="bi bi-pencil"></i></button>
        <form action="{{ route('pengguna.toggle',$u->id) }}" method="POST">@csrf @method('PATCH')<button class="btn btn-sm {{ $u->is_active?'btn-outline-warning':'btn-outline-success' }}"><i class="bi bi-{{ $u->is_active?'pause':'play' }}"></i></button></form>
        @if($u->id!==auth()->id())<form action="{{ route('pengguna.destroy',$u->id) }}" method="POST" onsubmit="return confirm('Hapus pengguna?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>@endif
      </div></td>
    </tr>
  @empty<tr><td colspan="8" class="text-center py-4 text-muted">Belum ada pengguna.</td></tr>@endforelse</tbody>
</table></div></div>

<div class="modal fade" id="mdlTambah" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content" style="border-radius:13px;border:none">
  <div class="modal-header" style="background:linear-gradient(135deg,#003B93,#0052CC);color:#fff;border-radius:13px 13px 0 0"><h5 class="modal-title fw-bold">Tambah Pengguna</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
  <form action="{{ route('pengguna.store') }}" method="POST">@csrf
    <div class="modal-body p-4"><div class="row g-3">
      <div class="col-md-6"><label class="form-label fw-bold" style="font-size:11px">NAMA *</label><input type="text" name="name" class="form-control" required></div>
      <div class="col-md-6"><label class="form-label fw-bold" style="font-size:11px">EMAIL *</label><input type="email" name="email" class="form-control" required></div>
      <div class="col-md-4"><label class="form-label fw-bold" style="font-size:11px">NIP</label><input type="text" name="nip" class="form-control" maxlength="20"></div>
      <div class="col-md-4"><label class="form-label fw-bold" style="font-size:11px">ROLE *</label><select name="role" class="form-select" required><option value="pegawai">Pegawai</option><option value="supervisor">Supervisor</option><option value="manajer">Manajer</option><option value="admin">Administrator</option></select></div>
      <div class="col-md-4"><label class="form-label fw-bold" style="font-size:11px">UNIT KERJA</label><input type="text" name="unit_kerja" class="form-control"></div>
      <div class="col-12"><label class="form-label fw-bold" style="font-size:11px">JABATAN</label><input type="text" name="jabatan" class="form-control"></div>
      <div class="col-md-6"><label class="form-label fw-bold" style="font-size:11px">PASSWORD *</label><input type="password" name="password" class="form-control" required minlength="6"></div>
      <div class="col-md-6"><label class="form-label fw-bold" style="font-size:11px">KONFIRMASI *</label><input type="password" name="password_confirmation" class="form-control" required></div>
    </div></div>
    <div class="modal-footer"><button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary btn-sm">Simpan</button></div>
  </form>
</div></div></div>

<div class="modal fade" id="mdlEdit" tabindex="-1"><div class="modal-dialog"><div class="modal-content" style="border-radius:13px;border:none">
  <div class="modal-header" style="background:linear-gradient(135deg,#059669,#065F46);color:#fff;border-radius:13px 13px 0 0"><h5 class="modal-title fw-bold">Edit Pengguna</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
  <form id="fmEdit" method="POST">@csrf @method('PUT')
    <div class="modal-body p-4">
      <div class="mb-3"><label class="form-label fw-bold" style="font-size:11px">NAMA</label><input type="text" name="name" id="ename" class="form-control" required></div>
      <div class="row g-3">
        <div class="col-md-6"><label class="form-label fw-bold" style="font-size:11px">ROLE</label><select name="role" id="erole" class="form-select"><option value="pegawai">Pegawai</option><option value="supervisor">Supervisor</option><option value="manajer">Manajer</option><option value="admin">Administrator</option></select></div>
        <div class="col-md-6"><label class="form-label fw-bold" style="font-size:11px">UNIT KERJA</label><input type="text" name="unit_kerja" id="eunit" class="form-control"></div>
        <div class="col-12"><label class="form-label fw-bold" style="font-size:11px">JABATAN</label><input type="text" name="jabatan" id="ejab" class="form-control"></div>
        <div class="col-md-6"><label class="form-label fw-bold" style="font-size:11px">PASSWORD BARU</label><input type="password" name="password" class="form-control" minlength="6"></div>
        <div class="col-md-6"><label class="form-label fw-bold" style="font-size:11px">KONFIRMASI</label><input type="password" name="password_confirmation" class="form-control"></div>
      </div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-success btn-sm">Simpan</button></div>
  </form>
</div></div></div>
@endsection
@push('scripts')
<script>
document.getElementById('mdlEdit').addEventListener('show.bs.modal',function(e){
  const b=e.relatedTarget;
  document.getElementById('fmEdit').action='/pengguna/'+b.dataset.id;
  document.getElementById('ename').value=b.dataset.name;
  document.getElementById('erole').value=b.dataset.role;
  document.getElementById('ejab').value=b.dataset.jabatan;
  document.getElementById('eunit').value=b.dataset.unit;
});
</script>
@endpush