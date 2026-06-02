<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gunung;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GunungController extends Controller
{
    public function index()
    {
        $gunungs = Gunung::latest()->get();
        return view('admin.gunung.index', compact('gunungs'));
    }

    public function create()
    {
        return view('admin.gunung.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_gunung' => 'required|string|max:255',
            'lokasi'      => 'required|string',
            'ketinggian'  => 'required|numeric',
            'deskripsi'   => 'nullable|string',
            'gambar.*'    => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();
        $storedImages = [];

        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $path = $file->store('assets/gunung', 'public');
                $storedImages[] = $path;
            }
        }

        $data['gambar'] = $storedImages;
        $data['user_id'] = auth()->id();
        $gunung = Gunung::create($data);

        ActivityLog::log("Menambahkan data Gunung " . $gunung->nama_gunung);

        return redirect()->route('admin.gunung.index')->with('success', 'Data Gunung dan Galeri berhasil disimpan!');
    }

    public function edit($id)
    {
        $gunung = Gunung::findOrFail($id);
        return view('admin.gunung.edit', compact('gunung'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_gunung' => 'required|string|max:255',
            'lokasi'      => 'required|string',
            'ketinggian'  => 'required|numeric',
            'deskripsi'   => 'nullable|string',
            'gambar.*'    => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $gunung = Gunung::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $storedImages = $gunung->gambar ?? []; // Ambil gambar lama
            foreach ($request->file('gambar') as $file) {
                $path = $file->store('assets/gunung', 'public');
                $storedImages[] = $path; // Tambahkan gambar baru ke array yang sudah ada
            }
            $data['gambar'] = $storedImages;
        } else {
            $data['gambar'] = $gunung->gambar;
        }

        $gunung->update($data);
        ActivityLog::log("Mengubah data Gunung " . $gunung->nama_gunung);
        return redirect()->route('admin.gunung.index')->with('success', 'Data Gunung berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $gunung = Gunung::findOrFail($id);
        if ($gunung->gambar && is_array($gunung->gambar)) {
            foreach ($gunung->gambar as $img) {
                Storage::disk('public')->delete($img);
            }
        }
        $gunung->delete();
        ActivityLog::log("Menghapus data Gunung " . $gunung->nama_gunung);
        return redirect()->route('admin.gunung.index')->with('success', 'Data Gunung berhasil dihapus!');
    }

    // Method Baru: Hapus satu gambar saja
    public function deleteImage(Request $request, $id)
    {
        $gunung = Gunung::findOrFail($id);
        $imagePath = $request->image_path;

        if ($gunung->gambar && in_array($imagePath, $gunung->gambar)) {
            Storage::disk('public')->delete($imagePath);
            $newImages = array_values(array_diff($gunung->gambar, [$imagePath]));
            $gunung->update(['gambar' => $newImages]);
            return back()->with('success', 'Gambar berhasil dihapus dari galeri!');
        }

        return back()->with('error', 'Gambar tidak ditemukan.');
    }
}