<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'cat_name',
        'cat_slug',
        'meta_desc',
        'meta_keyword',
        'parent_id'
    ];

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

}
