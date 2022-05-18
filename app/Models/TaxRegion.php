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
		return $this->hasMany(Tax::class, 'region_id', 'id');
	}

	public function customer() {
		return $this->hasMany(Customer::class);
	}
}