@extends('layouts.app')
@section('title','Profil Saya')
@section('page-title','Profil Saya')
@section('content')
<div class="row g-4">
  <div class="col-12 col-lg-4"><div class="card"><div class="card-body text-center py-4">
    <div style="width:68px;height:68px;border-radius:50%;background:var(--pln);display:flex;align-items:center;justify-content:center;color:#fff;font-size:22px;font-weight:800;margin:0 auto 12px">{{ $user->inisial }}</div>
    <div style="font-size:15px;font-weight:700">{{ $user->name }}</div>
    <div style="font-size:12px;color:#64748B;margin-top:2px">{{ $user->jabatan ?? '—' }}</div>
    <div class="mt-2"><span style="background:{{ match($user->role){'admin'=>'#FEF3C7','manajer'=>'#DBEAFE','supervisor'=>'#F3E8FF',default=>'#F1F5F9'} }};color:{{ match($user->role){'admin'=>'#92400E','manajer'=>'#1E40AF','supervisor'=>'#5B21B6',default=>'#64748B'} }};padding:3px 14px;border-radius:20px;font-size:12px;font-weight:700">{{ $user->role_label }}</span></div>
    <hr class="my-3">
    <div style="font-size:12px;text-align:left;display:flex;flex-direction:column;gap:8px">
      <div class="d-flex justify-content-between"><span style="color:#64748B">Email</span><span style="font-weight:500">{{ $user->email }}</span></div>
      <div class="d-flex justify-content-between"><span style="color:#64748B">NIP</span><span style="font-weight:500">{{ $user->nip ?? '—' }}</span></div>
      <div class="d-flex justify-content-between"><span style="color:#64748B">Unit Kerja</span><span style="font-weight:500">{{ $user->unit_kerja ?? '—' }}</span></div>
      <div class="d-flex justify-content-between"><span style="color:#64748B">Status</span><span style="font-weight:500;color:{{ $user->is_active?'#059669':'#DC2626' }}">{{ $user->is_active?'Aktif':'Nonaktif' }}</span></div>
    </div>
  </div></div></div>
  <div class="col-12 col-lg-8">
    <div class="card mb-4"><div class="card-header"><i class="bi bi-person-fill me-2" style="color:var(--pln)"></i>Update Profil</div>
      <div class="card-body p-4"><form action="{{ route('profil.update') }}" method="POST">@csrf
        <div class="row g-3">
          <div class="col-md-6"><label class="form-label fw-bold" style="font-size:11px">NAMA LENGKAP</label><input type="text" name="name" class="form-control" value="{{ old('name',$user->name) }}" required></div>
          <div class="col-md-6"><label class="form-label fw-bold" style="font-size:11px">JABATAN</label><input type="text" name="jabatan" class="form-control" value="{{ old('jabatan',$user->jabatan) }}"></div>
          <div class="col-md-6"><label class="form-label fw-bold" style="font-size:11px">UNIT KERJA</label><input type="text" name="unit_kerja" class="form-control" value="{{ old('unit_kerja',$user->unit_kerja) }}"></div>
        </div>
        <div class="mt-3 d-flex justify-content-end"><button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-save me-1"></i>Simpan</button></div>
      </form></div>
    </div>
    <div class="card"><div class="card-header"><i class="bi bi-key-fill me-2" style="color:var(--pln)"></i>Ganti Password</div>
      <div class="card-body p-4">
        @if($errors->has('password_lama'))<div class="alert alert-danger py-2 mb-3" style="font-size:13px;border-radius:8px">{{ $errors->first('password_lama') }}</div>@endif
        <form action="{{ route('profil.password') }}" method="POST">@csrf
          <div class="row g-3">
            <div class="col-12"><label class="form-label fw-bold" style="font-size:11px">PASSWORD LAMA</label><input type="password" name="password_lama" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label fw-bold" style="font-size:11px">PASSWORD BARU</label><input type="password" name="password" class="form-control" required minlength="6"></div>
            <div class="col-md-6"><label class="form-label fw-bold" style="font-size:11px">KONFIRMASI</label><input type="password" name="password_confirmation" class="form-control" required></div>
          </div>
          <div class="mt-3 d-flex justify-content-end"><button type="submit" class="btn btn-warning btn-sm text-dark fw-bold"><i class="bi bi-key me-1"></i>Ganti Password</button></div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection