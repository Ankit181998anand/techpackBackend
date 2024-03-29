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
        'product_price',
        'cat_id',
        'isActive'   
    ];

    public function images()
    {
        return $this->hasMany(Image::class, 'product_id');
    }

    public function files()
    {
        return $this->hasMany(File::class, 'product_id');
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class);
    }
}
