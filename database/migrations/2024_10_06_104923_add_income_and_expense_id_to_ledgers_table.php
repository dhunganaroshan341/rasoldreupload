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
            $table->unsignedBigInteger('income_id')->nullable();
            $table->foreign('income_id')->references('id')->on('incomes')->onUpdate('cascade');
            $table->unsignedBigInteger('expense_id')->nullable();
            $table->foreign('expense_id')->references('id')->on('expenses')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ledgers', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('income_id')->nullable();
            $table->foreign('income_id')->references('id')->on('incomes');

            $table->unsignedBigInteger('expense_id')->nullable();
            $table->foreign('expense_id')->references('id')->on('expenses')->onUpdate('cascade');
        });
    }
};
