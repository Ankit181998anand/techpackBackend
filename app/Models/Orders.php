<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'user_id',
        'email',
        'contact',
        'address',
        'products',
        'orderID',
        'payerID',
        'paymentID',
        'facilitatorAccessToken',
        'paymentSource',
        'status',
        'total'
    ];
}
