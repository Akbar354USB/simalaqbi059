<?php

namespace App\Http\Controllers;

use App\Models\WorkShift;
use Illuminate\Http\Request;

class WorkShiftController extends Controller
{
    // Tampilkan semua data
    public function index()
    {
        $workShifts = WorkShift::orderBy('id', 'desc')->paginate(10);
        return view('work_shifts.index', compact('workShifts'));
    }

    // Form tambah data
    public function create()
    {
        return view('work_shifts.create');
    }

    // Simpan data
    public function store(Request $request)
    {
        $request->validate([
            'shift_name' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time'   => 'required',
        ]);

        WorkShift::create($request->all());

        return redirect()->route('work-shifts.index')
            ->with('success', 'Shift berhasil ditambahkan');
    }

    // Form edit
    public function edit(WorkShift $workShift)
    {
        return view('work_shifts.edit', compact('workShift'));
    }

    // Update data
    public function update(Request $request, WorkShift $workShift)
    {
        $request->validate([
            'shift_name' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time'   => 'required',
        ]);

        $workShift->update($request->all());

        return redirect()->route('work-shifts.index')
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
