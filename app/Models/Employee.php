<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_name',
        'nip',
        'email',
        'status',
        'is_active',
    ];

    public function workSchedule(): HasOne
    {
        return $this->hasOne(WorkSchedule::class);
    }

    public function googleAccount(): HasOne
    {
        return $this->hasOne(GoogleAccount::class);
    }

    public function reminderLogs(): HasMany
    {
        return $this->hasMany(ReminderLog::class);
    }

    public function guest_books()
    {
        return $this->belongsToMany(GuestBook::class, 'employee_guest_books');
    }

    /**
     * Relasi ke user
     */
    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }


    public function additionalLeaveRequests()
    {
        return $this->hasMany(AdditionalLeaveRequest::class);
    }

    public function additionalLeaves()
    {
        return $this->hasMany(AdditionalLeave::class);
    }

    public function workUnitsLed()
    {
        return $this->hasMany(WorkUnit::class, 'employee_id');
    }

    // Relasi ke pengajuan lembur
    public function overtimeRequests()
    {
        return $this->hasMany(OvertimeRequestV2::class);
    }

    // Relasi sebagai approver (pimpinan Sub Bagian Umum)
    public function approvedOvertimes()
    {
        return $this->hasMany(OvertimeApproval::class, 'approved_by');
    }

    // Relasi ke work unit sebagai pimpinan
    public function headedWorkUnit()
    {
        return $this->hasOne(WorkUnit::class, 'employee_id');
    }
}
