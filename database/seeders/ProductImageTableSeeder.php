<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductImageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(Product::all() as $product) {
            $total = random_int(1, 2);

            ProductImage::factory($total)->create([
                'product_id' => $product->id
            ]);
        }
    }
}
