<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EnsureGoogleConnected
{
    public function handle($request, Closure $next)
    {
        $employee = auth()->user()->employee;
        $googleAccount = $employee?->googleAccount;

        if (!$googleAccount || !$googleAccount->refresh_token) {
            return redirect()->route('google.connect', $employee->id);
        }

        return $next($request);
    }
}
