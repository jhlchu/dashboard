<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceRowFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition()
	{
		$hasDiscount    = $this->faker->boolean($chanceOfGettingTrue = 10);
		$hasRefund      = $this->faker->boolean($chanceOfGettingTrue = 10);
		$price          = $this->faker->randomFloat($nbMaxDecimals = 2, $min = 50, $max = 1000);
		$quantity       = $this->faker->numberBetween($min = 1, $max = 3);
		$discountSign   = $hasDiscount ? $this->faker->randomElement(['$', '%']) : null;
		$discountString = $hasDiscount ?  $this->generateDiscount($discountSign) : null;

		return [
			'invoice_id'  => \App\Models\Invoice::inRandomOrder()->take(1)->value('id'),
			'description' => $this->faker->text($maxNbChars = 50),
			'price'       => $price,
			'quantity'    => $quantity,
			'discount'    => $discountString,
			'refund'      => $hasRefund ? $this->faker->numberBetween($min = 1, $max = $quantity) : null,
			'deleted'     => $this->faker->boolean($chanceOfGettingTrue = 5)
		];
	}

	private function generateDiscount($discountSign)
	{
		if ($discountSign === '$') {
			$discountStringValue = $this->faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 500);
			return $discountSign . $discountStringValue;
		} else {
			$discountStringValue = $this->faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 100);
			return $discountStringValue . $discountSign;
		}
	}
}
