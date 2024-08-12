<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Store;
use Validator;
use Automattic\WooCommerce\Client;
class ApiController extends Controller
{

    public function products()
    {
         $products = Product::get();
       

         if(sizeof($products))
            {
                $data['status_code']    =   1;
                $data['status_text']    =   'Success';             
                $data['message']        =   'Successfully Fected Products!';
                $data['data']      =         $products;  
            }
            else
            {
                $data['status_code']    =   0;
                $data['status_text']    =   'Failed';             
                $data['message']        =   'No Data Found';
                $data['data']           =   [];  
            }
            return $data;
    
    }
    
    public function categories()
    {
         $products = Categories::get();
       

         if(sizeof($products))
            {
                $data['status_code']    =   1;
                $data['status_text']    =   'Success';             
                $data['message']        =   'Successfully Fected Categories!';
                $data['data']      =         $products;  
            }
            else
            {
                $data['status_code']    =   0;
                $data['status_text']    =   'Failed';             
                $data['message']        =   'No Data Found';
                $data['data']           =   [];  
            }
            return $data;
       
    }
    
        public function webhook(Request $request){
         
         
        header('Content-Type: application/json');
        $request = file_get_contents ('php://input');
        $req_dump = print_r($request, true );
        $json_data = file_put_contents('request.log', $req_dump);
        $action = json_decode($json_data, true);
        
        $test = json_decode($request, true);
        
        $data = (array)$test;
        
         $order_meta = [];
            
            $user_meta = [];
            
            $order_id = $data['id'];
            $status = $data['status'];
            $customer_id = $data['customer_id'];
            $transaction_id = $data['transaction_id'];
            $line_items = $data['line_items'];
            $order_key = $data['order_key'];
             
            $first_name = $data['billing']['first_name'];
            $last_name = $data['billing']['last_name'];
            $address_1 = $data['billing']['address_1'];
            $address_2 = $data['billing']['address_2'];
            $city = $data['billing']['city'];
            $state = $data['billing']['state'];
            $postcode = $data['billing']['postcode'];
            $country = $data['billing']['country'];
            $phone_number = $data['billing']['phone'];
            $email = $data['billing']['email'];
            $role = '0';
            
            $user_meta['first_name'] = $first_name;
            $user_meta['customer_id'] = $email;
            $user_meta['last_name'] = $last_name;
            $user_meta['address_1'] = $address_1;
            $user_meta['address_1'] = $address_2;
            $user_meta['city'] = $city;
            $user_meta['state'] = $state;
            $user_meta['postcode'] = $postcode;
            $user_meta['country'] = $country;
            $user_meta['phone_number'] = $phone_number;
            $user_meta['email'] = $email;
            $user_meta['role'] = $role;
            
            
            
            $date_created = date( 'Y-m-d 00:00:00', strtotime($data['date_created']));
            
            
            for($j=0;$j<count($line_items);$j++){
                
                $line_item_id =  $line_items[$j]['id'];
                $product_id = $line_items[$j]['product_id'];
                $quantity = $line_items[$j]['quantity'];
                $subtotal = $line_items[$j]['subtotal'];
                $sku = $line_items[$j]['sku'];

            
                $order_meta['order_id'] = $order_id;
                $order_meta['status'] = $status;
                $order_meta['customer_id'] = $email;
                $order_meta['transaction_id'] = $transaction_id;
                $order_meta['line_item_id'] = $line_item_id;
                $order_meta['product_id'] = $product_id;
                $order_meta['sku'] = $sku;
                $order_meta['quantity'] = $quantity;
                $order_meta['subtotal'] = $subtotal;
                $order_meta['order_key'] = $order_key;
                $order_meta['date_created'] = $date_created;
                
                Order::insert($order_meta);
                
            
        }
        
       $order_ids = Order::where('order_id',$order_id)->where('order_key',$order_key)->get(['product_id', 'quantity','sku']);
        $min_quans="";
       foreach ($order_ids as $orderItem) {
        $product_ids = $orderItem->product_id;
        $sku = $orderItem->sku;
        $stores = $orderItem->store;
        $quantityy = $orderItem->quantity;

        // Retrieve the current stock for the product
          $current_stock = Product::where('sku', $sku)->first();
          $store = explode(',',$current_stock->store);
           $productid = explode(',',$current_stock->product_id);
          $current = explode(',',$current_stock->current_stock);
          $total_quantity = explode(',',$current_stock->total_quantity);

          // dd($productid);
         
          
          for($i=0;$i<count($store);$i++){
              
                //   print_r( $store[$i]);
            if($productid[$i] == $product_ids){
              $subject =  implode(',',$current);
          
                     // $search = $current[$i];
 
                     if($current[$i] > 0){
               $stock = $current[$i] - $quantityy;
                     }else{
                     $stock = 0;    
                     }

               $current[$i] = $stock;
                      
               $total = Order::where('product_id', $product_ids)->sum('quantity');
               // $array[] = 
               $total_quantity[$i] = $quantityy ;
               $data = implode(',',$current);
               $subject1 =  implode(',',$total_quantity);
             
              Product::where('sku', $sku)->update(['current_stock' => $data, 'total_quantity' => $subject1]);

                  
                $dd =  Product::where('sku', $sku)->get();
               
               $quant = explode(',',$dd[0]->current_stock) ;
               
                $quanty = explode(',',$dd[0]->store) ;
                
                
               
               $min_quan = min($quant);
               
               $min_quans=$min_quan;
               
               
                 for($j=0;$j<count($quanty);$j++){
               
        $store_data = Store::where('id',$quanty[$j])->get();
        $woocommerce = new Client(
        $store_data[0]->store_url,
        $store_data[0]->key,
        $store_data[0]->secret_key,
        [
          'version' => 'wc/v3',
            'timeout' => 400 // curl timeout
        ]
      );
     
    $update_data = [
        "manage_stock" => true,
        "stock_quantity" => $min_quans
      ];

     


     $result1 = $woocommerce->put('products/'.$productid[$j], $update_data);
     

                 } 
                
                 
              $count=count($quanty);
              
              if($count==1){
                  
                 $pcount= $stock;
              }elseif($count==2){
                  
                   $pcount= $stock.','.$stock;
              }else{
                  $pcount= $stock.','.$stock.','.$stock; 
              }
    Product::where('sku', $sku)->update(['current_stock' => $pcount, 'total_quantity' => $subject1]);     
               
               
       
        
            }
            
 
               
          

            
            
          }
        
              }
           
    // foreach ($order_ids as $orderItem) {
    //     $product_ids = $orderItem->product_id;
    //     $quantityy = $orderItem->quantity;

    //     // Retrieve the current stock for the product
    //     $current_stock = Product::where('product_id', $product_ids)->first();

    //     if (!$current_stock) {
    //         throw new \Exception("Product with ID $product_ids not found.");
    //     }

    //     // Calculate the new stock and total quantity
    //     $stock = $current_stock->current_stock - $quantityy;
        
    //     $total = Order::where('product_id', $product_ids)->sum('quantity');
        
    //     //   $total_stock = $current_stock->current_stock + $total;

    //     // Update the product's stock and total quantity
    //     Product::where('product_id', $product_ids)->update(['current_stock' => $stock, 'total_quantity' => $total]);
    // }
   
        
       
         $user = User::where('email',$email)->count();
        
            if( $user == 0 ){
               
                  User::insert($user_meta); 
               
             }
             



     }
   
    
}
