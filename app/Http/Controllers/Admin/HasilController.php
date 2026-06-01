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

        // 2. Normalisasi, Terbobot & Perhitungan Nilai Akhir (MOORA)
        $terbobot = [];
        $hasil = [];
        
        foreach ($jalurs as $j) {
            $namaUnik = $j->gunung->nama_gunung . ' (' . $j->nama_jalur . ')';
            $max = 0; // Untuk menampung akumulasi kriteria BENEFIT
            $min = 0; // Untuk menampung akumulasi kriteria COST
            
            foreach ($kriterias as $k) {
                $nilaiAsli = $matriks[$namaUnik][$k->nama_kriteria] ?? 0;
                $norm = $nilaiAsli / $pembagi[$k->id];
                $nilaiBobot = $norm * ($k->bobot ?? 0);
                
                $terbobot[$namaUnik][$k->nama_kriteria] = $nilaiBobot;

                // Memisahkan penjumlahan secara dinamis berdasarkan tipe kriteria dari database
                if (strtolower($k->tipe) == 'benefit') {
                    $max += $nilaiBobot;
                } else {
                    $min += $nilaiBobot;
                }
            }

            // Rumus Utama MOORA: Yi = (Σ Max Benefit) - (Σ Min Cost)
            $skorAkhir = $max - $min;

            $hasil[] = [
                'jalur' => $namaUnik, // Mengirimkan nama gabungan yang informatif ke Blade
                'max'   => $max,
                'min'   => $min,
                'skor'  => $skorAkhir // Nilai Yi (Skor Akhir) hasil optimasi penuh
            ];
        }

        // Urutkan Ranking dari skor tertinggi ke terendah
        usort($hasil, fn($a, $b) => $b['skor'] <=> $a['skor']);

        return view('admin.hasil.index', compact('kriterias', 'matriks', 'terbobot', 'hasil'));
    }
}