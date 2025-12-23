<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\ItemDocuments;
use App\Models\Items;
use App\Models\SubCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostWbbmController extends Controller
{
    public function createWbbm()
    {
        $categories = Categories::all();
        $subcategories = SubCategories::all();
        $items = Items::all();

        return view('wbbm.input_kategori', compact("categories", "subcategories", "items"));
    }

    /* ==========================
        1. FORM KATEGORI
       ========================== */
    public function storeCategories(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ], [
            'name.required' => 'Kategori belum di masukkan',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator, 'categories') // error bag
                ->withInput();
        }

        Categories::create([
            'name' => $request->name
        ]);

        // return redirect()->back();
        return redirect()->back()->with('success_categories', 'Kategori berhasil disimpan!');
    }

    /* ==========================
        2. FORM SUB KATEGORI
       ========================== */
    public function storeSubCategories(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'categories_id' => 'required',
        ], [
            'name.required' => 'Point Kategori belum di masukkan',
            'categories_id.required' => 'Kategori belum di Pilih',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator, 'subcategories') // error bag
                ->withInput();
        }

        SubCategories::create($request->all());
        return redirect()->back()->with('success_subcategories', 'Point Kategori berhasil disimpan!');
    }

    /* ==========================
        3. FORM ITEM
       ========================== */
    public function storeItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'sub_categories_id' => 'required',
            'required_document' => 'required|numeric',
        ], [
            'name.required' => 'Item Point Kategori belum di masukkan',
            'sub_categories_id.required' => 'Point Kategori belum di Pilih',
            'required_document.required' => 'Jumlah Dokumen Wajib belum di Masukkan',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator, 'items') // error bag
                ->withInput();
        }

        Items::create($request->all());
        return redirect()->back()->with('success_items', 'Item Point Kategori berhasil disimpan!');
    }

    /* ==========================
        4. FORM ITEM DOCUMENTS
       ========================== */
    public function storeItemDocument(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document_name' => 'required',
            'item_id' => 'required',
        ], [
            'document_name.required' => 'Nama dokumen belum di masukkan',
            'item_id.required' => 'Item Point Kategori belum di Pilih',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator, 'itemdocuments') // error bag
                ->withInput();
        }

        ItemDocuments::create($request->all());
        return redirect()->back()->with('success_ItemDocument', 'Nama Dokumen berhasil disimpan!');
    }
}
