<?php

namespace App\Http\Controllers;

use App\Models\AdditionalLeave;
use App\Models\AdditionalLeaveRequest;
use App\Models\Employee;
use App\Models\User;
use App\Models\WorkUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class AdditionalLeaveRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = AdditionalLeaveRequest::with([
            'periods',
            'employee'
        ])->latest();

        // 🔍 SEARCH: Nama / NIP / No Surat
        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('letter_number', 'like', '%' . $request->keyword . '%')
                    ->orWhereHas('employee', function ($emp) use ($request) {
                        $emp->where('employee_name', 'like', '%' . $request->keyword . '%')
                            ->orWhere('nip', 'like', '%' . $request->keyword . '%');
                    });
            });
        }

        // 📅 FILTER PERIODE CUTI
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereHas('periods', function ($q) use ($request) {
                $q->whereDate('start_date', '>=', $request->start_date)
                    ->whereDate('end_date', '<=', $request->end_date);
            });
        }

        $requests = $query->get();

        return view(
            'additional_leave_requests.index',
            compact('requests')
        );
    }



    public function create()
    {
        $workUnits = WorkUnit::orderBy('work_unit')->get();
        return view('additional_leave_requests.create', compact('workUnits'));
    }

    public function history()
    {
        $requests = AdditionalLeaveRequest::with(['periods'])
            ->where('employee_id', auth()->user()->employee->id)
            ->latest()
            ->get();

        return view('additional_leave_requests.history', compact('requests'));
    }


    public function store(Request $request)
    {
        if (!auth()->user()->employee) {
            abort(403, 'User tidak terhubung dengan data pegawai');
        }

        $request->validate([
            'position'          => 'required|string',
            'length_of_service' => 'required|string',
            'work_unit_id'      => 'required|exists:work_units,id',
            'leave_reason'      => 'required|string',
            'phone'             => 'required|string',
            'leave_address'     => 'required|string',

            'periods'              => 'required|array|min:1',
            'periods.*.start_date' => 'required|date',
            'periods.*.end_date'   => 'required|date|after_or_equal:periods.*.start_date',
        ]);

        $employee = auth()->user()->employee;
        $year = now()->year;

        // 🔹 cek apakah pegawai adalah pimpinan unit
        $isUnitHead = WorkUnit::where('employee_id', $employee->id)->exists();

        // 🔹 tentukan status awal
        $status = $isUnitHead
            ? 'pending_head_office'
            : 'pending_unit_head';

        $additionalLeave = AdditionalLeave::where('employee_id', $employee->id)
            ->where('year', $year)
            ->first();

        if (!$additionalLeave) {
            return back()
                ->withErrors(['quota' => 'Anda belum memiliki kuota cuti tambahan'])
                ->withInput();
        }

        // 🔹 hitung total hari cuti
        $totalDays = 0;
        foreach ($request->periods as $period) {
            $start = Carbon::parse($period['start_date']);
            $end   = Carbon::parse($period['end_date']);
            $totalDays += $start->diffInDays($end) + 1;
        }

        if ($additionalLeave->remaining_quota < $totalDays) {
            return back()
                ->withErrors([
                    'quota' => "Sisa kuota hanya {$additionalLeave->remaining_quota} hari"
                ])
                ->withInput();
        }

        DB::transaction(function () use (
            $request,
            $employee,
            $totalDays,
            $year,
            $status
        ) {

            $leaveRequest = AdditionalLeaveRequest::create([
                'employee_id'       => $employee->id,
                'work_unit_id'      => $request->work_unit_id,
                'position'          => $request->position,
                'length_of_service' => $request->length_of_service,
                'leave_reason'      => $request->leave_reason,
                'phone'             => $request->phone,
                'leave_address'     => $request->leave_address,
                'letter_number'     => 'CUTI-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5)),
                'status'            => $status,
            ]);

            foreach ($request->periods as $period) {
                $start = Carbon::parse($period['start_date']);
                $end   = Carbon::parse($period['end_date']);

                $leaveRequest->periods()->create([
                    'start_date' => $start,
                    'end_date'   => $end,
                    'total_days' => $start->diffInDays($end) + 1,
                ]);
            }

            AdditionalLeave::where('employee_id', $employee->id)
                ->where('year', $year)
                ->decrement('remaining_quota', $totalDays);
        });

        $message = $isUnitHead
            ? 'Pengajuan cuti berhasil dikirim dan menunggu persetujuan Kepala Kantor'
            : 'Pengajuan cuti berhasil dikirim dan menunggu persetujuan Pimpinan Unit';

        return redirect()
            ->route('additional-leave-requests.history')
            ->with('success', $message);
    }



    public function show(AdditionalLeaveRequest $additionalLeaveRequest)
    {
        $additionalLeaveRequest->load('periods');

        return view('additional_leave_requests.show', compact('additionalLeaveRequest'));
    }

    public function edit(AdditionalLeaveRequest $additionalLeaveRequest)
    {
        return view('additional_leave_requests.edit', compact('additionalLeaveRequest'));
    }

    public function update(Request $request, AdditionalLeaveRequest $additionalLeaveRequest)
    {
        $request->validate([
            'leave_reason' => 'required',
            'phone'        => 'required',
            'leave_address' => 'required',

            'periods'                  => 'required|array|min:1',
            'periods.*.start_date'     => 'required|date',
            'periods.*.end_date'       => 'required|date|after_or_equal:periods.*.start_date',
        ]);

        DB::transaction(function () use ($request, $additionalLeaveRequest) {

            // 1. Update data utama
            $additionalLeaveRequest->update([
                'leave_reason'  => $request->leave_reason,
                'phone'         => $request->phone,
                'leave_address' => $request->leave_address,
            ]);

            $totalDays = 0;

            // 2. Update periode cuti
            foreach ($request->periods as $periodData) {

                $start = Carbon::parse($periodData['start_date']);
                $end   = Carbon::parse($periodData['end_date']);
                $days  = $start->diffInDays($end) + 1;

                $totalDays += $days;

                // update periode lama
                $additionalLeaveRequest->periods()
                    ->where('id', $periodData['id'])
                    ->update([
                        'start_date' => $start,
                        'end_date'   => $end,
                        'total_days' => $days,
                    ]);
            }

            // 3. Update total hari cuti (jika kolom ada)
            if (Schema::hasColumn('additional_leave_requests', 'total_leave_days')) {
                $additionalLeaveRequest->update([
                    'total_leave_days' => $totalDays
                ]);
            }
        });

        return redirect()
            ->route('additional-leave-requests.index')
            ->with('success', 'Pengajuan cuti berhasil diperbarui');
    }

    public function destroy(AdditionalLeaveRequest $additionalLeaveRequest)
    {
        DB::transaction(function () use ($additionalLeaveRequest) {

            // 1. Total hari cuti yang harus dikembalikan
            $totalDays = $additionalLeaveRequest->periods->sum('total_days');

            // 2. Ambil kuota cuti pegawai (berdasarkan tahun berjalan)
            $year = now()->year;

            $additionalLeave = AdditionalLeave::where('employee_id', $additionalLeaveRequest->employee_id)
                ->where('year', $year)
                ->lockForUpdate()
                ->first();

            // 3. Kembalikan kuota cuti
            if ($additionalLeave) {
                $additionalLeave->remaining_quota += $totalDays;
                $additionalLeave->save();
            }

            // 4. Hapus periode cuti
            $additionalLeaveRequest->periods()->delete();

            // 5. Hapus pengajuan cuti
            $additionalLeaveRequest->delete();
        });

        return redirect()
            ->route('additional-leave-requests.index')
            ->with('success', 'Pengajuan cuti berhasil dihapus dan kuota cuti dikembalikan');
    }

    public function print(AdditionalLeaveRequest $additionalLeaveRequest)
    {
        $additionalLeaveRequest->load([
            'periods',
            'employee.additionalLeaves' => function ($q) {
                $q->where('year', date('Y'));
            },
            'workUnit.employee' // pimpinan unit
        ]);

        $additionalLeave = $additionalLeaveRequest
            ->employee
            ->additionalLeaves
            ->first();

        // 🔑 Ambil Kepala Kantor (HEAD OFFICE)
        $headOffice = User::where('role', 'head_office')
            ->whereHas('employee')
            ->with('employee')
            ->first();

        $pdf = Pdf::loadView(
            'additional_leave_requests.pdf',
            [
                'request'         => $additionalLeaveRequest,
                'additionalLeave' => $additionalLeave,
                'headOffice'     => $headOffice, // ⬅ kirim ke blade
            ]
        )->setPaper('A4', 'portrait');

        return $pdf->stream(
            'Pengajuan-Cuti-' . $additionalLeaveRequest->letter_number . '.pdf'
        );
    }
}
