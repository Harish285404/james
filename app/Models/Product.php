<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Categories;
class Product extends Model
{

    use HasFactory;
     protected $table = 'products';

     protected $fillable = [
        'category_id','title','product_id','product_type','bundle_child_id','store','image','sku','product_quantity','varient','stock_quantity','sales_price','retail_price','adjustment_stock', 'current_stock','total_quantity','status','description','store','sales_from','sales_to'
    ];
    
    // public function getProductImageUrlAttribute()
    // {   
    //         $image = 'https://inventory-management.chainpulse.tech/Admin/images/'.$this->image;
    
    //      return  $image ;
    // }
    //  public function getCategoryNameAttribute()
    // {   
    //         $name = Categories::where('id',$this->category_id)->get(['name']);
    
    //      return  $name[0]->name ;
    // }
    
    //  public function getProductStatusAttribute()
    // {   
    //      if($this->status == '1'){
    //          $status = 'Live';
    //      }elseif($this->status == '2'){
    //          $status = 'Hide';
    //      }
         
    //      return $status;
    // }

    // public function toArray()
    // {
    //     $array = parent::toArray();
    //     foreach ($this->getMutatedAttributes() as $key)
    //     {
    //         if ( ! array_key_exists($key, $array)) {
    //             $array[$key] = $this->{$key};   
    //         }
    //     }
    //     return $array;
    // }
}
