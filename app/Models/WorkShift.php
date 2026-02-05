<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WorkShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'shift_name',
        'start_time',
        'end_time',
        'day_span',
        'is_cross_day',
    ];

    protected $casts = [
        'is_cross_day' => 'boolean',
    ];

    /**
     * Relasi ke absensi
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Ambil datetime mulai shift
     */
    public function startDateTime($date)
    {
        return Carbon::parse($date . ' ' . $this->start_time);
    }

    /**
     * Ambil datetime selesai shift (aman untuk lintas hari)
     */
    public function endDateTime($date)
    {
        return $this->is_cross_day
            ? Carbon::parse($date . ' ' . $this->end_time)->addDays($this->day_span)
            : Carbon::parse($date . ' ' . $this->end_time);
    }

    /**
     * Cek apakah waktu tertentu berada dalam jam shift
     */
    public function isWithinShift(Carbon $time, $date)
    {
        return $time->between(
            $this->startDateTime($date),
            $this->endDateTime($date)
        );
    }
}
