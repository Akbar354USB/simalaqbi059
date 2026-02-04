<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalLeaveRequest extends Model
{
    use HasFactory;

    protected $table = 'additional_leave_requests';

    protected $fillable = [
        // Pengaju
        'employee_id',
        'work_unit_id',
        'position',
        'length_of_service',

        // Data cuti
        'leave_reason',
        'phone',
        'leave_address',
        'letter_number',

        // 🔥 STATUS & APPROVAL (INI KUNCI)
        'status',
        'approved_by_unit_head',
        'approved_unit_head_at',
        'approved_by_head_office',
        'approved_head_office_at',
    ];

    protected $casts = [
        'approved_unit_head_at' => 'datetime',
        'approved_head_office_at' => 'datetime',
    ];

    /* ================= RELATION ================= */

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function workUnit()
    {
        return $this->belongsTo(WorkUnit::class);
    }

    public function periods()
    {
        return $this->hasMany(AdditionalLeavePeriod::class);
    }
}
