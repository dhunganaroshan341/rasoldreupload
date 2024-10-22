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
            //
            // Add client_service_id column
            $table->unsignedBigInteger('client_service_id')->nullable(); // Adjust after() as needed

            // Add foreign key constraint
            $table->foreign('client_service_id')
                ->references('id')->on('client_services')
                ->onDelete('cascade'); // Adjust onDelete behavior as needed (e.g., 'set null')
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ledgers', function (Blueprint $table) {
            //
            // Drop the foreign key first, then the column
            $table->dropForeign(['client_service_id']);
            $table->dropColumn('client_service_id');
        });
    }
};
