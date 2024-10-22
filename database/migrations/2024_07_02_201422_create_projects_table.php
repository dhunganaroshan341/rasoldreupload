<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id'); // Foreign key to clients table
            $table->unsignedBigInteger('our_service_id'); // Foreign key to services table
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('start_date_english');
            $table->string('start_date_nepali')->nullable();
            $table->date('end_date_english')->nullable();
            $table->string('end_date_nepali')->nullable();
            $table->string('status')->default('ongoing'); // E.g., ongoing, completed, etc.
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('our_service_id')->references('id')->on('our_services')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
