<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->tinyIncrements('id');
			$table->string('name');
			$table->string('address1');
			$table->string('address2')->nullable();
			$table->string('business_name');
			$table->string('city');
			$table->string('province');
			$table->string('country');
			$table->string('postalcode');
			$table->string('phone');
			$table->string('email')->nullable();
			$table->string('url')->nullable();
			$table->string('logo')->nullable();
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
        Schema::dropIfExists('companies');
    }
}
