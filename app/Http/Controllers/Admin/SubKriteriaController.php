<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubKriteria;
use App\Models\Kriteria;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class SubKriteriaController extends Controller
{
    public function index()
    {
        $subkriterias = SubKriteria::with('kriteria')->get();
        $kriterias = Kriteria::all();
        return view('admin.sub_kriteria.index', compact('subkriterias', 'kriterias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kriteria_id' => 'required',
            'nama_sub'    => 'required|string|max:255',
            'bobot'       => 'required|numeric',
        ]);

        $sub = SubKriteria::create($request->all());
        ActivityLog::log("Menambahkan Sub-Kriteria " . $sub->nama_sub . " pada " . ($sub->kriteria->nama_kriteria ?? ''));
        return back()->with('success', 'Sub-Kriteria berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kriteria_id' => 'required',
            'nama_sub'    => 'required|string|max:255',
            'bobot'       => 'required|numeric',
        ]);

        $subKriteria = SubKriteria::findOrFail($id);
        $subKriteria->update($request->all());
        ActivityLog::log("Mengubah Sub-Kriteria " . $subKriteria->nama_sub);
        return back()->with('success', 'Sub-Kriteria berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $subKriteria = SubKriteria::findOrFail($id);
        $subKriteria->delete();
        ActivityLog::log("Menghapus Sub-Kriteria " . $subKriteria->nama_sub);
        return back()->with('success', 'Sub-Kriteria berhasil dihapus!');
    }
}