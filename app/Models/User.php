<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function gunungs() { return $this->hasMany(Gunung::class, 'user_id'); }
    public function terminals() { return $this->hasMany(Terminal::class, 'user_id'); }
    public function jalurs() { return $this->hasMany(Jalur::class, 'user_id'); }
    public function biayas() { return $this->hasMany(Biaya::class, 'user_id'); }
    public function kriterias() { return $this->hasMany(Kriteria::class, 'user_id'); }
    public function penilaians() { return $this->hasMany(Penilaian::class, 'user_id'); }
    public function activityLogs() { return $this->hasMany(ActivityLog::class, 'user_id'); }
}
