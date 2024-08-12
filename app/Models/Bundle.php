<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{
    // use HasFactory;
      protected $table = 'bundleproduct';
      
       protected $fillable = [
        'product_id','parent_id','child_id'
    ];
    

}
