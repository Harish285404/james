<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PDF;
use Illuminate\Support\Facades\Mail;
use App\Models\Quantation;
use App\Mail\QuantationMail;
use App\Models\Product;
use App\Models\Store;
use Automattic\WooCommerce\Client;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
 

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

 public function user_quotataion($id)
    {
        $User = Quantation::where('quote_id',$id)->get(['status']);
        if($User[0]->status == '0'){
               return view('quotation-view',['id'=>$id]); 
           }else{
         return redirect('not-found');
           }
    
    }



     public function user_quotataion_check (Request $request)
    {

    $User = Quantation::where('quote_id',$request->id)->get();


    if($User->isNotEmpty()) {
    $User2 = Quantation::where('quote_id',$request->id)->update(['status'=>'1']);

          $details = [
        'title' => 'Mail from Inventory.com',
         'email' => $request->email,
        'body' => 'This is for testing email'
    ];

    // $data = Quantation::where('quote_id',$request->id)->get();
$orders = Quantation::where('quote_id',$request->id)->get();
// dd($orders);
    $data = [];
    $product_name = [];
        $product_price = [];
          $product_ids = [];
            $product_quantity = [];
for($i=0;$i<count($orders);$i++){
  $data['name'] =  $orders[$i]->name;
  $data['email'] =  $orders[$i]->email;
    $data['date'] =  $orders[$i]->date;
  $arr=[];
$arr[$i] = $orders[$i]->total;
$product_name[] = $orders[$i]->product_name;
$product_price[] = $orders[$i]->product_price;
$product_quantity[] = $orders[$i]->product_quantity;
$product_total[] = $orders[$i]->total;
$product_ids[] = $orders[$i]->product_id;
$orderss = Quantation::where('parent_key',$orders[$i]->id)->get();
// dd($orderss);
for($j=0;$j<count($orderss);$j++){
         $arr[$j+1] = $orderss[$j]->total;
 $product_name[$j+1] =  $orderss[$j]->product_name;
 $product_price[$j+1] =  $orderss[$j]->product_price;
 $product_quantity[$j+1] =  $orderss[$j]->product_quantity;
  $product_total[$j+1] = $orderss[$j]->total;
  $product_ids[$j+1] = $orderss[$j]->product_id;

  }
  
// $prices_without_dollar = array_map(function($price) {
//     return (float)str_replace('$', '', $price);
// }, $arr);

// Calculate the sum of the prices
$sum = array_sum($arr);
// echo $sum;
$data['total'] =  $sum;
$data['product_name'] =  $product_name;
$data['product_price'] =  $product_price;
$data['product_quantity'] =  $product_quantity;
$data['quote_id'] = $request->id ;
$data['product_total'] =  $product_total;
}
// dd($product_ids);
$product_stocks = Product::whereIn('id',$product_ids)->get();
for($k=0;$k<count($product_ids);$k++){
 $product_stock = Product::where('id',$product_ids[$k])->get();  
//  echo $product_ids[$k];
         if($product_stock->isNotEmpty()) {
	   
	   $store = $product_stock[0]->store;
    
    $product_id = explode(',',$product_stock[0]->product_id);
  
    $stores = explode(',',$store);
    //  dd($stores);
    $count = count($stores);
	   
	    if($count < 2){

    if($product_stock[0]->product_type == 'simple'){
	 $storess = Store::where('id',$stores[0])->get();
        // print_r($stores);
            $woocommerce = new Client(
              $storess[0]->store_url,
              $storess[0]->key,
              $storess[0]->secret_key,
              [
                'version' => 'wc/v3',
              ]
            );
              $current = $product_stock[0]->current_stock - $product_quantity[$k];
       
      $total = $product_stock[0]->stock_quantity - $product_quantity[$k];
      
        $update_data = [
            "manage_stock" => true,
            "stock_quantity" => $current
          ];
       
          $result1 = $woocommerce->put('products/'.$product_id[0], $update_data);
      

       
      $productt_data = Product::where('id', $product_ids[$k])->update(['current_stock' => $current, 'stock_quantity' => $total]);
	
	
	}else{
	   //  echo "bundle";
        $pids = explode(',',$product_stock[0]->bundle_child_id);
                $storess = Store::where('id',$stores[0])->get();
        // print_r($stores);
            $woocommerce = new Client(
              $storess[0]->store_url,
              $storess[0]->key,
              $storess[0]->secret_key,
              [
                'version' => 'wc/v3',
              ]
            );
            $min = [];
            for($d=0;$d<count($pids);$d++){
                $p_id = Product::where('product_id',$pids[$d])->get();
              $current = $p_id[0]->current_stock - $product_quantity[$k];
       
      $total = $p_id[0]->stock_quantity - $product_quantity[$k];
      
        $update_data = [
            "manage_stock" => true,
            "stock_quantity" => $current
          ];
       
          $result1 = $woocommerce->put('products/'.$pids[$d], $update_data);
      
         $min[]  = $current;
      
       
      $productt_data = Product::where('product_id', $pids[$d])->update(['current_stock' => $current, 'stock_quantity' => $total]);
      //$productt_datas = Product::where('id', $product_ids[$k])->update(['stock_quantity' =>$total ]);
            }
            // print_r($min);
            $min_value =  min($min);
            $main_product = $product_stock[0]->stock_quantity - $product_quantity[$k];
            
    $productt_dataa = Product::where('id', $product_ids[$k])->update(['current_stock' => $min_value,'stock_quantity'=>$main_product]);
	
	}

}else{
    if($product_stock[0]->product_type == 'simple'){
	

	
   $stores = explode(',', $product_stock[0]->store);
    //   $product_idss = explode(',', $product_stock[0]->product_id);
    $store = Store::whereIn('id', $stores)->get();
// dd($product_ids);
    $ss = 0;
$current_stockss = [];
    for($ss=0;$ss<count($stores);$ss++) {
                 // foreach ($product as $products) {
				 
			 $sc  = $ss;


        $storess = Store::where('id',$stores[$ss])->get();
        // print_r($store);
            $woocommerce = new Client(
              $storess[0]->store_url,
              $storess[0]->key,
              $storess[0]->secret_key,
              [
                'version' => 'wc/v3',
              ]
            );
 

      $new_bundled_items = [];
    //   $pp = Product::where('id',$product_ids[$k])->get();
    //   dd($pp);
      $p_ids = explode(',',$product_stock[0]->product_id);
     
 $productIds = explode(',', $product_stock[0]->product_id);
    
    $productIds = array_map('intval', $productIds);

  $pairs = [];
$pairs_parent = [];
// Iterate through the product IDs and check for pairs
foreach ($productIds as $productId) {
    // Check if this ID exists in the product table
    $product = Product::where('product_id',$productId)->get();
//  
 
  foreach ($product as $products) {
    
            $pairs[] = $products->product_id;
           
    }
}	
 $store_dataa = implode('-',$pairs);

$array3 = explode('-', $store_dataa);

// dd($productIds);

      $t = 0;
      $current_stock = [];
$stock_quantity = [];
      $productids = [];
	  
	  
	  
	  
	   $test = Product::whereIn('product_id', $array3)->get(['current_stock', 'stock_quantity', 'product_id','id','adjustment_stock']);

      
        $db_stock_quantity = [];
        $db_product_id = [];
        $new_bundled_items = [];
        $adjustment_stock = [];
        $product_data = [];



          $bb = 0;
          foreach($test as $b){
		       
			            $productid = explode(',', $b->product_id);
						 $stock_quantitys = explode(',', $b->stock_quantity);
						 $array32 = explode(',', $b->current_stock);
					$current_stock[] =   $array32[$sc];
					// print_r($array32);
					$productids[] =   $productid[$sc];
					$stock_quantity[]=$stock_quantitys[$sc];

					  $product_data[$bb]['current_stock'] = $array32[$sc];
					  $product_data[$bb]['product_id'] = $productid[$sc];
					  $product_data[$bb]['stock_quantity'] = $stock_quantitys[$sc];
					  $product_data[$bb]['id'] = $b->id;
					//   $product_data[$bb]['adjustment_stock'] = $b->adjustment_stock;
					  // $star[$y]['id'] = $test[$b]['product_id'];
					$db_stock_quantity[] = $b->stock_quantity;
					// $adjustment_stock[] = $b->adjustment_stock;
					$db_product_id[] = $b->product_id;

          $bb++;
		  
		  }

 
				 
		$current_stockss[$ss]['data'] = $product_data;		 
				 
				 
	//}
	
	}
	      $star = [];
     $rr = 0;
     $st = 1;
     for ($rr = 0; $rr < count($current_stockss); $rr++) {
      // $store = Store::where('id',$st)->get();
      // print_r($store[$rr]->store_url);
            $woocommerce1 = new Client(
              $store[$rr]->store_url,
              $store[$rr]->key,
              $store[$rr]->secret_key,
              [
                'version' => 'wc/v3',
              ]
            );
      for ($y = 0; $y < count($current_stockss[$rr]); $y++) {

       

          $star[0]['product_id'] = $current_stockss[$rr]['data'][$y]['product_id'];
          $star[0]['current_stock'] = $current_stockss[$rr]['data'][$y]['current_stock'];
          $star[0]['stock_quantity'] = $current_stockss[$rr]['data'][$y]['stock_quantity'];
        //   $star[0]['adjustment_stock'] = $current_stockss[$rr]['data'][$y]['adjustment_stock'];
          $star[0]['id'] = $current_stockss[$rr]['data'][$y]['id'];
      }
      // print_r($star[0]['product_id']);
      $z =0;

        $currentt =    $star[$z]['current_stock'] - $product_quantity[$k];
        $totalt =  $star[$z]['stock_quantity'] - $product_quantity[$k];
       
      // echo $currenttt;
      $DB_PRODUCT_STOCK[] = $currentt;
     $DB_PRODUCT_STOCKs = implode(',',$DB_PRODUCT_STOCK);
    //   dd($DB_PRODUCT_STOCKs);
    //   print_r($DB_PRODUCT_STOCK);
    // echo $DB_PRODUCT_STOCKs;
      $producttt = Product::where('id', $star[$z]['id'])->update(['current_stock' => $DB_PRODUCT_STOCKs, 'stock_quantity' => $DB_PRODUCT_STOCKs]);



      $update_data = [
        "manage_stock" => true,
        "stock_quantity" => $currentt
      ];

     

     $result1 = $woocommerce1->put('products/'.$star[$z]['product_id'], $update_data);

      $test_data = Product::where('id', $star[$z]['id'])->get();

       //  $productt = Product::where('id', $product_ids[$k])->update(['current_stock' => $test_data[0]->current_stock, 'stock_quantity' => $test_data[0]->current_stock]);
//          echo "<pre>";
//   print_r($test_data);
  $st++;
  }
//   echo "both simple";
  
  
	}else{
// 		echo"both bundle";
		
		
		$stores = explode(',', $product_stock[0]->store);
    //   $product_idss = explode(',', $product_stock[0]->product_id);
    $store = Store::whereIn('id', $stores)->get();
    $ss = 0;
$current_stockss = [];

$restp = [];
$rests = [];
        for($s=0;$s<count($stores);$s++){
          // foreach ($product as $products) {
 $sc  = $s;
// Session::put('s', $s);

    

        $storess = Store::where('id',$stores[$s])->get();
        // print_r($store);
            $woocommerce = new Client(
              $storess[0]->store_url,
              $storess[0]->key,
              $storess[0]->secret_key,
              [
                'version' => 'wc/v3',
              ]
            );
 

      $new_bundled_items = [];
      $pp = Product::where('id', $product_ids[$k])->get();
      $p_ids = explode(',',$pp[0]->product_id);
      
    //   dd($p_ids);
     
 $productIds = explode(',', $product_stock[0]->bundle_child_id);
    
    $productIds = array_map('intval', $productIds);

   $pairs = [];
$pairs_parent = [];
// Iterate through the product IDs and check for pairs
foreach ($productIds as $productId) {
    // Check if this ID exists in the product table
    $product = Product::where('product_id',$productId)->get();
//  
 
   foreach ($product as $products) {
    
            $pairs[] = $products->product_id;
           
    }
}
 $store_dataa = implode('-',$pairs);

$array3 = explode('-', $store_dataa);
// dd($array3);
      $t = 0;
      $current_stock = [];
$stock_quantity = [];
      $productids = [];
    //   foreach ($array3 as $p) {
   
        $test = Product::whereIn('product_id', $array3)->get(['current_stock', 'stock_quantity', 'product_id','id','adjustment_stock']);
        // dd($test);
       
        // dd($bundle);
        $db_stock_quantity = [];
        $db_product_id = [];
        $new_bundled_items = [];
        $adjustment_stock = [];
        $product_data = [];

        // for($b=0;$b<count($test);$b++){
        $bb = 0;
        $tests = [];
        $btest = [];
        $btestid = [];
        $btest= [];
          foreach($test as $b){
            // $sc = Session::get('s');
            // echo $sc;
             $productid = explode(',', $b->product_id);
             $stock_quantitys = explode(',', $b->stock_quantity);
             $array32 = explode(',', $b->current_stock);
        $current_stock[] =   $array32[$sc]-$product_quantity[$k];
        // print_r($array32[0]);
      
     $tests[] = $b->current_stock;
    //  $stests[] = $b->current_stock;
      $btest[] = $b->stock_quantity ;
        $productids[] =   $productid[$sc];
        $stock_quantity[]=$stock_quantitys[$sc]-$product_quantity[$k];

          $product_data[$bb]['current_stock'] = $array32[$sc]-$product_quantity[$k];
          $product_data[$bb]['product_id'] = $productid[$sc];
          $product_data[$bb]['stock_quantity'] = $stock_quantitys[$sc]-$product_quantity[$k];
          $product_data[$bb]['id'] = $b->id;
          $btestid[]=$b->id;
        //   $product_data[$bb]['adjustment_stock'] = $b->adjustment_stock;
          // $star[$y]['id'] = $test[$b]['product_id'];
        $db_stock_quantity[] = $b->stock_quantity;
        // $adjustment_stock[] = $b->adjustment_stock;
        $db_product_id[] = $b->product_id;
        // $tests[]= $product_data[$bb]['current_stock'];

$bb++;
        // Prepare the data for the update
      }



      $rest = min($current_stock);
         $rest1 = min($stock_quantity);
    //   echo "<pre>";
      $current_stockss[$s]['data'] = $product_data;
      $current_stockss[$s]['min'] = $rest;
// dd($tests);

// dd($btest);
$restp[] = $rest;
$rests[] = $rest1;
// echo "<pre>";
// print_r($product_data);
// echo "<pre>";
// print_r($current_stock);
// $ty = 0;
foreach($product_data as $s_data) {
      $update_data = [
        "manage_stock" => true,
        "stock_quantity" => $s_data['current_stock'],
      ];

    //  print_r($update_data);

     $result1 = $woocommerce->put('products/'.$s_data['product_id'], $update_data);

}
}
// dd($product_data);
$resultArray = [];
$resultArray1 = [];
$o = 0;
// print_r($rests);
$current_min  = implode(',',$restp);
$stock_min = implode(',',$rests);
// echo $stock_min;
foreach ($tests as $entry) {
    // Split the entry into two numbers
    list($number1, $number2) = explode(',', $entry);
     list($number11, $number22) = explode(',', $btest[$o]);
    // Subtract 50 from each number
    $newNumber1 = $number1 - $product_quantity[$k];
    $newNumber2 = $number2 - $product_quantity[$k];
    
    $newNumber11 = $number11 - $product_quantity[$k];
    $newNumber22 = $number22 - $product_quantity[$k];
//   echo $number11;
    // Combine the updated numbers back into a string
    $updatedEntry = $newNumber1 . ',' . $newNumber2;
    
    $updatedEntry2 = $newNumber11 . ',' . $newNumber22;
    //  echo $updatedEntry;
    // echo $updatedEntry2;
// echo $o;
//   Product::where('id',$btestid[$o])->update(['current_stock'=>$updatedEntry,'stock_quantity'=>$updatedEntry2]);
    // Add the updated entry to the result array
    $resultArray[] = $updatedEntry;
    $resultArray1[] = $updatedEntry2;
    $o++;
}
for($a = 0 ; $a < count($resultArray1);$a++) {
     Product::where('id',$btestid[$a])->update(['current_stock'=>$resultArray[$a],'stock_quantity'=>$resultArray1[$a]]);
}

Product::where('id',$product_ids[$k])->update(['current_stock'=>$current_min,'stock_quantity'=>$stock_min]);
		
	
	}


}		

}   
//for (if isNotEmpty)   
}
// dd();
 // $array = json_decode ( $data  , true);

    //   dd($product_stocks);

        $pdf = PDF::loadView('testmail', ['details'=>$data]);
   
      Mail::send('quotationusermail', $details, function($message)use($details, $pdf) {
                    $message->to($details['email'])
                       ->subject($details["title"])
                    ->attachData($pdf->output(), "text.pdf");
                });

       return redirect()->back()->with('message', 'Please check email');
  }else{

           return redirect()->back()->with('message', 'Please enter correct email');
    }

    }

 public function user_R_quotataion($id)
    {  
          $User = Quantation::where('quote_id',$id)->get(['status']);
          if($User[0]->status == '0'){
              return view('quotation-view-reject',['id'=>$id]);
           }else{
         return redirect('not-found');
           }

   
    }

   public function users_quote_check(Request $request)
    {  

    $User = Quantation::where('quote_id',$request->id)->get();

     if($User->isNotEmpty()) {

   $User2 = Quantation::where('quote_id',$request->id)->update(['status'=>'2','reason'=>$request->textarea]);
   return redirect()->back()->with('message', 'Quote rejected.');
}else{

return redirect()->back()->with('message', 'Please enter correct email');
}

    }
    
    }


