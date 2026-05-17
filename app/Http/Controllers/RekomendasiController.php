<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Terminal;
use App\Models\Jalur;
use App\Models\Kriteria;
use App\Models\Penilaian;

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

        // Tangkap input tanggal keberangkatan dari user
        $tanggalKeberangkatan = $request->tanggal_keberangkatan;

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
                // --- PERBAIKAN LOGIKA PENENTUAN HARGA TRANSPORT ---
                // Set default harga ke harga reguler/pp terlebih dahulu
                $hargaTransport = $biayaBus->harga_pp; 

                // Periksa apakah harga periode diset, dan apakah tanggal input masuk dalam range periode tersebut
                if (!empty($biayaBus->harga_periode) && !empty($biayaBus->start_date) && !empty($biayaBus->end_date)) {
                    if ($tanggalKeberangkatan >= $biayaBus->start_date && $tanggalKeberangkatan <= $biayaBus->end_date) {
                        // Jika tanggal pendaki masuk dalam range, switch ke harga khusus periode
                        $hargaTransport = $biayaBus->harga_periode; 
                    }
                }
                // --- END PERBAIKAN LOGIKA ---

                $hargaSimaksi = $jalur->biaya_simaksi;
                $totalEstimasi = ($hargaTransport + $hargaSimaksi) * $request->jumlah_anggota;
        
                if ($totalEstimasi <= $request->budget) {
                    $terminalTujuan = Terminal::find($biayaBus->end_terminal_id);
                    
                    // Set properti dinamis untuk keperluan visual di Blade
                    $jalur->nama_terminal_tujuan = $terminalTujuan->nama_terminal ?? '-';
                    $jalur->nama_armada = $biayaBus->nama_armada;
                    $jalur->harga_pp = $hargaTransport; // Sekarang nilainya dinamis (bisa reguler / periode)
                    $jalur->estimasi_perjalanan = $biayaBus->estimasi_perjalanan;
                    $jalur->biaya_per_orang = $hargaTransport + $hargaSimaksi;
                    $jalur->total_dana_kelompok = $totalEstimasi;
                    
                    // Simpan ID Biaya Bus yang aktif digunakan untuk mengambil penilaian terkait
                    $jalur->active_biaya_id = $biayaBus->id;

                    $jalurLolosFilter[] = $jalur;

                    // Bentuk Matriks Keputusan Awal (Tabel 1)
                    foreach ($kriterias as $kriteria) {
                        // Cari baris nilai berdasarkan kombinasi jalur_id, biaya_id, dan kriteria_id
                        $penilaian = Penilaian::where('jalur_id', $jalur->id)
                            ->where('biaya_id', $biayaBus->id)
                            ->where('kriteria_id', $kriteria->id)
                            ->first();

                        // Jika data penilaian belum diisi admin, default ke skor minimum 1
                        $matriksKeputusan[$jalur->id][$kriteria->id] = $penilaian ? $penilaian->nilai : 1;
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
        foreach ($kriterias as $kriteria) {
            $jumlahKuadrat = 0;
            foreach ($jalurLolosFilter as $jalur) {
                $nilaiSkor = $matriksKeputusan[$jalur->id][$kriteria->id];
                $jumlahKuadrat += pow($nilaiSkor, 2);
            }
            // Hindari pembagian dengan angka nol
            $pembagiKriteria[$kriteria->id] = $jumlahKuadrat > 0 ? sqrt($jumlahKuadrat) : 1;
        }

        // LANGKAH B & C: Normalisasi, Kalikan Bobot, dan Hitung Nilai Akhir Yi (Max - Min)
        $rekomendasiHasilMoora = [];
        foreach ($jalurLolosFilter as $jalur) {
            $nilaiMaxBenefit = 0;
            $nilaiMinCost = 0;

            foreach ($kriterias as $kriteria) {
                $nilaiAwal = $matriksKeputusan[$jalur->id][$kriteria->id];
                
                // Rumus Normalisasi Xij
                $nilaiNormalisasi = $nilaiAwal / $pembagiKriteria[$kriteria->id];
                
                // Rumus Matriks Terbobot Yij
                $nilaiTerbobot = $nilaiNormalisasi * $kriteria->bobot;

                // Pisahkan penjumlahan berdasarkan tipe kriteria (case-insensitive)
                if (strtolower($kriteria->tipe) == 'benefit') {
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