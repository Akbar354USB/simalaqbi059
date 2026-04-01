<?php

namespace App\Http\Controllers;

use App\Models\OvertimeRequestV2;
use App\Models\Employee;
use App\Models\OfficeLocation;
use App\Models\OvertimeApproval;
use App\Models\WorkUnit;
use App\Services\GeoService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class OvertimeRequestV2Controller extends Controller
{
    /**
     * Tampilkan semua data
     */
    // public function index(Request $request)
    // {
    //     $query = OvertimeRequestV2::with('employee')->latest();

    //     // 🔍 Filter Nama (berdasarkan employee_id)
    //     if ($request->nama) {
    //         $query->where('employee_id', $request->nama);
    //     }

    //     // Filter Tanggal
    //     if ($request->tanggal) {
    //         $query->whereDate('overtime_date', $request->tanggal);
    //     }

    //     // Filter Status
    //     if ($request->status) {
    //         $query->where('status', $request->status);
    //     }

    //     $overtimes = $query->paginate(10);
    //     $overtimes->appends($request->all());

    //     // 🔥 Ambil pegawai status PPNPN
    //     $employees = Employee::where('status', 'PPNPN')->orderBy('employee_name')->get();

    //     return view('overtime_v2.index', compact('overtimes', 'employees'));
    // }


    public function index(Request $request)
    {
        $user = auth()->user();
        $employee = $user->employee;

        // 🔍 Cek approver (Sub Bag Umum)
        $unit = WorkUnit::where('work_unit', 'Sub Bagian Umum')->first();
        $isSubBagUmumApprover = $unit && $employee && $unit->employee_id == $employee->id;

        // 🔍 Cek superadmin
        $isSuperAdmin = $user->role === 'superadmin';

        // 🔥 Final status approver
        $isApprover = $isSubBagUmumApprover || $isSuperAdmin;

        // ================= QUERY =================
        $query = OvertimeRequestV2::with('employee')->latest();

        // 🔐 Batasi jika bukan approver
        if (!$isApprover && $employee) {
            $query->where('employee_id', $employee->id);
        }

        // 🔍 FILTER (TETAP JALAN)
        if ($request->nama) {
            $query->where('employee_id', $request->nama);
        }

        if ($request->tanggal) {
            $query->whereDate('overtime_date', $request->tanggal);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        // ================= PAGINATION =================
        $overtimes = $query->paginate(1);
        $overtimes->appends($request->all());

        // 🔥 Dropdown pegawai (PPNPN)
        $employees = Employee::where('status', 'PPNPN')
            ->orderBy('employee_name')
            ->get();

        return view('overtime_v2.index', compact(
            'overtimes',
            'employees',
            'isApprover' // 🔥 kirim ke blade (opsional)
        ));
    }

    /**
     * Form tambah
     */
    public function create()
    {
        $overtimeToday = OvertimeRequestV2::where('employee_id', auth()->user()->employee_id)
            ->where('overtime_date', now()->toDateString())
            ->whereNull('check_out_photo') // 🔥 INI KUNCINYA
            ->first();

        $employees = Employee::all();

        return view('overtime_v2.create', compact('overtimeToday', 'employees'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $employeeId = $user->employee_id;

        // ================= CEK DATA HARI INI =================
        $overtimeDate = now()->toDateString();
        $overtime = OvertimeRequestV2::where('employee_id', $employeeId)
            ->where('overtime_date', $overtimeDate)
            ->first();

        try {

            if (!$overtime) {
                // ✅ CHECK-IN → purpose wajib
                $request->validate([
                    'latitude' => 'required',
                    'longitude' => 'required',
                    'photo' => 'required|image',
                    'purpose' => 'required|string'
                ]);
            } else {
                // ✅ CHECK-OUT → purpose tidak wajib
                $request->validate([
                    'latitude' => 'required',
                    'longitude' => 'required',
                    'photo' => 'required|image',
                ]);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        }

        // ================= VALIDASI LOKASI =================
        $office = OfficeLocation::first();

        if (!$office) {
            return response()->json([
                'status' => false,
                'message' => 'Lokasi kantor belum diset'
            ]);
        }

        $distance = GeoService::distanceMeter(
            $request->latitude,
            $request->longitude,
            $office->latitude,
            $office->longitude
        );

        if ($distance > $office->radius_meter) {
            return response()->json([
                'status' => false,
                'message' => 'Di luar radius kantor'
            ]);
        }

        $photoPath = $request->file('photo')->store('overtime', 'public');

        /* ================= CHECK IN ================= */
        if (!$overtime) {

            OvertimeRequestV2::create([
                'employee_id' => $employeeId,
                'overtime_date' => $request->overtime_date,
                'check_in_photo' => $photoPath,
                'purpose' => $request->purpose,
                'status' => 'pending'
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Check-in lembur berhasil'
            ]);
        }

        /* ================= CHECK OUT ================= */
        if ($overtime->check_out_photo) {
            return response()->json([
                'status' => false,
                'message' => 'Sudah check-out lembur'
            ]);
        }

        $checkInTime = $overtime->created_at;
        $duration = now()->diffInMinutes($checkInTime);

        $overtime->update([
            'check_out_photo' => $photoPath,
            'duration' => $duration
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Check-out lembur berhasil'
        ]);
    }

    /**
     * Detail data
     */
    public function show($id)
    {
        $overtime = OvertimeRequestV2::with('employee')->findOrFail($id);
        return view('overtime_v2.show', compact('overtime'));
    }

    /**
     * Form edit
     */
    public function edit($id)
    {
        $overtime = OvertimeRequestV2::findOrFail($id);
        $employees = Employee::all();

        return view('overtime_v2.edit', compact('overtime', 'employees'));
    }

    /**
     * Update data
     */
    public function update(Request $request, $id)
    {
        $overtime = OvertimeRequestV2::findOrFail($id);

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'overtime_date' => 'required|date',
            'purpose' => 'required|string',
            'duration' => 'nullable|integer',
            'check_in_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'check_out_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $data = $request->only([
            'employee_id',
            'overtime_date',
            'purpose',
            'duration',
            'status'
        ]);

        // Update check-in photo
        if ($request->hasFile('check_in_photo')) {
            if ($overtime->check_in_photo) {
                Storage::disk('public')->delete($overtime->check_in_photo);
            }

            $data['check_in_photo'] = $request->file('check_in_photo')
                ->store('overtime/checkin', 'public');
        }

        // Update check-out photo
        if ($request->hasFile('check_out_photo')) {
            if ($overtime->check_out_photo) {
                Storage::disk('public')->delete($overtime->check_out_photo);
            }

            $data['check_out_photo'] = $request->file('check_out_photo')
                ->store('overtime/checkout', 'public');
        }

        $overtime->update($data);

        return redirect()->route('overtime_v2.index')
            ->with('success', 'Data lembur berhasil diperbarui');
    }

    /**
     * Hapus data
     */
    public function destroy($id)
    {
        $overtime = OvertimeRequestV2::findOrFail($id);

        // Hapus file
        if ($overtime->check_in_photo) {
            Storage::disk('public')->delete($overtime->check_in_photo);
        }

        if ($overtime->check_out_photo) {
            Storage::disk('public')->delete($overtime->check_out_photo);
        }

        $overtime->delete();

        return redirect()->route('overtime_v2.index')
            ->with('success', 'Data lembur berhasil dihapus');
    }

    public function exportPdf(Request $request)
    {
        $query = OvertimeRequestV2::with('employee');

        // 🔍 Filter
        if ($request->nama) {
            $query->where('employee_id', $request->nama);
        }

        if ($request->tanggal) {
            $query->whereDate('overtime_date', $request->tanggal);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $data = $query->get();

        // 🔥 Ambil nama pegawai (biar tidak error walaupun data kosong)
        $employeeName = null;
        if ($request->nama) {
            $employee = Employee::find($request->nama);
            $employeeName = $employee->employee_name ?? null;
        }

        // ✅ Load PDF SEKALI saja
        $pdf = Pdf::loadView('overtime_v2.pdf', [
            'data' => $data,
            'employeeName' => $employeeName
        ]);

        return $pdf->stream('data_lembur.pdf');
    }

    public function exportCsv(Request $request)
    {
        $fileName = 'data-lembur-' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate",
            "Expires"             => "0",
        ];

        $columns = [
            'Nama Pegawai',
            'Tanggal',
            'Status',
            'Durasi (menit)',
            'Keperluan'
        ];

        $callback = function () use ($columns, $request) {
            $file = fopen('php://output', 'w');

            // 🔥 BOM agar tidak rusak di Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header
            fputcsv($file, $columns, ';'); // pakai ; biar aman di Excel Indonesia

            // Query + filter
            $query = OvertimeRequestV2::with('employee')->orderBy('overtime_date', 'desc');

            if ($request->nama) {
                $query->where('employee_id', $request->nama);
            }

            if ($request->tanggal) {
                $query->whereDate('overtime_date', $request->tanggal);
            }

            if ($request->status) {
                $query->where('status', $request->status);
            }

            $data = $query->get();

            foreach ($data as $item) {
                fputcsv($file, [
                    $item->employee->employee_name ?? '-',
                    $item->overtime_date,
                    $item->status,
                    $item->duration,
                    $item->purpose,
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function approve($id)
    {
        $overtime = OvertimeRequestV2::findOrFail($id);

        $user = auth()->user();
        $employee = $user->employee;

        $unit = WorkUnit::where('work_unit', 'Sub Bagian Umum')->first();

        // cek apakah pimpinan sub bagian umum
        $isUnitApprover = $unit && $employee && $employee->id === $unit->employee_id;

        // cek apakah superadmin
        $isSuperAdmin = $user->role === 'superadmin';

        // jika bukan keduanya, tolak akses
        if (!$isUnitApprover && !$isSuperAdmin) {
            abort(403);
        }

        $overtime->update(['status' => 'approved']);

        OvertimeApproval::create([
            'overtime_request_id' => $overtime->id,
            'approved_by'         => $employee ? $employee->id : null,
            'approval_status'     => 'approved',
            'approved_at'         => now(),
        ]);

        return back()->with('success', 'Lembur disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $overtime = OvertimeRequestV2::findOrFail($id);

        $user     = auth()->user();
        $employee = $user->employee;

        $unit = WorkUnit::where('work_unit', 'Sub Bagian Umum')->first();

        // cek apakah pimpinan sub bagian umum
        $isUnitApprover = $unit && $employee && $employee->id === $unit->employee_id;

        // cek apakah superadmin
        $isSuperAdmin = $user->role === 'superadmin';

        // jika bukan keduanya, tolak akses
        if (!$isUnitApprover && !$isSuperAdmin) {
            abort(403);
        }

        $overtime->update(['status' => 'rejected']);

        OvertimeApproval::create([
            'overtime_request_id' => $overtime->id,
            'approved_by'         => $employee ? $employee->id : null,
            'approval_status'     => 'rejected',
            'note'                => $request->note,
            'approved_at'         => now(),
        ]);

        return back()->with('success', 'Lembur ditolak.');
    }
}
