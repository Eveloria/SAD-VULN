<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gateway_checks', function (Blueprint $table) {
            $table->id();
            $table->string('provider_status', 24);
            $table->string('database_status', 24);
            $table->text('details')->nullable();
            $table->timestamp('checked_at')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gateway_checks');
    }
};
