<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
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

        Kriteria::create($request->all());
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

        $kriteria->update($request->all());
        return back()->with('success', 'Kriteria berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Kriteria::findOrFail($id)->delete();
        return back()->with('success', 'Kriteria berhasil dihapus!');
    }
}