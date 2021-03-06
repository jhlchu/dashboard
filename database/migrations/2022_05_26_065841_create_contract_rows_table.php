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
		Schema::create('contract_rows', function (Blueprint $table) {
			$table->id();
			$table->unsignedInteger('contract_id');
			$table->string('name');
			$table->string('unit')->default('x')->nullable();
			$table->smallInteger('quantity');
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
		Schema::dropIfExists('contract_rows');
	}
};
