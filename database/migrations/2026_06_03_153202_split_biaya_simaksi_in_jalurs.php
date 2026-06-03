<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jalurs', function (Blueprint $table) {
            $table->decimal('biaya_simaksi_weekday', 15, 2)->nullable()->after('nama_jalur');
            $table->decimal('biaya_simaksi_weekend', 15, 2)->nullable()->after('biaya_simaksi_weekday');
        });

        // Copy existing data
        DB::table('jalurs')->update([
            'biaya_simaksi_weekday' => DB::raw('biaya_simaksi'),
            'biaya_simaksi_weekend' => DB::raw('biaya_simaksi')
        ]);

        Schema::table('jalurs', function (Blueprint $table) {
            $table->decimal('biaya_simaksi_weekday', 15, 2)->nullable(false)->change();
            $table->decimal('biaya_simaksi_weekend', 15, 2)->nullable(false)->change();
            $table->dropColumn('biaya_simaksi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jalurs', function (Blueprint $table) {
            $table->decimal('biaya_simaksi', 15, 2)->nullable()->after('nama_jalur');
        });

        DB::table('jalurs')->update([
            'biaya_simaksi' => DB::raw('biaya_simaksi_weekday')
        ]);

        Schema::table('jalurs', function (Blueprint $table) {
            $table->decimal('biaya_simaksi', 15, 2)->nullable(false)->change();
            $table->dropColumn(['biaya_simaksi_weekday', 'biaya_simaksi_weekend']);
        });
    }
};
