<?php

namespace App\Services;

use App\Models\Kriteria;
use App\Models\Penilaian;

class MooraService
{
    /**
     * Hitung perhitungan MOORA dan kembalikan peringkat alternatif terurut.
     */
    public static function calculate()
    {
        $kriterias = Kriteria::all();
        $penilaians = Penilaian::with([
            'jalur.gunung', 
            'biaya.start_terminal', 
            'biaya.end_terminal', 
            'kriteria'
        ])->get();

        if ($kriterias->isEmpty() || $penilaians->isEmpty()) {
            return [];
        }

        // Cari semua kombinasi alternatif yang memiliki penilaian
        $alternatifs = [];
        $groupedPenilaian = $penilaians->groupBy(function($item) {
            return $item->jalur_id . '-' . $item->biaya_id;
        });

        foreach ($groupedPenilaian as $key => $items) {
            $first = $items->first();
            if (!$first->jalur || !$first->biaya) {
                continue;
            }
            $alternatifs[$key] = [
                'jalur_id' => $first->jalur_id,
                'biaya_id' => $first->biaya_id,
                'nama_gunung' => $first->jalur->gunung->nama_gunung ?? '-',
                'nama_jalur' => $first->jalur->nama_jalur ?? '-',
                'nama_armada' => $first->biaya->nama_armada ?? '-',
                'start_terminal' => $first->biaya->start_terminal->nama_terminal ?? '-',
                'end_terminal' => $first->biaya->end_terminal->nama_terminal ?? '-',
                'items' => $items
            ];
        }

        if (empty($alternatifs)) {
            return [];
        }

        // 1. Hitung Pembagi (Normalization Denominator) untuk masing-masing kriteria
        $pembagi = [];
        foreach ($kriterias as $k) {
            $sumKuadrat = 0;
            foreach ($alternatifs as $altKey => $alt) {
                $nilai = $alt['items']->where('kriteria_id', $k->id)->first()->nilai ?? 0;
                $sumKuadrat += pow($nilai, 2);
            }
            $pembagi[$k->id] = $sumKuadrat > 0 ? sqrt($sumKuadrat) : 1;
        }

        // 2. Normalisasi, Pembobotan, dan Perangkingan MOORA
        $hasil = [];
        foreach ($alternatifs as $altKey => $alt) {
            $max = 0; // Akumulasi kriteria BENEFIT
            $min = 0; // Akumulasi kriteria COST

            foreach ($kriterias as $k) {
                $nilaiAsli = $alt['items']->where('kriteria_id', $k->id)->first()->nilai ?? 0;
                $norm = $nilaiAsli / $pembagi[$k->id];
                $nilaiBobot = $norm * ($k->bobot ?? 0);

                if (strtolower($k->tipe) == 'benefit') {
                    $max += $nilaiBobot;
                } else {
                    $min += $nilaiBobot;
                }
            }

            $skorAkhir = $max - $min;

            $hasil[] = [
                'alt_key' => $altKey,
                'nama_gunung' => $alt['nama_gunung'],
                'nama_jalur' => $alt['nama_jalur'],
                'nama_armada' => $alt['nama_armada'],
                'start_terminal' => $alt['start_terminal'],
                'end_terminal' => $alt['end_terminal'],
                'max' => $max,
                'min' => $min,
                'skor' => $skorAkhir
            ];
        }

        // Urutkan Ranking dari skor tertinggi ke terendah
        usort($hasil, fn($a, $b) => $b['skor'] <=> $a['skor']);

        return $hasil;
    }
}
