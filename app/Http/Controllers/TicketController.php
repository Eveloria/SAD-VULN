<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class TicketController extends Controller
{
    public function index(Request $request): View
    {
        $query = Ticket::with('event')->latest();
        if ($request->filled('q')) {
            $term = trim((string) $request->input('q'));
            $query->where(function ($builder) use ($term) {
                $builder->where('booking_code', 'like', '%' . addcslashes($term, '%_\\') . '%')
                    ->orWhere('buyer_name', 'ilike', '%' . addcslashes($term, '%_\\') . '%')
                    ->orWhere('buyer_email', 'ilike', '%' . addcslashes($term, '%_\\') . '%');
            });
        }

        return view('tickets.index', ['tickets' => $query->paginate(12)->withQueryString()]);
    }

    public function create(): View
    {
        return view('tickets.create', ['events' => Event::where('is_active', true)->orderBy('starts_at')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'event_id' => ['required', 'integer', 'exists:events,id'],
            'buyer_name' => ['required', 'string', 'max:120'],
            'buyer_email' => ['required', 'email:rfc', 'max:190'],
        ]);

        $event = Event::where('is_active', true)->findOrFail($data['event_id']);
        $code = $this->newBookingCode();
        $ticket = Ticket::create([
            'event_id' => $event->id,
            'buyer_name' => $data['buyer_name'],
            'buyer_email' => $data['buyer_email'],
            'booking_code' => $code,
            'amount' => $event->price,
            'status' => 'pending',
        ]);

        return redirect()->route('tickets.show', $ticket)->with('success', 'Pembelian dicatat. Tiket menunggu pembayaran demo.');
    }

    public function show(Ticket $ticket): View
    {
        $ticket->load('event');
        return view('tickets.show', compact('ticket'));
    }

    public function markPaid(Ticket $ticket): RedirectResponse
    {
        $updated = DB::transaction(function () use ($ticket) {
            $locked = Ticket::whereKey($ticket->id)->lockForUpdate()->firstOrFail();
            if ($locked->status !== 'pending') {
                return false;
            }
            $locked->update([
                'status' => 'paid',
                'gateway_reference' => 'DEMO-' . Str::upper(Str::random(10)),
            ]);
            return true;
        });

        return redirect()->route('tickets.show', $ticket)->with(
            $updated ? 'success' : 'error',
            $updated ? 'Pembayaran demo berhasil ditandai lunas.' : 'Status tiket bukan menunggu pembayaran; tidak ada perubahan.'
        );
    }

    public function send(Ticket $ticket): RedirectResponse
    {
        if (! in_array($ticket->status, ['paid'], true)) {
            return back()->with('error', 'E-tiket hanya dapat dikirim setelah pembayaran berstatus lunas dan sebelum check-in.');
        }

        try {
            $ticket->load('event');
            $body = "E-TIKET EVENTPASS\n\n" .
                "Event: {$ticket->event->name}\n" .
                "Lokasi: {$ticket->event->venue}\n" .
                "Waktu: {$ticket->event->starts_at->format('d M Y H:i')}\n" .
                "Nama: {$ticket->buyer_name}\n" .
                "Kode booking: {$ticket->booking_code}\n" .
                "Status: {$ticket->status_label}\n\n" .
                "Simpan kode booking ini untuk proses check-in.\n";

            Mail::raw($body, function ($message) use ($ticket) {
                $message->to($ticket->buyer_email, $ticket->buyer_name)
                    ->subject('E-Tiket EventPass - ' . $ticket->event->name);
            });
            $ticket->update(['ticket_sent_at' => now()]);

            return back()->with('success', 'Permintaan pengiriman diproses oleh mailer Laravel. Pada konfigurasi lokal mailer=log, isi e-mail tersimpan di storage/logs/laravel.log, bukan dikirim ke internet.');
        } catch (Throwable $exception) {
            report($exception);
            return back()->with('error', 'Mailer gagal memproses e-tiket. Periksa konfigurasi mailer dan storage/logs/laravel.log.');
        }
    }

    private function newBookingCode(): string
    {
        do {
            $code = 'EVP-' . Str::upper(Str::random(8));
        } while (Ticket::where('booking_code', $code)->exists());

        return $code;
    }
}
