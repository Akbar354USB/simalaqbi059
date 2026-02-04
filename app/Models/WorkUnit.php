<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_unit',
        'employee_id',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function additionalLeaveRequests()
    {
        return $this->hasMany(AdditionalLeaveRequest::class);
    }
}
