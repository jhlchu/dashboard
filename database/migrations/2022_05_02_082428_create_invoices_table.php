<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
			$table->id();
			$table->unsignedInteger('invoice_number')->unique();
			$table->unsignedTinyInteger('company_id');
            $table->timestamps();
			$table->unsignedTinyInteger('status_id');
			//$table->enum('status', ['Draft', 'Completed', 'Paid', 'Deleted'])->default('Draft');
			$table->unsignedTinyInteger('salesperson_id');
			$table->unsignedInteger('customer_id');
			$table->text('notes')->nullable()->nullable();
			$table->unsignedFloat('shipping_handling')->nullable();
			$table->string('discount_string')->nullable();
			$table->unsignedFloat('discount_value')->nullable();
			$table->timestamp('completed_at')->nullable();
			$table->timestamp('paid_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::dropIfExists('invoices');
    }
}
