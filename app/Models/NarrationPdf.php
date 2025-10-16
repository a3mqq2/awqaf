<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NarrationPdf extends Model
{
    use HasFactory;

    protected $fillable = [
        'narration_id',
        'title',
        'file_path',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * العلاقة مع الرواية
     */
    public function narration()
    {
        return $this->belongsTo(Narration::class);
    }

    /**
     * الحصول على رابط الملف
     */
    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }
}