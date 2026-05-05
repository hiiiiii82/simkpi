<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash};

class PenggunaController extends Controller {
    public function index() { return view('pengguna.index', ['users'=>User::orderBy('role')->orderBy('name')->get()]); }

    public function store(Request $request) {
        $request->validate(['name'=>'required|string|max:100','email'=>'required|email|unique:users,email','role'=>'required|in:admin,manajer,supervisor,pegawai','nip'=>'nullable|string|max:20|unique:users,nip','jabatan'=>'nullable|string|max:100','unit_kerja'=>'nullable|string|max:100','password'=>'required|min:6|confirmed']);
        User::create(['name'=>$request->name,'email'=>$request->email,'role'=>$request->role,'nip'=>$request->nip,'jabatan'=>$request->jabatan,'unit_kerja'=>$request->unit_kerja,'password'=>Hash::make($request->password),'is_active'=>true]);
        return back()->with('success','Pengguna berhasil ditambahkan.');
    }

    public function update(Request $request, $id) {
        $u = User::findOrFail($id);
        $request->validate(['name'=>'required|string|max:100','role'=>'required|in:admin,manajer,supervisor,pegawai']);
        $u->update($request->only('name','role','jabatan','unit_kerja'));
        if ($request->filled('password')) { $request->validate(['password'=>'min:6|confirmed']); $u->update(['password'=>Hash::make($request->password)]); }
        return back()->with('success','Data pengguna diperbarui.');
    }

    public function destroy($id) {
        if ((int)$id === Auth::id()) return back()->withErrors(['Tidak dapat menghapus akun sendiri.']);
        User::findOrFail($id)->delete();
        return back()->with('success','Pengguna dihapus.');
    }

    public function toggle($id) { $u = User::findOrFail($id); $u->update(['is_active'=>!$u->is_active]); return back()->with('success','Status pengguna diperbarui.'); }

    public function profil()         { return view('pengguna.profil', ['user'=>Auth::user()]); }
    public function updateProfil(Request $request) {
        $request->validate(['name'=>'required|string|max:100']);
        Auth::user()->update($request->only('name','jabatan','unit_kerja'));
        return back()->with('success','Profil diperbarui.');
    }
    public function gantiPassword(Request $request) {
        $request->validate(['password_lama'=>'required','password'=>'required|min:6|confirmed']);
        if (!Hash::check($request->password_lama, Auth::user()->password)) return back()->withErrors(['password_lama'=>'Password lama salah.']);
        Auth::user()->update(['password'=>Hash::make($request->password)]);
        return back()->with('success','Password berhasil diubah.');
    }
}