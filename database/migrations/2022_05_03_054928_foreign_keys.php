<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('customers', function (Blueprint $table) {
			$table->foreign('tax_region')->references('id')->on('tax_regions');
		});

		Schema::table('invoice_rows', function (Blueprint $table) {
			$table->foreign('invoice_id')->references('id')->on('invoices');
		});
		
		Schema::table('invoices', function (Blueprint $table) {
			$table->foreign('status_id')->references('id')->on('statuses');
			$table->foreign('salesperson_id')->references('id')->on('users');
			$table->foreign('customer_id')->references('id')->on('customers');
			$table->foreign('company_id')->references('id')->on('companies');
		});
		
		Schema::table('taxes', function (Blueprint $table) {
			$table->foreign('region_id')->references('id')->on('tax_regions');
		});

		


		

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
