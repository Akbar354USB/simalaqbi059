<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Employee;
use App\Models\GuestBook;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
// use Barryvdh\DomPDF\Facade\Pdf;

class GuestBookController extends Controller
{
    public function index(Request $request)
    {
        // Mulai query
        // $query = GuestBook::with('employees');
        // Mulai query + eager loading
        $query = GuestBook::with(['employees', 'agency']);

        // 🔹 Filter berdasarkan tanggal tertentu
        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        // 🔹 Filter berdasarkan bulan
        if ($request->month) {
            [$year, $month] = explode('-', $request->month);

            $query->whereYear('created_at', $year)
                ->whereMonth('created_at', $month);
        }

        // 🔹 Filter berdasarkan range tanggal
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        // Ambil data
        // $guestBooks = $query->latest()->get();
        $guestBooks = $query->latest()->paginate(10)->appends($request->all());

        return view('admin.guest_book.index', compact('guestBooks'));
    }

    // ============================
    // FORM INPUT TAMU
    // ============================
    public function create()
    {
        // $employees = Employee::all();
        $employees = Employee::where('status', 'PNS')->get();
        $agency = Agency::all();
        return view('admin.guest_book.create', compact('employees', 'agency'));
    }

    // ============================
    // SIMPAN DATA TAMU
    // ============================
    public function store(Request $request)
    {
        $request->validate([
            'guest_name'    => 'required',
            'number_phone'  => 'required',
            'agency_id'        => 'required',
            'objective'     => 'required',
            'employee_ids'  => 'required|array',
        ]);

        $guest = GuestBook::create([
            'guest_name'   => $request->guest_name,
            'number_phone' => $request->number_phone,
            'objective'    => $request->objective,
            'arrival_time'    => $request->arrival_time,
            'agency_id'    => $request->agency_id,
        ]);

        // Simpan relasi pegawai
        $guest->employees()->sync($request->employee_ids);

        return redirect()->route('guest_book_index')
            ->with('success', 'Data tamu berhasil disimpan.');
    }

    public function printPdf(Request $request)
    {
        $guestBooks = GuestBook::with(['employees', 'agency'])->latest();

        \Carbon\Carbon::setLocale('id'); // ⬅ penting
        // ==============================
        // KETERANGAN WAKTU UNTUK VIEW
        // ==============================
        $filterText = "Semua Data"; // default

        // FILTER TANGGAL
        if ($request->date) {
            $guestBooks->whereDate('created_at', $request->date);

            $filterText = \Carbon\Carbon::parse($request->date)
                ->translatedFormat('d F Y');
        }

        // FILTER BULAN
        if ($request->month) {
            $tahun = substr($request->month, 0, 4);
            $bulan = substr($request->month, 5, 2);

            $guestBooks->whereYear('created_at', $tahun)
                ->whereMonth('created_at', $bulan);

            $filterText = \Carbon\Carbon::parse($request->month . "-01")
                ->translatedFormat('F Y');
        }

        // RANGE
        if ($request->start_date && $request->end_date) {
            $guestBooks->whereBetween('created_at', [$request->start_date, $request->end_date]);

            $filterText =
                \Carbon\Carbon::parse($request->start_date)->translatedFormat('d F Y') .
                " s/d " .
                \Carbon\Carbon::parse($request->end_date)->translatedFormat('d F Y');
        }


        // AMBIL DATA
        $data = $guestBooks->get();


        // ==============================
        // NAMA FILE BERDASARKAN FILTER
        // ==============================
        if ($request->date) {
            $fileName = 'buku_tamu_' . $request->date . '.pdf';
        } elseif ($request->month) {
            $fileName = 'buku_tamu_' . str_replace('-', '_', $request->month) . '.pdf';
        } elseif ($request->start_date && $request->end_date) {
            $fileName = 'buku_tamu_' . $request->start_date . '_sd_' . $request->end_date . '.pdf';
        } else {
            $fileName = 'buku_tamu_semua_data.pdf';
        }

        // GENERATE PDF
        $pdf = Pdf::loadView('admin.guest_book.pdf', compact('data', 'filterText'))
            ->setPaper('A4', 'landscape');

        return $pdf->stream($fileName);
    }




    // ============================
    // FORM EDIT TAMU
    // ============================
    public function edit($id)
    {
        $guest = GuestBook::findOrFail($id);
        $employees = Employee::all();
        $agency = Agency::all();

        return view('admin.guest_book.edit', compact('guest', 'employees', 'agency'));
    }

    // ============================
    // UPDATE DATA TAMU
    // ============================
    public function update(Request $request, $id)
    {
        $request->validate([
            'guest_name'    => 'required',
            'number_phone'  => 'required',
            'agency_id'        => 'required',
            'objective'     => 'required',
            'employee_ids'  => 'required|array',
        ]);

        $guest = GuestBook::findOrFail($id);

        $guest->update([
            'guest_name'   => $request->guest_name,
            'number_phone' => $request->number_phone,
            'agency_id'       => $request->agency_id,
            'objective'    => $request->objective,
        ]);

        // Update relasi pegawai
        $guest->employees()->sync($request->employee_ids);

        return redirect()->route('guest_book_index')
            ->with('success', 'Data tamu berhasil diperbarui.');
    }

    // ============================
    // HAPUS DATA TAMU
    // ============================
    public function destroy($id)
    {
        $guest = GuestBook::findOrFail($id);
        $guest->delete();

        return redirect()->route('guest_book_index')
            ->with('success', 'Data tamu berhasil dihapus.');
    }
}
