<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeFace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeFaceController extends Controller
{
    public function index()
    {
        $faces = EmployeeFace::with('employee')->latest()->get();
        return view('employee_faces.index', compact('faces'));
    }

    public function create()
    {
        $employee = Auth::user()->employee;
        // asumsi relasi: User hasOne Employee

        return view('employee_faces.create', compact('employee'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'embedding' => 'required|array'
        ]);

        $employee = Auth::user()->employee;

        EmployeeFace::updateOrCreate(
            ['employee_id' => $employee->id],
            ['face_embedding' => $request->embedding]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Wajah berhasil direkam'
        ]);
    }



    public function destroy(EmployeeFace $employeeFace)
    {
        $employeeFace->delete();

        return redirect()
            ->route('employee-faces.index')
            ->with('success', 'Data wajah berhasil dihapus');
    }
}
