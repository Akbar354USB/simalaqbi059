<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdditionalLeaveRequest;

class GeneralAffairsLeaveController extends Controller
{
    public function approve($id)
    {
        $leaveRequest = AdditionalLeaveRequest::where('id', $id)
            ->where('status', 'pending_general_affairs')
            ->firstOrFail();

        $currentYear = now()->year;

        $lastLetter = AdditionalLeaveRequest::where('letter_number', 'like', "SICTT-%/KPN.2602/$currentYear")
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = 1;

        if ($lastLetter) {
            preg_match('/SICTT-(\d+)/', $lastLetter->letter_number, $matches);
            $nextNumber = isset($matches[1]) ? ((int)$matches[1] + 1) : 1;
        }

        $letterNumber = "SICTT-{$nextNumber}/KPN.2602/{$currentYear}";

        $leaveRequest->update([
            'letter_number' => $letterNumber,
            'status' => 'approved_general_affairs',
            'approved_by_general_affairs' => auth()->id(),
            'approved_general_affairs_at' => now()
        ]);

        return back()->with('success', 'Cuti ditetapkan Subbag Umum');
    }

    public function reject($id)
    {
        $leaveRequest = AdditionalLeaveRequest::where('id', $id)
            ->where('status', 'pending_general_affairs')
            ->firstOrFail();

        $leaveRequest->update([
            'status' => 'rejected_general_affairs',
            'approved_by_general_affairs' => auth()->id(),
            'approved_general_affairs_at' => now()
        ]);

        return back()->with('success', 'Pengajuan ditolak Subbag Umum');
    }
}
