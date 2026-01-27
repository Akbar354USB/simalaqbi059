<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeFace extends Model
{
    use HasFactory;

    protected $table = 'employee_faces';

    protected $fillable = [
        'employee_id',
        'face_embedding'
    ];

    protected $casts = [
        'face_embedding' => 'array'
    ];

    /* ================= RELATION ================= */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
