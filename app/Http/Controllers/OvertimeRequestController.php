<?php

namespace App\Http\Controllers;

use App\Models\OvertimeRequest;
use App\Models\OvertimeApproval;
use App\Models\WorkUnit;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class OvertimeRequestController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST DATA
    |--------------------------------------------------------------------------
    */

    // public function index()
    // {
    //     $employee = auth()->user()->employee;

    //     // cek apakah pimpinan sub bagian umum
    //     $unit = WorkUnit::where('work_unit', 'Sub Bagian Umum')->first();
    //     $isApprover = $unit && $unit->employee_id == $employee->id;

    //     if ($isApprover) {
    //         $overtimes = OvertimeRequest::latest()->get();
    //     } else {
    //         $overtimes = OvertimeRequest::where('employee_id', $employee->id)
    //             ->latest()
    //             ->get();
    //     }

    //     return view('overtime.index', compact('overtimes', 'isApprover'));
    // }


    public function index()
    {
        $user = auth()->user();
        $employee = $user->employee;

        // Cek apakah pimpinan Sub Bagian Umum
        $unit = WorkUnit::where('work_unit', 'Sub Bagian Umum')->first();
        $isSubBagUmumApprover = $unit && $employee && $unit->employee_id == $employee->id;

        // Cek apakah superadmin
        $isSuperAdmin = $user->role === 'superadmin';

        // Jika salah satu adalah approver
        $isApprover = $isSubBagUmumApprover || $isSuperAdmin;

        if ($isApprover) {
            $overtimes = OvertimeRequest::latest()->paginate(10);
        } else {
            $overtimes = OvertimeRequest::where('employee_id', $employee->id)
                ->latest()
                ->paginate(10);
        }

        return view('overtime.index', compact('overtimes', 'isApprover'));
    }

    /*
    |--------------------------------------------------------------------------
    | FORM CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('overtime.create');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'overtime_date' => 'required|date',
            'start_time'    => 'required',
            'end_time'      => 'required|after:start_time',
            'photo'         => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'overtime_date.required' => 'Tanggal lembur wajib diisi.',
            'overtime_date.date'     => 'Tanggal lembur harus berupa format tanggal yang valid.',

            'start_time.required'    => 'Jam mulai wajib diisi.',
            'end_time.required'      => 'Jam selesai wajib diisi.',
            'end_time.after'         => 'Jam selesai harus setelah jam mulai.',

            'photo.required'         => 'Foto wajib diunggah.',
            'photo.image'            => 'File harus berupa gambar.',
            'photo.mimes'            => 'Format foto harus jpg, jpeg, atau png.',
            'photo.max'              => 'Ukuran foto tidak boleh lebih dari 2 MB.',
        ]);

        $employee = auth()->user()->employee;

        $start = Carbon::parse($request->start_time);
        $end   = Carbon::parse($request->end_time);

        $totalMinutes = $start->diffInMinutes($end);

        $photoPath = $request->file('photo')->store('overtime_photos', 'public');

        OvertimeRequest::create([
            'employee_id'   => $employee->id,
            'overtime_date' => $request->overtime_date,
            'start_time'    => $request->start_time,
            'end_time'      => $request->end_time,
            'total_minutes' => $totalMinutes,
            'photo'         => $photoPath,
            'status'        => 'pending',
        ]);

        return redirect()->back()
            ->with('success', 'Pengajuan lembur berhasil dikirim.');
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $overtime = OvertimeRequest::findOrFail($id);

        if ($overtime->status !== 'pending') {
            abort(403, 'Tidak bisa edit data yang sudah diproses.');
        }

        return view('overtime.edit', compact('overtime'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $overtime = OvertimeRequest::findOrFail($id);

        if ($overtime->status !== 'pending') {
            abort(403);
        }

        $request->validate([
            'overtime_date' => 'required|date',
            'start_time'    => 'required',
            'end_time'      => 'required|after:start_time',
            'photo'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $start = Carbon::parse($request->start_time);
        $end   = Carbon::parse($request->end_time);

        $totalMinutes = $start->diffInMinutes($end);

        if ($request->hasFile('photo')) {
            Storage::disk('public')->delete($overtime->photo);
            $photoPath = $request->file('photo')->store('overtime_photos', 'public');
            $overtime->photo = $photoPath;
        }

        $overtime->update([
            'overtime_date' => $request->overtime_date,
            'start_time'    => $request->start_time,
            'end_time'      => $request->end_time,
            'total_minutes' => $totalMinutes,
        ]);

        return redirect()->route('overtime.index')
            ->with('success', 'Data lembur berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $overtime = OvertimeRequest::findOrFail($id);

        // kalau mau tetap bisa hapus, jangan batasi status
        Storage::disk('public')->delete($overtime->photo);
        $overtime->delete();

        return back()->with('success', 'Data berhasil dihapus.');
    }


    public function approve($id)
    {
        $overtime = OvertimeRequest::findOrFail($id);

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
        $overtime = OvertimeRequest::findOrFail($id);

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
