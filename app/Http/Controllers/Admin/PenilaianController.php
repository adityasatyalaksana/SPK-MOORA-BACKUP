<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Penilaian, Jalur, Kriteria, Biaya};
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    public function index()
    {
        $kriterias = Kriteria::with('subKriterias')->get(); 
        
        // Eager Loading relasi biaya agar data Estimasi dan Harga Reguler bisa terbaca
        $penilaians = Penilaian::with([
            'jalur.gunung', 
            'biaya.start_terminal', 
            'biaya.end_terminal'
        ])->get();
        
        $jalurs = Jalur::with('gunung')->get();
        $biayas = Biaya::with(['start_terminal', 'end_terminal'])->get();

        return view('admin.penilaian.index', compact('kriterias', 'penilaians', 'jalurs', 'biayas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jalur_id' => 'required',
            'biaya_id' => 'required',
            'nilai' => 'required|array',
        ]);

        foreach ($request->nilai as $kriteria_id => $skor) {
            Penilaian::updateOrCreate(
                ['jalur_id' => $request->jalur_id, 'biaya_id' => $request->biaya_id, 'kriteria_id' => $kriteria_id],
                ['nilai' => $skor]
            );
        }
        return back()->with('success', 'Data Berhasil Disimpan.');
    }

    public function destroy($jalur_id, $biaya_id)
    {
        Penilaian::where('jalur_id', $jalur_id)->where('biaya_id', $biaya_id)->delete();
        return back()->with('success', 'Penilaian berhasil dihapus.');
    }
}