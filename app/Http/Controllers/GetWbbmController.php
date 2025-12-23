<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\ItemDocuments;
use App\Models\Items;
use App\Models\SubCategories;
use App\Models\Uploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GetWbbmController extends Controller
{
    // public function monitorWbbm()
    // {
    //     return view('wbbm.monitoring');
    // }

    public function monitorWbbm()
    {
        $categories = Categories::with([
            'sub_categories.items.item_documents.upload' // perbaiki uploads → upload
        ])->get();

        $dokumen = ItemDocuments::with('upload')->get(); // perbaiki uploads → upload

        return view('wbbm.monitoring', compact('categories', 'dokumen'));
    }

    public function dataCapaian()
    {
        $categories = Categories::with([
            'sub_categories.items.item_documents.upload'
        ])->get();

        return view('wbbm.data_capaian', compact('categories'));
    }


    public function storeDok(Request $request)
    {
        $request->validate([
            'item_documents_id' => 'required|exists:item_documents,id',
            'file' => 'required|file|max:2048'
        ]);

        // Simpan file ke storage/app/public/uploads
        $path = $request->file('file')->store('uploads', 'public');

        // Update atau buat data baru
        $upload = Uploads::updateOrCreate(
            ['item_documents_id' => $request->item_documents_id], // kondisi
            [
                'file_path' => $path,
                'status' => 'uploaded',
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Upload berhasil',
            'file_url' => asset('storage/' . $path),
            'item_documents_id' => $request->item_documents_id
        ]);
    }

    public function destroyCategory($id)
    {
        $kategori = Categories::where("id", $id)->first();
        $kategori->delete();

        return redirect()->route("wbbm-data")->with('success', 'Data Kategori Rencana Kerja Berhasil diHapus!');
    }

    public function destroySubCategory($id)
    {
        $subkategori = SubCategories::where("id", $id)->first();
        $subkategori->delete();

        return redirect()->route("wbbm-data")->with('success', 'Data Point Kategori Rencana Kerja Berhasil diHapus!');
    }

    public function destroyItem($id)
    {
        $item = Items::where("id", $id)->first();
        $item->delete();

        return redirect()->route("wbbm-data")->with('success', 'Data Item Point Rencana Kerja Berhasil diHapus!');
    }

    public function destroyDocument($id)
    {
        $upload = Uploads::findOrFail($id);

        // Hapus file fisik dari storage/app/public
        if (Storage::disk('public')->exists($upload->file_path)) {
            Storage::disk('public')->delete($upload->file_path);
        }

        // Hapus data dari database
        $upload->delete();

        return redirect()
            ->route("wbbm-monitor")
            ->with('success', 'Dokumen Bukti Dukung Berhasil dihapus!');
    }


    public function tesProgress()
    {
        $categories = Categories::with([
            'sub_categories.items.item_documents.upload' // perbaiki uploads → upload
        ])->get();

        return view('wbbm.tes_progres', compact('categories'));
    }
}
