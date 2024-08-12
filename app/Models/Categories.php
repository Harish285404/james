<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;
     protected $table = 'categories';

     protected $fillable = [
        'category_id','name','status','description','store','image'
    ];
    
    public function getCategoryImageUrlAttribute()
    {   
            $image = 'https://inventory-management.chainpulse.tech/Admin/images/'.$this->image;
    
         return  $image ;
    }
    
     public function getCategoryStatusAttribute()
    {   
         if($this->status == '1'){
             $status = 'Live';
         }elseif($this->status == '2'){
             $status = 'Hide';
         }
         
         return $status;
    }

    public function toArray()
    {
        $array = parent::toArray();
        foreach ($this->getMutatedAttributes() as $key)
        {
            if ( ! array_key_exists($key, $array)) {
                $array[$key] = $this->{$key};   
            }
        }
        return $array;
    }
}
