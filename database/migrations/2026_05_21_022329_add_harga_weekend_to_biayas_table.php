<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('biayas', function (Blueprint $table) {
            // Menambahkan kolom harga_weekend setelah harga_pp
            $table->integer('harga_weekend')->nullable()->after('harga_pp');
        });
    }

    public function down(): void
    {
        Schema::table('biayas', function (Blueprint $table) {
            $table->dropColumn('harga_weekend');
        });
    }
};