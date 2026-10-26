<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 'buyer_name', 'buyer_email', 'booking_code', 'amount',
        'status', 'gateway_reference', 'checked_in_at', 'ticket_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'checked_in_at' => 'datetime',
            'ticket_sent_at' => 'datetime',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu pembayaran',
            'paid' => 'Lunas',
            'used' => 'Sudah check-in',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }
}
