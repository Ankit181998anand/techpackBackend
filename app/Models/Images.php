<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'product_id',
        'isActive'   
    ];

    public function products()
    {
        return $this->belongsTo(Products::class);
    }
}
