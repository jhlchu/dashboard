<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxRegion extends Model
{
    use HasFactory;
	//protected $table = 'tax_region';
	public $timestamps = false;

	public function tax() {
		$this->hasMany(Tax::class, 'id', 'region_id');
	}

	public function customer() {
		$this->hasMany(Customer::class);
	}
}