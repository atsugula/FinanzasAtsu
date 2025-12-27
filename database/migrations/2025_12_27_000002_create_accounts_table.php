<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');
            $table->decimal('initial_balance', 15, 2)->default(0);
            $table->boolean('is_archived')->default(false);

            $table->timestamps();

            $table->index(['user_id', 'is_archived']);
            $table->unique(['user_id', 'name']); // evita duplicados por usuario
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
