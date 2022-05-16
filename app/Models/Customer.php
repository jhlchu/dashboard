<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

	public function invoice() {
		return $this->hasMany(Invoice::class,  'customer_id', 'id');
	}

	public function taxRegion() {
		return $this->belongsTo(TaxRegion::class, 'tax_region');
		//return $this->hasOne(TaxRegion::class, 'id');
	}

	public function tax() {
		return $this->hasManyThrough(Tax::class, TaxRegion::class, 'id', 'region_id', 'tax_region', 'id');
	}
}