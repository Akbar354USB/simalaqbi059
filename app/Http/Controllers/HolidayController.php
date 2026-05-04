<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function create()
    {
        return view('holidays.create');
    }

    public function index()
    {
        $holidays = Holiday::latest()->get();
        return view('holidays.index', compact('holidays'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'start_date' => 'required|date',
        ]);

        Holiday::create([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ]);

        return back()->with('success', 'Data berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $holiday = Holiday::findOrFail($id);

        $holiday->update([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ]);

        return back()->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        Holiday::findOrFail($id)->delete();
        return back()->with('success', 'Data berhasil dihapus');
    }
}
