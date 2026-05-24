<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Biaya;
use App\Models\Terminal;
use App\Models\Jalur; // Penting: Import Model Jalur
use Illuminate\Http\Request;

class BiayaController extends Controller
{
    public function index()
    {
        // Menambahkan 'jalur.gunung' agar nama gunung bisa tampil di tabel
        $biayas = Biaya::with(['start_terminal', 'end_terminal', 'jalur.gunung'])->latest()->get();
        
        // Data untuk dropdown di modal
        $startPoints = Terminal::where('tipe', 'Starting Point')->get();
        $endPoints = Terminal::where('tipe', 'Ending Point')->get();
        $jalurs = Jalur::with('gunung')->get();

        return view('admin.biaya.index', compact('biayas', 'startPoints', 'endPoints', 'jalurs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jalur_id' => 'required|exists:jalurs,id', // Validasi jalur harus diisi
            'start_terminal_id' => 'required',
            'end_terminal_id' => 'required',
            'nama_armada' => 'required',
            'estimasi_perjalanan' => 'required|integer',
            'harga_pp' => 'required|integer',
            'harga_weekend' => 'nullable|integer', // Menampung input harga weekend (opsional)
        ]);

        Biaya::create($request->all());

        return back()->with('success', 'Jalur Bus berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jalur_id' => 'required|exists:jalurs,id',
            'start_terminal_id' => 'required',
            'end_terminal_id' => 'required',
            'nama_armada' => 'required',
            'estimasi_perjalanan' => 'required|integer',
            'harga_pp' => 'required|integer',
            'harga_weekend' => 'nullable|integer', // Menampung update harga weekend (opsional)
        ]);

        $biaya = Biaya::findOrFail($id);
        $biaya->update($request->all());

        return back()->with('success', 'Data armada berhasil diperbarui!');
    }

    public function applyPeriod(Request $request)
    {
        $request->validate([
            'biaya_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'harga_periode' => 'required|integer',
        ]);

        $biaya = Biaya::findOrFail($request->biaya_id);
        $biaya->update([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'harga_periode' => $request->harga_periode,
        ]);

        return back()->with('success', 'Harga periode berhasil diterapkan!');
    }

    /**
     * FITUR BARU: Mengosongkan data periode khusus (Reset ke tarif reguler/weekend)
     */
    public function resetPeriod($id)
    {
        $biaya = Biaya::findOrFail($id);
        $biaya->update([
            'start_date' => null,
            'end_date' => null,
            'harga_periode' => null,
        ]);

        return back()->with('success', 'Harga periode berhasil direset ke tarif normal!');
    }

    public function destroy($id)
    {
        Biaya::findOrFail($id)->delete();
        return back()->with('success', 'Data berhasil dihapus!');
    }
}