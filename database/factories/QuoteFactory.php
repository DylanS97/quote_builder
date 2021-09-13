<?php

namespace Database\Factories;

use App\Models\Quote;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Quote::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'client_first_name' => $this->faker->firstName(),
            'client_last_name' => $this->faker->lastName(),
            'client_phone' => $this->faker->phoneNumber(),
            'client_email' => $this->faker->email(),
            'sub_total' => $this->faker->numberBetween(0, 2),
            'sub_total_vat' => $this->faker->numberBetween(0, 2),
            'total' => $this->faker->numberBetween(0, 2),
            'completed' => $this->faker->boolean()
        ];
    }
}
