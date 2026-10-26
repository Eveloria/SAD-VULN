
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class LoginSqlLabController extends Controller
{
    public function index(): View
    {
        $this->ensureLocalLab();

        return view('lab.login', [
            'result' => null,
        ]);
    }

    public function simulate(Request $request): View
    {
        $this->ensureLocalLab();

        $data = $request->validate([
            'username' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string', 'max:100'],
            'mode' => ['required', 'in:vulnerable,safe'],
        ]);

        $result = [
            'mode' => $data['mode'],
            'query' => $data['mode'] === 'vulnerable'
                ? 'SIMULASI: query dirangkai dari input pengguna.'
                : 'Query aktual menggunakan parameter binding.',
            'message' => $data['mode'] === 'vulnerable'
                ? 'Mode edukasi: input diperiksa sebagai teks saja. Tidak ada query rentan atau login yang dijalankan.'
                : 'Mode aman: contoh ini tidak mengautentikasi pengguna. Gunakan parameter binding dan Hash::check() untuk login sebenarnya.',
        ];

        return view('lab.login', compact('result'));
    }

    private function ensureLocalLab(): void
    {
        abort_unless(
            app()->environment('local')
                && in_array(request()->ip(), ['127.0.0.1', '::1'], true),
            404
        );
    }
}
