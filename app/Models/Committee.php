<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Committee extends Model
{
    protected $fillable = [
        'name',
        'cluster_id',
    ];

    /**
     * التجمع الذي تتبع له اللجنة
     */
    public function cluster(): BelongsTo
    {
        return $this->belongsTo(Cluster::class);
    }

    /**
     * المحكمين المرتبطين باللجنة
     */
    public function judges(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'committee_user')
                    ->whereHas('roles', function($q) {
                        $q->where('name', 'judge');
                    });
    }

    /**
     * جميع المستخدمين المرتبطين باللجنة (محكمين)
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'committee_user');
    }

    /**
     * الروايات المخصصة للجنة
     */
    public function narrations(): BelongsToMany
    {
        return $this->belongsToMany(Narration::class, 'committee_narration');
    }

    /**
     * الممتحنين المخصصين للجنة
     */
    public function examinees(): HasMany
    {
        return $this->hasMany(Examinee::class);
    }
}