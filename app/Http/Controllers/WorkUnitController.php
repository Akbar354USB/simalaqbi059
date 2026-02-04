<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\WorkUnit;
use Illuminate\Http\Request;

class WorkUnitController extends Controller
{
    // READ
    public function index()
    {
        $workUnits = WorkUnit::with('employee')->paginate(10);
        return view('work_units.index', compact('workUnits'));
    }

    public function create()
    {
        $employees = Employee::where('is_active', true)
            ->where('status', 'PNS') // hanya PNS sebagai pimpinan
            ->orderBy('employee_name')
            ->get();

        return view('work_units.create', compact('employees'));
    }

    // STORE
    public function store(Request $request)
    {
        $validated = $request->validate([
            'work_unit'   => 'required|string|max:255',
            'employee_id' => 'required|exists:employees,id',
        ]);

        WorkUnit::create([
            'work_unit'   => $validated['work_unit'],
            'employee_id' => $validated['employee_id'], // pimpinan
        ]);

        return redirect()
            ->route('work-units.index')
            ->with('success', 'Unit Kerja berhasil ditambahkan');
    }
    // DELETE
    public function destroy($id)
    {
        $workUnit = WorkUnit::findOrFail($id);
        $workUnit->delete();

        return redirect()
            ->route('work-units.index')
            ->with('success', 'Unit Kerja Berhasil di hapus.');
    }
}
