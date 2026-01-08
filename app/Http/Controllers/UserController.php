<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('employee')->latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $employees = Employee::whereDoesntHave('user')->get();
        return view('users.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id|unique:users,employee_id',
            'password'    => 'required|min:6|confirmed',
            'role'        => 'required',
        ]);

        // dd($request);

        User::create([
            'employee_id' => $request->employee_id,
            'password'    => Hash::make($request->password),
            'role'        => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        $employees = Employee::all();
        return view('users.edit', compact('user', 'employees'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id|unique:users,employee_id,' . $user->id,
            'password'    => 'nullable|min:6|confirmed',
            'role'        => 'required|in:admin,pegawai',
        ]);

        $data = [
            'employee_id' => $request->employee_id,
            'role'        => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User berhasil dihapus');
    }
}
