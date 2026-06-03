<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Biaya;
use App\Models\Terminal;
use App\Models\Jalur; // Penting: Import Model Jalur
use App\Models\ActivityLog;
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
        // Bersihkan pemisah ribuan (titik) sebelum validasi
        if ($request->has('harga_pp')) {
            $request->merge([
                'harga_pp' => (int) str_replace('.', '', $request->harga_pp)
            ]);
        }
        if ($request->has('harga_weekend') && !is_null($request->harga_weekend)) {
            $request->merge([
                'harga_weekend' => (int) str_replace('.', '', $request->harga_weekend)
            ]);
        }

        $request->validate([
            'jalur_id' => 'required|exists:jalurs,id', // Validasi jalur harus diisi
            'start_terminal_id' => 'required',
            'end_terminal_id' => 'required',
            'nama_armada' => 'required',
            'estimasi_perjalanan' => 'required|integer',
            'harga_pp' => 'required|integer',
            'harga_weekend' => 'nullable|integer', // Menampung input harga weekend (opsional)
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();
        $biaya = Biaya::create($data);
        ActivityLog::log("Menambahkan biaya armada " . $biaya->nama_armada);

        return back()->with('success', 'Jalur Bus berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        // Bersihkan pemisah ribuan (titik) sebelum validasi
        if ($request->has('harga_pp')) {
            $request->merge([
                'harga_pp' => (int) str_replace('.', '', $request->harga_pp)
            ]);
        }
        if ($request->has('harga_weekend') && !is_null($request->harga_weekend)) {
            $request->merge([
                'harga_weekend' => (int) str_replace('.', '', $request->harga_weekend)
            ]);
        }

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
        $data = $request->all();
        $data['user_id'] = auth()->id();
        $biaya->update($data);

        ActivityLog::log("Mengubah data biaya armada " . $biaya->nama_armada);

        return back()->with('success', 'Data armada berhasil diperbarui!');
    }

    public function applyPeriod(Request $request)
    {
        // Bersihkan pemisah ribuan (titik) sebelum validasi
        if ($request->has('harga_periode')) {
            $request->merge([
                'harga_periode' => (int) str_replace('.', '', $request->harga_periode)
            ]);
        }

        $request->validate([
            'biaya_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'harga_periode' => 'required|integer',
        ]);

        $biaya = Biaya::findOrFail($request->biaya_id);
        $biaya->update([
            'user_id' => auth()->id(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'harga_periode' => $request->harga_periode,
        ]);

        ActivityLog::log("Menerapkan harga periode khusus untuk armada " . $biaya->nama_armada);

        return back()->with('success', 'Harga periode berhasil diterapkan!');
    }

    /**
     * FITUR BARU: Mengosongkan data periode khusus (Reset ke tarif reguler/weekend)
     */
    public function resetPeriod($id)
    {
        $biaya = Biaya::findOrFail($id);
        $biaya->update([
            'user_id' => auth()->id(),
            'start_date' => null,
            'end_date' => null,
            'harga_periode' => null,
        ]);

        ActivityLog::log("Mereset harga periode khusus untuk armada " . $biaya->nama_armada);

        return back()->with('success', 'Harga periode berhasil direset ke tarif normal!');
    }

    public function destroy($id)
    {
        $biaya = Biaya::findOrFail($id);
        $biaya->delete();
        ActivityLog::log("Menghapus biaya armada " . $biaya->nama_armada);
        return back()->with('success', 'Data berhasil dihapus!');
    }
}