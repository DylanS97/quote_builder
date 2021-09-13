<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductQuote;
use App\Models\Quote;
use Illuminate\Database\Seeder;

class ProductQuoteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(Quote::all() as $quote) {
            $total = random_int(1, 9);

            $products = Product::all();
            $subTotal = 0;

            for ($i = 0, $c = $total; $i < $c; $i++) {
                $product = $products->find(random_int(1, count($products)));
                $quantity = random_int(1, 7);

                ProductQuote::factory()->create([
                    'quote_id' => $quote->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_price' => $product->price,
                    'quantity' => $quantity
                ]);

                $subTotal += ($product->price * $quantity);
            }

            $quote->update([
                'sub_total' => $subTotal,
                'sub_total_vat' => $subTotal * 0.2,
                'total' => $subTotal * 1.2
            ]);
        }
    }
}
