<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    // FIX: Mengganti 'jenis' menjadi 'tipe' agar sinkron dengan request form dan controller
    protected $fillable = ['kode_kriteria', 'nama_kriteria', 'bobot', 'tipe'];

    /**
     * Relasi ke SubKriteria (Satu Kriteria memiliki banyak Sub-Kriteria)
     */
    public function subKriterias()
    {
        return $this->hasMany(SubKriteria::class, 'kriteria_id');
    }
}