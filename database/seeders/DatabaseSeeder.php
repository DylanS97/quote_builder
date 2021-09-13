<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->deleteImages('public/storage/product_images');

        $this->call(UserTableSeeder::class);

        // To change quantity, change number in 'run' parenthesis.
        $this->call(QuoteSeeder::run(200));
        $this->call(ProductTableSeeder::run(100));

        // To change the quantity, navigate to the seeder and locate a variable - $total.
        $this->call(ProductQuoteTableSeeder::class);
        $this->call(ProductImageTableSeeder::class);
    }

    /**
     * Delete the existing product images.
     * 
     * @param string $dir - File path to images folder.
     *
     * @return void
     */
    public function deleteImages($dir)
    {
        ini_set('display_errors', '1');
        $directory = glob($dir . '/*');
        foreach($directory as $image){
            if(is_file($image)){
                unlink($image);
            }
        }
        print "\nDeleted Images from: " . $dir . "\n\n";
    }
}
