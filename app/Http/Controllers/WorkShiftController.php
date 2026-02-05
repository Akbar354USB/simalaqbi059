<?php

namespace App\Http\Controllers;

use App\Models\WorkShift;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WorkShiftController extends Controller
{
    // Tampilkan semua data
    public function index()
    {
        $workShifts = WorkShift::orderBy('id', 'desc')->paginate(10);
        return view('work_shifts.index', compact('workShifts'));
    }

    // Form tambah
    public function create()
    {
        return view('work_shifts.create');
    }

    // Simpan data
    public function store(Request $request)
    {
        $request->validate([
            'shift_name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i',
            'day_span'   => 'required|integer|min:0|max:7',
        ]);

        $startTime = Carbon::createFromFormat('H:i', $request->start_time);
        $endTime   = Carbon::createFromFormat('H:i', $request->end_time);

        // Tentukan lintas hari
        $isCrossDay = false;

        // Jika jam selesai <= jam mulai → pasti lintas hari
        if ($endTime->lte($startTime) || $request->day_span > 0) {
            $isCrossDay = true;
        }

        WorkShift::create([
            'shift_name'  => $request->shift_name,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'day_span'    => $request->day_span,
            'is_cross_day' => $isCrossDay,
        ]);

        return redirect()
            ->route('work-shifts.index')
            ->with('success', 'Shift kerja berhasil ditambahkan');
    }

    // Form edit
    public function edit(WorkShift $workShift)
    {
        return view('work_shifts.edit', compact('workShift'));
    }

    // Update data
    public function update(Request $request, WorkShift $workShift)
    {
        $validated = $request->validate([
            'shift_name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i',
        ]);

        $workShift->update($validated);

        return redirect()
            ->route('work-shifts.index')
            ->with('success', 'Shift berhasil diperbarui');
    }

    // Hapus data
    public function destroy(WorkShift $workShift)
    {
        $workShift->delete();

        return redirect()->route('work-shifts.index')
            ->with('success', 'Shift berhasil dihapus');
    }
}
