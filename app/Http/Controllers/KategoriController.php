<?php
namespace App\Http\Controllers;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::withCount('indikators')->orderBy('kode')->get();
        return view('kpi.kategori', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:100',
            'kode'  => 'required|string|max:10|unique:kategoris,kode',
            'warna' => 'required|string|max:7',
        ]);
        Kategori::create($request->only('nama','kode','deskripsi','warna'));
        return back()->with('success','Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $kat = Kategori::findOrFail($id);
        $request->validate([
            'nama'  => 'required|string|max:100',
            'kode'  => 'required|string|max:10|unique:kategoris,kode,'.$id,
            'warna' => 'required|string|max:7',
        ]);
        $kat->update($request->only('nama','kode','deskripsi','warna'));
        return back()->with('success','Kategori berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Kategori::findOrFail($id)->delete();
        return back()->with('success','Kategori dihapus.');
    }
}
