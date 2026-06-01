<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Terminal;
use App\Models\Jalur;
use App\Models\Kriteria;
use App\Models\Penilaian;
use Carbon\Carbon;

class RekomendasiController extends Controller
{
    public function index()
    {
        $terminals = Terminal::where('tipe', 'Starting Point')->get();
        return view('pendaki.rekomendasi.index', compact('terminals'));
    }

    public function proses(Request $request)
    {
        // 1. Validasi input dari user
        $request->validate([
            'budget' => 'required|numeric',
            'jumlah_anggota' => 'required|numeric|min:1',
            'terminal_id' => 'required',
            'tanggal_keberangkatan' => 'required|date'
        ]);

        $tanggalKeberangkatan = $request->tanggal_keberangkatan;
        $date = Carbon::parse($tanggalKeberangkatan);

        $terminal = Terminal::find($request->terminal_id);
        $nama_terminal = $terminal ? $terminal->nama_terminal : 'N/A';

        $kriterias = Kriteria::all();
        $semuaJalur = Jalur::with(['gunung', 'biayas'])->get();
        
        // Ambil semua data penilaian secara global untuk menghitung nilai pembagi normalisasi yang valid
        $semuaPenilaian = Penilaian::all(); 
        
        $jalurLolosFilter = [];

        // 2. Logika Filter Jalur Berdasarkan Target Budget Pengguna
        foreach ($semuaJalur as $jalur) {
            $biayaBus = $jalur->biayas->where('start_terminal_id', $request->terminal_id)->first();

            if ($biayaBus) {
                // --- SINKRONISASI TINGKATAN (KASTA) HARGA TRANSPORT ---
                $hargaTransport = null;

                // Kasta 1: Periksa apakah ada Harga Periode Khusus (Event/Hari Besar)
                if (!empty($biayaBus->harga_periode) && !empty($biayaBus->start_date) && !empty($biayaBus->end_date)) {
                    if ($tanggalKeberangkatan >= $biayaBus->start_date && $tanggalKeberangkatan <= $biayaBus->end_date) {
                        $hargaTransport = $biayaBus->harga_periode; 
                    }
                }

                // Kasta 2 & 3: Jika Kasta 1 null, cek weekend/weekday reguler
                if (is_null($hargaTransport)) {
                    if ($date->isWeekend()) {
                        $hargaTransport = $biayaBus->harga_weekend ?? $biayaBus->harga_pp;
                    } else {
                        $hargaTransport = $biayaBus->harga_pp;
                    }
                }

                $hargaSimaksi = $jalur->biaya_simaksi;
                $totalEstimasi = ($hargaTransport + $hargaSimaksi) * $request->jumlah_anggota;
        
                // Masukkan ke array hanya jika total estimasi biaya kelompok masuk dalam budget user
                if ($totalEstimasi <= $request->budget) {
                    $terminalTujuan = Terminal::find($biayaBus->end_terminal_id);
                    
                    // Set properti dinamis untuk rendering kartu di view Blade
                    $jalur->nama_terminal_tujuan = $terminalTujuan->nama_terminal ?? '-';
                    $jalur->nama_armada = $biayaBus->nama_armada;
                    $jalur->harga_pp = $hargaTransport;
                    $jalur->estimasi_perjalanan = $biayaBus->estimasi_perjalanan;
                    $jalur->biaya_per_orang = $hargaTransport + $hargaSimaksi;
                    $jalur->total_dana_kelompok = $totalEstimasi;
                    $jalur->active_biaya_id = $biayaBus->id;

                    $jalurLolosFilter[] = $jalur;
                }
            }
        }

        // Jika tidak ada satu pun rute jalur yang sesuai anggaran kelompok, hentikan proses
        if (empty($jalurLolosFilter)) {
            return view('pendaki.rekomendasi.pilihan', [
                'rekomendasi' => [],
                'input' => $request->all(),
                'nama_terminal' => $nama_terminal
            ]);
        }

        // =====================================================================
        // 3. PROSES PERHITUNGAN METODE MOORA
        // =====================================================================

        // LANGKAH A: Menghitung Nilai Pembagi Normalisasi Lintas Jalur Global (Agar Sinkron Sempurna Dengan Admin)
        $pembagiKriteria = [];
        foreach ($kriterias as $kcriteria) {
            $jumlahKuadrat = 0;
            foreach ($semuaJalur as $jalurGlobal) {
                // Tarik nilai matriks keputusan awal global
                $penilaianGlobal = $semuaPenilaian->where('jalur_id', $jalurGlobal->id)
                                                  ->where('kriteria_id', $kcriteria->id)
                                                  ->first();
                
                $nilaiSkorGlobal = $penilaianGlobal ? $penilaianGlobal->nilai : 1;
                $jumlahKuadrat += pow($nilaiSkorGlobal, 2);
            }
            $pembagiKriteria[$kcriteria->id] = $jumlahKuadrat > 0 ? sqrt($jumlahKuadrat) : 1;
        }

        // LANGKAH B & C: Normalisasi, Perkalian Bobot, dan Kalkulasi Nilai Akhir Preferensi Yi (Max - Min)
        $rekomendasiHasilMoora = [];
        foreach ($jalurLolosFilter as $jalur) {
            $nilaiMaxBenefit = 0;
            $nilaiMinCost = 0;

            foreach ($kriterias as $kcriteria) {
                // OPSI 1: Cari baris penilaian spesifik berdasarkan kombinasi jalur_id DAN biaya_id aktif
                $penilaianAwal = $semuaPenilaian->where('jalur_id', $jalur->id)
                                                ->where('biaya_id', $jalur->active_biaya_id)
                                                ->where('kriteria_id', $kcriteria->id)
                                                ->first();

                // OPSI 2 (FALLBACK): Jika Opsi 1 null, cari baris default yang hanya mencocokkan jalur_id dan kriteria_id
                if (!$penilaianAwal) {
                    $penilaianAwal = $semuaPenilaian->where('jalur_id', $jalur->id)
                                                    ->where('kriteria_id', $kcriteria->id)
                                                    ->first();
                }

                // Ambil nilai asli, jika data penilaian kosong set ke default terendah (1)
                $nilaiAwal = $penilaianAwal ? $penilaianAwal->nilai : 1;
                
                // Eksekusi Rumus Normalisasi: Xij = xij / sqrt(Σ x^2)
                $nilaiNormalisasi = $nilaiAwal / $pembagiKriteria[$kcriteria->id];
                
                // Eksekusi Rumus Matriks Terbobot: Yij = Xij * Wj
                $nilaiTerbobot = $nilaiNormalisasi * $kcriteria->bobot;

                // Akumulasikan penjumlahan terpisah secara dinamis berdasarkan tipe kriteria dari database
                if (strtolower($kcriteria->tipe) == 'benefit') {
                    $nilaiMaxBenefit += $nilaiTerbobot;
                } else {
                    $nilaiMinCost += $nilaiTerbobot;
                }
            }

            // Rumus Utama Perangkingan Multi-Objektif MOORA: Yi = (Σ Max Benefit) - (Σ Min Cost)
            $jalur->nilai_moora = $nilaiMaxBenefit - $nilaiMinCost;
            $rekomendasiHasilMoora[] = $jalur;
        }

        // LANGKAH D: Urutkan data rekomendasi berdasarkan Nilai Preferensi Yi terbesar ke terkecil (Descending)
        $rekomendasiSorted = collect($rekomendasiHasilMoora)->sortByDesc('nilai_moora')->values()->all();

        // 4. Kirim paket data hasil perangkingan MOORA ke halaman view Blade
        return view('pendaki.rekomendasi.pilihan', [
            'rekomendasi'   => $rekomendasiSorted,
            'input'         => $request->all(),
            'nama_terminal' => $nama_terminal
        ]);
    }
}