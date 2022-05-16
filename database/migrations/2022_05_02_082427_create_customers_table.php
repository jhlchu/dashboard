<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedTinyInteger('tax_region');
			$table->string('name');
			$table->string('email')->nullable();
			$table->string('address')->nullable();
			$table->string('province')->nullable();
			$table->string('country')->nullable();
			$table->string('phone')->nullable();
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
        //Schema::dropIfExists('customers');
    }
}
