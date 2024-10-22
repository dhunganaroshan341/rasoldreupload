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
        Schema::create('employee_payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')->onUpdate('cascade');
            $table->double('amount')->nullable();
            $table->unsignedBigInteger('month_id');
            $table->foreign('month_id')->references('id')->on('months')->onUpdate('cascade');
            $table->enum('payroll_status', ['paid', 'remaining'])->default('remaining');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('employee_payrolls');

    }
};
