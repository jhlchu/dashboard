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
			$table->text('notes')->nullable()->nullable();
			$table->unsignedFloat('shipping_handling')->nullable();
			$table->string('discount')->nullable();
			$table->timestamps();
			$table->timestamp('completed_at')->nullable();
			$table->timestamp('paid_at')->nullable();
        });
    }
	//Get Customer -> $customer_id
		//Create Customer OR Get First
	//Create Invoice
	//Generate $invoice_number
	//If Status



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
