<?php
namespace App\Http\Controllers;

use App\Models\{User, Ulp};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash};
use Illuminate\Validation\Rule;

class PenggunaController extends Controller
{
    public function index()
    {
        $users = User::with('ulp')->orderBy('role')->orderBy('name')->get();
        $ulps  = Ulp::where('is_active', true)->orderBy('nama')->get();

        return view('pengguna.index', compact('users', 'ulps'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email',
            'role'       => 'required|in:admin_up3,admin_ulp',
            'ulp_id'     => [
                Rule::requiredIf($request->role === 'admin_ulp'),
                'nullable',
                'exists:ulps,id',
            ],
            'nip'        => 'nullable|string|max:20|unique:users,nip',
            'jabatan'    => 'nullable|string|max:100',
            'unit_kerja' => 'nullable|string|max:100',
            'password'   => 'required|min:6|confirmed',
        ]);

        // 1 ULP hanya boleh punya 1 admin_ulp aktif
        if ($request->role === 'admin_ulp' && $request->ulp_id) {
            $sudahAda = User::where('role', 'admin_ulp')
                ->where('ulp_id', $request->ulp_id)
                ->where('is_active', true)
                ->exists();

            if ($sudahAda) {
                return back()->withInput()
                    ->withErrors(['ulp_id' => 'ULP ini sudah memiliki admin aktif.']);
            }
        }

        User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'role'       => $request->role,
            'ulp_id'     => $request->role === 'admin_ulp' ? $request->ulp_id : null,
            'nip'        => $request->nip,
            'jabatan'    => $request->jabatan,
            'unit_kerja' => $request->unit_kerja,
            'password'   => Hash::make($request->password),
            'is_active'  => true,
        ]);

        return back()->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $u = User::findOrFail($id);

        $request->validate([
            'name'       => 'required|string|max:100',
            'role'       => 'required|in:admin_up3,admin_ulp',
            'ulp_id'     => [
                Rule::requiredIf($request->role === 'admin_ulp'),
                'nullable',
                'exists:ulps,id',
            ],
            'jabatan'    => 'nullable|string|max:100',
            'unit_kerja' => 'nullable|string|max:100',
        ]);

        // Cek duplikasi admin_ulp jika ulp_id berubah
        if ($request->role === 'admin_ulp' && $request->ulp_id) {
            $sudahAda = User::where('role', 'admin_ulp')
                ->where('ulp_id', $request->ulp_id)
                ->where('is_active', true)
                ->where('id', '!=', $u->id)
                ->exists();

            if ($sudahAda) {
                return back()->withInput()
                    ->withErrors(['ulp_id' => 'ULP ini sudah memiliki admin aktif.']);
            }
        }

        $u->update([
            'name'       => $request->name,
            'role'       => $request->role,
            'ulp_id'     => $request->role === 'admin_ulp' ? $request->ulp_id : null,
            'jabatan'    => $request->jabatan,
            'unit_kerja' => $request->unit_kerja,
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6|confirmed']);
            $u->update(['password' => Hash::make($request->password)]);
        }

        return back()->with('success', 'Data pengguna diperbarui.');
    }

    public function destroy($id)
    {
        if ((int)$id === Auth::id()) {
            return back()->withErrors(['Tidak dapat menghapus akun sendiri.']);
        }
        User::findOrFail($id)->delete();
        return back()->with('success', 'Pengguna dihapus.');
    }

    public function toggle($id)
    {
        $u = User::findOrFail($id);
        if ((int)$id === Auth::id()) {
            return back()->withErrors(['Tidak dapat menonaktifkan akun sendiri.']);
        }
        $u->update(['is_active' => !$u->is_active]);
        return back()->with('success', 'Status pengguna diperbarui.');
    }

    public function profil()
    {
        return view('pengguna.profil', ['user' => Auth::user()->load('ulp')]);
    }

    public function updateProfil(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100']);
        Auth::user()->update($request->only('name', 'jabatan', 'unit_kerja'));
        return back()->with('success', 'Profil diperbarui.');
    }

    public function gantiPassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password'      => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($request->password_lama, Auth::user()->password)) {
            return back()->withErrors(['password_lama' => 'Password lama salah.']);
        }

        Auth::user()->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Password berhasil diubah.');
    }
}
