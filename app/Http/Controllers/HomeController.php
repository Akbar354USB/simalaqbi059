<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\GoogleAccount;
use App\Models\GuestBook;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $totalPegawai = Employee::count();
        $totalTamu = GuestBook::count();
        $googleaccount = GoogleAccount::count();
        $overallProgress = Categories::overallProgress();
        $categories = Categories::with([
            'sub_categories.items.item_documents.upload' // perbaiki uploads → upload
        ])->get();
        return view('dashboard',  compact('totalPegawai', 'totalTamu', 'overallProgress', 'googleaccount', 'categories'));
    }
}
