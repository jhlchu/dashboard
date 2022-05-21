<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceRowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_rows', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedBigInteger('invoice_id');
			$table->tinyText('description');
			$table->unsignedFloat('price');
			$table->unsignedTinyInteger('quantity');
			$table->string('discount')->default('$0');
			$table->unsignedTinyInteger('refund')->default(0);
			$table->boolean('deleted')->default(false);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('invoice_row');
    }
}
