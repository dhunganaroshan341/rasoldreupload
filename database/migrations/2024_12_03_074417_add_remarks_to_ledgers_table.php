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
        Schema::table('ledgers', function (Blueprint $table) {
            // Modify the remarks column to allow more characters
            $table->text('remarks')->nullable(); // Use text for longer remarks
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ledgers', function (Blueprint $table) {
            // If rolling back, we change remarks back to string (if needed)
            $table->string('remarks')->nullable(); // You can change this if necessary
        });
    }
};
