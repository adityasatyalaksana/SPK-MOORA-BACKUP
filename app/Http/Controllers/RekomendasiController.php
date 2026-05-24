<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Terminal;
use App\Models\Jalur;
use App\Models\Kriteria;
use App\Models\Penilaian;
use Carbon\Carbon; // <-- PENTING: Import library Carbon untuk deteksi hari/tanggal

class RekomendasiController extends Controller
{
    public function index()
    {
        $terminals = Terminal::where('tipe', 'Starting Point')->get();
        return view('pendaki.rekomendasi.index', compact('terminals'));
    }

    public function proses(Request $request)
    {
        // 1. Validasi input (Tambahkan tanggal_keberangkatan agar wajib diisi)
        $request->validate([
            'budget' => 'required|numeric',
            'jumlah_anggota' => 'required|numeric|min:1',
            'terminal_id' => 'required',
            'tanggal_keberangkatan' => 'required|date' // Pastikan name="tanggal_keberangkatan" di view blade Anda
        ]);

        // Tangkap input tanggal keberangkatan dari user dan bungkus dengan Carbon
        $tanggalKeberangkatan = $request->tanggal_keberangkatan;
        $date = Carbon::parse($tanggalKeberangkatan);

        // 2. Ambil data terminal terpilih
        $terminal = Terminal::find($request->terminal_id);
        $nama_terminal = $terminal ? $terminal->nama_terminal : 'N/A';

        // 3. Ambil semua kriteria beserta tipe dan bobot dari database
        $kriterias = Kriteria::all();
        
        // 4. Ambil semua jalur beserta relasi gunung dan biayas
        $semuaJalur = Jalur::with(['gunung', 'biayas'])->get();
        
        $jalurLolosFilter = [];
        $matriksKeputusan = [];

        // 5. Logika Filter Berdasarkan Target Budget Pengguna
        foreach ($semuaJalur as $jalur) {
            $biayaBus = $jalur->biayas->where('start_terminal_id', $request->terminal_id)->first();

            if ($biayaBus) {
                // =====================================================================
                // --- SINKRONISASI 3 TINGKATAN (KASTA) HARGA TRANSPORT ---
                // =====================================================================
                $hargaTransport = null;

                // KASTA 1: Periksa apakah ada Harga Periode Khusus (Event/Hari Besar) yang sedang aktif
                if (!empty($biayaBus->harga_periode) && !empty($biayaBus->start_date) && !empty($biayaBus->end_date)) {
                    if ($tanggalKeberangkatan >= $biayaBus->start_date && $tanggalKeberangkatan <= $biayaBus->end_date) {
                        $hargaTransport = $biayaBus->harga_periode; 
                    }
                }

                // Jika Kasta 1 tidak terpenuhi, lanjut ke pengecekan berikutnya
                if (is_null($hargaTransport)) {
                    if ($date->isWeekend()) {
                        // KASTA 2: Hari Sabtu/Minggu (Jika harga_weekend kosong, otomatis fallback ke harga_pp reguler)
                        $hargaTransport = $biayaBus->harga_weekend ?? $biayaBus->harga_pp;
                    } else {
                        // KASTA 3: Hari Biasa Senin - Jumat (Menggunakan tarif dasar reguler)
                        $hargaTransport = $biayaBus->harga_pp;
                    }
                }
                // =====================================================================

                $hargaSimaksi = $jalur->biaya_simaksi;
                $totalEstimasi = ($hargaTransport + $hargaSimaksi) * $request->jumlah_anggota;
        
                if ($totalEstimasi <= $request->budget) {
                    $terminalTujuan = Terminal::find($biayaBus->end_terminal_id);
                    
                    // Set properti dinamis untuk keperluan visual di Blade
                    $jalur->nama_terminal_tujuan = $terminalTujuan->nama_terminal ?? '-';
                    $jalur->nama_armada = $biayaBus->nama_armada;
                    $jalur->harga_pp = $hargaTransport; // Nilai dinamis mengikuti hirarki kasta di atas
                    $jalur->estimasi_perjalanan = $biayaBus->estimasi_perjalanan;
                    $jalur->biaya_per_orang = $hargaTransport + $hargaSimaksi;
                    $jalur->total_dana_kelompok = $totalEstimasi;
                    
                    // Simpan ID Biaya Bus yang aktif digunakan untuk mengambil penilaian terkait
                    $jalur->active_biaya_id = $biayaBus->id;

                    $jalurLolosFilter[] = $jalur;

                    // Bentuk Matriks Keputusan Awal (Tabel 1)
                    foreach ($kriterias as $kcriteria) {
                        // Cari baris nilai berdasarkan kombinasi jalur_id, biaya_id, dan kriteria_id
                        $penilaian = Penilaian::where('jalur_id', $jalur->id)
                            ->where('biaya_id', $biayaBus->id)
                            ->where('kriteria_id', $kcriteria->id) // <-- AMAN: Sudah murni kriteria_id tanpa huruf c
                            ->first();

                        // Jika data penilaian belum diisi admin, default ke skor minimum 1
                        $matriksKeputusan[$jalur->id][$kcriteria->id] = $penilaian ? $penilaian->nilai : 1;
                    }
                }
            }
        }

        // Jika tidak ada jalur yang sesuai anggaran kelompok, langsung hentikan proses
        if (empty($jalurLolosFilter)) {
            return view('pendaki.rekomendasi.pilihan', [
                'rekomendasi' => [],
                'input' => $request->all(),
                'nama_terminal' => $nama_terminal
            ]);
        }

        // =====================================================================
        // 6. PROSES HITUNG METODE MOORA
        // =====================================================================

        // LANGKAH A: Menghitung Nilai Pembagi Normalisasi (Akar Jumlah Kuadrat Lintas Alternatif)
        $pembagiKriteria = [];
        foreach ($kriterias as $kcriteria) { // Konsisten menggunakan objek $kcriteria
            $jumlahKuadrat = 0;
            foreach ($jalurLolosFilter as $jalur) {
                $nilaiSkor = $matriksKeputusan[$jalur->id][$kcriteria->id];
                $jumlahKuadrat += pow($nilaiSkor, 2);
            }
            // Hindari pembagian dengan angka nol
            $pembagiKriteria[$kcriteria->id] = $jumlahKuadrat > 0 ? sqrt($jumlahKuadrat) : 1;
        }

        // LANGKAH B & C: Normalisasi, Kalikan Bobot, dan Hitung Nilai Akhir Yi (Max - Min)
        $rekomendasiHasilMoora = [];
        foreach ($jalurLolosFilter as $jalur) {
            $nilaiMaxBenefit = 0;
            $nilaiMinCost = 0;

            foreach ($kriterias as $kcriteria) {
                $nilaiAwal = $matriksKeputusan[$jalur->id][$kcriteria->id];
                
                // Rumus Normalisasi Xij
                $nilaiNormalisasi = $nilaiAwal / $pembagiKriteria[$kcriteria->id];
                
                // Rumus Matriks Terbobot Yij
                $nilaiTerbobot = $nilaiNormalisasi * $kcriteria->bobot;

                // Pisahkan penjumlahan berdasarkan tipe kriteria (case-insensitive)
                if (strtolower($kcriteria->tipe) == 'benefit') {
                    $nilaiMaxBenefit += $nilaiTerbobot;
                } else {
                    $nilaiMinCost += $nilaiTerbobot;
                }
            }

            // Rumus Akhir Nilai Yi MOORA
            $jalur->nilai_moora = $nilaiMaxBenefit - $nilaiMinCost;
            $rekomendasiHasilMoora[] = $jalur;
        }

        // LANGKAH D: Urutkan data berdasarkan Nilai Yi terbesar (Descending)
        $rekomendasiSorted = collect($rekomendasiHasilMoora)->sortByDesc('nilai_moora')->values()->all();

        // 7. Kirim data hasil perangkingan MOORA ke view Blade
        return view('pendaki.rekomendasi.pilihan', [
            'rekomendasi'   => $rekomendasiSorted,
            'input'         => $request->all(),
            'nama_terminal' => $nama_terminal
        ]);
    }
}