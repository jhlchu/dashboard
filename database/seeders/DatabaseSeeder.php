<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Customer;
use App\Models\Company;
use App\Models\Status;
use App\Models\Invoice;
use App\Models\InvoiceRow;
use App\Models\TaxRegion;
use App\Models\Tax;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

		User::create([
			'name'              => "Allen",
			'email'             => "allen@element-acoustics.ca",
			'email_verified_at' => now(),
			'password'          => '$2y$10$PUi96D.TSKYAkC0xhHTmBO0JG5420DG3dTJvk1j5qcq4/nqGms5NC',
			'remember_token'    => Str::random(10),
			'is_manager'           => FALSE,
			'is_sales' => TRUE
		]);
		User::create([
			'name'              => "Dragan",
			'email'             => "dragan@element-acoustics.ca",
			'email_verified_at' => now(),
			'password'          => '$2y$10$AYcvlsKmUgRMN3Nd6Z/iJ.51lSjiNCG.1ADeucRgyegCrR/Y7DE4q',
			'remember_token'    => Str::random(10),
			'is_manager'           => FALSE,
			'is_sales' => TRUE
		]);
		User::create([
			'name'              => "Edward",
			'email'             => "edward@element-acoustics.ca",
			'email_verified_at' => now(),
			'password'          => '$2y$10$x78l3v959PDbJhMGE96YWuCDJHPdEyjJBXy0rCtcFPEARcSP05R32',
			'remember_token'    => Str::random(10),
			'is_manager'           => TRUE,
			'is_sales' => TRUE
		]);
		User::create([
			'name'              => "Eric",
			'email'             => "eric@element-acoustics.ca",
			'email_verified_at' => now(),
			'password'          => '$2y$10$qbppLSSpzfrKf0VbM7MkLuy3FU6gewg1xq/DfwFTqQpKuqIaOtlVi',
			'remember_token'    => Str::random(10),
			'is_manager'           => FALSE,
			'is_sales' => TRUE
		]);
		User::create([
			'name'              => "Jeffrey",
			'email'             => "jeffrey@element-acoustics.ca",
			'email_verified_at' => now(),
			'password'          => '$2y$10$677ru.yjndm6NxzNJg2L.uVTbWSnmof8GPmH1Tlf4lb8pBkWXHPu6',
			'remember_token'    => Str::random(10),
			'is_manager'           => FALSE,
			'is_sales' => TRUE
		]);
		User::create([
			'name'              => "Melvin",
			'email'             => "melvin@element-acoustics.ca",
			'email_verified_at' => now(),
			'password'          => '$2y$10$TAh1ayimeJ9B3qh75OyZYe4l13Dmo3dIMgBSIXrK/LKT79apCIPkS',
			'remember_token'    => Str::random(10),
			'is_manager'           => FALSE,
			'is_sales' => TRUE
		]);

		TaxRegion::create([ 'name' => 'NONE' ]);			// 1
		TaxRegion::create([ 'name' => 'BC/CA' ]);			// 2
		TaxRegion::create([ 'name' => 'AB/CA' ]);			// 3
		TaxRegion::create([ 'name' => 'MB/CA' ]);			// 4
		TaxRegion::create([ 'name' => 'SA/CA' ]);			// 5
		TaxRegion::create([ 'name' => 'ON/CA' ]);			// 6
		TaxRegion::create([ 'name' => 'QC/CA' ]);			// 7
		TaxRegion::create([ 'name' => 'NT/CA' ]);			// 8
		TaxRegion::create([ 'name' => 'NU/CA' ]);			// 9
		TaxRegion::create([ 'name' => 'YT/CA' ]);			// 10
		TaxRegion::create([ 'name' => 'NB/CA' ]);			// 11
		TaxRegion::create([ 'name' => 'NL/CA' ]);			// 12
		TaxRegion::create([ 'name' => 'NS/CA' ]);			// 13
		TaxRegion::create([ 'name' => 'PE/CA' ]);			// 14
		TaxRegion::create([ 'name' => 'US' ]);				// 15
		TaxRegion::create([ 'name' => 'EU' ]);				// 16
		TaxRegion::create([ 'name' => 'INTERNATIONAL' ]);	// 17

		Tax::create([ 'region_id' => 1 , 'name' => 'NO TAX', 'value' => 0.00 ]);
		Tax::create([ 'region_id' => 2 , 'name' => 'GST'   , 'value' => 0.05 ]);
		Tax::create([ 'region_id' => 2 , 'name' => 'PST'   , 'value' => 0.07 ]);
		Tax::create([ 'region_id' => 6 , 'name' => 'HST'   , 'value' => 0.13 ]);
		Tax::create([ 'region_id' => 7 , 'name' => 'PST'   , 'value' => 0.05 ]);
		Tax::create([ 'region_id' => 3 , 'name' => 'PST'   , 'value' => 0.05 ]);
		Tax::create([ 'region_id' => 5 , 'name' => 'PST'   , 'value' => 0.05 ]);
		Tax::create([ 'region_id' => 8 , 'name' => 'PST'   , 'value' => 0.05 ]);
		Tax::create([ 'region_id' => 9 , 'name' => 'PST'   , 'value' => 0.05 ]);
		Tax::create([ 'region_id' => 10, 'name' => 'PST'   , 'value' => 0.05 ]);
		Tax::create([ 'region_id' => 4 , 'name' => 'PST'   , 'value' => 0.05 ]);
		Tax::create([ 'region_id' => 11, 'name' => 'HST'   , 'value' => 0.15 ]);
		Tax::create([ 'region_id' => 12, 'name' => 'HST'   , 'value' => 0.15 ]);
		Tax::create([ 'region_id' => 13, 'name' => 'HST'   , 'value' => 0.15 ]);
		Tax::create([ 'region_id' => 14, 'name' => 'HST'   , 'value' => 0.15 ]);
		Tax::create([ 'region_id' => 15 , 'name' => 'US', 'value' => 0.00 ]);
		Tax::create([ 'region_id' => 16 , 'name' => 'EU', 'value' => 0.00 ]);
		Tax::create([ 'region_id' => 17 , 'name' => 'INTL', 'value' => 0.00 ]);

		Customer::create([ 'tax_region' => 1, 'name' => 'No Tax Customer' ]);
		Customer::create([ 'tax_region' => 1, 'name' => 'US Customer', 'country' => 'US' ]);
		Customer::create([ 'tax_region' => 2, 'name' => 'BC Customer', 'province' => 'BC', 'country' => 'CA' ]);
		Customer::create([ 'tax_region' => 6, 'name' => 'ON Customer', 'province' => 'ON', 'country' => 'CA' ]);
		Customer::create([ 'tax_region' => 7, 'name' => 'QC Customer', 'province' => 'QC', 'country' => 'CA' ]);
		Customer::create([ 'tax_region' => 3, 'name' => 'AB Customer', 'province' => 'AB', 'country' => 'CA' ]);

		Company::create([
			'name'       => 'Element Acoustics BC'        ,
			'address1'   => '11420 Blacksmith Place'      ,
			'business_name'   => 'Element Acoustics Inc.'      ,
			'city'       => 'Richmond'                    ,
			'province'   => 'BC'                          ,
			'country'    => 'Canada'                      ,
			'postalcode' => 'V7A 4X1'                     ,
			'phone'      => '123-4567'                    ,
			'email'      => 'edward@element-acoustics.ca' ,
			'url'        => 'www.element-acoustics.com'
		]);
		
		Company::create([
			'name'       => 'Element Acoustics Home Entertainment' ,
			'address1'   => '11420 Blacksmith Place'               ,
			'business_name'   => 'Element Acoustics Inc.'      ,
			'city'       => 'Richmond'                             ,
			'province'   => 'BC'                                   ,
			'country'    => 'Canada'                               ,
			'postalcode' => 'V7A 4X1'                              ,
			'phone'      => '123-4567'                             ,
			'email'      => 'edward@element-acoustics.ca'          ,
			'url'        => 'hr.element-acoustics.com'
		]);
		
		Company::create([
			'name'       => 'Element Acoustics Ontario'   ,
			'address1'   => '1234 ABCD'                   ,
			'business_name'   => 'Element Acoustics Inc.'      ,
			'city'       => 'Toronto'                     ,
			'province'   => 'ON'                          ,
			'country'    => 'Canada'                      ,
			'postalcode' => 'V7A 4X1'                     ,
			'phone'      => '345-6789'                    ,
			'email'      => 'dragan@element-acoustics.ca' ,
			'url'        => 'www.element-acoustics.com'
		]);

		Status::create(['name' => 'Draft', 'color' => 'gray', 'icon' => 'draft']);
		Status::create(['name' => 'Completed', 'color' => 'blue', 'icon' => 'done']);
		//Status::create(['name' => 'Paid', 'color' => 'green', 'icon' => 'price_check']);
		Status::create(['name' => 'Deleted', 'color' => 'red', 'icon' => 'delete_forever']);
		Status::create(['name' => 'Refunded', 'color' => 'purple', 'icon' => 'currency_exchange']);

		Invoice::factory(20)->create();
		InvoiceRow::factory(40)->create();



		/* 
		NO TAX,0.00,0.00
		BC/CA,5.00,7.00
		ON/CA,13.00,0.00
		QC/CA,0.00,5.00
		AB/CA,0.00,5.00
		SA/CA,0.00,5.00
		NT/CA,0.00,5.00
		NU/CA,0.00,5.00
		YT/CA,0.00,5.00
		MB/CA,0.00,5.00
		NB/CA,15.00,0.00
		NL/CA,15.00,0.00
		NS/CA,15.00,0.00
		PE/CA,15.00,0.00
		*/
    }
}
