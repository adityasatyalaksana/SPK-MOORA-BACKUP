<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    protected $fillable = ['user_id', 'activity'];

    /**
     * Helper static method to easily record a log.
     */
    public static function log($activity)
    {
        if (auth()->check()) {
            self::create([
                'user_id' => auth()->id(),
                'activity' => $activity
            ]);
        }
    }

    /**
     * Relationship to the User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
