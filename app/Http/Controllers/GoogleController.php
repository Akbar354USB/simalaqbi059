<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\GoogleAccount;
use Illuminate\Http\Request;
use Google\Client as GoogleClient;
use Google\Service\Calendar;
use Google\Service\Oauth2;

class GoogleController extends Controller
{
    public function redirect(Employee $employee)
    {
        $client = new \Google\Client();
        $client->setClientId(config('google.client_id'));
        $client->setClientSecret(config('google.client_secret'));
        $client->setRedirectUri(config('google.redirect_uri'));
        $client->setScopes(config('google.scopes'));
        $client->setAccessType('offline');
        $client->setPrompt('consent');

        // 🔑 simpan employee_id
        $client->setState((string) $employee->id);

        return redirect()->away($client->createAuthUrl());
    }

    public function callback(Request $request)
    {
        $employeeId = $request->state; // 👈 dari Google

        if (!$employeeId) {
            abort(400, 'Employee ID tidak ditemukan');
        }

        $client = new \Google\Client();
        $client->setClientId(config('google.client_id'));
        $client->setClientSecret(config('google.client_secret'));
        $client->setRedirectUri(config('google.redirect_uri'));
        $client->setScopes(config('google.scopes'));
        $client->setAccessType('offline');
        $client->setPrompt('consent');

        $token = $client->fetchAccessTokenWithAuthCode($request->code);

        if (isset($token['error'])) {
            return dd($token);
        }

        $client->setAccessToken($token);

        $oauth2 = new \Google\Service\Oauth2($client);
        $userInfo = $oauth2->userinfo->get();

        GoogleAccount::updateOrCreate(
            ['employee_id' => $employeeId],
            [
                'google_email' => $userInfo->email,
                'access_token' => $token['access_token'],
                'refresh_token' => $token['refresh_token'],
                'token_expires_at' => now()->addSeconds($token['expires_in']),
            ]
        );

        return redirect('/tamu');
    }
}
