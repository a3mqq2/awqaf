<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasClusterFiltering;

class Examinee extends Model
{
    use HasFactory, HasClusterFiltering;

    protected $fillable = [
        'first_name',
        'father_name',
        'grandfather_name',
        'last_name',
        'national_id',
        'passport_no',
        'birth_date',
        'gender',
        'phone',
        'email',
        'address',
        'office_id',
        'cluster_id',
        'narration_id',
        'drawing_id',
        'status',
        'notes',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function cluster()
    {
        return $this->belongsTo(Cluster::class);
    }

    public function examAttempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }

    public function narration()
    {
        return $this->belongsTo(Narration::class);
    }

    public function drawing()
    {
        return $this->belongsTo(Drawing::class);
    }

    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->father_name} {$this->grandfather_name} {$this->last_name}");
    }

    public function getFormattedBirthDateAttribute()
    {
        return $this->birth_date ? $this->birth_date->format('d/m/Y') : null;
    }
}