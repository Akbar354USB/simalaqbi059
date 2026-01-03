<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use Illuminate\Http\Request;

class AgencyController extends Controller
{
    // 1. Tampilkan data
    // public function index()
    // {
    //     $agencies = Agency::all();
    //     return view('agencies.index', compact('agencies'));
    // }
    public function index(Request $request)
    {
        $search = $request->search;

        $agencies = Agency::when($search, function ($query, $search) {
            $query->where('agency_code', 'like', "%{$search}%")
                ->orWhere('agency_name', 'like', "%{$search}%");
        })->get();

        return view('agencies.index', compact('agencies', 'search'));
    }


    // 2. Form tambah data
    public function create()
    {
        return view('agencies.create');
    }

    // 3. Simpan data
    public function store(Request $request)
    {
        $request->validate([
            'agency_code' => 'required|unique:agencies',
            'agency_name' => 'required',
        ]);

        Agency::create($request->all());

        return redirect()->route('agencies.index')
            ->with('success', 'Data instansi berhasil ditambahkan');
    }

    // 4. Form edit
    public function edit($id)
    {
        $agency = Agency::findOrFail($id);
        return view('agencies.edit', compact('agency'));
    }

    // 5. Update data
    public function update(Request $request, $id)
    {
        $agency = Agency::findOrFail($id);

        $request->validate([
            'agency_code' => 'required|unique:agencies,agency_code,' . $agency->id,
            'agency_name' => 'required',
        ]);

        $agency->update($request->all());

        return redirect()->route('agencies.index')
            ->with('success', 'Data instansi berhasil diupdate');
    }

    // 6. Hapus data
    public function destroy($id)
    {
        Agency::findOrFail($id)->delete();

        return redirect()->route('agencies.index')
            ->with('success', 'Data instansi berhasil dihapus');
    }
}
