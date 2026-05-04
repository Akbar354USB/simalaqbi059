<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type'
    ];

    public function questions()
    {
        return $this->hasMany(SurveyQuestion::class);
    }

    public function targets()
    {
        return $this->hasMany(SurveyTarget::class);
    }

    public function results()
    {
        return $this->hasMany(SurveyResult::class);
    }
}
