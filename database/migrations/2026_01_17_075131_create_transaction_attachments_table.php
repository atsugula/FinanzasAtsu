<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaction_attachments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('transaction_id')->constrained('transactions')->cascadeOnDelete();

            // Ruta relativa en storage (public disk)
            $table->string('path');

            $table->timestamps();

            $table->index(['user_id', 'transaction_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_attachments');
    }
};
