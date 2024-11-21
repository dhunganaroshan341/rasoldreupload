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
        Schema::table('incomes', function (Blueprint $table) {
            $table->string('payment_type')->default('general'); // Type of payment (e.g., 'advance_paid', 'full_payment')
            $table->boolean('is_advance')->default(false); // Flag to indicate if it's an advance payment
            $table->decimal('advance_amount', 10, 2)->nullable(); // Amount of advance if applicable
            // $table->decimal('total_amount', 10, 2)->nullable(); // Total amount for the service if applicable
        });
    }

    public function down()
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->dropColumn('payment_type');
            $table->dropColumn('is_advance');
            $table->dropColumn('advance_amount');
            // $table->dropColumn('total_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
};
