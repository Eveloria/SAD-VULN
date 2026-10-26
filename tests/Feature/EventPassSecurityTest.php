<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class EventPassSecurityTest extends TestCase
{
    use RefreshDatabase;

    private function event(): Event
    {
        return Event::create([
            'name' => 'Security Test Event',
            'venue' => 'Local Test Hall',
            'starts_at' => now()->addDay(),
            'price' => 10000,
            'capacity' => 100,
            'is_active' => true,
        ]);
    }

    public function test_unknown_or_sql_like_booking_input_does_not_validate_a_real_ticket(): void
    {
        $event = $this->event();
        Ticket::create([
            'event_id' => $event->id,
            'buyer_name' => 'Private Demo Buyer',
            'buyer_email' => 'buyer@example.test',
            'booking_code' => 'EVP-REAL123',
            'amount' => 10000,
            'status' => 'paid',
        ]);

        $this->post('/validate', ['booking_code' => "not-a-code' OR condition"])
            ->assertOk()
            ->assertSee('Kode tidak ditemukan')
            ->assertDontSee('EVP-REAL123');
    }

    public function test_exact_paid_booking_is_valid_and_can_be_checked_in_once(): void
    {
        $event = $this->event();
        $ticket = Ticket::create([
            'event_id' => $event->id,
            'buyer_name' => 'Demo Buyer',
            'buyer_email' => 'buyer@example.test',
            'booking_code' => 'EVP-' . Str::upper(Str::random(8)),
            'amount' => 10000,
            'status' => 'paid',
        ]);

        $this->post('/validate', ['booking_code' => $ticket->booking_code])
            ->assertOk()
            ->assertSee('Valid untuk masuk');

        $this->post(route('tickets.check-in', $ticket))->assertRedirect(route('validation.form'));
        $this->assertDatabaseHas('tickets', ['id' => $ticket->id, 'status' => 'used']);
        $this->post(route('tickets.check-in', $ticket))->assertSessionHas('error');
    }

    public function test_command_lab_never_runs_an_os_process_and_flags_shell_control_syntax(): void
    {
        app()->detectEnvironment(fn () => 'local');
        $this->post('/lab/command-simulation', ['command_input' => 'report.txt; suspicious text'])
            ->assertOk()
            ->assertSee('tidak ada proses OS yang dijalankan');
    }
}
