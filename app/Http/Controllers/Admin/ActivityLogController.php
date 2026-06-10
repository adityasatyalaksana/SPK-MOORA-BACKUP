<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::with('user')->latest()->paginate(15);
        return view('admin.logs.index', compact('logs'));
    }

    public function export()
    {
        $fileName = 'activity_logs_' . date('Ymd_His') . '.csv';
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            // Tambahkan UTF-8 BOM untuk kompatibilitas Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header Kolom
            fputcsv($file, ['No', 'Waktu & Tanggal', 'Admin Pelaku', 'Aktivitas']);

            // Mengambil log dalam chunk untuk mencegah isu penggunaan memori besar
            $key = 1;
            ActivityLog::with('user')->orderBy('created_at', 'desc')->chunk(200, function($logs) use ($file, &$key) {
                foreach ($logs as $item) {
                    fputcsv($file, [
                        $key++,
                        $item->created_at->format('d-m-Y H:i:s') . ' WIB',
                        $item->user->name ?? 'System',
                        $item->activity
                    ]);
                }
            });
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function clear(Request $request)
    {
        // Pengecekan keamanan Superadmin
        if (!auth()->check() || auth()->user()->role->name !== 'Superadmin') {
            abort(403, 'Aksi ini hanya diperbolehkan untuk Superadmin.');
        }

        $type = $request->input('clear_type');

        if ($type === 'older_than_30_days') {
            $thirtyDaysAgo = now()->subDays(30);
            $count = ActivityLog::where('created_at', '<', $thirtyDaysAgo)->count();
            ActivityLog::where('created_at', '<', $thirtyDaysAgo)->delete();
            
            ActivityLog::log("Menghapus log aktivitas yang berusia lebih dari 30 hari (jumlah: {$count})");
            
            return back()->with('success', "Berhasil menghapus {$count} log aktivitas yang berusia lebih dari 30 hari.");
        } elseif ($type === 'all') {
            ActivityLog::query()->delete();
            
            // Catat log pembersihan agar selalu ada minimal 1 log audit
            ActivityLog::log("Membersihkan seluruh riwayat log aktivitas sistem");
            
            return back()->with('success', 'Seluruh riwayat log aktivitas telah dibersihkan.');
        }

        return back()->with('error', 'Tipe pembersihan tidak valid.');
    }
}
