<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'name' => 'Nusantara Tech Expo',
                'venue' => 'Jakarta Convention Center',
                'starts_at' => now()->addDays(21)->setTime(9, 0),
                'price' => 75000,
                'capacity' => 1500,
                'is_active' => true,
            ],
            [
                'name' => 'Creative Market & Design Fair',
                'venue' => 'Indonesia Design District, PIK 2',
                'starts_at' => now()->addDays(35)->setTime(10, 0),
                'price' => 50000,
                'capacity' => 800,
                'is_active' => true,
            ],
            [
                'name' => 'Startup & Innovation Summit',
                'venue' => 'The Kasablanka Hall, Jakarta',
                'starts_at' => now()->addDays(50)->setTime(8, 30),
                'price' => 125000,
                'capacity' => 600,
                'is_active' => true,
            ],
        ];

        foreach ($events as $attributes) {
            $event = Event::firstOrCreate(['name' => $attributes['name']], $attributes);
            if (! $event->tickets()->exists()) {
                $paid = Ticket::create([
                    'event_id' => $event->id,
                    'buyer_name' => 'Demo Buyer',
                    'buyer_email' => 'demo@example.test',
                    'booking_code' => 'EVP-' . Str::upper(Str::random(8)),
                    'amount' => $event->price,
                    'status' => 'paid',
                    'gateway_reference' => 'DEMO-' . Str::upper(Str::random(8)),
                ]);
                // The first seeded paid ticket is intentionally usable for a local walkthrough.
                if ($event->name === 'Nusantara Tech Expo') {
                    $paid->update(['booking_code' => 'EVP-DEMO2026']);
                }
            }
        }
    }
}
