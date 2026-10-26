@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<section class="page-heading">
    <div><p class="eyebrow">OVERVIEW</p><h1>Event operations</h1><p class="muted">Pantau penjualan, validasi tiket, dan kondisi layanan dari satu tempat.</p></div>
    <a class="button primary" href="{{ route('tickets.create') }}">＋ Catat pembelian</a>
</section>
<section class="stat-grid">
    <article class="stat-card"><span class="stat-label">Event aktif</span><strong>{{ number_format($eventCount) }}</strong><span class="stat-note">Event yang tersedia</span></article>
    <article class="stat-card"><span class="stat-label">Total tiket</span><strong>{{ number_format($ticketCount) }}</strong><span class="stat-note">Seluruh pencatatan pembelian</span></article>
    <article class="stat-card"><span class="stat-label">Tiket lunas</span><strong>{{ number_format($paidCount) }}</strong><span class="stat-note">Lunas dan sudah check-in</span></article>
    <article class="stat-card"><span class="stat-label">Check-in</span><strong>{{ number_format($checkInCount) }}</strong><span class="stat-note">Tiket yang sudah digunakan</span></article>
</section>
<section class="content-grid">
    <article class="panel wide-panel">
        <div class="panel-heading"><div><h2>Pembelian terbaru</h2><p class="muted">Aktivitas tiket terakhir yang tercatat.</p></div><a class="text-link" href="{{ route('tickets.index') }}">Semua tiket →</a></div>
        <div class="table-wrap"><table><thead><tr><th>Kode booking</th><th>Pembeli</th><th>Event</th><th>Status</th><th></th></tr></thead><tbody>
        @forelse ($latestTickets as $ticket)
            <tr><td><code>{{ $ticket->booking_code }}</code></td><td>{{ $ticket->buyer_name }}<small>{{ $ticket->buyer_email }}</small></td><td>{{ $ticket->event->name }}</td><td><span class="status status-{{ $ticket->status }}">{{ $ticket->status_label }}</span></td><td><a class="text-link" href="{{ route('tickets.show', $ticket) }}">Detail</a></td></tr>
        @empty <tr><td colspan="5" class="empty-state">Belum ada pembelian. Tambahkan tiket pertama untuk memulai.</td></tr> @endforelse
        </tbody></table></div>
    </article>
    <aside class="panel side-panel">
        <div class="panel-heading"><div><h2>Ringkasan pembayaran</h2><p class="muted">Data tiket berstatus lunas atau sudah check-in.</p></div></div>
        <p class="revenue">Rp {{ number_format($revenue, 0, ',', '.') }}</p><span class="muted">Total nilai tiket tercatat</span>
        <div class="quick-links"><a href="{{ route('validation.form') }}"><span class="quick-icon">✓</span><span><strong>Validasi booking</strong><small>Periksa tiket sebelum masuk</small></span><span>→</span></a><a href="{{ route('gateway.index') }}"><span class="quick-icon">↗</span><span><strong>Status gateway</strong><small>Lihat pemeriksaan terakhir</small></span><span>→</span></a></div>
        <div class="mini-status"><span class="dot {{ $latestGatewayCheck?->provider_status === 'online' ? 'dot-green' : 'dot-amber' }}"></span><span><strong>Gateway demo:</strong> {{ strtoupper($latestGatewayCheck?->provider_status ?? config('eventpass.gateway_status', 'online')) }}</span></div>
    </aside>
</section>
<section class="panel event-panel"><div class="panel-heading"><div><h2>Alur kerja</h2><p class="muted">Alur dasar untuk demonstrasi EventPass.</p></div></div><div class="workflow"><div><span>01</span><strong>Catat pembelian</strong><p>Buat booking dan simpan ke PostgreSQL.</p></div><div><span>02</span><strong>Tandai pembayaran</strong><p>Gunakan tombol pembayaran demo untuk simulasi.</p></div><div><span>03</span><strong>Kirim e-tiket</strong><p>Mailer log menyimpan isi e-mail ke log lokal.</p></div><div><span>04</span><strong>Check-in</strong><p>Validasi kode dan tandai tiket telah digunakan.</p></div></div></section>
@endsection
