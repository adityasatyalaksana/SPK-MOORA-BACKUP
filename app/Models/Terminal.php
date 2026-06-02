<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Terminal extends Model
{
    use HasFactory;

    // Tambahkan baris ini untuk menentukan kolom mana saja yang boleh diisi
    protected $fillable = ['user_id', 'nama_terminal', 'lokasi'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}