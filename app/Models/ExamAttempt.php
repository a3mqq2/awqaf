<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'examinee_id',
        'year',
        'narration_id',
        'drawing_id',
        'side',
        'result',
        'percentage',
        'notes',
    ];

    public function examinee()
    {
        return $this->belongsTo(Examinee::class);
    }

    public function narration()
    {
        return $this->belongsTo(Narration::class);
    }

    public function drawing()
    {
        return $this->belongsTo(Drawing::class);
    }
}