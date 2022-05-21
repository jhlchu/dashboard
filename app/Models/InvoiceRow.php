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

	public function getDiscountAttribute()
	{
		return $this->discount;
	}

	public function getTotalAttribute()
	{
		return FormatOutput::moneyFormat(($this->price * $this->discount) * ($this->quantity - $this->refund));
	}

	protected function discount(): Attribute
	{
		return Attribute::make(
			get: function ($discount_string, $attributes) {
				$discount_value = (float)preg_match('/[0-9]+\.?[0-9]+/', $discount_string, $out) ? $out[0] : 0.00;
				if (str_contains($discount_string, '%')) {
					return FormatOutput::moneyFormat($attributes['price'] * $discount_value);
				} else {
					return FormatOutput::moneyFormat($attributes['price'] - $discount_value);
				}
			}
		);
	}
}