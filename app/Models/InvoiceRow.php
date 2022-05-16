<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceRow extends Model
{
    use HasFactory;
	public $timestamps = false;

	public function getTotalAttribute() {
		$discountValue = (float)preg_match('/[0-9]+\.?[0-9]+/', $this->discount_string, $out) ? $out[0] : 0.00;
		if (str_contains($this->discount_string, '%')) {
			return ($this->price * (1-$discountValue/100)) * ($this->quantity - $this->refund_quantity);
		} else {
			return ($this->price - $discountValue) * ($this->quantity - $this->refund_quantity);
		}
	}
}
