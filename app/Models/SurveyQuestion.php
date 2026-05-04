<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    protected $fillable = [
        'survey_id',
        'question',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function details()
    {
        return $this->hasMany(SurveyResultDetail::class, 'question_id');
    }
}
