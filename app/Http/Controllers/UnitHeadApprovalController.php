<?php

namespace App\Http\Controllers;

use App\Models\AdditionalLeaveRequest;
use App\Models\WorkUnit;
use Illuminate\Http\Request;

class UnitHeadApprovalController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // ambil employee pimpinan
        $employee = $user->employee;

        // cari unit yang dia pimpin
        $workUnit = WorkUnit::where('employee_id', $employee->id)->first();

        if (!$workUnit) {
            return view('approvals.unit_head.index', [
                'requests' => collect()
            ]);
        }

        $requests = AdditionalLeaveRequest::with([
            'employee',
            'periods',
            'workUnit'
        ])
            ->where('status', 'pending_unit_head')
            ->where('work_unit_id', $workUnit->id) // ✅ KUNCI UTAMA
            ->latest()
            ->get();

        return view('approvals.unit_head.index', compact('requests'));
    }


    public function approve($id)
    {
        $user = auth()->user();
        $employee = $user->employee;

        // Ambil unit kerja yang dipimpin user login
        $workUnit = WorkUnit::where('employee_id', $employee->id)->first();

        if (!$workUnit) {
            return back()->with('error', 'Anda tidak terdaftar sebagai pimpinan unit');
        }

        // Ambil pengajuan cuti
        $leaveRequest = AdditionalLeaveRequest::with('workUnit')
            ->where('id', $id)
            ->where('status', 'pending_unit_head')
            ->firstOrFail();

        // 🔒 Validasi: pastikan unit pengajuan == unit yang dipimpin
        if ($leaveRequest->work_unit_id !== $workUnit->id) {
            return back()->with('error', 'Anda tidak berhak menyetujui pengajuan ini');
        }

        // Update status
        $leaveRequest->update([
            'status' => 'approved_unit_head',
            'approved_by_unit_head' => $user->id,
            'approved_unit_head_at' => now(),
        ]);

        return back()->with('success', 'Pengajuan cuti disetujui');
    }


    public function reject($id)
    {
        $user = auth()->user();
        $employee = $user->employee;

        $workUnit = WorkUnit::where('employee_id', $employee->id)->first();

        if (!$workUnit) {
            return back()->with('error', 'Anda tidak terdaftar sebagai pimpinan unit');
        }

        $leaveRequest = AdditionalLeaveRequest::where('id', $id)
            ->where('status', 'pending_unit_head')
            ->firstOrFail();

        if ($leaveRequest->work_unit_id !== $workUnit->id) {
            return back()->with('error', 'Anda tidak berhak menolak pengajuan ini');
        }

        $leaveRequest->update([
            'status' => 'rejected_unit_head',
            'approved_by_unit_head' => $user->id,
            'approved_unit_head_at' => now(),
        ]);

        return back()->with('success', 'Pengajuan cuti ditolak');
    }
}
