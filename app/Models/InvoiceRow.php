<?php

namespace App\Models;

use App\FormatOutput\FormatOutput;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceRow extends Model
{
    use HasFactory;
	public $timestamps = false;

	protected $casts = [
		'deleted' => 'boolean',
	];
	/* 
	public function getDiscountStringAttribute()
	{
		dump($this->discount);
		return $this->discount;
	} */

	public function getDiscountValueAttribute()
	{
		$discount_value = preg_match('/[0-9]+\.?[0-9]*/', $this->discount, $out) ? floatVal($out[0]) : 0.00;
		if (str_contains($this->discount, '%')) {
			return $this->price * ($discount_value / 100);
		} else {
			return $discount_value;
		}
	}

	public function getTotalAttribute()
	{
		return ($this->price - $this->discount_value) * ($this->quantity - $this->refund);
	}

	/* protected function discount(): Attribute
	{
		return Attribute::make(
			get: function ($discount_string) {
				$discount_value = (float)preg_match('/[0-9]+\.?[0-9]+/', $discount_string, $out) ? $out[0] : 0.00;
				if (str_contains($discount_string, '%')) {
					return FormatOutput::moneyFormat($this->price * $discount_value);
				} else {
					return FormatOutput::moneyFormat($discount_value);
				}
			}
		);
	} */
}