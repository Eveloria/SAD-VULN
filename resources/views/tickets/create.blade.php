@extends('layouts.app')
@section('title', 'Catat pembelian')
@section('content')
<section class="page-heading"><div><p class="eyebrow">NEW BOOKING</p><h1>Catat pembelian tiket</h1><p class="muted">Data akan disimpan dengan status awal menunggu pembayaran.</p></div><a class="button secondary" href="{{ route('tickets.index') }}">← Kembali ke tiket</a></section>
<div class="form-layout"><section class="panel form-panel"><form method="POST" action="{{ route('tickets.store') }}">@csrf
    <label for="event_id">Pilih event</label><select name="event_id" id="event_id" required><option value="">Pilih event</option>@foreach($events as $event)<option value="{{ $event->id }}" @selected(old('event_id') == $event->id)>{{ $event->name }} — Rp {{ number_format((float)$event->price, 0, ',', '.') }} ({{ $event->starts_at->format('d M Y') }})</option>@endforeach</select>
    <label for="buyer_name">Nama pembeli</label><input id="buyer_name" name="buyer_name" value="{{ old('buyer_name') }}" maxlength="120" autocomplete="name" required>
    <label for="buyer_email">E-mail pembeli</label><input id="buyer_email" name="buyer_email" type="email" value="{{ old('buyer_email') }}" maxlength="190" autocomplete="email" required>
    <div class="info-note">Kode booking dibuat otomatis. Jumlah pembayaran mengikuti harga event di database dan tidak dapat diubah dari form ini.</div>
    <button class="button primary full-width" type="submit">Simpan pembelian</button>
</form></section><aside class="panel aside-note"><h2>Setelah booking dibuat</h2><ol class="steps"><li>Status awal menjadi <strong>Menunggu pembayaran</strong>.</li><li>Gunakan <strong>Simulasikan pembayaran</strong> pada halaman detail.</li><li>Setelah lunas, e-tiket bisa diproses dan kode booking dapat divalidasi.</li></ol><p class="muted small">Gateway pembayaran pada starter ini hanya simulasi. Tidak ada transaksi uang sungguhan.</p></aside></div>
@endsection
