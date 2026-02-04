<?php

namespace App\Http\Controllers;

use App\Models\AdditionalLeaveRequest;
use Illuminate\Http\Request;

class HeadOfficeApprovalController extends Controller
{
    public function index()
    {
        $requests = AdditionalLeaveRequest::with([
            'employee',
            'periods',
            'workUnit'
        ])
            ->whereIn('status', [
                'approved_unit_head',
                'pending_head_office'
            ])
            ->latest()
            ->get();

        return view('approvals.head_office.index', compact('requests'));
    }

    public function approve($id)
    {
        $leaveRequest = AdditionalLeaveRequest::where('id', $id)
            ->whereIn('status', [
                'approved_unit_head',
                'pending_head_office'
            ])
            ->firstOrFail();

        $leaveRequest->update([
            'status' => 'approved',
            'approved_by_head_office' => auth()->id(),
            'approved_head_office_at' => now(),
        ]);

        return back()->with('success', 'Pengajuan cuti disetujui kepala kantor');
    }

    public function reject($id)
    {
        $leaveRequest = AdditionalLeaveRequest::findOrFail($id);

        $leaveRequest->update([
            'status' => 'rejected',
            'approved_by_head_office' => auth()->id(),
            'approved_head_office_at' => now(),
        ]);

        return back()->with('success', 'Pengajuan cuti ditolak kepala kantor');
    }
}
