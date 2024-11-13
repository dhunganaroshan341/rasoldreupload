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
        Schema::table('client_services', function (Blueprint $table) {
            $table->enum('billing_period_frequency', ['one-time', 'monthly', 'quarterly', 'semi-annually', 'annually'])
                ->default('one-time')
                ->after('service_id');

            // Increase precision for larger values
            $table->decimal('advance_paid', 15, 2)
                ->default(0)
                ->after('billing_period_frequency');
        });
    }

    public function down()
    {
        Schema::table('client_services', function (Blueprint $table) {
            $table->dropColumn('billing_period_frequency');
            $table->dropColumn('advance_paid');
        });
    }
};
