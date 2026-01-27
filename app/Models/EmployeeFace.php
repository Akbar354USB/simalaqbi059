<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeFace extends Model
{
    protected $fillable = [
        'employee_id',
        'face_descriptor'
    ];

    protected $casts = [
        'face_descriptor' => 'array'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
