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
        Schema::create('imported_transactions', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 20, 2); // Adjust precision and scale as per your requirements
            $table->string('source'); // You can adjust the data type according to your needs
            $table->date('date');
            $table->string('type');
            $table->string('file_name')->unique();
            $table->string('description')->nullable(); // Enum column to store income or expense
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imported_transactions');
    }
};
