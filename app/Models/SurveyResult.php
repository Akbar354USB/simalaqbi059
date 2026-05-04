<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResult extends Model
{
    protected $fillable = [
        'survey_id',
        'employee_id',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }


    public function details()
    {
        return $this->hasMany(SurveyResultDetail::class);
    }
}
