<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

     protected $fillable = [
        'id','order_id','customer_id','line_item_id','product_id','sku','store','quantity','subtotal','transaction_id','order_key','date_created','status'
    ];
}
