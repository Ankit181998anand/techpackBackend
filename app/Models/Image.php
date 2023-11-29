<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_path',
        'product_id',
        'isActive'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
