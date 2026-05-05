<?php
namespace App\Http\Controllers;
use App\Models\{Indikator, Kategori, Realisasi};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KpiController extends Controller {
    public function index() {
        $indikators = Indikator::with('kategori')->orderBy('kategori_id')->orderBy('kode')->get();
        $kategoris  = Kategori::where('is_active',true)->get();
        return view('kpi.index', compact('indikators','kategoris'));
    }
    public function store(Request $request) {
        $request->validate(['kategori_id'=>'required|exists:kategoris,id','nama'=>'required|string|max:200','kode'=>'required|string|max:20|unique:indikators,kode','satuan'=>'required|string|max:60','target'=>'required|numeric','bobot'=>'required|numeric|min:0|max:100','arah'=>'required|in:naik,turun','periode'=>'required|in:bulanan,triwulan,tahunan']);
        Indikator::create($request->all());
        return back()->with('success','Indikator KPI berhasil ditambahkan.');
    }
    public function update(Request $request, $id) {
        $ind = Indikator::findOrFail($id);
        $request->validate(['nama'=>'required|string|max:200','target'=>'required|numeric','bobot'=>'required|numeric|min:0|max:100','arah'=>'required|in:naik,turun']);
        $ind->update($request->only('nama','satuan','target','bobot','arah','periode'));
        return back()->with('success','Indikator KPI berhasil diperbarui.');
    }
    public function destroy($id) { Indikator::findOrFail($id)->delete(); return back()->with('success','Indikator KPI dihapus.'); }
}