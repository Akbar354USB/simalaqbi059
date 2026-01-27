<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use Illuminate\Http\Request;

class AgencyController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $agencies = Agency::when($search, function ($query, $search) {
            $query->where('agency_name', 'like', "%{$search}%");
        })
            ->paginate(10) // jumlah data per halaman
            ->withQueryString(); // agar parameter search tidak hilang

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
            'agency_name' => 'required|string|max:255',
        ]);

        $agency = Agency::create([
            'agency_name' => $request->agency_name
        ]);

        // Jika request dari AJAX (Select2)
        if ($request->ajax()) {
            return response()->json([
                'id' => $agency->id,
                'agency_name' => $agency->agency_name,
            ]);
        }

        // Jika dari form biasa
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
