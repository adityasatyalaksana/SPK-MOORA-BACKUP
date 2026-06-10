<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Penilaian;

echo "Total row count in 'penilaians' table: " . Penilaian::count() . "\n";
echo "Distinct combinations (jalur_id, biaya_id) count: " . Penilaian::select('jalur_id', 'biaya_id')->distinct()->count() . "\n";

$allDistinct = Penilaian::select('jalur_id', 'biaya_id')->distinct()->get();
echo "All distinct combinations list:\n";
foreach ($allDistinct as $i => $p) {
    $jalur = \App\Models\Jalur::find($p->jalur_id);
    $biaya = \App\Models\Biaya::find($p->biaya_id);
    
    $jalurName = $jalur ? ($jalur->nama_jalur . " (Gn. " . ($jalur->gunung->nama_gunung ?? 'N/A') . ")") : "Jalur ID {$p->jalur_id} (DELETED/MISSING)";
    $biayaName = $biaya ? ($biaya->nama_armada . " (" . ($biaya->start_terminal->nama_terminal ?? 'N/A') . " -> " . ($biaya->end_terminal->nama_terminal ?? 'N/A') . ")") : "Biaya ID {$p->biaya_id} (DELETED/MISSING)";
    
    echo ($i + 1) . ". Jalur: $jalurName | Biaya/Bus: $biayaName\n";
}
