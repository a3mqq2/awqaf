<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examinee extends Model
{
    use HasFactory;

    // Status Constants
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_ATTENDED = 'attended';
    const STATUS_WITHDRAWN = 'withdrawn';
    const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'full_name',
        'national_id',
        'passport_no',
        'phone',
        'email',
        'gender',
        'birth_date',
        'status',
        'cluster_id',
        'office_id',
        'narration_id',
        'drawing_id',
        'committee_id',
        'is_attended',
        'attended_at',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'attended_at' => 'datetime',
        'is_attended' => 'boolean',
    ];

    /**
     * Get all available statuses
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'قيد الانتظار',
            self::STATUS_CONFIRMED => 'مؤكد',
            self::STATUS_ATTENDED => 'حضر',
            self::STATUS_WITHDRAWN => 'منسحب',
            self::STATUS_REJECTED => 'مرفوض',
        ];
    }

    /**
     * Get status label in Arabic
     */
    public function getStatusLabelAttribute()
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    /**
     * العلاقة مع التجمع
     */
    public function cluster()
    {
        return $this->belongsTo(Cluster::class);
    }

    /**
     * العلاقة مع المكتب
     */
    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    /**
     * العلاقة مع الرواية
     */
    public function narration()
    {
        return $this->belongsTo(Narration::class);
    }

    /**
     * العلاقة مع الرسم
     */
    public function drawing()
    {
        return $this->belongsTo(Drawing::class);
    }

    /**
     * العلاقة مع اللجنة
     */
    public function committee()
    {
        return $this->belongsTo(Committee::class);
    }


    public function evaluations()
    {
        return $this->hasMany(ExamineeEvaluation::class);
    }
    
    /**
     * الحصول على تقييم محكم معين
     */
    public function evaluationByJudge($judgeId)
    {
        return $this->evaluations()->where('judge_id', $judgeId)->first();
    }


    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' .  $this->father_name  .  ' '  . $this->grandfather_name . ' ' . $this->last_name;
    }

    /**
 * العلاقة مع التقييمات الشفهية
 */
public function oralEvaluations()
{
    return $this->hasMany(OralEvaluation::class);
}

/**
 * التحقق من اجتياز المنهج العلمي
 */
public function hasPassedWrittenTest()
{
    // يجب أن يكون لديه تقييم مكتمل بدرجة >= 28 (70% من 40)
    return $this->evaluations()
        ->where('status', 'completed')
        ->where('score', '>=', 28)
        ->exists();
}

/**
 * الحصول على متوسط درجة المنهج العلمي
 */
public function getAverageWrittenScoreAttribute()
{
    return $this->evaluations()
        ->where('status', 'completed')
        ->avg('score');
}

/**
 * الحصول على متوسط درجة الاختبار الشفهي
 */
public function getAverageOralScoreAttribute()
{
    return $this->oralEvaluations()
        ->where('status', 'completed')
        ->avg('final_score');
}
}