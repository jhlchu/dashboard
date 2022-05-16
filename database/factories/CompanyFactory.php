<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        return [
			'name' => $this->faker->company(),
			'address1' => $this->faker->streetAddress(),
			'address2' => '',
			'city' => $this->faker->city(),
			'province' => $this->faker->stateAbbr(),
			'country' => $this->faker->country(),
			'postalcode' => $this->faker->postcode(),
			'phone' => $this->faker->phoneNumber(),
			'email' => $this->faker->email(),
			'url' => $this->faker->url(),
			'logo' => null,
        ];
    }
}
