<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_sku',
        'product_slug',
        'product_name',
        'meta_desc',
        'meta_keyword',
        'short_description',
        'long_description',
        'addi_info',
        'product-price',
        'cat_id',
        'isActive'   
    ];

    public function uploads()
    {
        return $this->hasMany(Uplodes::class, 'product_id');
    }
    
}
