<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_first_name',
        'client_last_name',
        'client_phone',
        'client_email',
        'sub_total',
        'sub_total_vat',
        'total',
        'completed'
    ];

    public function products() 
    {
        return $this->hasMany(ProductQuote::class);
    }
}
