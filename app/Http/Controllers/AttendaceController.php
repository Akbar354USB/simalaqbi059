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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Holiday;

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

    public function exportCsv()
    {
        $fileName = 'laporan-absensi-' . date('Y-m-d') . '.csv';

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
            'Jam Masuk',
            'Jam Pulang',
            'Shift'
        ];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');

            // Tambahkan BOM supaya tidak rusak di Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header kolom
            fputcsv($file, $columns);

            $attendances = Attendance::with(['employee', 'workShift'])
                ->orderBy('attendance_date', 'desc')
                ->get();

            foreach ($attendances as $attendance) {
                fputcsv($file, [
                    $attendance->employee->name ?? '-',
                    $attendance->attendance_date,
                    $attendance->check_in_time,
                    $attendance->check_out_time,
                    $attendance->workShift->shift_name ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // public function cetakkalender(Request $request)
    // {
    //     if (!$request->month) {
    //         return back()->with('error', 'Pilih bulan terlebih dahulu');
    //     }

    //     [$year, $month] = explode('-', $request->month);

    //     $daysInMonth = Carbon::create($year, $month)->daysInMonth;

    //     // ✅ weekend
    //     $weekends = [];
    //     for ($i = 1; $i <= $daysInMonth; $i++) {
    //         if (Carbon::create($year, $month, $i)->isWeekend()) {
    //             $weekends[$i] = true;
    //         }
    //     }

    //     // ✅ mapping status
    //     $statusMap = [
    //         'ON_TIME' => 'H',
    //         'TERLAMBAT' => 'TL',
    //         'LEBIH AWAL' => 'PSW',
    //         'CUTI TAHUNAN' => 'CT',
    //         'CUTI SETENGAH HARI' => 'CSH',
    //         'DINAS LUAR' => 'DL',
    //         'CUTI SAKIT' => 'CS',
    //     ];

    //     // ✅ prioritas
    //     $priority = ['PSW', 'TL', 'H', 'CT', 'CSH', 'DL', 'CS'];

    //     $workStatuses = ['H', 'TL', 'PSW'];

    //     $query = Employee::whereHas('attendances', function ($q) use ($month, $year) {
    //         $q->whereMonth('attendance_date', $month)
    //             ->whereYear('attendance_date', $year);
    //     })->where('status', 'PPNPN');

    //     if ($request->employee_id) {
    //         $query->where('id', $request->employee_id);
    //     }

    //     $employees = $query->with([
    //         'attendances.workShift',
    //         'attendances' => function ($q) use ($month, $year, $request) {
    //             $q->whereMonth('attendance_date', $month)
    //                 ->whereYear('attendance_date', $year);

    //             if ($request->work_shift_id) {
    //                 $q->where('work_shift_id', $request->work_shift_id);
    //             }
    //         }
    //     ])->get();

    //     $data = [];

    //     foreach ($employees as $emp) {

    //         $calendar = array_fill(1, $daysInMonth, []);
    //         $hadirDays = [];

    //         $rekap = [
    //             'H' => 0,
    //             'TL' => 0,
    //             'PSW' => 0,
    //             'CT' => 0,
    //             'CSH' => 0,
    //             'DL' => 0,
    //             'CS' => 0,
    //         ];

    //         foreach ($emp->attendances as $att) {

    //             $startDate = Carbon::parse($att->attendance_date);

    //             // =========================
    //             // ✅ STATUS
    //             // =========================
    //             $inRaw = $att->check_in_status ? strtoupper(trim($att->check_in_status)) : null;
    //             $inStatus = $statusMap[$inRaw] ?? null;

    //             $outStatus = ($att->check_out_status == 'LEBIH AWAL') ? 'PSW' : null;

    //             // =========================
    //             // ✅ SPAN SHIFT
    //             // =========================
    //             $span = ($att->workShift && (
    //                 in_array($inStatus, $workStatuses) || $outStatus
    //             ))
    //                 ? ($att->workShift->day_span ?? 0)
    //                 : 0;

    //             // =========================
    //             // ✅ LOOP HARI
    //             // =========================
    //             for ($d = 0; $d <= $span; $d++) {

    //                 $currentDate = $startDate->copy()->addDays($d);

    //                 if ($currentDate->month != $month) continue;

    //                 $day = $currentDate->day;

    //                 if ($inStatus) {
    //                     $calendar[$day][] = $inStatus;
    //                 }

    //                 if ($outStatus) {
    //                     $calendar[$day][] = $outStatus;
    //                 }

    //                 if (
    //                     in_array($inStatus, ['H', 'TL', 'CSH']) || // ✅ tambah CSH
    //                     $outStatus === 'PSW'
    //                 ) {
    //                     $hadirDays[$day] = true;
    //                 }
    //             }

    //             // =========================
    //             // ✅ REKAP
    //             // =========================
    //             if ($inStatus && isset($rekap[$inStatus])) {
    //                 $rekap[$inStatus]++;
    //             }

    //             if ($outStatus) {
    //                 $rekap['PSW']++;
    //             }
    //         }

    //         // =========================
    //         // ✅ FINAL CELL (UPDATED)
    //         // =========================
    //         for ($i = 1; $i <= $daysInMonth; $i++) {

    //             if (empty($calendar[$i])) {
    //                 $calendar[$i] = '-';
    //                 continue;
    //             }

    //             // 🔥 hapus duplikat
    //             $statuses = array_unique($calendar[$i]);

    //             // 🔥 sort prioritas
    //             usort($statuses, function ($a, $b) use ($priority) {
    //                 return array_search($a, $priority) - array_search($b, $priority);
    //             });

    //             // 🔥 jika ada PSW + TL tampilkan keduanya
    //             if (in_array('PSW', $statuses) && in_array('TL', $statuses)) {
    //                 $calendar[$i] = 'TL/PSW';
    //             } else {
    //                 $calendar[$i] = $statuses[0];
    //             }
    //         }

    //         $hadir = count($hadirDays);

    //         $data[] = [
    //             'name' => $emp->employee_name,
    //             'calendar' => $calendar,
    //             'hadir' => $hadir,
    //             'rekap' => $rekap
    //         ];
    //     }

    //     $pdf = Pdf::loadView('attendance.kalender', compact(
    //         'data',
    //         'daysInMonth',
    //         'month',
    //         'year',
    //         'weekends'
    //     ))->setPaper('A4', 'landscape');

    //     return $pdf->stream('absensi.pdf');
    // }

    public function cetakkalender(Request $request)
    {
        if (!$request->month) {
            return back()->with('error', 'Pilih bulan terlebih dahulu');
        }

        [$year, $month] = explode('-', $request->month);

        $daysInMonth = Carbon::create($year, $month)->daysInMonth;
        // =========================
        // ✅ AMBIL HARI LIBUR
        // =========================
        $holidays = Holiday::where(function ($q) use ($month, $year) {
            $q->whereMonth('start_date', $month)
                ->whereYear('start_date', $year)
                ->orWhere(function ($q2) use ($month, $year) {
                    $q2->whereNotNull('end_date')
                        ->whereMonth('end_date', $month)
                        ->whereYear('end_date', $year);
                });
        })->get();

        // 🔥 mapping tanggal libur
        $holidayDates = [];
        $holidayLabels = [];

        foreach ($holidays as $h) {

            $start = \Carbon\Carbon::parse($h->start_date);
            $end = $h->end_date
                ? \Carbon\Carbon::parse($h->end_date)
                : $start;

            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {

                if ($date->month != $month) continue;

                $day = $date->day;

                $holidayDates[$day] = true;

                // simpan nama libur (bisa lebih dari satu)
                $holidayLabels[$day][] = $h->name;
            }
        }

        // ✅ weekend
        $weekends = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            if (Carbon::create($year, $month, $i)->isWeekend()) {
                $weekends[$i] = true;
            }
        }

        // ✅ mapping status
        $statusMap = [
            'ON_TIME' => 'H',
            'TERLAMBAT' => 'TL',
            'LEBIH AWAL' => 'PSW',
            'CUTI TAHUNAN' => 'CT',
            'CUTI SETENGAH HARI' => 'CSH',
            'DINAS LUAR' => 'DL',
            'CUTI SAKIT' => 'CS',
        ];

        // ✅ prioritas
        $priority = ['PSW', 'TL', 'H', 'CT', 'CSH', 'DL', 'CS'];

        $workStatuses = ['H', 'TL', 'PSW'];

        $query = Employee::whereHas('attendances', function ($q) use ($month, $year) {
            $q->whereMonth('attendance_date', $month)
                ->whereYear('attendance_date', $year);
        })->where('status', 'PPNPN');

        if ($request->employee_id) {
            $query->where('id', $request->employee_id);
        }

        $employees = $query->with([
            'attendances.workShift',
            'attendances' => function ($q) use ($month, $year, $request) {
                $q->whereMonth('attendance_date', $month)
                    ->whereYear('attendance_date', $year);

                if ($request->work_shift_id) {
                    $q->where('work_shift_id', $request->work_shift_id);
                }
            }
        ])->get();

        $data = [];

        foreach ($employees as $emp) {

            $calendar = array_fill(1, $daysInMonth, []);
            $hadirDays = [];

            $rekap = [
                'H' => 0,
                'TL' => 0,
                'PSW' => 0,
                'CT' => 0,
                'CSH' => 0,
                'DL' => 0,
                'CS' => 0,
            ];

            foreach ($emp->attendances as $att) {

                $startDate = Carbon::parse($att->attendance_date);

                // =========================
                // ✅ STATUS
                // =========================
                $inRaw = $att->check_in_status ? strtoupper(trim($att->check_in_status)) : null;
                $inStatus = $statusMap[$inRaw] ?? null;

                $outStatus = ($att->check_out_status == 'LEBIH AWAL') ? 'PSW' : null;

                // =========================
                // ✅ SPAN SHIFT
                // =========================
                $span = ($att->workShift && (
                    in_array($inStatus, $workStatuses) || $outStatus
                ))
                    ? ($att->workShift->day_span ?? 0)
                    : 0;

                // =========================
                // ✅ LOOP HARI
                // =========================
                for ($d = 0; $d <= $span; $d++) {

                    $currentDate = $startDate->copy()->addDays($d);

                    if ($currentDate->month != $month) continue;

                    $day = $currentDate->day;

                    if ($inStatus) {
                        $calendar[$day][] = $inStatus;
                    }

                    if ($outStatus) {
                        $calendar[$day][] = $outStatus;
                    }

                    if (
                        in_array($inStatus, ['H', 'TL', 'CSH']) || // ✅ tambah CSH
                        $outStatus === 'PSW'
                    ) {
                        $hadirDays[$day] = true;
                    }
                }

                // =========================
                // ✅ REKAP
                // =========================
                if ($inStatus && isset($rekap[$inStatus])) {
                    $rekap[$inStatus]++;
                }

                if ($outStatus) {
                    $rekap['PSW']++;
                }
            }

            // =========================
            // ✅ FINAL CELL (UPDATED)
            // =========================
            for ($i = 1; $i <= $daysInMonth; $i++) {

                if (empty($calendar[$i])) {
                    $calendar[$i] = '-';
                    continue;
                }

                // 🔥 hapus duplikat
                $statuses = array_unique($calendar[$i]);

                // 🔥 sort prioritas
                usort($statuses, function ($a, $b) use ($priority) {
                    return array_search($a, $priority) - array_search($b, $priority);
                });

                // 🔥 jika ada PSW + TL tampilkan keduanya
                if (in_array('PSW', $statuses) && in_array('TL', $statuses)) {
                    $calendar[$i] = 'TL/PSW';
                } else {
                    $calendar[$i] = $statuses[0];
                }
            }

            $hadir = count($hadirDays);

            $data[] = [
                'name' => $emp->employee_name,
                'calendar' => $calendar,
                'hadir' => $hadir,
                'rekap' => $rekap
            ];
        }

        $pdf = Pdf::loadView('attendance.kalender', compact(
            'data',
            'daysInMonth',
            'month',
            'year',
            'weekends',
            'holidayDates',   // 🔥 tambahan
            'holidayLabels',   // 🔥 tambahan
            'holidays' // 🔥 TAMBAHAN INI
        ))->setPaper('A4', 'landscape');

        return $pdf->stream('absensi.pdf');
    }

    public function storeManual(Request $request)
    {
        $request->validate([
            'employee_id'     => 'required|exists:employees,id',
            'attendance_date' => 'required|date',
            'work_shift_id'   => 'required|exists:work_shifts,id', // ✅ tambah ini
            'status'          => 'required|string',
        ]);

        // ✅ cek duplikasi (karena ada unique constraint)
        $exists = \App\Models\Attendance::where('employee_id', $request->employee_id)
            ->where('attendance_date', $request->attendance_date)
            ->where('work_shift_id', $request->work_shift_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Data sudah ada untuk tanggal & shift ini'
            ], 400);
        }

        \App\Models\Attendance::create([
            'employee_id'     => $request->employee_id,
            'attendance_date' => $request->attendance_date,
            'work_shift_id'   => $request->work_shift_id,

            // status manual
            'check_in_status' => $request->status,

            // default kosong
            'check_in_time'  => null,
            'check_out_time' => null,
            'check_out_status' => null,
        ]);

        return response()->json([
            'message' => 'Kehadiran manual berhasil ditambahkan'
        ]);
    }

    public function updateInline(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        $attendance->update([
            'check_in_time' => $request->check_in_time
                ? date('Y-m-d H:i:s', strtotime($attendance->attendance_date . ' ' . $request->check_in_time))
                : null,

            'check_out_time' => $request->check_out_time
                ? date('Y-m-d H:i:s', strtotime($attendance->attendance_date . ' ' . $request->check_out_time))
                : null,

            'check_in_status' => $request->check_in_status,
            'check_out_status' => $request->check_out_status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diupdate'
        ]);
    }
}
