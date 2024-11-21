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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ID of the user who performed the action
            $table->string('model'); // The model that was affected
            $table->unsignedBigInteger('model_id'); // The ID of the affected model
            $table->string('action'); // The action performed (created, updated, deleted)
            $table->timestamps(); // Automatically create created_at and updated_at columns
        });

        // Add foreign key constraint
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Set cascade or set null based on your need
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // Drop the foreign key if rolling back
        });

        Schema::dropIfExists('activity_logs');
    }
};
