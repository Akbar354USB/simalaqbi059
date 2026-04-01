<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OvertimeRequestV2 extends Model
{
    use HasFactory;

    // Nama tabel (karena tidak mengikuti konvensi plural default Laravel)
    protected $table = 'overtime_request_v2_s';

    // Field yang boleh diisi (mass assignment)
    protected $fillable = [
        'employee_id',
        'overtime_date',
        'check_in_photo',
        'check_out_photo',
        'status',
        'purpose',
        'duration'
    ];

    // Casting tipe data
    protected $casts = [
        'overtime_date' => 'date',
        'duration' => 'integer',
    ];

    // Default value (opsional, sebenarnya sudah di DB)
    protected $attributes = [
        'status' => 'pending',
    ];

    /**
     * Relasi ke tabel employees
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getDurationInHoursAttribute()
    {
        return $this->duration ? round($this->duration / 60, 2) : 0;
    }

    // Relasi ke approval (1 lembur = 1 approval)
    public function approval()
    {
        return $this->hasOne(OvertimeApproval::class);
    }
}
