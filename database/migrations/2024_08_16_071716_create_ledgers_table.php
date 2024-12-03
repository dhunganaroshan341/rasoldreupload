<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('ledgers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->nullable(); // Reference to the client
            $table->enum('transaction_type', ['income', 'expense']); // Income or Expense
            $table->string('source'); // The source of income/expense
            $table->date('transaction_date'); // Date of the transaction
            $table->decimal('amount', 10, 2); // Amount of the transaction
            $table->string('medium'); // Payment medium (e.g., bank transfer, cash)
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ledgers');
    }
};
