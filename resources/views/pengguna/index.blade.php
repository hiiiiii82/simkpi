@extends('layouts.app')
@section('title','Manajemen Pengguna')
@section('page-title','Manajemen Pengguna')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
  <p class="text-muted mb-0" style="font-size:13px">Kelola akun pengguna sistem SIMKPI. Terdapat 2 role: <strong>Admin UP3</strong> (admin utama) dan <strong>Admin ULP</strong> (input KPI per ULP).</p>
  <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#mdlTambah">
    <i class="bi bi-plus-lg me-1"></i>Tambah Pengguna
  </button>
</div>

{{-- Ringkasan per ULP --}}
<div class="row g-2 mb-4">
  @php
    $ulpStatus = $ulps->map(function($u) use ($users) {
      $admin = $users->where('role','admin_ulp')->where('ulp_id',$u->id)->first();
      return ['ulp'=>$u,'admin'=>$admin];
    });
  @endphp
  @foreach($ulpStatus as $row)
  <div class="col-md-2 col-sm-4">
    <div class="card" style="border:2px solid {{ $row['admin'] && $row['admin']->is_active ? '#059669' : '#DC2626' }}">
      <div class="card-body p-2 text-center">
        <div style="font-size:10px;font-weight:700;color:#64748B">{{ $row['ulp']->kode }}</div>
        <div style="font-size:11px;font-weight:600;color:#1E293B">{{ Str::title(strtolower($row['ulp']->nama)) }}</div>
        @if($row['admin'])
          <span style="font-size:9px;background:#DCFCE7;color:#166534;padding:1px 6px;border-radius:8px">
            ✓ {{ Str::limit($row['admin']->name, 12) }}
          </span>
        @else
          <span style="font-size:9px;background:#FEE2E2;color:#991B1B;padding:1px 6px;border-radius:8px">
            ✗ Belum ada admin
          </span>
        @endif
      </div>
    </div>
  </div>
  @endforeach
</div>

<div class="card">
  <div class="table-responsive">
    <table class="table mb-0">
      <thead>
        <tr>
          <th>#</th><th>Nama</th><th>Email</th><th>NIP</th>
          <th>Role</th><th>ULP</th><th class="text-center">Status</th><th class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $i => $u)
        <tr>
          <td style="color:#94A3B8">{{ $i+1 }}</td>
          <td>
            <div class="d-flex align-items-center gap-2">
              <div style="width:30px;height:30px;border-radius:50%;background:{{ $u->role==='admin_up3' ? '#003B93' : '#059669' }};display:flex;align-items:center;justify-content:center;color:#fff;font-size:10px;font-weight:700;flex-shrink:0">
                {{ $u->inisial }}
              </div>
              <div>
                <div style="font-weight:600;font-size:13px">{{ $u->name }}</div>
                <div style="font-size:10px;color:#94A3B8">{{ $u->jabatan ?? '—' }}</div>
              </div>
            </div>
          </td>
          <td style="font-size:12px;color:#64748B">{{ $u->email }}</td>
          <td><code style="font-size:11px">{{ $u->nip ?? '—' }}</code></td>
          <td>
            @if($u->role === 'admin_up3')
              <span style="background:#EFF6FF;color:#1E40AF;padding:2px 10px;border-radius:20px;font-size:11px;font-weight:700">
                <i class="bi bi-shield-fill-check me-1"></i>Admin UP3
              </span>
            @else
              <span style="background:#F0FDF4;color:#166534;padding:2px 10px;border-radius:20px;font-size:11px;font-weight:700">
                <i class="bi bi-building me-1"></i>Admin ULP
              </span>
            @endif
          </td>
          <td>
            @if($u->ulp)
              <span style="font-size:11px;font-weight:600;color:#0052CC">{{ $u->ulp->nama }}</span>
            @else
              <span style="font-size:11px;color:#94A3B8">— UP3 Surakarta</span>
            @endif
          </td>
          <td class="text-center">
            <span class="{{ $u->is_active ? 'badge-ok' : 'badge-bad' }}">
              {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
            </span>
          </td>
          <td class="text-center">
            <div class="d-flex gap-1 justify-content-center">
              <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#mdlEdit"
                data-id="{{ $u->id }}" data-name="{{ $u->name }}" data-role="{{ $u->role }}"
                data-jabatan="{{ $u->jabatan }}" data-ulp="{{ $u->ulp_id ?? '' }}">
                <i class="bi bi-pencil"></i>
              </button>
              <form action="{{ route('pengguna.toggle', $u->id) }}" method="POST">
                @csrf @method('PATCH')
                <button class="btn btn-sm {{ $u->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}"
                  title="{{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                  <i class="bi bi-{{ $u->is_active ? 'pause-fill' : 'play-fill' }}"></i>
                </button>
              </form>
              @if($u->id !== auth()->id())
              <form action="{{ route('pengguna.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Hapus pengguna ini?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
              </form>
              @endif
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada pengguna.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- MODAL TAMBAH ─────────────────────────────────────────── --}}
<div class="modal fade" id="mdlTambah" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="border-radius:13px;border:none">
      <div class="modal-header" style="background:linear-gradient(135deg,#003B93,#0052CC);color:#fff;border-radius:13px 13px 0 0">
        <h5 class="modal-title fw-bold"><i class="bi bi-person-plus-fill me-2"></i>Tambah Pengguna</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('pengguna.store') }}" method="POST">@csrf
        <div class="modal-body p-4">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-bold" style="font-size:11px">NAMA LENGKAP *</label>
              <input type="text" name="name" class="form-control" required placeholder="Nama lengkap pengguna">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold" style="font-size:11px">EMAIL *</label>
              <input type="email" name="email" class="form-control" required placeholder="email@pln.local">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold" style="font-size:11px">NIP</label>
              <input type="text" name="nip" class="form-control" maxlength="20" placeholder="Nomor Induk Pegawai">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold" style="font-size:11px">ROLE *</label>
              <select name="role" id="trole" class="form-select" required onchange="toggleUlpField(this.value, 'tulp')">
                <option value="">— Pilih Role —</option>
                <option value="admin_up3">Admin UP3 (Admin Utama)</option>
                <option value="admin_ulp">Admin ULP (Input KPI)</option>
              </select>
            </div>
            <div class="col-md-4" id="wrap_tulp" style="display:none">
              <label class="form-label fw-bold" style="font-size:11px">ULP <span class="text-danger">*</span></label>
              <select name="ulp_id" id="tulp" class="form-select">
                <option value="">— Pilih ULP —</option>
                @foreach($ulps as $ul)
                @php $sudahAda = $users->where('role','admin_ulp')->where('ulp_id',$ul->id)->where('is_active',true)->isNotEmpty(); @endphp
                <option value="{{ $ul->id }}" {{ $sudahAda ? 'disabled' : '' }}>
                  {{ $ul->nama }} {{ $sudahAda ? '(sudah ada admin)' : '' }}
                </option>
                @endforeach
              </select>
            </div>
            <div class="col-12">
              <label class="form-label fw-bold" style="font-size:11px">JABATAN</label>
              <input type="text" name="jabatan" class="form-control" placeholder="Jabatan pengguna">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold" style="font-size:11px">PASSWORD *</label>
              <input type="password" name="password" class="form-control" required minlength="6" placeholder="Min. 6 karakter">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold" style="font-size:11px">KONFIRMASI PASSWORD *</label>
              <input type="password" name="password_confirmation" class="form-control" required placeholder="Ulangi password">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-floppy-fill me-1"></i>Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- MODAL EDIT ──────────────────────────────────────────── --}}
<div class="modal fade" id="mdlEdit" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius:13px;border:none">
      <div class="modal-header" style="background:linear-gradient(135deg,#059669,#065F46);color:#fff;border-radius:13px 13px 0 0">
        <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Pengguna</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="fmEdit" method="POST">@csrf @method('PUT')
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label fw-bold" style="font-size:11px">NAMA</label>
            <input type="text" name="name" id="ename" class="form-control" required>
          </div>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-bold" style="font-size:11px">ROLE</label>
              <select name="role" id="erole" class="form-select" onchange="toggleUlpField(this.value, 'eulp')">
                <option value="admin_up3">Admin UP3</option>
                <option value="admin_ulp">Admin ULP</option>
              </select>
            </div>
            <div class="col-md-6" id="wrap_eulp">
              <label class="form-label fw-bold" style="font-size:11px">ULP</label>
              <select name="ulp_id" id="eulp" class="form-select">
                <option value="">— Pilih ULP —</option>
                @foreach($ulps as $ul)
                <option value="{{ $ul->id }}">{{ $ul->nama }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-12">
              <label class="form-label fw-bold" style="font-size:11px">JABATAN</label>
              <input type="text" name="jabatan" id="ejab" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold" style="font-size:11px">PASSWORD BARU</label>
              <input type="password" name="password" class="form-control" minlength="6" placeholder="Kosongkan jika tidak diubah">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold" style="font-size:11px">KONFIRMASI</label>
              <input type="password" name="password_confirmation" class="form-control">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-floppy-fill me-1"></i>Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function toggleUlpField(role, selectId) {
  const wrapT = document.getElementById('wrap_' + selectId.replace('ulp','') + 'ulp') ||
                document.getElementById('wrap_tulp') ||
                document.getElementById('wrap_eulp');
  const tWrap = selectId === 'tulp' ? document.getElementById('wrap_tulp') : document.getElementById('wrap_eulp');
  const sel   = document.getElementById(selectId);
  if (role === 'admin_ulp') {
    tWrap.style.display = 'block';
    sel.required = true;
  } else {
    tWrap.style.display = 'none';
    sel.required = false;
    sel.value = '';
  }
}

document.getElementById('mdlEdit').addEventListener('show.bs.modal', function(e) {
  const b = e.relatedTarget;
  document.getElementById('fmEdit').action = '/pengguna/' + b.dataset.id;
  document.getElementById('ename').value   = b.dataset.name;
  document.getElementById('erole').value   = b.dataset.role;
  document.getElementById('ejab').value    = b.dataset.jabatan || '';
  // Set ULP
  const eulp = document.getElementById('eulp');
  eulp.value = b.dataset.ulp || '';
  toggleUlpField(b.dataset.role, 'eulp');
});

// Init form tambah
document.getElementById('trole').dispatchEvent(new Event('change'));
</script>
@endpush
