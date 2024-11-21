<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_services', function (Blueprint $table) {
            // Billing start date and end date for the service
            $table->date('billing_start_date')->nullable();
            $table->date('billing_end_date')->nullable();

            // Billing frequency: monthly, quarterly, semi-annually, annually
            $table->enum('billing_frequency', ['monthly', 'quarterly', 'semi-annually', 'annually'])
                ->default('monthly');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_services', function (Blueprint $table) {
            $table->dropColumn(['billing_start_date', 'billing_end_date', 'billing_frequency']);
        });
    }
};
