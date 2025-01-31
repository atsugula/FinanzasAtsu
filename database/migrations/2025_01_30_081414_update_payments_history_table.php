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
        Schema::table('payments_history', function (Blueprint $table) {
            // Eliminar la clave foránea actual de expense_id
            $table->dropForeign(['expense_id']);
            // Eliminar la columna expense_id
            $table->dropColumn('expense_id');

            // Agregar la nueva columna transaction_id
            $table->unsignedBigInteger('transaction_id')->after('status')->nullable();

            // Definir la nueva clave foránea
            $table->foreign('transaction_id')->references('id')->on('transactions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments_history', function (Blueprint $table) {
            // Eliminar la clave foránea de transaction_id
            $table->dropForeign(['transaction_id']);
            // Eliminar la columna transaction_id
            $table->dropColumn('transaction_id');

            // Restaurar la columna expense_id
            $table->unsignedBigInteger('expense_id')->after('status');
            $table->foreign('expense_id')->references('id')->on('expenses');
        });
    }
};
