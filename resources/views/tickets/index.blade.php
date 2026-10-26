@extends('layouts.app')
@section('title', 'Daftar tiket')
@section('content')
<section class="page-heading"><div><p class="eyebrow">TICKETING</p><h1>Daftar tiket</h1><p class="muted">Kelola pembelian dan status setiap kode booking.</p></div><a class="button primary" href="{{ route('tickets.create') }}">＋ Pembelian baru</a></section>
<section class="panel">
    <form class="search-form" method="GET" action="{{ route('tickets.index') }}"><input type="search" name="q" value="{{ request('q') }}" placeholder="Cari kode booking, nama, atau e-mail"><button class="button secondary" type="submit">Cari</button>@if(request('q'))<a class="text-link" href="{{ route('tickets.index') }}">Reset</a>@endif</form>
    <div class="table-wrap"><table><thead><tr><th>Kode booking</th><th>Nama / e-mail</th><th>Event</th><th>Nominal</th><th>Status</th><th>Dibuat</th><th></th></tr></thead><tbody>
    @forelse($tickets as $ticket)
        <tr><td><code>{{ $ticket->booking_code }}</code></td><td>{{ $ticket->buyer_name }}<small>{{ $ticket->buyer_email }}</small></td><td>{{ $ticket->event->name }}</td><td>Rp {{ number_format((float)$ticket->amount, 0, ',', '.') }}</td><td><span class="status status-{{ $ticket->status }}">{{ $ticket->status_label }}</span></td><td>{{ $ticket->created_at->format('d M Y H:i') }}</td><td><a class="text-link" href="{{ route('tickets.show', $ticket) }}">Detail →</a></td></tr>
    @empty <tr><td colspan="7" class="empty-state">Tidak ada tiket yang cocok.</td></tr> @endforelse
    </tbody></table></div>
    <div class="pagination">{{ $tickets->links() }}</div>
</section>
@endsection
