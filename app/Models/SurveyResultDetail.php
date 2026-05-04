<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResultDetail extends Model
{
    protected $fillable = [
        'survey_result_id',
        'question_id',
        'score',
        'answer'
    ];

    public function result()
    {
        return $this->belongsTo(SurveyResult::class, 'survey_result_id');
    }

    public function question()
    {
        return $this->belongsTo(SurveyQuestion::class);
    }
}
