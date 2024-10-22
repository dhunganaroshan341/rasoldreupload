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
        Schema::table('client_services', function (Blueprint $table) {
            //
            $table->string('name')->nullable(); //this was to store indvidual client_service name suppose
            //  if webdevelopement selected is selected as  ourservice  then
            //  legrammar.com will be individual name and using this we will be able to do calculation for each service we provide
            $table->string('description')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_services', function (Blueprint $table) {
            //
            $table->string('name'); //this was to store indvidual client_service name suppose
            //  if webdevelopement selected is selected as  ourservice  then
            //  legrammar.com will be individual name and using this we will be able to do calculation for each service we provide
            $table->string('description');

        });
    }
};
