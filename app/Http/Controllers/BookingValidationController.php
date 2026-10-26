<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BookingValidationController extends Controller
{
    public function form(): View
    {
        return view('validation.index');
    }

    public function validateBooking(Request $request): View
    {
        $data = $request->validate([
            'booking_code' => ['required', 'string', 'max:32'],
        ]);

        // Safe exact-value lookup: the submitted value is bound as a parameter by Eloquent.
        $input = trim($data['booking_code']);
        $ticket = Ticket::with('event')->where('booking_code', $input)->first();
        $result = match (true) {
            ! $ticket => 'not_found',
            $ticket->status === 'cancelled' => 'cancelled',
            $ticket->status === 'pending' => 'unpaid',
            $ticket->status === 'used' || $ticket->checked_in_at !== null => 'used',
            $ticket->status === 'paid' => 'valid',
            default => 'not_found',
        };

        $suspiciousInput = preg_match('/[\'\"]\s*(or|and)\b|--|\/\*|\bunion\b/i', $input) === 1;

        return view('validation.index', compact('ticket', 'result', 'input', 'suspiciousInput'));
    }

    public function checkIn(Ticket $ticket): RedirectResponse
    {
        $outcome = DB::transaction(function () use ($ticket) {
            $locked = Ticket::whereKey($ticket->id)->lockForUpdate()->firstOrFail();
            if ($locked->status !== 'paid' || $locked->checked_in_at !== null) {
                return false;
            }

            $locked->update(['status' => 'used', 'checked_in_at' => now()]);
            return true;
        });

        return redirect()->route('validation.form')->with(
            $outcome ? 'success' : 'error',
            $outcome ? 'Check-in berhasil. Tiket sekarang berstatus sudah digunakan.' : 'Check-in ditolak: tiket belum lunas atau sudah digunakan.'
        );
    }
}
