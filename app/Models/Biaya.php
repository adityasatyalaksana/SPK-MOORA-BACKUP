<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Biaya extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jalur_id', // Pastikan jalur_id ada di fillable
        'start_terminal_id', 
        'end_terminal_id', 
        'nama_armada', 
        'estimasi_perjalanan', 
        'harga_pp',
        'harga_weekend', 
        'start_date', 
        'end_date', 
        'harga_periode'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Jalur (Penting untuk filter di halaman penilaian)
    public function jalur()
    {
        return $this->belongsTo(Jalur::class, 'jalur_id');
    }

    // Relasi ke Terminal sebagai titik awal
    public function start_terminal()
    {
        return $this->belongsTo(Terminal::class, 'start_terminal_id');
    }

    // Relasi ke Terminal sebagai titik tujuan
    public function end_terminal()
    {
        return $this->belongsTo(Terminal::class, 'end_terminal_id');
    }
}