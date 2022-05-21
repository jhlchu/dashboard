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
			$table->unsignedTinyInteger('status_id');
			$table->unsignedTinyInteger('company_id');
			$table->unsignedTinyInteger('salesperson_id');
			$table->unsignedInteger('customer_id');
			$table->text('notes')->nullable();
			$table->unsignedFloat('shipping_handling')->default(0.00);
			$table->string('discount')->default('$0');
			$table->timestamps();
			$table->timestamp('completed_at')->nullable()->default(null);
			$table->timestamp('paid_at')->nullable()->default(null);
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
