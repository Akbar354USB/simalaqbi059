<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OvertimeApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'overtime_request_id',
        'approved_by',
        'approval_status',
        'note',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */

    // Relasi ke pengajuan lembur
    public function overtimeRequest()
    {
        return $this->belongsTo(OvertimeRequest::class);
    }

    // Relasi ke pegawai yang menyetujui (pimpinan Sub Bagian Umum)
    public function approver()
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }
}
