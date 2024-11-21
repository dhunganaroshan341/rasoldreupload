<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutstandingInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outstanding_invoices', function (Blueprint $table) {
            $table->id();  // Auto-incremented ID for the invoice record.

            // Foreign key to the client_services table
            $table->unsignedBigInteger('client_service_id');

            // The total amount due for this particular invoice (monthly, yearly, etc.)
            $table->decimal('total_amount', 15, 2)->default(0);

            // The previous remaining amount from the previous invoice(s)
            $table->decimal('prev_remaining_amount', 15, 2)->default(0);

            // The total amount due for this invoice, including the previous balance and current charge
            $table->decimal('all_total', 15, 2)->default(0);

            // The amount that has been paid towards this particular invoice
            $table->decimal('paid_amount', 15, 2)->default(0);

            // The remaining amount to be paid on this invoice
            $table->decimal('remaining_amount', 15, 2)->nullable();

            // The discount applied to the invoice (absolute value)
            $table->decimal('discount_amount', 15, 2)->default(0);

            // The discount percentage applied to the invoice
            $table->decimal('discount_percentage', 5, 2)->default(0);

            // The date by which the invoice is due
            $table->date('due_date');

            // The latest payment date for this invoice
            $table->date('last_paid')->nullable(); // Nullable as it may not have been paid yet.

            // The remarks provides additional information about this invoice
            $table->text('remarks')->nullable();

            // Bill number represents the physical bill that is generated later
            $table->string('bill_number')->nullable()->unique();

            // The status represents whether the invoice is paid, pending, or overdue
            $table->enum('status', ['pending', 'paid', 'overdue'])->default('pending');

            // Cumulative total amount paid for this invoice (including partial payments)
            $table->decimal('all_total_paid', 15, 2)->default(0);

            // Timestamps for created_at and updated_at
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('client_service_id')
                ->references('id')
                ->on('client_services')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('outstanding_invoices');
    }
}
