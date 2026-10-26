<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SecurityLabController extends Controller
{
    public function index(): View
    {
        $this->ensureLocalLab();
        return view('lab.index');
    }

    public function sqlSimulation(Request $request): View|RedirectResponse
    {
        $this->ensureLocalLab();
        $data = $request->validate(['booking_code' => ['required', 'string', 'max:200']]);
        $input = $data['booking_code'];
        $suspicious = preg_match('/[\'\"]\s*(or|and)\b|--|\/\*|\bunion\b/i', $input) === 1;

        // Even in this lab, we do not execute user input as SQL. This exact-match query is safe.
        $exactMatch = Ticket::where('booking_code', $input)->exists();
        $message = $suspicious
            ? 'Pola input tampak seperti percobaan manipulasi sintaks SQL. Ditandai oleh simulator; query tidak diubah dan tidak ada tiket yang dibypass.'
            : ($exactMatch
                ? 'Input cocok dengan kode booking yang ada. Pemeriksaan tetap menggunakan parameter binding.'
                : 'Tidak ada kode booking yang cocok persis. Tidak ada SQL dinamis yang dijalankan.');

        return view('lab.index', compact('input', 'suspicious', 'exactMatch', 'message'));
    }

    public function commandSimulation(Request $request): View|RedirectResponse
    {
        $this->ensureLocalLab();
        $data = $request->validate(['command_input' => ['required', 'string', 'max:200']]);
        $input = $data['command_input'];
        $suspicious = preg_match('/[;&|`$<>]|\$\(|\b(powershell|cmd\.exe|sh|bash|curl|wget)\b/i', $input) === 1;
        $message = $suspicious
            ? 'Karakter/pola kontrol shell terdeteksi. Simulasi memblokir input; tidak ada proses OS yang dijalankan.'
            : 'Tidak ditemukan pola kontrol shell sederhana. Input tetap diperlakukan sebagai teks; tidak ada proses OS yang dijalankan.';

        return view('lab.index', compact('input', 'suspicious', 'message'));
    }

    private function ensureLocalLab(): void
    {
        abort_unless(
            app()->environment('local') && in_array(request()->ip(), ['127.0.0.1', '::1'], true),
            404
        );
    }
}
