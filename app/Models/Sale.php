<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
     protected $table = 'sales';

     protected $fillable = [
        'product_id','order_id','customer_id','total_price','quantity','mode','status','charge_id'
    ];
    
}
