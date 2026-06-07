<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jalurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gunung_id')->constrained('gunungs')->onDelete('cascade');
            $table->string('nama_jalur');
            
            // PERUBAHAN DISINI: integer ke decimal
            $table->decimal('biaya_simaksi'); 
            $table->decimal('estimasi_jam'); 
            
            $table->enum('tingkat_kesulitan', ['Sangat Mudah', 'Mudah', 'Sedang', 'Sulit', 'Sangat Sulit']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jalurs');
    }
};