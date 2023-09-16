<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'subcat_name',
        'subcat_slug',
        'meta_desc',
        'meta_keyword',
        'category_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function innercategories()
    {
        return $this->hasMany(Innercategory::class);
    }

}
