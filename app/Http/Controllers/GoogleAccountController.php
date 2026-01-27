<?php

namespace App\Http\Controllers;

use App\Models\GoogleAccount;
use App\Models\Employee;
use Illuminate\Http\Request;

class GoogleAccountController extends Controller
{
    public function index()
    {
        $googleAccounts = GoogleAccount::with('employee')->latest()->paginate(10);
        return view('google_accounts.index', compact('googleAccounts'));
    }

    public function destroy(GoogleAccount $googleAccount)
    {
        $googleAccount->delete();

        return redirect()->route('google-accounts.index')
            ->with('success', 'Akun Google berhasil dihapus');
    }
}
