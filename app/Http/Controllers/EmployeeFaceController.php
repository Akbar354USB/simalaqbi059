<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeFace;
use Illuminate\Http\Request;

class EmployeeFaceController extends Controller
{
    public function index()
    {
        $faces = EmployeeFace::with('employee')->latest()->get();
        return view('employee_faces.index', compact('faces'));
    }

    public function create()
    {
        $employees = Employee::where('is_active', true)->get();
        return view('employee_faces.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'face_descriptor' => 'required|json'
        ]);

        EmployeeFace::create([
            'employee_id' => $request->employee_id,
            'face_descriptor' => json_decode($request->face_descriptor, true)
        ]);

        return redirect()
            ->route('employee-faces.index')
            ->with('success', 'Data wajah berhasil disimpan');
    }


    public function destroy(EmployeeFace $employeeFace)
    {
        $employeeFace->delete();

        return redirect()
            ->route('employee-faces.index')
            ->with('success', 'Data wajah berhasil dihapus');
    }
}
