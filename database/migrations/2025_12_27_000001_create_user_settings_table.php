<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->unique()
                ->constrained()
                ->cascadeOnDelete();

            $table->char('currency', 3)->default('COP'); // ISO 4217
            $table->unsignedTinyInteger('month_start_day')->default(1); // 1..28 recomendado
            $table->timestamps();

            $table->index(['user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};
