<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
		$hasNotes            = $this->faker->boolean($chanceOfGettingTrue = 80);
		$hasDiscount         = $this->faker->boolean($chanceOfGettingTrue = 30);
		$discountSign        = $hasDiscount ? $this->faker->randomElement(['$', '%']) : null;
		$discountStringValue = $hasDiscount ? $this->faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 100) : null;

        return [
			'invoice_number' => date("y") . str_pad($this->faker->unique(true)->numberBetween($min = 1, $max = 10000), 6, '0', STR_PAD_LEFT),
            'status_id'         => \App\Models\Status::inRandomOrder()->take(1)->value('id'),
            'company_id'        => \App\Models\Company::inRandomOrder()->take(1)->value('id'),
            'salesperson_id'    => \App\Models\User::inRandomOrder()->take(1)->value('id'),
            'customer_id'       => \App\Models\Customer::inRandomOrder()->take(1)->value('id'),
            'notes'             => $hasNotes ? implode(', ', $this->faker->sentences($nb = 3, $asText = false)) : null,
            'shipping_handling' => $this->faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 100),
			'discount'   => $hasDiscount ? ($discountSign === '$' ? $discountSign . $discountStringValue : $discountStringValue . $discountSign) : '$0',
            'completed_at'      => now(),
            //'paid_at'           => now()
        ];
	}
}