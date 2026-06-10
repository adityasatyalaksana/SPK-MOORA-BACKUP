<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gunung;
use App\Models\Jalur;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\Terminal;
use App\Models\Biaya;
use App\Models\User;
use App\Models\ActivityLog;
use App\Services\MooraService;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_gunung'      => Gunung::count(),
            'total_jalur'       => Jalur::count(),
            'total_kriteria'    => Kriteria::count(),
            'total_user'        => User::count(),
            'total_terminal'    => Terminal::count(),
            'total_biaya'       => Biaya::count(),
            'total_penilaian'   => Penilaian::select('jalur_id', 'biaya_id')->distinct()->get()->count(),
            'total_logs'        => ActivityLog::count(),
        ];

        // Ambil data untuk grafik perbandingan kriteria
        $kriterias = Kriteria::select('nama_kriteria', 'bobot')->get();
        $chartLabels = $kriterias->pluck('nama_kriteria');
        $chartWeights = $kriterias->pluck('bobot');

        // Ambil 5 Log Aktivitas Terbaru untuk Superadmin
        $recentLogs = ActivityLog::with('user')->latest()->take(5)->get();

        // Ambil 3 peringkat rute terbaik dari MooraService
        $mooraRankings = MooraService::calculate();
        $topAlternatives = array_slice($mooraRankings, 0, 3);

        return view('admin.dashboard.index', compact('data', 'chartLabels', 'chartWeights', 'recentLogs', 'topAlternatives'));
    }
}