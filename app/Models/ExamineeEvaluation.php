<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamineeEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'examinee_id',
        'judge_id',
        'committee_id',
        'score',
        'notes',
        'status',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * العلاقة مع الممتحن
     */
    public function examinee()
    {
        return $this->belongsTo(Examinee::class);
    }

    /**
     * العلاقة مع المحكم
     */
    public function judge()
    {
        return $this->belongsTo(User::class, 'judge_id');
    }

    /**
     * العلاقة مع اللجنة
     */
    public function committee()
    {
        return $this->belongsTo(Committee::class);
    }
}