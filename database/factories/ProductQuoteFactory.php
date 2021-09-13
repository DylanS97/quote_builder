<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductQuote;
use App\Models\Quote;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductQuoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductQuote::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'quote_id' => Quote::factory(),
            'product_id' => Product::factory()
        ];
    }
}
