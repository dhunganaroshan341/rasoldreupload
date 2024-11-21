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
        Schema::table('incomes', function (Blueprint $table) {
            // Add the invoice_id column to the incomes table
            $table->unsignedBigInteger('invoice_id')->nullable();

            // Add the foreign key constraint for invoice_id
            $table->foreign('invoice_id')
                ->references('id') // references the id column in the outstanding_invoices table
                ->on('out_standing_invoices') // the table that contains the outstanding invoices
                ->onDelete('cascade'); // Optional: If an outstanding invoice is deleted, the related income will be deleted
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incomes', function (Blueprint $table) {
            // Remove the foreign key constraint
            $table->dropForeign(['invoice_id']);

            // Drop the invoice_id column
            $table->dropColumn('invoice_id');
        });
    }
};
