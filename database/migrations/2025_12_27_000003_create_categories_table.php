<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');
            $table->enum('type', ['income', 'expense']);
            $table->boolean('is_archived')->default(false);

            $table->timestamps();

            $table->index(['user_id', 'type', 'is_archived']);
            $table->unique(['user_id', 'type', 'name']); // mismo nombre permitido si cambia type
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
