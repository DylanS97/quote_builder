<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->on('quotes')->onDelete('cascade');
            $table->foreignId('product_id')->on('products')->onDelete('cascade');
            $table->string('product_name');
            $table->integer('product_price');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_quotes');
    }
}
