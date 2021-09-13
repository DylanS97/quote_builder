<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductQuote extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_id',
        'product_id',
        'product_name',
        'product_price',
        'quantity'
    ];

    public function quotes() {
        return $this->belongsToMany(Quote::class);
    }
}
