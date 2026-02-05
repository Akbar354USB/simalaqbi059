<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'attendance_date',
        'work_shift_id',

        // check in
        'check_in_time',
        'check_in_latitude',
        'check_in_longitude',
        'check_in_distance_meter',
        'check_in_photo_path',
        'check_in_status',

        // check out
        'check_out_time',
        'check_out_latitude',
        'check_out_longitude',
        'check_out_distance_meter',
        'check_out_photo_path',
        'check_out_status',
    ];

    protected $casts = [
        'check_in_time'  => 'datetime',
        'check_out_time' => 'datetime',
    ];

    /* ================= RELATION ================= */

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function workShift()
    {
        return $this->belongsTo(WorkShift::class);
    }

    /* ================= HELPER ================= */

    public function isCheckedIn()
    {
        return !is_null($this->check_in_time);
    }

    public function isCheckedOut()
    {
        return !is_null($this->check_out_time);
    }
}
