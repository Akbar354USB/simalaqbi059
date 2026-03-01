<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OvertimeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'overtime_date',
        'start_time',
        'end_time',
        'total_minutes',
        'photo',
        'status',
    ];

    protected $casts = [
        'overtime_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */

    // Relasi ke pegawai
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Relasi ke approval (1 lembur = 1 approval)
    public function approval()
    {
        return $this->hasOne(OvertimeApproval::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSOR
    |--------------------------------------------------------------------------
    */

    // Format jam mulai
    public function getStartTimeFormattedAttribute()
    {
        return Carbon::parse($this->start_time)->format('H:i');
    }

    // Format jam selesai
    public function getEndTimeFormattedAttribute()
    {
        return Carbon::parse($this->end_time)->format('H:i');
    }

    // Durasi dalam jam
    public function getTotalHoursAttribute()
    {
        return round($this->total_minutes / 60, 2);
    }

    // URL foto
    public function getPhotoUrlAttribute()
    {
        return asset('storage/' . $this->photo);
    }
}
