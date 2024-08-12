<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Categories;
use App\Models\Product;
use App\Models\Sale;
use Automattic\WooCommerce\Client;
use App\Models\Store;
use App\Models\Bundle;
use App\Models\Order;
use DateTime;
use App\Models\Meta_details;
use Illuminate\Support\Facades\Http;
class FetchProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Fetch:product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Product';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
                   
            
        // for($s=1;$s<=2;$s++)
        // {
        $s=3;
                 $store = Store::where('id',$s)->get();
              
                 $woocommerce = new Client(
                  $store[0]->store_url,
                  $store[0]->key,
                  $store[0]->secret_key,
                  [
                    'version' => 'wc/v3',
                  'timeout' => 400 // curl timeout
                  ]
                );
   
                $url = $store[0]->store_url.'/wp-json/wc/v3/products?consumer_key='. $store[0]->key.'&consumer_secret='.$store[0]->secret_key;
                $tsheaders = get_headers($url);
                $total = explode(":", $tsheaders[7]);
                $icount = $total[1];
                $string = $icount/100;
                preg_match('/(\d+)\./', $string, $matches);

                if (isset($matches[1])) 
                {
                    $firstDigit = $matches[1];
                    $total_count = $firstDigit+1;
 
                    for($p=1;$p<=$total_count;$p++)
                    {
                          $result1 =  $woocommerce->get('products?per_page=100&page='.$p);
       
                                for($j=0;$j<count($result1);$j++)
                                {
                                    $categories = [];
                                    $v = [];
                                    for($i=0;$i<count($result1[$j]->categories);$i++)
                                    {
                                          $type =  $result1[$j]->type;
                                          $categories[] = $result1[$j]->categories[$i]->id;
                                          $result = $woocommerce->get('products/categories/'.$result1[$j]->categories[$i]->id);
                                     
        
                                         
                                        $destinationPath = 'https://inventory-management.chainpulse.tech/Admin/images';
                                        
                                        if($result->image)
                                        {
                                            $imageUrl = $result->image->src;
                                            $pathinfo = pathinfo($imageUrl);
                                            $name = $pathinfo['filename'].'.'.$pathinfo['extension'];
                                            $response = Http::get($imageUrl);

                                            if ($response->successful()) 
                                            {
                                                // Generate a unique filename
                                                $filename =  $name;
                                    
                                                
                                                $storagePath = 'Admin/images';
                                    
                                                
                                                if (!file_exists($storagePath)) {
                                                    mkdir($storagePath, 0755, true);
                                                }
                                    
                                                // Save the image to the specified path
                                                file_put_contents($storagePath . '/' . $filename, $response->body());
                                    
                                            }
                                            $count =  Categories::where('category_id',$result->id)->count();
                                            if($count == 0)
                                            {
                                                $category = new Categories;
                                                $category->name = $result->name;
                                                $category->category_id = $result->id;
                                                $category->description = $result->description;
                                                $category->store = $s;
                                                $category->image = $name;
                                                $category->save();
                                                
                                                   $meta = new Meta_details;
                                                        $meta->c_id = $category->id;
                                                        $meta->category_id = $result->id;
                                                        $meta->store_id = $s;
                                                        $meta->save();
                                             }else{
                                                 $count1 =  Categories::where('name',$result->name)->where('store','1')->get();
                                                 $count2 =  Categories::where('name',$result->name)->where('store','2')->get();
                                                 if($count1->isNotEmpty() && $count2->isNotEmpty()){
                                                     $c_id_data = $count1[0]->category_id.','.$count2[0]->category_id;
                                                    $category = Categories::Where('id',$count1[0]->id)->update(['store'=>'1,2','category_id'=>$c_id_data]);
                                                    $category1 = Categories::Where('id',$count2[0]->id)->delete();
                                                    
                                                 }
                                                 
                                             }
                                        }
                                        else
                                        {
                                                 $count =  Categories::where('category_id',$result->id)->count();
                                                 if($count == 0)
                                                 {
                                                    $category = new Categories;
                                                    $category->name = $result->name;
                                                    $category->category_id = $result->id;
                                                    $category->description = $result->description;
                                                    $category->store = $s;
                                                    $category->image = 'woocommerce-placeholder.png';
                                                    $category->save();
                                                    
                                                        $meta = new Meta_details;
                                                        $meta->c_id = $category->id;
                                                        $meta->category_id = $result->id;
                                                        $meta->store_id = $s;
                                                        $meta->save();
                                                 }else{
                                                 $count1 =  Categories::where('name',$result->name)->where('store','1')->get();
                                                 $count2 =  Categories::where('name',$result->name)->where('store','2')->get();
                                                 if($count1->isNotEmpty() && $count2->isNotEmpty()){
                                                     $c_id_data = $count1[0]->category_id.','.$count2[0]->category_id;
                                                    $category = Categories::Where('id',$count1[0]->id)->update(['store'=>'1,2','category_id'=>$c_id_data]);
                                                    $category1 = Categories::Where('id',$count2[0]->id)->delete();
                                                 }
                                                 
                                             }
                                        }
                                               
                                    } 
                                        // dd($result1);
                                    if($result1[$j]->images)
                                    {
                                            $imageUrll = $result1[$j]->images[0]->src;
                                            $responsee = Http::get($imageUrll);
                                    
                                            if ($responsee->successful()) 
                                            {
                                                // Generate a unique filename
                                                $filenamee =  time() . '.jpg';
                                    
                                                
                                                $storagePathe = 'Admin/images';
                                    
                                                
                                                if (!file_exists($storagePathe)) {
                                                    mkdir($storagePathe, 0755, true);
                                                }
                                    
                                                // Save the image to the specified path
                                                file_put_contents($storagePathe . '/' . $filenamee, $responsee->body());
                                    
                                            }
         
                                          $category = implode(',',$categories);
                                            $dataa  = Categories::whereIn('category_id',$categories)->get();
    
                                                                $length = count($dataa);
                                                             for($t=0;$t<$length;$t++){ 

                                                                   $v[] = $dataa[$t]->category_id;
                                                                
                                                             }
                                                                $varient = implode('-',$v);
                                          $pcount =  Product::where('product_id',$result1[$j]->id)->count();
                                          if($pcount == 0)
                                          {
                                                    
                                              
                                              
                                                  $child_id = [];
                                                   $qty = [];
                                                //   $line_items = $result1[$j]->bundled_items;
                                                   
                                            //       if(!empty($line_items))
                                            //         {
                                            //                 for($b=0;$b<count($line_items);$b++)
                                            //                 { 
                                            //                     $bundle_count = Bundle::where('parent_id',$line_items[$b]->bundled_item_id)->count();
                                            //                     if($bundle_count == 0){
                                            //                             $Bundle = new Bundle;
                                            //                             $Bundle->product_id =$result1[$j]->id;
                                            //                             $Bundle->parent_id = $line_items[$b]->bundled_item_id;
                                            //                             $Bundle->child_id = $line_items[$b]->product_id;
                                            //                             $Bundle->save(); 
                                            //                             $child_id[] = $line_items[$b]->product_id;
                                            //                             $qty[] = $line_items[$b]->quantity_max;
                                            //                     }else{
                                                                    
                                            //                             $child_id[] = $line_items[$b]->product_id;
                                            //                             $qty[] = $line_items[$b]->quantity_max;
                                                                         
                                            //                     }
                                                                    
                                            //                 }

                                            //                  $quantity_maxs = implode(',',$qty);
                                                                                                                 
                                            //                 $child_ids =implode(',',$child_id);
                                            //                 $product = new Product;
                                            //                 $product->title = $result1[$j]->name;
                                            //                 $product->product_id = $result1[$j]->id;
                                            //                 $product->product_type = $result1[$j]->type;
                                            //                 $product->varient =$varient;
                                            //                 $product->bundle_child_id = $child_ids;
                                            //                 $product->category_id = $category;
                                            //                 $product->description = $result1[$j]->short_description;
                                            //                 $product->sku = $result1[$j]->sku;
                                            //                 $product->product_quantity = $quantity_maxs;
                                            //                 $product->sales_price = $result1[$j]->sale_price;
                                            //                 $product->sales_from = date( 'Y-m-d 00:00:00', strtotime($result1[$j]->date_on_sale_from));
                                            //                 $product->sales_to = date( 'Y-m-d 23:59:59', strtotime($result1[$j]->date_on_sale_to));
                                            //                 $product->retail_price = $result1[$j]->regular_price;
                                            //                 $product->stock_quantity =$result1[$j]->bundle_stock_quantity;
                                            //                 $product->current_stock = $result1[$j]->bundle_stock_quantity;
                                            //                 $product->status = $result1[$j]->status;
                                            //                 $product->store = $s;
                                            //                 $product->image = $filenamee;
                                            //                  $product->created_at  =date( 'Y-m-d 00:00:00', strtotime($result1[$j]->date_created));
                                            //                 $product->save();
                                            //                 $product_id[] = $result1[$j]->id;
                                            //                  //for($s=0;$s<count($store_data);$s++){
                                            //                     $meta = new Meta_details;
                                            //                     $meta->p_id = $product->id;
                                            //                     $meta->product_id = $result1[$j]->id;
                                            //                     $meta->store_id = $s;
                                            //                     $meta->save();
                                            //                  // }
                                                             
                                                    
                                            //                 if($product->stock_quantity == NULL)
                                            //                 {
                                            //                     Product::where('product_id', $result1[$j]->id)->update(['current_stock' =>'2000' , 'stock_quantity' => '2000']);
                                            //                 }
                                                            
                                                            
                                            //     $current_stock = Product::where('product_id', $result1[$j]->id)->first();
                                               
                                            //     $total = Order::where('product_id', $result1[$j]->id)->sum('quantity');
                                                
                                            //     $total_stock = $current_stock->stock_quantity - $total;

                                            //     // Update the product's stock and total quantity
                                            //     Product::where('product_id', $result1[$j]->id)->update(['current_stock' => $total_stock, 'total_quantity' => $total]);
                                              
                                            //   // Product::where('product_id', $result1[$j]->id)->update(['total_quantity' => $total]);
                                                            
                                            //         }else{
                                            
                                                  $product = new Product;
$product->title = $result1[$j]->name;
$product->product_id = $result1[$j]->id;
$product->product_type = $result1[$j]->type;
$product->varient = $varient;
$product->category_id = $category;
$product->description = $result1[$j]->short_description;
$product->sku = $result1[$j]->sku;
$product->sales_price = $result1[$j]->sale_price;
$product->sales_from = date('Y-m-d 00:00:00', strtotime($result1[$j]->date_on_sale_from));
$product->sales_to = date('Y-m-d 23:59:59', strtotime($result1[$j]->date_on_sale_to));
$product->retail_price = $result1[$j]->regular_price;
$product->stock_quantity = $result1[$j]->stock_quantity;
$product->current_stock = $result1[$j]->stock_quantity;
$product->status = $result1[$j]->status;
$product->store = $s;
$product->image = $filenamee;
$product->save();

$product_id[] = $result1[$j]->id;

// Save meta details
$meta = new Meta_details;
$meta->p_id = $product->id;
$meta->product_id = $result1[$j]->id;
$meta->store_id = $s;
$meta->save();

// Check and update stock quantities
if ($product->stock_quantity == null) {
    $product->current_stock = 2000;
    $product->stock_quantity = 2000;
    $product->save();
}

$current_stock = $product->stock_quantity;
$total = Order::where('product_id', $result1[$j]->id)->sum('quantity');
$total_stock = $current_stock - $total;

// Update the product's stock and total quantity
$product->current_stock = $total_stock;
$product->total_quantity = $total;
$product->save();

// Update the WooCommerce product
$data = [
    'manage_stock' => true,
    'stock_quantity' => $total_stock,
];

$resulttt = $woocommerce->put('products/' . $result1[$j]->id, $data);
                                                
                                               
                                                     }else{
                                                 $pcount1 =  Product::where('title',$result1[$j]->name)->where('store','1')->get();
                                                 $pcount2 =  Product::where('title',$result1[$j]->name)->where('store','2')->get();
                                                 if($pcount1->isNotEmpty() && $pcount2->isNotEmpty()){
                                                     if($pcount1[0]->product_type == 'simple' && $pcount2[0]->product_type == 'simple'){
                                                     $p_id_data = $pcount1[0]->product_id.','.$pcount2[0]->product_id;
                                                     $p_c_data = $pcount1[0]->current_stock.','.$pcount2[0]->current_stock;
                                                     $p_s_data = $pcount1[0]->total_quantity.','.$pcount2[0]->total_quantity;
                                                       $var_cat1 = explode(',',$pcount1[0]->category_id);
                                                      $var_cat2 = explode(',',$pcount2[0]->category_id);
                                                      $p_cat_datas = [];
                                                      for($x =0 ;$x < count($var_cat1);$x++){
                                                         $p_cat_datas[] =  $var_cat1[$x].','.$var_cat2[$x];
                                                      }
                                                     $p_cat_data = implode('-',$p_cat_datas);//$pcount1[0]->category_id.','.$pcount2[0]->category_id;
                                                      $p_v_data = $pcount1[0]->category_id.','.$pcount2[0]->category_id;
                                                    $categoryp = Product::Where('id',$pcount1[0]->id)->update(['store'=>'1,2','product_id'=>$p_id_data,'stock_quantity'=>'2000,2000','current_stock'=>$p_c_data,'total_quantity'=>$p_s_data,'category_id'=>$p_v_data,'varient'=>$p_cat_data]);
                                                    // $meta =Meta_details::where('product_id',$pcount2[0]->product_id)->update(['p_id'=>$pcount1[0]->id]);
                                                    $categorypt = Product::Where('id',$pcount2[0]->id)->delete();
                                                     }else{
                                                         $p_id_data = $pcount1[0]->product_id.','.$pcount2[0]->product_id;
                                                     $p_c_data = $pcount1[0]->current_stock.','.$pcount2[0]->current_stock;
                                                     $p_s_data = $pcount1[0]->total_quantity.','.$pcount2[0]->total_quantity;
                                                     $p_b_data = $pcount1[0]->bundle_child_id.','.$pcount2[0]->bundle_child_id;
                                                     $var_cat1 = explode(',',$pcount1[0]->category_id);
                                                      $var_cat2 = explode(',',$pcount2[0]->category_id);
                                                      $p_cat_datas = [];
                                                      for($x =0 ;$x < count($var_cat1);$x++){
                                                         $p_cat_datas[] =  $var_cat1[$x].','.$var_cat2[$x];
                                                      }
                                                     $p_cat_data = implode('-',$p_cat_datas);//$pcount1[0]->category_id.','.$pcount2[0]->category_id;
                                                     $p_v_data = $pcount1[0]->category_id.','.$pcount2[0]->category_id;
                                                      $pro_data = $pcount1[0]->product_quantity.','.$pcount2[0]->product_quantity;
                                                    $categoryp = Product::Where('id',$pcount1[0]->id)->update(['store'=>'1,2','product_id'=>$p_id_data,'stock_quantity'=>'2000,2000','current_stock'=>$p_c_data,'total_quantity'=>$p_s_data,'bundle_child_id'=>$p_b_data,'category_id'=>$p_v_data,'varient'=>$p_cat_data,'product_quantity'=>$pro_data]);
                                                    //  $meta =Meta_details::where('product_id',$pcount2[0]->product_id)->update(['p_id'=>$pcount1[0]->id]);
                                                              
                                                    $categorypt = Product::Where('id',$pcount2[0]->id)->delete();
                                                     }
                                                 
                                             }
                                        
                                    }

                              }
                          }
                          
                      }
                  sleep(1);
                   $urll = $store[0]->store_url.'/wp-json/wc/v3/customers?consumer_key='. $store[0]->key.'&consumer_secret='.$store[0]->secret_key;
                $tsheaderss = get_headers($urll);
                $totall = explode(":", $tsheaderss[9]);
                $icountt = $totall[1];
                $totall = explode(":", $tsheaderss[9]);
                $icountt = $totall[1];
                if($icountt !=0 ){
                $strings = $icountt/100;
                preg_match('/(\d+)\./', $strings, $matchess);

                if (isset($matchess[1])) 
                {
                    $firstDigitt = $matchess[1];
                    $total_countt = $firstDigitt+1;
 
                    for($c=1;$c<=$total_countt;$c++)
                    {
                          $customer =  $woocommerce->get('customers?per_page=100&page='.$c);
       
                                for($d=0;$d<count($customer);$d++)
                                {
                                    $date = date( 'Y-m-d 00:00:00', strtotime($customer[$d]->date_created));
                                    User::where('customer_id',$customer[$d]->email)->update(['created_at'=>$date]);
                               }
                           }
              
                      }
                      
                }
                }
                
                sleep(1);
        // }
    
     $this->info('Successfully fetched product.');
    }
}
