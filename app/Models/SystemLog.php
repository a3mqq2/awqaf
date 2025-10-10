<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SystemLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'user_id',
    ];

    /**
     * Get the user that owns the system log.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}