
@extends('layouts.app')

@section('title', 'Login SQL Injection Lab')

@section('content')
<section class="page-heading">
    <div>
        <p class="eyebrow">LOCAL-ONLY TRAINING</p>
        <h1>Login SQL Injection Lab</h1>
        <p class="muted">
            Bandingkan konsep query rentan dan pendekatan aman.
            Halaman ini tidak melakukan autentikasi atau menjalankan query rentan.
        </p>
    </div>
    <span class="status status-warning">LOCAL ONLY</span>
</section>

<div class="panel lab-card">
    <form method="POST" action="{{ route('lab.login.simulate') }}">
        @csrf

        <label for="username">Username / email dummy</label>
        <input
            id="username"
            name="username"
            maxlength="100"
            value="{{ old('username') }}"
            required
        >

        <label for="password">Password dummy</label>
        <input
            id="password"
            name="password"
            type="password"
            maxlength="100"
            required
        >

        <label for="mode">Mode simulasi</label>
        <select id="mode" name="mode" required>
            <option value="vulnerable">Konsep query rentan (simulasi)</option>
            <option value="safe">Pendekatan aman</option>
        </select>

        <button class="button secondary full-width" type="submit">
            Jalankan simulasi
        </button>
    </form>

    @if ($errors->any())
        <div class="alert warning">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($result)
        <div class="lab-result result-safe">
            <strong>Hasil simulasi</strong>
            <p>{{ $result['query'] }}</p>
            <p>{{ $result['message'] }}</p>
        </div>
    @endif
</div>

<div class="info-note wide-note">
    <strong>Catatan:</strong>
    simulasi ini tidak membuat sesi login, tidak membaca akun pengguna,
    dan tidak membypass autentikasi. Mode aman pada aplikasi nyata perlu
    menggunakan query terparameterisasi serta verifikasi password yang benar.
</div>
@endsection
