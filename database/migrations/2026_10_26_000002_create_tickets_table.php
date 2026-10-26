<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('buyer_name');
            $table->string('buyer_email');
            $table->string('booking_code', 32)->unique();
            $table->decimal('amount', 12, 2);
            $table->string('status', 24)->default('pending')->index();
            $table->string('gateway_reference')->nullable();
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('ticket_sent_at')->nullable();
            $table->timestamps();
            $table->index(['event_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
