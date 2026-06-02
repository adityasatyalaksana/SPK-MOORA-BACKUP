<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    // FIX: Mengganti 'jenis' menjadi 'tipe' agar sinkron dengan request form dan controller
    protected $fillable = ['user_id', 'kode_kriteria', 'nama_kriteria', 'bobot', 'tipe'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke SubKriteria (Satu Kriteria memiliki banyak Sub-Kriteria)
     */
    public function subKriterias()
    {
        return $this->hasMany(SubKriteria::class, 'kriteria_id');
    }
}