<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Terminal;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class TerminalController extends Controller
{
    public function index()
    {
        $terminals = Terminal::latest()->get();
        return view('admin.terminal.index', compact('terminals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_terminal' => 'required|string|max:255',
            'lokasi'        => 'required|string|max:255',
            'tipe'          => 'required',
        ]);

        // Menyimpan secara eksplisit agar data tidak tertukar
        $terminal = new Terminal();
        $terminal->nama_terminal = $request->nama_terminal;
        $terminal->lokasi = $request->lokasi;
        $terminal->tipe = $request->tipe;
        $terminal->user_id = auth()->id();
        $terminal->save();

        ActivityLog::log("Menambahkan data Terminal " . $terminal->nama_terminal);

        return redirect()->route('admin.terminal.index')->with('success', 'Data Terminal berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_terminal' => 'required|string|max:255',
            'lokasi'        => 'required|string|max:255',
            'tipe'          => 'required',
        ]);

        $terminal = Terminal::findOrFail($id);
        $terminal->nama_terminal = $request->nama_terminal;
        $terminal->lokasi = $request->lokasi;
        $terminal->tipe = $request->tipe;
        $terminal->user_id = auth()->id();
        $terminal->save();

        ActivityLog::log("Mengubah data Terminal " . $terminal->nama_terminal);

        return redirect()->route('admin.terminal.index')->with('success', 'Data Terminal berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $terminal = Terminal::findOrFail($id);
        $terminal->delete();
        ActivityLog::log("Menghapus data Terminal " . $terminal->nama_terminal);

        return redirect()->route('admin.terminal.index')->with('success', 'Data Terminal berhasil dihapus!');
    }
}