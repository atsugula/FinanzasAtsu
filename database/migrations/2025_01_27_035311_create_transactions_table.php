<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->char('type')->default('I'); // I: Ingreso, E: Egreso, A: Ahorro
            $table->double('amount'); 
            $table->date('date')->nullable();
            $table->text('description')->nullable();
            
            $table->string('source')->nullable();

            // Campos específicos para gastos
            $table->unsignedBigInteger('category')->nullable();
            $table->foreign('category')->references('id')->on('expenses_categories');

            // Campos específicos para ahorros
            $table->unsignedBigInteger('goal_id')->nullable();
            $table->foreign('goal_id')->references('id')->on('goals');
            $table->text('goal')->nullable();

            $table->unsignedBigInteger('status_id')->nullable();
            $table->foreign('status_id')->references('id')->on('statuses');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
