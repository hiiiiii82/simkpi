<?php
namespace App\Http\Controllers;
use App\Models\{Indikator, Kategori};
use Illuminate\Http\Request;

class IndikatorController extends Controller
{
    public function index()
    {
        $indikators = Indikator::with('kategori')->orderBy('kategori_id')->orderBy('kode')->get();
        $kategoris  = Kategori::where('is_active',true)->orderBy('kode')->get();
        return view('kpi.indikator', compact('indikators','kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'nama'        => 'required|string|max:200',
            'kode'        => 'required|string|max:20|unique:indikators,kode',
            'satuan'      => 'required|string|max:50',
            'target'      => 'required|numeric|min:0',
            'bobot'       => 'required|numeric|min:0|max:100',
            'arah'        => 'required|in:naik,turun',
            'periode'     => 'required|in:bulanan,triwulan,tahunan',
        ]);
        Indikator::create($request->all());
        return back()->with('success','Indikator KPI berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $ind = Indikator::findOrFail($id);
        $request->validate([
            'nama'   => 'required|string|max:200',
            'target' => 'required|numeric|min:0',
            'bobot'  => 'required|numeric|min:0|max:100',
            'arah'   => 'required|in:naik,turun',
        ]);
        $ind->update($request->only('nama','target','bobot','arah','periode','satuan'));
        return back()->with('success','Indikator KPI berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Indikator::findOrFail($id)->delete();
        return back()->with('success','Indikator KPI dihapus.');
    }
}
