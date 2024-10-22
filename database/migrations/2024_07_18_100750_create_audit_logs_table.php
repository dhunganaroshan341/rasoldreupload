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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action'); // Action performed (e.g., created, updated, deleted)
            $table->string('model'); // Model name (e.g., Transaction, User, etc.)
            $table->unsignedBigInteger('model_id'); // ID of the model instance affected
            $table->json('changes')->nullable(); // Optional: store changes made (if applicable)
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

        });
    }

    public function down()
    {
        Schema::dropIfExists('audit_logs');
    }

    /**
     * Reverse the migrations.
     */
};
