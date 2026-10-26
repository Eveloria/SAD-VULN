@extends('layouts.app')
@section('title', 'Validasi booking')
@section('content')
<section class="page-heading"><div><p class="eyebrow">ADMISSION CONTROL</p><h1>Validasi kode booking</h1><p class="muted">Periksa status pembayaran sebelum mengizinkan pemegang tiket masuk.</p></div></section>
<section class="panel validation-panel"><form method="POST" action="{{ route('validation.submit') }}" class="validation-form">@csrf<label for="booking_code">Kode booking</label><div class="input-action"><input id="booking_code" name="booking_code" value="{{ old('booking_code', $input ?? '') }}" placeholder="Contoh: EVP-DEMO2026" maxlength="32" required autocomplete="off"><button class="button primary" type="submit">Validasi tiket</button></div></form><p class="muted small">Pencarian memakai kecocokan kode yang persis dan parameter binding. Masukkan kode booking yang tercatat.</p></section>
@if(isset($result))
    <section class="panel result-panel">
        @php($resultMeta = ['valid' => ['Valid untuk masuk', 'success'], 'used' => ['Sudah digunakan', 'error'], 'unpaid' => ['Belum lunas', 'warning'], 'cancelled' => ['Dibatalkan', 'error'], 'not_found' => ['Kode tidak ditemukan', 'error']])
        <div class="result-heading"><div><p class="eyebrow">HASIL PEMERIKSAAN</p><h2>{{ $resultMeta[$result][0] }}</h2></div><span class="status status-{{ $resultMeta[$result][1] }}">{{ strtoupper($result) }}</span></div>
        @if(!empty($suspiciousInput))<div class="alert warning">Input memiliki pola yang menyerupai manipulasi sintaks SQL. Input tetap diperlakukan sebagai nilai biasa; tidak ada bypass yang dijalankan.</div>@endif
        @if($ticket)<div class="detail-list compact"><div><span>Kode booking</span><strong>{{ $ticket->booking_code }}</strong></div><div><span>Event</span><strong>{{ $ticket->event->name }}</strong></div><div><span>Pembeli</span><strong>{{ $ticket->buyer_name }}</strong></div><div><span>Status tiket</span><strong>{{ $ticket->status_label }}</strong></div></div>@endif
        @if($result === 'valid' && $ticket)<form method="POST" action="{{ route('tickets.check-in', $ticket) }}" class="checkin-form">@csrf<button class="button primary" type="submit">Konfirmasi check-in</button><span class="muted small">Aksi ini mengubah status menjadi sudah digunakan dan mencegah check-in ulang.</span></form>@endif
    </section>
@endif
<section class="info-note wide-note"><strong>Aturan validasi:</strong> hanya tiket berstatus lunas yang belum digunakan dapat check-in. Tiket menunggu pembayaran, dibatalkan, atau sudah digunakan akan ditolak.</section>
@endsection
