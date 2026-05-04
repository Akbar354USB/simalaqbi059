<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSurveiEmployee extends Model
{
    use HasFactory;
    protected $fillable = [
        'survei_name_name',
    ];
}
