<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    use HasFactory;

    protected $table = 'downloads'; // Specify the table name

    protected $fillable = [
        'user_id',
        'product_id',
        'downlode_count'        
    ];
}
