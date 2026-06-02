<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    public function index()
    {
        $kriterias = Kriteria::all();
        return view('admin.kriteria.index', compact('kriterias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_kriteria' => 'required|unique:kriterias',
            'nama_kriteria' => 'required',
            'tipe'          => 'required|in:Benefit,Cost',
            'bobot'         => 'required|numeric',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();
        $kriteria = Kriteria::create($data);
        ActivityLog::log("Menambahkan Kriteria " . $kriteria->kode_kriteria . " - " . $kriteria->nama_kriteria);
        return back()->with('success', 'Kriteria berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $kriteria = Kriteria::findOrFail($id);

        // FIX: Tambahkan validasi ketat agar data inputan aman dan sinkron
        $request->validate([
            'kode_kriteria' => 'required|unique:kriterias,kode_kriteria,' . $id,
            'nama_kriteria' => 'required',
            'tipe'          => 'required|in:Benefit,Cost', // Memastikan isinya hanya 'Benefit' atau 'Cost'
            'bobot'         => 'required|numeric',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();
        $kriteria->update($data);
        ActivityLog::log("Mengubah Kriteria " . $kriteria->kode_kriteria . " - " . $kriteria->nama_kriteria);
        return back()->with('success', 'Kriteria berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        $kriteria->delete();
        ActivityLog::log("Menghapus Kriteria " . $kriteria->kode_kriteria . " - " . $kriteria->nama_kriteria);
        return back()->with('success', 'Kriteria berhasil dihapus!');
    }
}