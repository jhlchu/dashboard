<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
	use HasFactory;

	public function paymentToString($val)
	{
		return ($val * 100);
	}

	public function getPaymentsAttribute()
	{
		return (object) [
			'payment_1_deposit'      => $this->total * $this->payment_1_deposit,
			'payment_2_purchase'     => $this->total * $this->payment_2_purchase,
			'payment_3_installation' => $this->total * $this->payment_3_installation,
			'payment_4_inspection'   => $this->total * $this->payment_4_inspection,
		];
	}

	public function getPaymentPercentsAttribute()
	{
		return (object) [
			'payment_1_deposit'      => $this->paymentToString($this->payment_1_deposit) . '%',
			'payment_2_purchase'     => $this->paymentToString($this->payment_2_purchase) . '%',
			'payment_3_installation' => $this->paymentToString($this->payment_3_installation) . '%',
			'payment_4_inspection'   => $this->paymentToString($this->payment_4_inspection) . '%',
		];
	}

	public function getPaymentStringsAttribute()
	{
		return (object) [
			'payment_1_deposit'      => $this->paymentToString($this->payment_1_deposit),
			'payment_2_purchase'     => $this->paymentToString($this->payment_2_purchase),
			'payment_3_installation' => $this->paymentToString($this->payment_3_installation),
			'payment_4_inspection'   => $this->paymentToString($this->payment_4_inspection),
		];
	}
}
