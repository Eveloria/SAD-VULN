<?php

namespace App\Http\Controllers;

use App\Models\GatewayCheck;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class GatewayController extends Controller
{
    public function index(): View
    {
        // Provider status is intentionally simulated; no payment provider API is called.
        $providerStatus = strtolower((string) config('eventpass.gateway_status', 'online'));
        if (! in_array($providerStatus, ['online', 'degraded', 'offline'], true)) {
            $providerStatus = 'unknown';
        }

        $databaseStatus = 'online';
        $details = 'Gateway payment menggunakan status simulasi dari EVENTPASS_GATEWAY_STATUS.';
        try {
            DB::select('SELECT 1');
        } catch (Throwable $exception) {
            $databaseStatus = 'offline';
            $details = 'Koneksi database gagal: ' . class_basename($exception);
        }

        GatewayCheck::create([
            'provider_status' => $providerStatus,
            'database_status' => $databaseStatus,
            'details' => $details,
            'checked_at' => now(),
        ]);

        return view('gateway.index', [
            'providerStatus' => $providerStatus,
            'databaseStatus' => $databaseStatus,
            'checks' => GatewayCheck::latest('checked_at')->limit(10)->get(),
        ]);
    }
}
