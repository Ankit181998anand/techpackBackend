<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Innercategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'innercat_name',
        'innersubcat_slug',
        'meta_desc',
        'meta_keyword',
        'subcategory_id'
    ];

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }
}
