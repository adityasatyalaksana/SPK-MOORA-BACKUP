<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jalur;
use App\Models\Gunung;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class JalurController extends Controller
{
    public function index()
    {
        $jalurs = Jalur::with('gunung')->latest()->get();
        $gunungs = Gunung::all();
        return view('admin.jalur.index', compact('jalurs', 'gunungs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'gunung_id' => 'required',
            'nama_jalur' => 'required|string|max:255',
            'biaya_simaksi' => 'required|integer',
            'estimasi_jam' => 'required|integer',
            'tingkat_kesulitan' => 'required',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();
        $jalur = Jalur::create($data);
        ActivityLog::log("Menambahkan data Jalur " . $jalur->nama_jalur);
        return back()->with('success', 'Jalur berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'gunung_id' => 'required',
            'nama_jalur' => 'required|string|max:255',
            'biaya_simaksi' => 'required|integer',
            'estimasi_jam' => 'required|integer',
            'tingkat_kesulitan' => 'required',
        ]);

        $jalur = Jalur::findOrFail($id);
        $data = $request->all();
        $data['user_id'] = auth()->id();
        $jalur->update($data);

        ActivityLog::log("Mengubah data Jalur " . $jalur->nama_jalur);

        return back()->with('success', 'Jalur berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $jalur = Jalur::findOrFail($id);
        $jalur->delete();
        ActivityLog::log("Menghapus data Jalur " . $jalur->nama_jalur);
        return back()->with('success', 'Jalur berhasil dihapus!');
    }
}