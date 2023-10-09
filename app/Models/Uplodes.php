<?php

namespace App\Models;  // Update the namespace to match the directory structure

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uplodes extends Model  // Update the class name to match the filename
{
    use HasFactory;

    protected $fillable = [
        'path',
        'product_id',
        'isActive'   
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
