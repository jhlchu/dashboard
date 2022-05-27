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
		Schema::create('contracts', function (Blueprint $table) {
			$table->increments('id');
			$table->enum('type', ['residential', 'commercial', 'government'])->default('residential');
			$table->unsignedFloat('total');
			$table->unsignedFloat('payment_1_deposit')->default(0.3);
			$table->unsignedFloat('payment_2_purchasing')->default(0.4);
			$table->unsignedFloat('payment_3_installation')->default(0.2);
			$table->unsignedFloat('payment_4_inspection')->default(0.1);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('contracts');
	}
};
