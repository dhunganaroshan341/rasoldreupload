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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expense_source');
            $table->string('source_type')->nullable();
            $table->unsignedBigInteger('client_service_id')->nullable(); // Adjust 'some_existing_column' to where you want to add the new column
            $table->string('medium')->default('cash');
            // Add foreign key constraint
            $table->foreign('client_service_id')
                ->references('id')
                ->on('client_services')
                ->onDelete('set null'); // This will set the `client_service_id` to null if the referenced `client_services` record is deleted
            $table->date('transaction_date');
            $table->decimal('amount', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
