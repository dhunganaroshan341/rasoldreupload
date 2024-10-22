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
        Schema::create('employee_individual_payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_payroll_id')->nullable();
            $table->foreign('employee_payroll_id')->references('id')->on('employee_payrolls')->onUpdate('cascade');
            $table->double('amount')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_individual_payrolls');
    }
};
