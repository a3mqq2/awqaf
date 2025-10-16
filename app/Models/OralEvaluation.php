<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OralEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'examinee_id',
        'judge_id',
        'committee_id',
        'questions_data',
        'current_question',
        'total_score',
        'final_score',
        'notes',
        'status',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'questions_data' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function examinee()
    {
        return $this->belongsTo(Examinee::class);
    }

    public function judge()
    {
        return $this->belongsTo(User::class, 'judge_id');
    }

    public function committee()
    {
        return $this->belongsTo(Committee::class);
    }

    /**
     * درجة السؤال الواحد
     */
    public const QUESTION_SCORE = 8.3333;

    /**
     * قيم الخصم لكل بند
     */
    public const DEDUCTIONS = [
        'stutter' => 0.125,           // تعلثم
        'repeat' => 0.25,              // إعادة
        'hesitation' => 0.375,         // تردد
        'alert' => 0.5,                // تنبيه
        'open' => 1.0,                 // فتح
        'sub_ruling' => 0.125,         // الأحكام الفرعية
        'main_ruling' => 0.5,          // الأحكام الأصلية
        'not_mastered' => 0.5,         // عدم إتقان أصل
        'left_origin' => 1.0,          // ترك أصل
        'incomplete_stop' => 0.25,     // وقف لا يتم به معنى
        'bad_stop' => 0.25,            // وقف قبيح
    ];

    /**
     * تهيئة بيانات الأسئلة
     */
    public function initializeQuestions()
    {
        $questions = [];
        for ($i = 1; $i <= 12; $i++) {
            $questions[$i] = [
                'question_number' => $i,
                'score' => self::QUESTION_SCORE,
                'deductions' => [
                    'stutter' => 0,
                    'repeat' => 0,
                    'hesitation' => 0,
                    'alert' => 0,
                    'open' => 0,
                    'sub_ruling' => 0,
                    'main_ruling' => 0,
                    'not_mastered' => 0,
                    'left_origin' => 0,
                    'incomplete_stop' => 0,
                    'bad_stop' => 0,
                ],
                'final_score' => self::QUESTION_SCORE,
                'is_approved' => false,
            ];
        }
        
        $this->questions_data = $questions;
        $this->current_question = 1;
        $this->save();
    }

    /**
     * حساب درجة السؤال الحالي
     */
    public function calculateQuestionScore($questionNumber)
    {
        if (!isset($this->questions_data[$questionNumber])) {
            return 0;
        }

        $question = $this->questions_data[$questionNumber];
        $score = self::QUESTION_SCORE;

        foreach ($question['deductions'] as $type => $count) {
            if (isset(self::DEDUCTIONS[$type])) {
                $score -= ($count * self::DEDUCTIONS[$type]);
            }
        }

        return max(0, $score);
    }

    /**
     * اعتماد السؤال الحالي والانتقال للتالي
     */
    public function approveCurrentQuestion()
    {
        $currentQ = $this->current_question;
        
        if (!isset($this->questions_data[$currentQ])) {
            return false;
        }

        $questions = $this->questions_data;
        $questions[$currentQ]['final_score'] = $this->calculateQuestionScore($currentQ);
        $questions[$currentQ]['is_approved'] = true;
        
        $this->questions_data = $questions;
        
        // الانتقال للسؤال التالي
        if ($currentQ < 12) {
            $this->current_question = $currentQ + 1;
        }
        
        // حساب المجموع الكلي
        $this->calculateTotalScore();
        $this->save();
        
        return true;
    }

    /**
     * حساب المجموع الكلي
     */
    public function calculateTotalScore()
    {
        $total = 0;
        
        if (is_array($this->questions_data)) {
            foreach ($this->questions_data as $question) {
                if ($question['is_approved']) {
                    $total += $question['final_score'];
                }
            }
        }
        
        $this->total_score = round($total, 2);
        $this->final_score = $this->total_score;
        
        return $this->total_score;
    }

    /**
     * التراجع عن السؤال السابق
     */
    public function goToPreviousQuestion()
    {
        if ($this->current_question > 1) {
            $this->current_question--;
            
            // إلغاء اعتماد السؤال السابق
            $questions = $this->questions_data;
            $questions[$this->current_question]['is_approved'] = false;
            $this->questions_data = $questions;
            
            $this->calculateTotalScore();
            $this->save();
            
            return true;
        }
        
        return false;
    }
}