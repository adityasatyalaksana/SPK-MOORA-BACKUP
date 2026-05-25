<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\Jalur;
use Illuminate\Support\Facades\DB;

class HasilController extends Controller
{
    public function index()
    {
        // Mengambil kriteria dan penilaians beserta relasinya
        $kriterias = Kriteria::all();
        $penilaians = Penilaian::with(['jalur.gunung', 'kriteria'])->get();
        
        // Eager load relasi gunung pada model Jalur agar query efisien
        $jalurs = Jalur::with('gunung')->get();

        if ($kriterias->isEmpty() || $penilaians->isEmpty()) {
            return view('admin.hasil.index', ['hasil' => [], 'matriks' => [], 'terbobot' => []]);
        }

        // 1. Matriks Keputusan & Pembagi
        $matriks = [];
        $pembagi = [];
        
        foreach ($kriterias as $k) {
            $sumKuadrat = 0;
            foreach ($jalurs as $j) {
                // Membuat penanda unik (Nama Gunung - Nama Jalur) agar tidak saling menimpa
                $namaUnik = $j->gunung->nama_gunung . ' (' . $j->nama_jalur . ')';
                
                // Cari nilai berdasarkan jalur_id dan kriteria_id
                $nilai = $penilaians->where('jalur_id', $j->id)->where('kriteria_id', $k->id)->first()->nilai ?? 0;
                
                $matriks[$namaUnik][$k->nama_kriteria] = $nilai;
                $sumKuadrat += pow($nilai, 2);
            }
            $pembagi[$k->id] = $sumKuadrat > 0 ? sqrt($sumKuadrat) : 1;
        }

        // 2. Normalisasi & Terbobot
        $terbobot = [];
        $hasil = [];
        
        foreach ($jalurs as $j) {
            $namaUnik = $j->gunung->nama_gunung . ' (' . $j->nama_jalur . ')';
            $max = 0;
            
            foreach ($kriterias as $k) {
                $nilaiAsli = $matriks[$namaUnik][$k->nama_kriteria] ?? 0;
                $norm = $nilaiAsli / $pembagi[$k->id];
                $nilaiBobot = $norm * ($k->bobot ?? 0);
                
                $terbobot[$namaUnik][$k->nama_kriteria] = $nilaiBobot;

                // Karena SEKARANG murni menggunakan BENEFIT, semua nilai bobot langsung dijumlahkan ke $max
                $max += $nilaiBobot;
            }

            $hasil[] = [
                'jalur' => $namaUnik, // Mengirimkan nama gabungan yang informatif ke Blade
                'max'   => $max,
                'skor'  => $max // Nilai Yi (Skor Akhir) ekuivalen dengan nilai Max Benefit
            ];
        }

        // Urutkan Ranking dari skor tertinggi ke terendah
        usort($hasil, fn($a, $b) => $b['skor'] <=> $a['skor']);

        return view('admin.hasil.index', compact('kriterias', 'matriks', 'terbobot', 'hasil'));
    }
}