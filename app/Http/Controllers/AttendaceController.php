<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use App\Models\OfficeLocation;
use App\Models\WorkShift;
use App\Services\GeoService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AttendaceController extends Controller
{
    public function index()
    {
        $employeeId = auth()->user()->employee_id;
        $now = now();

        $attendanceToday = Attendance::where('employee_id', $employeeId)
            ->where(function ($q) use ($now) {
                $q->whereDate('attendance_date', $now->toDateString())
                    ->orWhereDate('attendance_date', $now->copy()->subDay()->toDateString());
            })
            ->whereNull('check_out_time')
            ->first();

        return view('attendance.index', [
            'shifts' => WorkShift::all(),
            'attendanceToday' => $attendanceToday
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'work_shift_id' => 'required|exists:work_shifts,id',
            'latitude'      => 'required',
            'longitude'     => 'required',
            'photo'         => 'required|image|mimes:jpg,jpeg,png',
        ]);

        $user = auth()->user();

        if (!$user->employee || !$user->employee->is_active) {
            return response()->json([
                'status'  => false,
                'message' => 'Akun tidak aktif'
            ], 403);
        }



        $shift = WorkShift::findOrFail($request->work_shift_id);
        $now   = now();

        $existingAttendance = Attendance::where('employee_id', $user->employee_id)
            ->where(function ($q) use ($now) {
                $q->whereDate('attendance_date', $now->toDateString())
                    ->orWhereDate('attendance_date', $now->copy()->subDay()->toDateString());
            })
            ->whereNull('check_out_time')
            ->first();

        if ($existingAttendance) {
            // FORCE shift dari absensi datang
            $request->merge([
                'work_shift_id' => $existingAttendance->work_shift_id
            ]);
        }
        /**
         * Tentukan tanggal absensi
         * Jika shift lintas hari dan sekarang < jam selesai
         * maka absensi dianggap milik hari sebelumnya
         */
        $attendanceDate = $shift->is_cross_day &&
            $now->lt($shift->endDateTime($now->toDateString()))
            ? $now->copy()->subDay()->toDateString()
            : $now->toDateString();

        // 📍 Validasi lokasi kantor
        $office = OfficeLocation::first();
        if (!$office) {
            return response()->json([
                'status'  => false,
                'message' => 'Lokasi kantor belum dikonfigurasi'
            ], 422);
        }

        $distance = GeoService::distanceMeter(
            $request->latitude,
            $request->longitude,
            $office->latitude,
            $office->longitude
        );

        if ($distance > $office->radius_meter) {
            return response()->json([
                'status'  => false,
                'message' => 'Di luar radius kantor'
            ], 403);
        }

        // Cari absensi hari ini (JANGAN CREATE DULU)
        $attendance = Attendance::where('employee_id', $user->employee_id)
            ->where('attendance_date', $attendanceDate)
            ->where('work_shift_id', $shift->id)
            ->first();

        // 📷 Simpan foto (dipakai check-in / check-out)
        $photoPath = $request->file('photo')
            ->store('attendance_photos', 'public');

        /* ================= CHECK IN ================= */
        if (!$attendance) {

            // waktu mulai shift
            $shiftStart = $shift->startDateTime($attendanceDate);

            // batas toleransi (15 menit setelah shift mulai)
            $lateTolerance = $shiftStart->copy()->addMinutes(15);

            // tentukan status datang
            $checkInStatus = $now->lte($lateTolerance) ? 'ON_TIME' : 'TERLAMBAT';

            Attendance::create([
                'employee_id'             => $user->employee_id,
                'attendance_date'         => $attendanceDate,
                'work_shift_id'           => $shift->id,
                'check_in_time'           => $now,
                'check_in_latitude'       => $request->latitude,
                'check_in_longitude'      => $request->longitude,
                'check_in_distance_meter' => $distance,
                'check_in_photo_path'     => $photoPath,
                'check_in_status'         => $checkInStatus,
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Absen datang berhasil'
            ]);
        }

        /* ================= CHECK OUT ================= */
        if ($attendance->check_out_time) {
            return response()->json([
                'status'  => false,
                'message' => 'Anda sudah absen pulang'
            ], 422);
        }

        $shiftEnd = $shift->endDateTime($attendanceDate);

        // toleransi pulang 10 menit
        $checkoutTolerance = $shiftEnd->copy()->addMinutes(10);

        if ($now->lt($shiftEnd)) {
            $checkOutStatus = 'LEBIH AWAL';
        } elseif ($now->lte($checkoutTolerance)) {
            $checkOutStatus = 'ON_TIME';
        } else {
            $checkOutStatus = 'TERLAMBAT';
        }

        $attendance->update([
            'check_out_time'           => $now,
            'check_out_latitude'       => $request->latitude,
            'check_out_longitude'      => $request->longitude,
            'check_out_distance_meter' => $distance,
            'check_out_photo_path'     => $photoPath,
            'check_out_status'         => $checkOutStatus,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Absen pulang berhasil'
        ]);
    }

    // public function dataindex(Request $request)
    // {
    //     $query = Attendance::with(['employee', 'workShift'])
    //         ->orderByDesc('attendance_date')
    //         ->orderByDesc('check_in_time');

    //     if ($request->filled('date')) {
    //         $query->whereDate('attendance_date', $request->date);
    //     }

    //     if ($request->filled('month')) {
    //         $query->whereMonth('attendance_date', date('m', strtotime($request->month)))
    //             ->whereYear('attendance_date', date('Y', strtotime($request->month)));
    //     }

    //     $attendances = $query->paginate(20);

    //     return view('attendance.dataindex', compact('attendances'));
    // }

    public function dataindex(Request $request)
    {
        $query = Attendance::with(['employee', 'workShift'])
            ->whereHas('employee', function ($q) {
                $q->where('status', 'PPNPN'); // ✅ hanya PPNPN
            })
            ->orderByDesc('attendance_date')
            ->orderByDesc('check_in_time');

        // FILTER TANGGAL
        if ($request->filled('date')) {
            $query->whereDate('attendance_date', $request->date);
        }

        // FILTER BULAN
        if ($request->filled('month')) {
            $query->whereMonth('attendance_date', date('m', strtotime($request->month)))
                ->whereYear('attendance_date', date('Y', strtotime($request->month)));
        }

        // FILTER NAMA PEGAWAI
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // FILTER SHIFT
        if ($request->filled('work_shift_id')) {
            $query->where('work_shift_id', $request->work_shift_id);
        }

        $attendances = $query->paginate(20)->withQueryString();

        // Dropdown hanya PPNPN
        $employees = Employee::where('status', 'PPNPN')
            ->orderBy('employee_name')
            ->get();

        $workShifts = WorkShift::orderBy('shift_name')->get();

        return view('attendance.dataindex', compact(
            'attendances',
            'employees',
            'workShifts'
        ));
    }

    public function destroy(Attendance $attendance)
    {
        // 🔥 Hapus foto check-in
        if (
            $attendance->check_in_photo_path &&
            Storage::disk('public')->exists($attendance->check_in_photo_path)
        ) {
            Storage::disk('public')->delete($attendance->check_in_photo_path);
        }

        // 🔥 Hapus foto check-out
        if (
            $attendance->check_out_photo_path &&
            Storage::disk('public')->exists($attendance->check_out_photo_path)
        ) {
            Storage::disk('public')->delete($attendance->check_out_photo_path);
        }

        // 🗑 Hapus data absensi
        $attendance->delete();

        return redirect()
            ->route('attendances.data')
            ->with('success', 'Data absensi dan foto berhasil dihapus');
    }

    public function destroyAll()
    {
        $attendances = Attendance::all();

        foreach ($attendances as $attendance) {

            // FOTO DATANG
            if (
                $attendance->check_in_photo_path &&
                Storage::disk('public')->exists($attendance->check_in_photo_path)
            ) {
                Storage::disk('public')->delete($attendance->check_in_photo_path);
            }

            // FOTO PULANG
            if (
                $attendance->check_out_photo_path &&
                Storage::disk('public')->exists($attendance->check_out_photo_path)
            ) {
                Storage::disk('public')->delete($attendance->check_out_photo_path);
            }
        }

        Attendance::truncate(); // hapus semua data

        return redirect()
            ->back()
            ->with('success', 'SEMUA data absensi dan foto berhasil dihapus');
    }

    // public function printPdf(Request $request)
    // {
    //     $query = Attendance::with(['employee', 'workShift'])
    //         ->orderBy('attendance_date', 'asc')
    //         ->orderBy('check_in_time', 'asc'); // ✅ GANTI

    //     // Filter tanggal
    //     if ($request->filled('date')) {
    //         $query->whereDate('attendance_date', $request->date);
    //     }

    //     // Filter bulan
    //     if ($request->filled('month')) {
    //         $query->whereMonth('attendance_date', date('m', strtotime($request->month)))
    //             ->whereYear('attendance_date', date('Y', strtotime($request->month)));
    //     }

    //     $attendances = $query->get();

    //     $pdf = Pdf::loadView('attendance.pdf', [
    //         'attendances' => $attendances,
    //         'filters' => [
    //             'date'  => $request->date,
    //             'month' => $request->month,
    //         ]
    //     ])->setPaper('A4', 'landscape');

    //     return $pdf->stream('data-absensi.pdf');
    // }

    public function printPdf(Request $request)
    {
        $query = Attendance::with(['employee', 'workShift'])
            ->whereHas('employee', function ($q) {
                $q->where('status', 'PPNPN'); // ✅ hanya PPNPN
            })
            ->orderBy('attendance_date', 'asc')
            ->orderBy('check_in_time', 'asc');

        // ✅ Filter tanggal
        if ($request->filled('date')) {
            $query->whereDate('attendance_date', $request->date);
        }

        // ✅ Filter bulan
        if ($request->filled('month')) {
            $query->whereMonth('attendance_date', date('m', strtotime($request->month)))
                ->whereYear('attendance_date', date('Y', strtotime($request->month)));
        }

        // ✅ Filter pegawai
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // ✅ Filter shift
        if ($request->filled('work_shift_id')) {
            $query->where('work_shift_id', $request->work_shift_id);
        }

        $attendances = $query->get();

        $pdf = Pdf::loadView('attendance.pdf', [
            'attendances' => $attendances,
            'filters' => [
                'date'          => $request->date,
                'month'         => $request->month,
                'employee_id'   => $request->employee_id,
                'work_shift_id' => $request->work_shift_id,
            ]
        ])->setPaper('A4', 'landscape');

        return $pdf->stream('data-absensi.pdf');
    }

    public function dashboardabsensi()
    {
        $employee = auth()->user()->employee;

        $attendances = auth()->user()
            ->employee
            ->attendances()
            ->with('workShift')
            ->latest('attendance_date')
            ->latest('check_in_time')
            ->limit(5)
            ->get();

        // ambil 5 data lembur terbaru
        $overtimes = $employee
            ->overtimeRequests()
            ->latest('overtime_date')
            ->latest('start_time')
            ->limit(5)
            ->get();

        return view('attendance.dashboard_presensi', compact('attendances', 'overtimes'));
    }
}
