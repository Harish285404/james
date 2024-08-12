<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Categories;
use App\Models\Product;
use App\Models\Sale;
use DataTables;
use Hash;
use DB;
use PDF;
use Carbon\Carbon;
use File;
use Automattic\WooCommerce\Client;
use Illuminate\Support\Facades\Mail;
use App\Models\Store;
use App\Models\Bundle;
use App\Models\Order;
use App\Models\Meta_details;
use App\Models\Quantation;
use App\Models\Invoice;
use App\Models\Purchase;
use App\Mail\QuantationMail;
use DateTime;
use Session;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{
  public function admin()
  {
    if (request()->start_date || request()->end_date) {

      $start_date = Carbon::parse(request()->start_date)->toDateTimeString();
      $end_date = Carbon::parse(request()->end_date)->toDateTimeString();
      $Category = Categories::whereBetween('created_at', [$start_date, $end_date])->count();
      $Product = Product::whereBetween('created_at', [$start_date, $end_date])->count();
      $Sale = Order::whereBetween('date_created', [$start_date, $end_date])->sum('subtotal');


      $data = DB::table('orders')
        ->join('products', 'orders.product_id', '=', 'products.product_id')
        ->select('products.product_id as product_id', 'products.title as title', 'products.sales_price as sales_price', 'products.retail_price as retail_price', 'orders.status as status', \DB::raw("sum(orders.subtotal) as subtotal"), \DB::raw("count(orders.product_id) as count"))

        ->groupBy('products.product_id', 'products.title', 'products.sales_price', 'products.retail_price', 'orders.status')
        ->whereBetween('orders.date_created', [$start_date, $end_date])
        ->get();



      return view('Admin.dashboard', ['Category' => $Category, 'Product' => $Product, 'data' => $data, 'Sale' => $Sale]);
    } else {
      $Category = Categories::count();
      $Product = Product::count();

      $Sale = Order::sum('subtotal');

      $data = DB::table('orders')
        ->join('products', 'orders.product_id', '=', 'products.product_id')
        ->select('products.product_id as product_id', 'products.title as title', 'products.sales_price as sales_price', 'products.retail_price as retail_price', 'orders.status as status', \DB::raw("sum(orders.subtotal) as subtotal"), \DB::raw("count(orders.product_id) as count"))

        ->groupBy('products.product_id', 'products.title', 'products.sales_price', 'products.retail_price', 'orders.status')

        ->get();

      //   dd( $data);
      return view('Admin.dashboard', ['Category' => $Category, 'Product' => $Product, 'data' => $data, 'Sale' => $Sale]);
    }
  }



  public function addcategory()
  {
    $store = Store::get();
    return view('Admin.addcategory',['store'=>$store]);
  }

  public function add_category(Request $request)
  {

    // $request->validate([
    //   'name' => 'required',
    //   'image' => 'required',
    //   'description' => 'required',
    // //   'status' => 'required|in:"1", "2"',

    // ]);

    //   dd($request);
    try {
        
        if ($request->image) {

        $image = $request->file('image');

        $name = time() . '.' . $image->getClientOriginalExtension();

        $destinationPath = 'Admin/images';

        $image->move($destinationPath, $name);
        
        $category_image = 'https://inventory-management.chainpulse.tech/Admin/images/' . $name;
     
        $data = [
          'name' => $request->name,
          'description' => $request->description,
          'image' => [
            'src' => $category_image
          ]
        ];

        //$result = $woocommerce->post('products/categories', $data);
       
      }
      elseif($request->image == null) {


        $data = [
          'name' => $request->name,
          'description' => $request->description,

        ];
      }
         $store_data = $request->input('store');
        $storess = Store::whereIn('id',$store_data)->get();
        //  dd($store);
         $stores = implode(',',array_values($request->input('store')));
      
        // for($s=0;$s<count($store_data);$s++){
            foreach($storess as $store){

      $woocommerce = new Client(
        $store->store_url,
        $store->key,
        $store->secret_key,
        [
          'version' => 'wc/v3',
            'timeout' => 400 // curl timeout
        ]
      );
      
      //$store->store_url;
      

        $result = $woocommerce->post('products/categories', $data);
        $category_id[] = $result->id;
       
        
    }
    $category_ids = implode(',',$category_id);
       if($result->image == null){
        $category = new Categories;
        $category->name = $result->name;
        $category->category_id = $category_ids;
        $category->description = $result->description;
        $category->store = $stores;
        // $category->status = $request->status;
        $category->image = 'woocommerce-placeholder.png';
        $category->save();
        
        
      }else{
           $category = new Categories;
        $category->name = $result->name;
        $category->category_id = $category_ids;
        $category->description = $result->description;
        $category->store = $stores;
        // $category->status = $request->status;
        $category->image = $result->image->name;
        $category->save();
      }
       for($s=0;$s<count($store_data);$s++){
        $meta = new Meta_details;
        $meta->c_id = $category->id;
        $meta->category_id = $category_id[$s];
        $meta->store_id = $store_data[$s];
        $meta->save();
        
        }
      return redirect()->back()->with('message', 'Category Successfully Inserted!');
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }

  }


  public function categorylist()
  {
       $store = Store::get();
       
    $data = Categories::all();

    return view('Admin.categorylist', ['data' => $data]);
  }

  public function editcategory(Request $request, $id)
  {
      $store = Store::get();
    $data = Categories::find($id);
    // $data = compact('data');
    return view('Admin.editcategory',['data'=>$data,'store'=>$store]);
  }

  public function update_category(Request $request)
  {
    $cat_image = Categories::where('id', $request->id)->get(['image']);
    
    $meta = Meta_details::where('c_id',$request->id)->get(['category_id','store_id']);

    if ($request->image) {

      $image = $request->file('image');

      $name = time() . '.' . $image->getClientOriginalExtension();

      $destinationPath = 'Admin/images';

      $image->move($destinationPath, $name);
      $category_image = 'https://inventory-management.chainpulse.tech/Admin/images/' . $name;
      $data = [
        'name' => $request->name,
        'description' => $request->description,
        'image' => [
          'src' => $category_image
        ]
      ];
    } else {
      $data = [
        'name' => $request->name,
        'description' => $request->description,

      ];
    }
    try {
        
 
        for($s=0;$s<count($meta);$s++){
             $store = Store::where('id',$meta[$s]->store_id)->get();
        
      $woocommerce = new Client(
        $store[0]->store_url,
        $store[0]->key,
        $store[0]->secret_key,
        [
          'version' => 'wc/v3',
            'timeout' => 400 // curl timeout
        ]
      );
      $result = $woocommerce->put('products/categories/'.$meta[$s]->category_id, $data);
            }
    
     if($result->image == null){
        $category = Categories::find($request->id);
        $category->name = $result->name;
        $category->description = $result->description;
        $category->save();
      }else{
           $category =Categories::find($request->id);
        $category->name = $result->name;
        $category->description = $result->description;
        $category->image = $result->image->name;
        $category->save();
      }


      return redirect()->back()->with('message', 'Category Successfully Updated!');
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function singlecategoryview($id)
  {

    $data = Categories::find($id);
    $data = compact('data');

    return view('Admin.singlecategoryview')->with($data);
  }

   public function delete_Category($id)
  {
    $meta = Meta_details::where('c_id',$id)->get(['category_id','store_id']);
    
    for($s=0;$s<count($meta);$s++){
             $store = Store::where('id',$meta[$s]->store_id)->get();

    $woocommerce = new Client(
      $store[0]->store_url,
      $store[0]->key,
      $store[0]->secret_key,
      [
        'version' => 'wc/v3',
      ]
    );

    $woocommerce->delete('products/categories/' . $meta[$s]->category_id, ['force' => true]);
      }

    $user = Categories::find($id)->delete();
    
     $category_id = Meta_details::where('c_id', $id)->delete();

    return redirect()->back()->with('message', 'Category Deleted Successfully!');
  }



  public function addproduct()
  {
     $store = Store::get();
     
    $catname = Categories::select('id', 'name')->get();

    // $catname = compact('catname');

    return view('Admin.addproduct',['catname'=>$catname,'store'=>$store]);
  }

  public function add_grouped_product()
  {
     $store = Store::get();
    $catname = Categories::select('id', 'name')->get();

    // $catname = compact('catname');

    return view('Admin.add_group_product',['catname'=>$catname,'store'=>$store]);
  }

  public function add_product(Request $request)
  {


// Add two years to the given date
$nextTwoYears = date('Y-m-d', strtotime($request->sale_from . ' + 2 years'));

$pastTwoYears = date('Y-m-d', strtotime($nextTwoYears . ' - 2 years'));



 $dataa  = Categories::whereIn('category_id',array_values($request->input('category')))->get();
    
    $length = count($dataa);
 for($t=0;$t<$length;$t++){ 

       $v[] = $dataa[$t]->category_id;
    
 }
    $varient = implode('-',$v);
      $store_data = $request->input('store');
        $storess = Store::whereIn('id',$store_data)->get();
        //  dd($store);
         $stores = implode(',',array_values($request->input('store')));

         if(count($store_data) == 2 ){

    $total_quantity = '0,0';
  }elseif(count($store_data) == 3){
      $total_quantity = '0,0,0';
  }else{
     $total_quantity = '0';
  }

    if ($request->image) {

      $image = $request->file('image');

      $name = time() . '.' . $image->getClientOriginalExtension();

      $destinationPath = 'Admin/images';

      $image->move($destinationPath, $name);
      $category_image = 'https://inventory-management.chainpulse.tech/Admin/images/' . $name;
    }
    $categories = implode(',', array_values($request->input('category')));
    // dd($categories);

    $i = 0;
    $cat = explode(',',$categories);
    foreach ($cat as $image) {
      $category_id[$i]['id'] = $image;
      $i++;
    }
    // dd($category_id);
     $data = [
      'name' => $request->name,
      'type' => 'simple',
      'status' => $request->status,
      'sku' => $request->Sku,
      // 'price' =>$request->cost_price,
      'regular_price' => $request->retail_price,
      'short_description' => $request->description,
      'sale_price' => $request->sales_price,
      'manage_stock' => true,
      'stock_quantity' => '1',//$request->total_quantity,
      'date_on_sale_from' => date('Y-m-d 00:00:00', strtotime($pastTwoYears)),
      'date_on_sale_to' => date('Y-m-d 23:59:59', strtotime($nextTwoYears)),
      'categories' => $category_id,
      'images' => [
        [
          'src' => $category_image
        ]

      ]
    ];

    // dd($data);
    try {
     foreach($storess as $store){

      $woocommerce = new Client(
        $store->store_url,
        $store->key,
        $store->secret_key,
        [
          'version' => 'wc/v3',
            'timeout' => 400 // curl timeout
        ]
      );

   
    // dd($data);
    
      $result = $woocommerce->post('products', $data);
      // dd($result);
       $product_id[] = $result->id;
       $stock_quantity[] = $result->stock_quantity;
}

      $quantity=implode(',',$stock_quantity);
    $product_ids = implode(',',$product_id);
    
    // if($result->date_on_sale_from == null){
    //     $sale_price = $result->regular_price;
    // }else{
    //     $sale_price = $result->sale_price;
    // }
      $product = new Product;
      $product->title = $result->name;
      $product->product_id = $product_ids;
      $product->product_type = $result->type;
      $product->category_id = $categories;
      $product->description = strip_tags($result->short_description);
      $product->sku = $result->sku;
      $product->varient =$varient;
      $product->sales_price =  $result->sale_price;
      // $product->cost_price =  $result->price;
      $product->sales_from = $pastTwoYears;
      $product->sales_to = $nextTwoYears;
      $product->retail_price = $result->regular_price;
      $product->stock_quantity = $quantity;
      $product->current_stock = $quantity;
      $product->total_quantity = $total_quantity;
      $product->status = $request->status;
      $product->store = $stores;
      $product->image = $result->images[0]->name;
      $product->save();
    
     for($s=0;$s<count($store_data);$s++){
        $meta = new Meta_details;
        $meta->p_id = $product->id;
        $meta->product_id = $product_id[$s];
        $meta->store_id = $store_data[$s];
        $meta->save();
     }
      return redirect()->back()->with('message', 'Product Successfully Inserted!');
    } catch (\Exception $e) {
      // dd( $e->getMessage());
      return redirect()->back()->with('error', $e->getMessage());
    }

    // dd($result);

  }

public function add_group_product(Request $request)

  {

// Add two years to the given date
$nextTwoYears = date('Y-m-d', strtotime($request->sale_from . ' + 2 years'));

$pastTwoYears = date('Y-m-d', strtotime($nextTwoYears . ' - 2 years'));

    if ($request->image) {

      $image = $request->file('image');

      $name = time() . '.' . $image->getClientOriginalExtension();

      $destinationPath = 'Admin/images';

      $image->move($destinationPath, $name);
      $category_image = 'https://inventory-management.chainpulse.tech/Admin/images/' . $name;
    }
    
     $dataa  = Categories::whereIn('category_id',array_values($request->input('category')))->get();
    
    $length = count($dataa);
 for($t=0;$t<$length;$t++){ 

       $v[] = $dataa[$t]->category_id;
    
 }
    $varient = implode('-',$v);
    $categories = implode(',', array_values($request->input('category')));
      $cat = explode(',',$categories);
   
 $i = 0;
    foreach ($cat as $image) {
      $category_id[$i]['id'] = $image;
      $i++;
    }
  $gcategories = implode(',', array_values($request->input('gproduct')));
  
  $gcat = explode(',',$gcategories);

 $product_quantity = implode(',', array_values($request->input('product_quantity')));

  $productqty = explode(',',$product_quantity);

  // dd($productqty);

  $stores = implode(',',array_values($request->input('store')));

  if(count($request->input('store')) > 1 ){

    $total_quantity = '0,0';
  }else{
     $total_quantity = '0';
  }
   $k = 0;
   $store_data = [];
    // foreach ($gcat as $product_id) {
     foreach ($gcat as $product_id) {
        // Retrieve the product data from your database or another source
        $productData = Meta_details::where('product_id', $product_id)->first();
$store_data[$k]['store'] = $productData->store_id;
$store_data[$k]['product_id'] = $productData->product_id;
$k++;
}

    $store_dataa = $request->input('store');
         $storess = Store::whereIn('id',$store_dataa)->get();
     
//   print_r($storess);


// Initialize separate arrays for each store
$store1Array = [];
$store2Array = [];


try{
  foreach($storess as $store){
     $tt = 0;
    foreach ($store_data as $item) {
    if ($item['store'] == $store->id) {
        $store1Array[$tt]['product_id'] = $item['product_id'];
         $store1Array[$tt]['quantity_min'] =  $productqty[$tt];
          $store1Array[$tt]['quantity_max'] = $productqty[$tt];
         $tt++;
    } 
   
}
   // dd($store1Array);

        $store = Store::where('id', $store->id)->first();

    
        $existingProduct = $this->getProductBySKU($store, $request->Sku);

        if ($existingProduct) {
            // Handle the case where the SKU is already in use
            continue;
        }

        // Build the product data to be added
        $data = [
            'name' => $request->name,
            'type' => 'bundle',
            'sku' => $request->Sku,
            'regular_price' => $request->retail_price,
            'short_description' => $request->description,
            'sale_price' => $request->sales_price,
            'status' => $request->status,
            'date_on_sale_from' => date('Y-m-d 00:00:00', strtotime($pastTwoYears)),
            'date_on_sale_to' => date('Y-m-d 23:59:59', strtotime($nextTwoYears)),
            'bundled_items' => $store1Array,
            'categories' => $category_id,
            'images' => [
                [
                    'src' => $category_image,
                ],
            ],
        ];

        $woocommerce = new Client(
            $store->store_url,
            $store->key,
            $store->secret_key,
            [
                'version' => 'wc/v3',
                'timeout' => 400,
            ]
        );

        // Add the product to WooCommerce
        $result = $woocommerce->post('products', $data);
        // echo "<pre>";
        // print_r($result);
         $line_items = $result->bundled_items;


       $product_id = [];
$product_idd[] = $result->id;
$bundle_stock_quantity[] = $result->bundle_stock_quantity;

      for ($j = 0; $j < count($line_items); $j++) {
        $Bundle = new Bundle;
        $Bundle->product_id = $result->id;
        $Bundle->parent_id = $line_items[$j]->bundled_item_id;
        $Bundle->child_id = $line_items[$j]->product_id;
        $Bundle->save();
        $child_id[] = $line_items[$j]->product_id;
        $quantity_max[] = $line_items[$j]->quantity_max;
      }
       
     sleep(1);   // ... The rest of your code for processing the result ...

    }
// if($result->date_on_sale_from == null){
//         $sale_price = $result->regular_price;
//     }else{
//         $sale_price = $result->sale_price;
//     }
        // dd($data); 
    $quantity = implode(',',$bundle_stock_quantity);
    $quantity_maxs = implode(',',$quantity_max);
     $product_ids = implode(',', $product_idd);
      $child_ids = implode(',', $child_id);
      $product = new Product;
      $product->title = $result->name;
      $product->product_id = $product_ids;
      $product->product_type = $result->type;
       $product->varient =$varient;
      $product->bundle_child_id = $child_ids;
      $product->category_id = $categories;
      $product->description = strip_tags($result->short_description);
      $product->sku = $result->sku;
      $product->sales_price =  $result->sale_price;
      $product->sales_from = $pastTwoYears;
      $product->sales_to = $nextTwoYears;
      $product->retail_price = $result->regular_price;
      $product->product_quantity = $quantity_maxs;
      $product->stock_quantity = $quantity;
      $product->current_stock = $quantity;
      $product->total_quantity = $total_quantity;
      $product->status = $request->status;
      $product->store = $stores;
      $product->image = $result->images[0]->name;
      $product->save();
      
      // dd($product);
        
 foreach ($store_data as $item) {
        $meta = new Meta_details;
        $meta->p_id = $product->id;
        $meta->product_id =$item['product_id'];
        $meta->store_id = $item['store'];
        $meta->save();
  }
      return redirect()->back()->with('message', 'Product Successfully Inserted!');
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }

    // dd($result);

}

 public function update_bundle_product(Request $request)
  {

         if ($request->image) {

      $image = $request->file('image');

      $name = time() . '.' . $image->getClientOriginalExtension();

      $destinationPath = 'Admin/images';

      $image->move($destinationPath, $name);
      $category_image = 'https://inventory-management.chainpulse.tech/Admin/images/' . $name;
    }
    
     $dataa  = Categories::whereIn('category_id',array_values($request->input('category')))->get();
    
    $length = count($dataa);
 for($t=0;$t<$length;$t++){ 

       $v[] = $dataa[$t]->category_id;
    
 }
    $varient = implode('-',$v);
    $categories = implode(',', array_values($request->input('category')));
      $cat = explode(',',$categories);
   
 $i = 0;
    foreach ($cat as $image) {
      $category_id[$i]['id'] = $image;
      $i++;
    }
  $gcategories = implode(',', array_values($request->input('gproduct')));
  
  $gcat = explode(',',$gcategories);
  
   $product_quantity = implode(',', array_values($request->input('product_quantity')));

  $productqty = explode(',',$product_quantity);
// dd($request->input('store_id'));
  $stores = explode(',',$request->input('store_id'));
   $k = 0;
   $store_data = [];
    // foreach ($gcat as $product_id) {
     foreach ($gcat as $product_id) {
        // Retrieve the product data from your database or another source
        $productData = Meta_details::where('product_id', $product_id)->first();
$store_data[$k]['store'] = $productData->store_id;
$store_data[$k]['product_id'] = $productData->product_id;

$k++;
}

    // $store_dataa = $request->input('store_id');
         $storess = Store::whereIn('id',$stores)->get();
     
//   dd($store_data);


// Initialize separate arrays for each store
$store1Array = [];
$store2Array = [];


$p=0;
  foreach($storess as $store){
     $tt = 0;
    foreach ($store_data as $item) {
    if ($item['store'] == $store->id) {
        $store1Array[$tt]['product_id'] = $item['product_id'];
        $store1Array[$tt]['quantity_min'] = $productqty[$tt];
          $store1Array[$tt]['quantity_max'] = $productqty[$tt];
         $tt++;
    } 
   
}
    // dd($store1Array);

    //     if (!$productData) {
    //         // Handle the case where the product data is not found
    //         continue;
    //     }
$edit_product_id = explode(',',$request->input('product_id'));
// dd($edit_product_id);
        $store = Store::where('id', $store->id)->first();

 $woocommerce = new Client(
            $store->store_url,
            $store->key,
            $store->secret_key,
            [
                'version' => 'wc/v3',
                'timeout' => 400,
            ]
        );
        $pp = Product::where('id', $request->id)->get();
        
    $bid =  Bundle::where('product_id', $edit_product_id[$p])->get();
    $t = 0;
    foreach ($bid as $pp) {

      $new_bundled_items[$t]['bundled_item_id'] = $pp->parent_id;
      $new_bundled_items[$t]['product_id'] = $pp->child_id;
      $new_bundled_items[$t]['delete'] = true;

      // Prepare the data for the update
      $t++;
    }

    $update_data = [
      "bundled_items" => $new_bundled_items
    ];

    $result1 = $woocommerce->put('products/' . $edit_product_id[$p], $update_data);

    Bundle::where('product_id', $edit_product_id[$p])->delete();
     if ($request->image) {

      $image = $request->file('image');

      $name = time() . '.' . $image->getClientOriginalExtension();

      $destinationPath = 'Admin/images';

      $image->move($destinationPath, $name);
      $category_image = 'https://inventory-management.chainpulse.tech/Admin/images/' . $name;
         $data = [
            'name' => $request->name,
            'type' => 'bundle',
            'sku' => $request->Sku,
            'regular_price' => $request->retail_price,
            'short_description' => $request->description,
            'sale_price' => $request->sales_price,
            'status' => $request->status,
            'date_on_sale_from' => date('Y-m-d 00:00:00', strtotime($request->sale_from)),
            'date_on_sale_to' => date('Y-m-d 23:59:59', strtotime($request->sale_to)),
            'bundled_items' => $store1Array,
            'categories' => $category_id,
            'images' => [
                [
                    'src' => $category_image,
                ],
            ],
        ];
     }else{
          $data = [
            'name' => $request->name,
            'type' => 'bundle',
            'sku' => $request->Sku,
            'regular_price' => $request->retail_price,
            'short_description' => $request->description,
            'sale_price' => $request->sales_price,
            'status' => $request->status,
            'date_on_sale_from' => date('Y-m-d 00:00:00', strtotime($request->sale_from)),
            'date_on_sale_to' => date('Y-m-d 23:59:59', strtotime($request->sale_to)),
            'bundled_items' => $store1Array,
            'categories' => $category_id,
            // 'images' => [
            //     [
            //         'src' => $category_image,
            //     ],
            // ],
        ];
     }
        // Add the product to WooCommerce
        $result = $woocommerce->put('products/'.$edit_product_id[$p], $data);
        // dd($result);
        
         $line_items = $result->bundled_items;


      $product_id = [];
       $product_idd[] = $result->id;
      for ($j = 0; $j < count($line_items); $j++) {
        $Bundle = new Bundle;
        $Bundle->product_id = $result->id;
        $Bundle->parent_id = $line_items[$j]->bundled_item_id;
        $Bundle->child_id = $line_items[$j]->product_id;
        $Bundle->save();
        $child_id[] = $line_items[$j]->product_id;
         $quantity_max[] = $line_items[$j]->quantity_max;
      }
       
     sleep(1);   // ... The rest of your code for processing the result ...
$p++;
    }
 
// $product_ids = implode(',', $product_idd);
    $child_ids = implode(',', $child_id);
    
    // if($result->date_on_sale_from == null){
    //     $sale_price = $result->regular_price;
    // }else{
    //     $sale_price = $result->sale_price;
    // }
     $quantity_maxs = implode(',',$quantity_max);
    $product = Product::find($request->id);
    $product->title = $result->name;
    // $product->product_id = $product_ids;
    $product->product_type = $result->type;
    $product->bundle_child_id = $child_ids;
    $product->category_id = $categories;
      $product->varient =$varient;
    $product->description = strip_tags($result->short_description);
    $product->sku = $result->sku;
     $product->product_quantity = $quantity_maxs;
    $product->sales_price =  $result->sale_price;
    $product->sales_from = $request->sale_from;
    $product->sales_to = $request->sale_to;
    $product->retail_price = $result->regular_price;
    $product->stock_quantity = $result->bundle_stock_quantity;
    $product->current_stock = $result->bundle_stock_quantity;
    $product->status = $request->status;
    // $product->store = $result->store;
    $product->image = $result->images[0]->name;
    $product->update();

    // dd($result);
    return redirect()->back()->with('message', 'Product Successfully Updated!');
  }

  private function getProductBySKU($store, $sku)
{
    $woocommerce = new Client(
        $store->store_url,
        $store->key,
        $store->secret_key,
        [
            'version' => 'wc/v3',
            'timeout' => 400,
        ]
    );

    $products = $woocommerce->get('products', ['sku' => $sku]);

    if (count($products) > 0) {
        return $products[0];
    }

    return null;
}

// public function add_group_product(Request $request)
//   {

//     // $request->validate([
//     //   'name' => 'required',
//     //   'image' => 'required',
//     //   'description' => 'required',
//     //   'Sku' => 'required',
//     //   'sales_price' => 'required',
//     //   'retail_price' => 'required',
//     // //   'total_quantity' => 'required',
//     // //   'status' => 'required|in:"1", "2"',

//     // ]);
//     if ($request->image) {

//       $image = $request->file('image');

//       $name = time() . '.' . $image->getClientOriginalExtension();

//       $destinationPath = 'Admin/images';

//       $image->move($destinationPath, $name);
//       $category_image = 'https://inventory-management.chainpulse.tech/Admin/images/' . $name;
//     }
//     $categories = implode(',', array_values($request->input('category')));
//     $i = 0;
//     foreach ($request->input('category') as $image) {
//       $category_id[$i]['id'] = $image;
//       $i++;
//     }

//     $gcategories = implode(',', array_values($request->input('gproduct')));

//     $j = 0;
//     foreach ($request->input('gproduct') as $image) {
//       $gcategory_id[$j]['product_id'] = $image;
//       $j++;
//     }

//     // dd($gcategories);

//     $store = Store::where('id', $request->store)->get();

//     $woocommerce = new Client(
//       $store[0]->store_url,
//       $store[0]->key,
//       $store[0]->secret_key,
//       [
//         'version' => 'wc/v3',
//         'timeout' => 400 // curl timeout
//       ]
//     );

//     $data = [
//       'name' => $request->name,
//       'type' => 'bundle',
//       'sku' => $request->Sku,
//       'regular_price' => $request->retail_price,
//       'short_description' => $request->description,
//       'sale_price' => $request->sales_price,
//       'status' => $request->status,
//       'date_on_sale_from' => date('Y-m-d 00:00:00', strtotime($request->sale_from)),
//       'date_on_sale_to' => date('Y-m-d 23:59:59', strtotime($request->sale_to)),
//       'bundled_items' => $gcategory_id,
//       'categories' => $category_id,
//       'images' => [
//         [
//           'src' => $category_image
//         ]

//       ]
//     ];
//     // dd($data);

//     try {
//       $result = $woocommerce->post('products', $data);
//       $line_items = $result->bundled_items;



//       for ($j = 0; $j < count($line_items); $j++) {
//         $Bundle = new Bundle;
//         $Bundle->product_id = $result->id;
//         $Bundle->parent_id = $line_items[$j]->bundled_item_id;
//         $Bundle->child_id = $line_items[$j]->product_id;
//         $Bundle->save();
//         $child_id[] = $line_items[$j]->product_id;
//       }

//       $child_ids = implode(',', $child_id);
//       $product = new Product;
//       $product->title = $result->name;
//       $product->product_id = $result->id;
//       $product->product_type = $result->type;
//       $product->bundle_child_id = $child_ids;
//       $product->category_id = $categories;
//       $product->description = strip_tags($result->short_description);
//       $product->sku = $result->sku;
//       $product->sales_price = $result->sale_price;
//       $product->sales_from = $request->sale_from;
//       $product->sales_to = $request->sale_to;
//       $product->retail_price = $result->regular_price;
//       $product->stock_quantity = $result->bundle_stock_quantity;
//       $product->current_stock = $result->bundle_stock_quantity;
//       $product->status = $request->status;
//       $product->store = $request->store;
//       $product->image = $result->images[0]->name;
//       $product->save();

//       return redirect()->back()->with('message', 'Product Successfully Inserted!');
//     } catch (\Exception $e) {
//       return redirect()->back()->with('error', $e->getMessage());
//     }
//     // dd($result);

//   }

 

  public function productlist()
  {

    $data = Product::all();

    return view('Admin.productlist', ['data' => $data]);
  }


  public function editproduct(Request $request, $id)
  {

    $data = Product::find($id);

    $store = Store::all();
    // dd($store);

    $Categories = Categories::get();

    return view('Admin.editproduct', ['data' => $data, 'Categories' => $Categories, 'store' => $store]);
  }

  public function editbundleproduct(Request $request, $id)
  {

    $data = Product::find($id);

    $store = Store::all();
    // dd($store);

    $Categories = Categories::get();

    return view('Admin.edit_bundle_product', ['data' => $data, 'Categories' => $Categories, 'store' => $store]);
  }

  public function update_product(Request $request)
  {

    $categories = implode(',', array_values($request->input('category')));
    $dataa  = Categories::whereIn('category_id',array_values($request->input('category')))->get();
    
    $length = count($dataa);
 for($t=0;$t<$length;$t++){ 

       $v[] = $dataa[$t]->category_id;
    
 }
    $varient = implode('-',$v);
    $i = 0;
       $cat = explode(',',$categories);
    foreach ($cat as $image) {
      $category_id[$i]['id'] = $image;
      $i++;
    }
 $meta = Meta_details::where('p_id',$request->id)->get(['product_id','store_id']);
   // $store = Store::where('id', $request->store_id)->get();
   
    if ($request->image) {

      $image = $request->file('image');

      $name = time() . '.' . $image->getClientOriginalExtension();

      $destinationPath = 'Admin/images';

      $image->move($destinationPath, $name);
      $category_image = 'https://inventory-management.chainpulse.tech/Admin/images/' . $name;

      $data = [
        'name' => $request->name,
        'type' => 'simple',
        'sku' => $request->Sku,
        'regular_price' => $request->retail_price,
        'short_description' => $request->description,
        'sale_price' => $request->sales_price,
        'manage_stock' => true,
        // 'stock_quantity'=>$request->total_quantity,
        'date_on_sale_from' => date('Y-m-d 00:00:00', strtotime($request->sale_from)),
        'date_on_sale_to' => date('Y-m-d 23:59:59', strtotime($request->sale_to)),
        'categories' => $category_id,
        'images' => [
          [
            'src' => $category_image
          ]

        ]
      ];
    } else {
      $data = [
        'name' => $request->name,
        'type' => 'simple',
        'sku' => $request->Sku,
        'regular_price' => $request->retail_price,
        'short_description' => $request->description,
        'sale_price' => $request->sales_price,
        'manage_stock' => true,
        // 'stock_quantity'=>$request->total_quantity,
        'date_on_sale_from' => date('Y-m-d 00:00:00', strtotime($request->sale_from)),
        'date_on_sale_to' => date('Y-m-d 23:59:59', strtotime($request->sale_to)),
        'categories' => $category_id,

      ];
    }
 try {
 for($s=0;$s<count($meta);$s++){
             $store = Store::where('id',$meta[$s]->store_id)->get();
         $woocommerce = new Client(
        $store[0]->store_url,
        $store[0]->key,
        $store[0]->secret_key,
        [
          'version' => 'wc/v3',
            'timeout' => 400 // curl timeout
        ]
      );
     
   
 
   
      $result = $woocommerce->put('products/' . $meta[$s]->product_id, $data);
   
       $p = Product::where('id', $request->id)->get();
    $product = Product::find($request->id);
    $product->title = $result->name;
    $product->category_id = $categories;
    $product->description = strip_tags($result->short_description);
    $product->sku = $result->sku;
     $product->varient =$varient;
    $product->sales_price =  $result->sale_price;
    $product->sales_from = $request->sale_from;
    $product->sales_to = $request->sale_to;
    $product->retail_price = $result->regular_price;
    $product->status = $request->status;
    $product->image = $result->images[0]->name;
    $product->save();
    
     
        // $meta = new Meta_details;
        // $meta->p_id = $product->id;
        // $meta->product_id = $product_id[$s];
        // $meta->store_id = $store_data[$s];
        // $meta->save();
     
    
    
    } 

     return redirect()->back()->with('message', 'Product Successfully Updated!');
 }
 catch (\Exception $e) {
      throw new \Exception("The error is " . $e->getMessage(), 1);
    }
  }


  public function singleproduct($id)
  {


      $data = Product::find($id);
    
    if($data->store == "1,2"){
         $array = explode('-', $data->varient);
     for ($i = 0; $i < count($array); $i++) {
    //   $meta = Meta_details::where('category_id', $array[$j])->get(['c_id']);
        $category = Categories::where('category_id', $array[$i])->get(['name']);
      $name[] = $category[0]->name;
    }
        
    }else{

    $array = explode(',', $data->category_id);
    // dd($array);
    for ($j = 0; $j < count($array); $j++) {
      $meta = Meta_details::where('category_id', $array[$j])->get(['c_id']);
        $category = Categories::whereIn('id', $meta)->get(['name']);
      $name[] = $category[0]->name;
    }
}
    $Categories = Categories::get();
    return view('Admin.productview', ['data' => $data, 'Categories' => $Categories, 'name' => $name]);
  }

  public function singlebundleproduct($id)
  {

    $data = Product::find($id);
    
    if($data->store == "1,2"){
         $array = explode('-', $data->varient);
     for ($j = 0; $j < count($array); $j++) {
    //   $meta = Meta_details::where('category_id', $array[$j])->get(['c_id']);
        $category = Categories::where('category_id', $array[$j])->get(['name']);
      $name[] = $category[0]->name;
    }

    $array2 = explode(',', $data->bundle_child_id);
    
     $productIds = array_map('intval', $array2);

   $pairs = [];

// Iterate through the product IDs and check for pairs
foreach ($productIds as $productId) {
    // Check if this ID exists in the product table
    $product = Product::where('product_id',$productId)->get();
 
   foreach ($product as $products) {
    
            $pairs[] = $products->product_id;
        
    }
}
 $store_dataa = implode('-',$pairs);

$array3 = explode('-', $store_dataa);
    for ($i = 0; $i < count($array3); $i++) {
        //   $meta = Meta_details::where('product_id', $array2[$i])->get(['p_id']);
      $product = Product::where('product_id', $array3[$i])->get(['title', 'image']);
      $pname[] = $product[0]->title;
      $image[] = $product[0]->image;
    }
        
    }else{

    $array = explode(',', $data->category_id);
     for ($j = 0; $j < count($array); $j++) {
      $meta = Meta_details::where('category_id', $array[$j])->get(['c_id']);
        $category = Categories::whereIn('id', $meta)->get(['name']);
      $name[] = $category[0]->name;
    }

    $array2 = explode(',', $data->bundle_child_id);
    for ($i = 0; $i < count($array2); $i++) {
          $meta = Meta_details::where('product_id', $array2[$i])->get(['p_id']);
      $product = Product::whereIn('id', $meta)->get(['title', 'image']);
      $pname[] = $product[0]->title;
      $image[] = $product[0]->image;
    }
}
    $Categories = Categories::get();
    return view('Admin.bundle-product-view', ['data' => $data, 'Categories' => $Categories, 'name' => $name, 'pname' => $pname, 'image' => $image]);
  }

  public function delete_product($id)
  {
      $users = Product::where('id',$id)->get();
      $stores = explode(',',$users[0]->store);
    //   $store_data = Store::whereIn('id',$stores)->get();
     // $meta = Meta_details::where('p_id',$id)->get(['product_id','store_id']);
     $products =  explode(',',$users[0]->product_id);
    for($s=0;$s<count($stores);$s++){
             $store = Store::where('id',$stores[$s])->get();
// dd($products);
    $woocommerce = new Client(
      $store[0]->store_url,
      $store[0]->key,
      $store[0]->secret_key,
      [
        'version' => 'wc/v3',
      ]
    );
 $woocommerce->delete('products/' . $products[$s], ['force' => true]);
 
      }

    $user = Product::find($id)->delete();
     $category_id = Meta_details::where('p_id', $id)->delete();
    return redirect()->back()->with('message', 'Product Deleted Successfully!');
  }



public function stocklist()
  {
      $data = DB::table('products')
      ->select('products.current_quantity as current_quantity','products.current_adjustment as current_adjustment','products.product_id as product_id', 'products.title as title', 'products.sales_price as sales_price',
       'products.adjustment_stock as adjustment_stock','products.id as id', 'products.current_stock as current_stock')

      ->groupBy('products.current_quantity','products.current_adjustment','products.product_id', 'products.title', 'products.sales_price', 'products.current_stock','products.id','products.adjustment_stock')

      ->get();
      // dd($data);

    return view('Admin.stocklist', ['data' => $data]);
  }




  public function userlist()
  {
      $user = User::where('role','0')->get();

    return view('Admin.userlist',['user'=>$user]);
  }

  public function getData(Request $request)
  {
    $data = user::where('role', '!=', '1')->get();


    return DataTables::of($data)
      ->addIndexColumn()
      ->addColumn('action', function ($row) {
      })
      ->editColumn('created_at', function ($data) {

        return date('F d, Y', strtotime($data->created_at));
      })
      ->rawColumns(['action'])
      ->make(true);
  }


  public function reports(Request $request)
  {

    if ($request->input('store') == '1') {
      $id = '1';
    } elseif ($request->input('store') == '2') {
      $id = '2';
    } else {
      $id = '1';
    }

    if (request()->start_date || request()->end_date) {

      $start_date = request()->start_date;
      $end_date = request()->end_date;


      Session::put('start_date', $start_date);
      Session::put('end_date', $end_date);



      $Product = Product::count();
      $start_date = Carbon::parse(request()->start_date)->toDateTimeString();

      $end_date = Carbon::parse(request()->end_date)->toDateTimeString();


      $store = Store::where('id', $id)->get();

      $woocommerce = new Client(
        $store[0]->store_url,
        $store[0]->key,
        $store[0]->secret_key,
        [
          'version' => 'wc/v3',
        ]
      );

      $days_query = [

        'period' => 'week',
      ];

      $days = $woocommerce->get('reports/sales', $days_query);

      $test = json_decode(json_encode($days[0]->totals), true);
      $dates = [];
      $dates[] = array_keys($test);

      $year_query = [

        'period' => 'year',
      ];

      $year = $woocommerce->get('reports/sales', $year_query);

      $test1 = json_decode(json_encode($year[0]->totals), true);
      $year_dates = [];
      $year_dates[] = array_keys($test1);

      $month_query = [

        'period' => 'month',
      ];

      $month = $woocommerce->get('reports/sales', $month_query);

      $test2 = json_decode(json_encode($month[0]->totals), true);
      $month_dates = [];
      $month_dates[] = array_keys($test2);

      $last_month_query = [

        'period' => 'last_month',
      ];

      $last_month = $woocommerce->get('reports/sales', $last_month_query);

      $test3 = json_decode(json_encode($last_month[0]->totals), true);
      $last_month_dates = [];
      $last_month_dates[] = array_keys($test3);
      //  dd($request->min);


      foreach ($dates[0] as $datess) {

        $monthName[] = date('j M', strtotime($datess));
      }

      $labels = array_values($test);

      for ($y = 0; $y < count($labels); $y++) {

        $days_sales[] =  $labels[$y]['sales'];

        $days_items[] =  $labels[$y]['items'];
      }

      foreach ($month_dates[0] as $month_datess) {

        $month_Name[] = date('j M', strtotime($month_datess));
      }

      $month_dates_labels = array_values($test2);

      for ($a = 0; $a < count($month_dates_labels); $a++) {

        $month_sales[] =  $month_dates_labels[$a]['sales'];

        $month_items[] =  $month_dates_labels[$a]['items'];
      }


      foreach ($last_month_dates[0] as $last_month_datess) {

        $last_month_dates_Name[] = date('j M', strtotime($last_month_datess));
      }

      $last_month_dates_labels = array_values($test3);

      for ($b = 0; $b < count($last_month_dates_labels); $b++) {

        $last_month_sales[] =  $last_month_dates_labels[$b]['sales'];

        $last_month_items[] =  $last_month_dates_labels[$b]['items'];
      }


      foreach ($year_dates[0] as $year_datess) {

        $year_dates_Name[] = date('M', strtotime($year_datess));
      }

      $year_dates_labels = array_values($test1);

      for ($c = 0; $c < count($year_dates_labels); $c++) {

        $year_sales[] =  $year_dates_labels[$c]['sales'];

        $year_items[] =  $year_dates_labels[$c]['items'];
      }


      $data = Product::whereBetween('created_at', [$start_date, $end_date])->get();

      // dd($data);


      return view('Admin.reports', [
        'data' => $data, 'Product' => $Product, 'days' => $days, 'year' => $year, 'month' => $month, 'last_month' => $last_month,
        'monthName' => $monthName, 'days_sales' => $days_sales, 'days_items' => $days_items, 'month_sales' => $month_sales, 'month_items' => $month_items, 'month_Name' => $month_Name,
        'last_month_sales' => $last_month_sales, 'last_month_items' => $last_month_items, 'last_month_dates_Name' => $last_month_dates_Name, 'year_sales' => $year_sales,
        'year_items' => $year_items, 'year_dates_Name' => $year_dates_Name
      ]);
    } else {

      $Product = Product::count();



      $store = Store::where('id', $id)->get();

      $woocommerce = new Client(
        $store[0]->store_url,
        $store[0]->key,
        $store[0]->secret_key,
        [
          'version' => 'wc/v3',
          'timeout' => 400 // curl timeout
        ]
      );

      $days_query = [

        'period' => 'week',
      ];

      $days = $woocommerce->get('reports/sales', $days_query);

      $test = json_decode(json_encode($days[0]->totals), true);
      $dates = [];
      $dates[] = array_keys($test);

      $year_query = [

        'period' => 'year',
      ];

      $year = $woocommerce->get('reports/sales', $year_query);

      $test1 = json_decode(json_encode($year[0]->totals), true);
      $year_dates = [];
      $year_dates[] = array_keys($test1);

      $month_query = [

        'period' => 'month',
      ];

      $month = $woocommerce->get('reports/sales', $month_query);

      $test2 = json_decode(json_encode($month[0]->totals), true);
      $month_dates = [];
      $month_dates[] = array_keys($test2);

      $last_month_query = [

        'period' => 'last_month',
      ];

      $last_month = $woocommerce->get('reports/sales', $last_month_query);

      $test3 = json_decode(json_encode($last_month[0]->totals), true);
      $last_month_dates = [];
      $last_month_dates[] = array_keys($test3);
      //  dd($request->min);


      foreach ($dates[0] as $datess) {

        $monthName[] = date('j M', strtotime($datess));
      }

      $labels = array_values($test);

      for ($y = 0; $y < count($labels); $y++) {

        $days_sales[] =  $labels[$y]['sales'];

        $days_items[] =  $labels[$y]['items'];
      }

      foreach ($month_dates[0] as $month_datess) {

        $month_Name[] = date('j M', strtotime($month_datess));
      }

      $month_dates_labels = array_values($test2);

      for ($a = 0; $a < count($month_dates_labels); $a++) {

        $month_sales[] =  $month_dates_labels[$a]['sales'];

        $month_items[] =  $month_dates_labels[$a]['items'];
      }


      foreach ($last_month_dates[0] as $last_month_datess) {

        $last_month_dates_Name[] = date('j M', strtotime($last_month_datess));
      }

      $last_month_dates_labels = array_values($test3);

      for ($b = 0; $b < count($last_month_dates_labels); $b++) {

        $last_month_sales[] =  $last_month_dates_labels[$b]['sales'];

        $last_month_items[] =  $last_month_dates_labels[$b]['items'];
      }


      foreach ($year_dates[0] as $year_datess) {

        $year_dates_Name[] = date('M', strtotime($year_datess));
      }

      $year_dates_labels = array_values($test1);

      for ($c = 0; $c < count($year_dates_labels); $c++) {

        $year_sales[] =  $year_dates_labels[$c]['sales'];

        $year_items[] =  $year_dates_labels[$c]['items'];
      }

      $data = Product::get();

      return view('Admin.reports', [
        'data' => $data, 'Product' => $Product, 'days' => $days, 'year' => $year, 'month' => $month, 'last_month' => $last_month,
        'monthName' => $monthName, 'days_sales' => $days_sales, 'days_items' => $days_items, 'month_sales' => $month_sales, 'month_items' => $month_items, 'month_Name' => $month_Name,
        'last_month_sales' => $last_month_sales, 'last_month_items' => $last_month_items, 'last_month_dates_Name' => $last_month_dates_Name, 'year_sales' => $year_sales,
        'year_items' => $year_items, 'year_dates_Name' => $year_dates_Name
      ]);
    }
  }



  public function generatePDF()
  {

    $startdate = Session::get('start_date');
    $enddate = Session::get('end_date');
    // dd($enddate);
    if ($startdate || $enddate) {

      $start_date1 = Carbon::parse($startdate)->toDateTimeString();
      $end_date1 = Carbon::parse($enddate)->toDateTimeString();

      $data = DB::table('products')
        ->whereBetween('products.created_at', [$start_date1, $end_date1])
        ->get();

      $current_stock_count = Product::whereBetween('date_created', [$start_date1, $end_date1])->sum('current_stock');
      $product_count = Product::whereBetween('created_at', [$start_date1, $end_date1])->count();
      $sales_count = Product::whereBetween('date_created', [$start_date1, $end_date1])->sum('sales_price');
     $cost_count = Product::whereBetween('date_created', [$start_date1, $end_date1])->sum('cost_price');

      $pdf = PDF::loadView('Admin.pdf', ['data' => $data,'current_stock_count'=>$current_stock_count,'product_count'=>$product_count,'sales_count'=>$sales_count,'cost_count'=>$cost_count]);

      ini_set('memory_limit', '2048M');

      Session::forget('start_date');
       Session::forget('end_date');


      return $pdf->download('inventory-management.pdf');
    } else {

      $data = Product::get();
        $current_stock_count = Product::sum('current_stock');
      $product_count = Product::count();
      $sales_count = Product::sum('sales_price');
       $cost_count = Product::sum('cost_price');
      $pdf = PDF::loadView('Admin.pdf', ['data' => $data,'current_stock_count'=>$current_stock_count,'product_count'=>$product_count,'sales_count'=>$sales_count,'cost_count'=>$cost_count]);
    
      ini_set('memory_limit', '2048M');
      return $pdf->download('inventory-management.pdf');
    }
  }



  public function ReconciliationPDF()
  {
    $startdate = Session::get('start_date');
    $enddate = Session::get('end_date');

    if ($startdate || $enddate) {

      $start_date1 = Carbon::parse($startdate)->toDateTimeString();
      $end_date1 = Carbon::parse($enddate)->toDateTimeString();

      $data = DB::table('products')
        ->whereBetween('products.created_at', [$start_date1, $end_date1])
        ->get();

      $pdf = PDF::loadView('Admin.Reconciliationpdf', ['data' => $data]);

      ini_set('memory_limit', '2048M');
       Session::forget('start_date');
       Session::forget('end_date');
      return $pdf->download('inventory-management.pdf');
    } else {


      $data = Product::get();

      $pdf = PDF::loadView('Admin.Reconciliationpdf', ['data' => $data]);

      ini_set('memory_limit', '2048M');

      return $pdf->download('inventory-management.pdf');
    }
  }

  public function InventoryPDF()
  {
    $startdate = Session::get('start_date');
    $enddate = Session::get('end_date');

    if ($startdate || $enddate) {

      $start_date1 = Carbon::parse($startdate)->toDateTimeString();
      $end_date1 = Carbon::parse($enddate)->toDateTimeString();

      $data = DB::table('products')
        ->whereBetween('products.created_at', [$start_date1, $end_date1])
        ->get();

      $pdf = PDF::loadView('Admin.Inventorypdf', ['data' => $data]);

      ini_set('memory_limit', '2048M');
       Session::forget('start_date');
       Session::forget('end_date');
       
      return $pdf->download('inventory-management.pdf');
    } else {
      $data = Product::get();

      $pdf = PDF::loadView('Admin.Inventorypdf', ['data' => $data]);

      ini_set('memory_limit', '2048M');

      return $pdf->download('inventory-management.pdf');
    }
  }

  public function SalesPDF()
  {
    $data = DB::table('categories')
      ->join('products', 'categories.category_id', '=', 'products.category_id')
      ->select('categories.*', 'products.*')
      ->get();

    $pdf = PDF::loadView('Admin.Salespdf', ['data' => $data]);

    ini_set('memory_limit', '2048M');
    return $pdf->download('inventory-management.pdf');
  }


  public function report_update($id)
  {

    $data = Product::find($id);

    return view('Admin.modal', ['data' => $data]);
  }




  public function profile()
  {
    $id = Auth::user()->id;

    $data = User::where('id', '=', $id)->get();

    $data = compact('data');

    return view('Admin.profile')->with($data);
  }

  public function update(Request $request)
  {

    // dd($request->all());

    $request->validate([
      'first_name' => 'required',
      'last_name' => 'required',
      'phone_number' => 'required|max:10',
      'email' => 'required',


    ]);

    $User = User::find($request->id);
    $User->first_name = $request->first_name;
    $User->last_name = $request->last_name;
    $User->phone_number = $request->phone_number;
    $User->email = $request->email;

    if ($request->image) {

      $image = $request->file('image');

      $name = time() . '.' . $image->getClientOriginalExtension();

      $destinationPath = 'Admin/images';

      $image->move($destinationPath, $name);
      $User->image = $name;
    }

    $update = $User->save();

    if ($update) {
      return redirect()->back()->with('message', 'Profile Successfully Updated!');
    }
  }


  public function changepassword()
  {
    return view('Admin.changepassword');
  }

  public function forgotpassword(Request $request)
  {
    $request->validate([
      'oldpass' => 'required',
      'newpass' => 'min:8|required',
      'cnewpass' => 'min:8|required',
    ]);

    if (Hash::check($request->oldpass, auth()->user()->password)) {
      if (!Hash::check($request->newpass, auth()->user()->password)) {
        if ($request->newpass == $request->cnewpass) {


          $user = User::find(auth()->id());
          $user->update([
            'password' => bcrypt($request->newpass),
            'plane_password' => ($request->newpass)
          ]);

          return redirect()->back()->with('message', 'Password updated successfully!');
        } else {

          return redirect()->back()->with('message', 'Password mismatched!');
        }
      }

      return redirect()->back()->with('message', 'New password can not be the old password!');
    }

    return redirect()->back()->with('message', 'Old password does not matched!');
  }




  public function signout()
  {
    Auth::logout();
    return redirect('/');
  }

  public function addsubcategory(Request $request)
  {
    $id = $request->input('id');


    $ids = [];
    $names = [];
    
    

        $count = count($id);
        if($count > 1){
            $numbers = range(1, $count);
        $ddd = implode(',', $numbers);
         $category = Categories::where('store', $ddd)->get(['category_id', 'name']);
        }else{
            $category = Categories::where('store', $id)->get(['category_id', 'name']);
    //           $store_data = Meta_details::whereIn('store_id',$id)->get(['c_id']);
          
    // $category = Categories::whereIn('id', $store_data)->get(['category_id', 'name']);
        }
    for ($i = 0; $i < count($category); $i++) {
      array_push($ids, $category[$i]->category_id);
      array_push($names, $category[$i]->name);
    }
                 
          
    return response()->json(['success' => true, 'subcategory' => $names, 'ids' => $ids]);
  }

  public function addgroupsubcategory(Request $request)
  {
    $id = $request->input('id');


    $ids = [];
    $names = [];
    $productid = [];
    $productname = [];
    
        $count = count($id);
        if($count > 1){
            $numbers = range(1, $count);
        $ddd = implode(',', $numbers);
         $category = Categories::where('store', $ddd)->get(['category_id', 'name']);
          $product = Product::where('store', $ddd)->where('product_type', 'simple')->get(['product_id', 'title']);
        }else{
            $category = Categories::where('store', $id)->get(['category_id', 'name']);
            //   $store_data = Meta_details::whereIn('store_id',$id)->get(['c_id']);
              
            //   $store_dataa = Meta_details::whereIn('store_id',$id)->get(['p_id']);
          
    // $category = Categories::whereIn('id', $store_data)->get(['category_id', 'name']);
    
     $product = Product::where('store', $id)->where('product_type', 'simple')->get(['product_id', 'title']);
        }
   

    for ($i = 0; $i < count($category); $i++) {
      array_push($ids, $category[$i]->category_id);
      array_push($names, $category[$i]->name);
    }

    for ($j = 0; $j < count($product); $j++) {
      array_push($productid, $product[$j]->product_id);
      array_push($productname, $product[$j]->title);
    }

    return response()->json(['success' => true, 'subcategory' => $names, 'ids' => $ids, 'pid' => $productid, 'pname' => $productname]);
  }

  public function subcategory(Request $request)
  {
      
      
    $id = $request->input('id');
    $product_id = $request->input('product_id');
    //  $array_store = explode(',', $id);
    $product = Product::where('product_id', $product_id)->get(['category_id','varient']);
    if(count($id) < 2){
         $array = explode(',', $product[0]->category_id);
    
        $ids = [];
        $names = [];
        $cids = [];
        for ($j = 0; $j < count($array); $j++) {
    
          array_push($cids, $array[$j]);
        }
    }else{
        $array = explode('-', $product[0]->varient);
    
        $ids = [];
        $names = [];
        $cids = [];
        for ($j = 0; $j < count($array); $j++) {
    
          array_push($cids, $array[$j]);
        }
    }


    $count = count($id);
        if($count > 1){
            $numbers = range(1, $count);
        $ddd = implode(',', $numbers);
         $category = Categories::where('store', $ddd)->get(['category_id', 'name']);
        }else{
            $category = Categories::where('store', $id)->get(['category_id', 'name']);
    //           $store_data = Meta_details::whereIn('store_id',$id)->get(['c_id']);
          
    // $category = Categories::whereIn('id', $store_data)->get(['category_id', 'name']);
        }
    for ($i = 0; $i < count($category); $i++) {
      array_push($ids, $category[$i]->category_id);
      array_push($names, $category[$i]->name);
    }
      

    return response()->json(['success' => true, 'subcategory' => $names, 'ids' => $ids, 'cids' => $cids,'id'=>$id]);
  }


  public function editsubcategory(Request $request)
  {
      
    $id = $request->input('id');
    $product_id = $request->input('product_id');
    $product = Product::where('product_id', $product_id)->get(['category_id', 'bundle_child_id','varient','product_type','product_id','product_quantity']);
    // $count = count($id);
   if($id === "1,2"){

    //  $productt = $product[0]->varient;
    // $array = explode('-', $productt);
   if($product[0]->product_type == 'simple'){
        $bundleid = $product[0]->product_id;
        // $bundleqty = $product[0]->product_quantity;
        // $arrayqty = explode(',', $bundleqty);
    $array3 = explode(',', $bundleid);
    
   }else{
        $productt = $product[0]->varient;
    $array = explode('-', $productt);
         $bundleid = $product[0]->bundle_child_id;
    $productIds = explode(',', $bundleid);
    
    $productIds = array_map('intval', $productIds);

   $pairs = [];

// Iterate through the product IDs and check for pairs
foreach ($productIds as $productId) {
    // Check if this ID exists in the product table
    $product = Product::where('product_id',$productId)->get();
 
   foreach ($product as $products) {
    
            $pairs[] = $products->product_id;
        
    }
}
 $store_dataa = implode('-',$pairs);

$array3 = explode('-', $store_dataa);
$bundqty = [];
 $bundleqty = $product[0]->product_quantity;
        $arrayqty = explode(',', $bundleqty);
         for ($t = 0; $t < count($arrayqty); $t++) {

      array_push($bundqty, $arrayqty[$t]);
    }
  
   }
//   $productIds = [49384, 49382, 967, 969];

 

    $ids = [];
    $names = [];
    $cids = [];
    $bids = [];
    $productid = [];
    $productname = [];
    for ($k = 0; $k < count($array); $k++) {

      array_push($cids, $array[$k]);
    }

    for ($h = 0; $h < count($array3); $h++) {

      array_push($bids, $array3[$h]);
    }
   
  
    }else{
        
    $array = explode(',', $product[0]->category_id);

   if($product[0]->product_type == 'simple'){
        $bundleid = $product[0]->product_id;
    $array2 = explode(',', $bundleid);
    //  $bundleqty = $product[0]->product_quantity;
    //     $arrayqty = explode(',', $bundleqty);
   }else{
       
        $productt = $product[0]->varient;
    $array = explode('-', $productt);
    $bundleid = $product[0]->bundle_child_id;
             $bundleid = $product[0]->bundle_child_id;
    $productIds = explode(',', $bundleid);
    
    $productIds = array_map('intval', $productIds);

   $pairs = [];

// Iterate through the product IDs and check for pairs
foreach ($productIds as $productId) {
    // Check if this ID exists in the product table
    $producty = Product::where('product_id',$productId)->get();
 
   foreach ($producty as $products) {
    
            $pairs[] = $products->product_id;
        
    }
}
 $store_dataa = implode('-',$pairs);

$array3 = explode('-', $store_dataa);
 $bundqty = [];
 $bundleqty = $product[0]->product_quantity;
        $arrayqty = explode(',', $bundleqty);
         for ($t = 0; $t < count($arrayqty); $t++) {

      array_push($bundqty, $arrayqty[$t]);
    }
   }

    $ids = [];
    $names = [];
    $cids = [];
    $bids = [];
    $productid = [];
    $productname = [];
    for ($k = 0; $k < count($array); $k++) {

      array_push($cids, $array[$k]);
    }

    for ($h = 0; $h < count($array3); $h++) {

      array_push($bids, $array3[$h]);
    }
      
    }
    // $category = Categories::where('store', $id)->get(['category_id', 'name']);
  $count = count($id);
        if($count > 1){
            $numbers = range(1, $count);
        $ddd = implode(',', $numbers);
         $category = Categories::where('store', $ddd)->get(['category_id', 'name']);
         
         $productd = Product::where('store', $ddd)->where('product_type', 'simple')->get(['product_id', 'title']);
        }else{
            $category = Categories::where('store', $id)->get(['category_id', 'name']);
            //   $store_data = Meta_details::whereIn('store_id',$id)->get(['c_id']);
              
            //   $store_dataa = Meta_details::whereIn('store_id',$id)->get(['p_id']);
              
            //   $product = Product::where('id', $store_dataa)->where('product_type', 'simple')->get(['product_id', 'title']);
            $productd = Product::where('store', $id)->where('product_type', 'simple')->get(['product_id', 'title']);
          
    // $category = Categories::whereIn('id', $store_data)->get(['category_id', 'name']);
        }


    for ($i = 0; $i < count($category); $i++) {
      array_push($ids, $category[$i]->category_id);
      array_push($names, $category[$i]->name);
    }

    // $product = Product::where('store', $id)->where('product_type', 'simple')->get(['product_id', 'title']);

    for ($j = 0; $j < count($productd); $j++) {
      array_push($productid, $productd[$j]->product_id);
      array_push($productname, $productd[$j]->title);
    }

    return response()->json(['success' => true, 'subcategory' => $names, 'ids' => $ids, 'cids' => $cids, 'pid' => $productid, 'pname' => $productname, 'bids' => $bids,'dd'=>$productIds,'qty'=>$bundqty]);
  }

  public function get_orders(Request $request)
  {
       $order_ids = Order::where('order_id','1163')->where('order_key','wc_order_RHJ8ogKrYWhMW')->get(['product_id', 'quantity','sku']);
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
             
            //   Product::where('sku', $sku)->update(['current_stock' => $data, 'total_quantity' => $subject1]);

              
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

     


     $result1 = $woocommerce->put('products/'.$productid[$i], $update_data);
       echo "<pre>";
                print_r($result1); 
                 }       
          
               
               
        
            }
       

            
            
          }
          
          
          
               
          
          
          
          
          
          
          
        
              }
     
  }


  public function showproduct(Request $request)
  {
    $id = $request->input('id');
    $products = Product::where('id', $id)->get(['current_stock', 'product_id', 'adjustment_stock', 'adjustment_stock', 'store', 'total_quantity']);
    return response()->json([
      'success' => true, 'total_quantity' => $products[0]->total_quantity, 'adjustment_stock' => $products[0]->adjustment_stock, 'id' => $products[0]->product_id,
      'current_stock' => $products[0]->current_stock, 'store' => $products[0]->store
    ]);
  }


 
public function storeadjustment(Request $request)
  {


$products = Product::where('id', $request->id)->get(['current_stock', 'product_id', 'product_type', 'adjustment_stock','store']);

    $a=$products[0]->adjustment_stock;
    $b=$products[0]->product_type;
    $stt=explode(',', $products[0]->store);

    $co=count($stt);
    // dd($co);


if($co < 2) {
    
    if($b == 'simple'){

   $stores = explode(',', $products[0]->store);
      $product_ids = explode(',', $products[0]->product_id);

    $store = Store::whereIn('id', $stores)->get();
// dd($store);
    $ss = 0;
$current_stockss = [];
        for($s=0;$s<count($stores);$s++) {
            
          // foreach ($product as $products) {
 $sc  = $s;


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
      $pp = Product::where('id', $request->id)->get();
      $p_ids = explode(',',$pp[0]->product_id);
     
 $productIds = explode(',', $pp[0]->product_id);
    
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
          foreach($test as $b){
            // $sc = Session::get('s');
            // echo $sc;
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
          $product_data[$bb]['adjustment_stock'] = $b->adjustment_stock;
          // $star[$y]['id'] = $test[$b]['product_id'];
        $db_stock_quantity[] = $b->stock_quantity;
        $adjustment_stock[] = $b->adjustment_stock;
        $db_product_id[] = $b->product_id;

$bb++;
    //  echo"ok";
        // Prepare the data for the update
      }



      // $rest = min($current_stock);
    //   echo "<pre>";
      $current_stockss[$s]['data'] = $product_data;
      
      // $current_stockss[$s]['min'] = $rest;
      
    // print_r($current_stockss);
    // echo $rest;
    }

    //   dd($current_stockss);

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

        // if ($current_stockss[$rr]['min'] == $current_stockss[$rr]['data'][$y]['current_stock']) {

          $star[0]['product_id'] = $current_stockss[$rr]['data'][$y]['product_id'];
          $star[0]['current_stock'] = $current_stockss[$rr]['data'][$y]['current_stock'];
          $star[0]['stock_quantity'] = $current_stockss[$rr]['data'][$y]['stock_quantity'];
          $star[0]['adjustment_stock'] = $current_stockss[$rr]['data'][$y]['adjustment_stock'];
          $star[0]['id'] = $current_stockss[$rr]['data'][$y]['id'];
        // }
      }
      // print_r($star[0]['product_id']);
      $z =0;

      if (strpos($request->input('adjustment'), '-')) {
        $currentt =    $star[$z]['current_stock'] - $request->adjustment;
        $totalt =  $star[$z]['stock_quantity'] - $request->adjustment;
        $adjust = $star[$z]['adjustment_stock'] - $request->adjustment;
        // echo $request->input('adjustment');
      } else {

        $currenttt =    $star[$z]['current_stock'] + $request->adjustment;
        $totalt =  $star[$z]['stock_quantity'] + $request->adjustment;
        $adjust =$star[$z]['adjustment_stock'] + $request->adjustment;
        // echo $request->input('adjustment');
      }

      // echo $currenttt;
       $DB_PRODUCT_STOCK[] = $currenttt;
     $DB_PRODUCT_STOCKs = implode(',',$DB_PRODUCT_STOCK);
    //   dd($DB_PRODUCT_STOCKs);
    //   print_r($DB_PRODUCT_STOCK);

$product_quant = Product::where('id', $star[$z]['id'])->get(['current_stock']);

      $producttt = Product::where('id', $star[$z]['id'])->update(['current_adjustment'=>$request->adjustment,'current_quantity' => $product_quant[0]->current_stock, 'adjustment_stock' => $adjust, 'current_stock' => $DB_PRODUCT_STOCKs, 'stock_quantity' => $DB_PRODUCT_STOCKs]);



      $update_data = [
        "manage_stock" => true,
        "stock_quantity" => $currenttt
      ];

     

     $result1 = $woocommerce1->put('products/'.$star[$z]['product_id'], $update_data);

      $test_data = Product::where('id', $star[$z]['id'])->get(['current_stock']);

         $productt = Product::where('id', $request->id)->update(['adjustment_stock' => $adjust, 'current_stock' => $test_data[0]->current_stock, 'stock_quantity' => $test_data[0]->current_stock]);
        //  echo "<pre>";
//   print_r($result1);
  $st++;
    }
   

 // dd($result1);
   sleep(1);
//   echo "ok";
        return redirect()->back()->with('message', 'Inventory updated successfully!');
  
  ///////////////////////////////////////////////second code//////////////////////////////////////////////////////////////////


        }
        else{ 

$stores = explode(',', $products[0]->store);
      $product_ids = explode(',', $products[0]->product_id);

    $store = Store::whereIn('id', $stores)->get();
// dd($store);
    $ss = 0;
$current_stockss = [];
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
      $pp = Product::where('id', $request->id)->get();
      $p_ids = explode(',',$pp[0]->product_id);
      
    //   dd($p_ids);
     
 $productIds = explode(',', $pp[0]->bundle_child_id);
    
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
          foreach($test as $b){
            // $sc = Session::get('s');
            // echo $sc;
             $productid = explode(',', $b->product_id);
             $stock_quantitys = explode(',', $b->stock_quantity);
             $array32 = explode(',', $b->current_stock);
             // echo $b->current_stock;
             // print_r($array32);
             // dd($array32);
        $current_stock[] =   $array32[$sc];
        // print_r($array32);
        $productids[] =   $productid[$sc];
        $stock_quantity[]=$stock_quantitys[$sc];

          $product_data[$bb]['current_stock'] = $array32[$sc];
          $product_data[$bb]['product_id'] = $productid[$sc];
          $product_data[$bb]['stock_quantity'] = $stock_quantitys[$sc];
          $product_data[$bb]['id'] = $b->id;
          $product_data[$bb]['adjustment_stock'] = $b->adjustment_stock;
          // $star[$y]['id'] = $test[$b]['product_id'];
        $db_stock_quantity[] = $b->stock_quantity;
        $adjustment_stock[] = $b->adjustment_stock;
        $db_product_id[] = $b->product_id;

$bb++;
        // Prepare the data for the update
      }



      $rest = min($current_stock);
    //   echo "<pre>";
      $current_stockss[$s]['data'] = $product_data;
      $current_stockss[$s]['min'] = $rest;
// print_r($current_stock);
// echo $rest;
}

      // dd($current_stock);

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

        if ($current_stockss[$rr]['min'] == $current_stockss[$rr]['data'][$y]['current_stock']) {

          $star[0]['product_id'] = $current_stockss[$rr]['data'][$y]['product_id'];
          $star[0]['current_stock'] = $current_stockss[$rr]['data'][$y]['current_stock'];
          $star[0]['stock_quantity'] = $current_stockss[$rr]['data'][$y]['stock_quantity'];
          $star[0]['adjustment_stock'] = $current_stockss[$rr]['data'][$y]['adjustment_stock'];
          $star[0]['id'] = $current_stockss[$rr]['data'][$y]['id'];
        }
      }
    //   print_r($star[0]['product_id']);
      $z =0;

      if (strpos($request->input('adjustment'), '-')) {
        $currentt =    $star[$z]['current_stock'] - $request->adjustment;
        $totalt =  $star[$z]['stock_quantity'] - $request->adjustment;
        $adjust = $star[$z]['adjustment_stock'] - $request->adjustment;
        // echo $request->input('adjustment');
      } else {

        $currenttt =    $star[$z]['current_stock'] + $request->adjustment;
        $totalt =  $star[$z]['stock_quantity'] + $request->adjustment;
        $adjust =$star[$z]['adjustment_stock'] + $request->adjustment;
        // echo $request->input('adjustment');
      }

      // echo $currenttt;
       $DB_PRODUCT_STOCK[] = $currenttt;
     $DB_PRODUCT_STOCKs = implode(',',$DB_PRODUCT_STOCK);
     // dd($DB_PRODUCT_STOCKs);
     // print_r($DB_PRODUCT_STOCK);

$product_quant = Product::where('id', $star[$z]['id'])->get(['current_stock']);

  

      $producttt = Product::where('id', $star[$z]['id'])->update(['current_adjustment'=>$request->adjustment,'current_quantity' => $product_quant[0]->current_stock, 'adjustment_stock' => $adjust, 'current_stock' => $DB_PRODUCT_STOCKs, 'stock_quantity' => $DB_PRODUCT_STOCKs]);



      $update_data = [
        "manage_stock" => true,
        "stock_quantity" => $currenttt
      ];

     

     $result1 = $woocommerce1->put('products/'.$star[$z]['product_id'], $update_data);
     
      $result2 = $woocommerce1->get('products/'.$p_ids[$rr]);
     
     $DB_PRODUCT_STOCKk[] = $result2->bundle_stock_quantity;
     
     $DB_PRODUCT_STOCKks = implode(',',$DB_PRODUCT_STOCKk);

      $test_data = Product::where('id', $star[$z]['id'])->get(['current_stock']);
      
      $productt = Product::where('id', $request->id)
      ->update(['adjustment_stock' => $adjust, 'current_stock' => $DB_PRODUCT_STOCKks, 'stock_quantity' => $DB_PRODUCT_STOCKks]);

        //  $productt = Product::where('id', $request->id)->update(['adjustment_stock' => $adjust, 'current_stock' => $test_data[0]->current_stock, 'stock_quantity' => $test_data[0]->current_stock]);
        //  echo "<pre>";
//   print_r($result1);
  $st++;
    }
      

    return redirect()->back()->with('message', 'Inventory updated successfully!');


}
   ///////////////////////////////////////////for both store///////////////////////////////////////////// 

}else{
    // dd($request->location);
    $products = Product::where('id', $request->id)->get(['current_stock', 'product_id', 'product_type', 'adjustment_stock','store','stock_quantity']);
      $stores = explode(',', $products[0]->store);
      $product_ids = explode(',', $products[0]->product_id);

    $store = Store::whereIn('id', $stores)->get();



    if (strpos($request->input('adjustment'), '-')) {
      $current = $request->current - $request->adjustment;
      $total = $products[0]->stock_quantity - $request->adjustment;
      $adjust = $products[0]->adjustment_stock - $request->adjustment;
      // echo $request->input('adjustment');
    } else {
      $a =explode(',',$request->current);
      $b =$request->adjustment;
      $result = [];
          for ($i = 0; $i < count($a); $i++) {
              $result[] = $a[$i] + $b;
          }
      $abc=implode(',',$result);



      $aa =explode(',',$products[0]->stock_quantity);

      $resultt = [];
          for ($j = 0; $j < count($aa); $j++) {
              $resultt[] = $aa[$j] + $b;
          }
      $abcc=implode(',',$resultt);


   // $current = $request->current + $request->adjustment;
      $current = $abc;
      $total = $abcc;
      $adjust = $products[0]->adjustment_stock + $request->adjustment;
      // echo $request->input('adjustment');
    }

// dd($total);
// echo $total;
$storearray =explode(',',$request->location);

   $store = Store::whereIn('id',$storearray)->get();
// dd($store);
// dd($woocommerce);
        if ($products[0]->product_type == 'simple') {

           for($l=0;$l<count($store);$l++){

    $woocommerce = new Client(
      $store[$l]->store_url,
      $store[$l]->key,
      $store[$l]->secret_key,
      [
        'version' => 'wc/v3',
      ]
    );

$product_quant = Product::where('id', $request->id)->get(['current_stock']);

  
      $productt_data = Product::where('id', $request->id)->update(['current_adjustment'=>$request->adjustment,'current_quantity' => $product_quant[0]->current_stock, 'adjustment_stock' => $adjust, 'current_stock' => $current, 'stock_quantity' => $total]);

      $productss = Product::where('id', $request->id)->get(['current_stock', 'product_id', 'product_type']);


      $dd = explode(',',$productss[0]->current_stock);
      $pid = explode(',',$productss[0]->product_id);

      // dd($pid);
      $data = [
        "manage_stock" => true,
        'stock_quantity' => $dd[$l]

      ];

      if (!empty($products)) {
  
        $result = $woocommerce->put('products/' . $pid[$l], $data);

        // print_r($result);
      }

    }
    // dd();
    }

     else
  {

   $stores = explode(',', $products[0]->store);
      $product_ids = explode(',', $products[0]->product_id);

    $store = Store::whereIn('id', $stores)->get();
// dd($store);
    $ss = 0;
$current_stockss = [];
        for($s=0;$s<count($stores);$s++){

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
      $pp = Product::where('id', $request->id)->get();
      $p_ids = explode(',',$pp[0]->product_id);
      
    //   dd($p_ids);
     
 $productIds = explode(',', $pp[0]->bundle_child_id);
    
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

   
        $test = Product::whereIn('product_id', $array3)->get(['current_stock', 'stock_quantity', 'product_id','id','adjustment_stock']);
        // dd($test);
       
        // dd($bundle);
        $db_stock_quantity = [];
        $db_product_id = [];
        $new_bundled_items = [];
        $adjustment_stock = [];
        $product_data = [];



        $bb = 0;
          foreach($test as $b){
            // $sc = Session::get('s');
            // echo $sc;
             $productid = explode(',', $b->product_id);
             $stock_quantitys = explode(',', $b->stock_quantity);
             $array32 = explode(',', $b->current_stock);
             // echo $b->current_stock;
             // print_r($array32);
             // dd($array32);
        $current_stock[] =   $array32[$sc];
        // print_r($array32);
        $productids[] =   $productid[$sc];
        $stock_quantity[]=$stock_quantitys[$sc];

          $product_data[$bb]['current_stock'] = $array32[$sc];
          $product_data[$bb]['product_id'] = $productid[$sc];
          $product_data[$bb]['stock_quantity'] = $stock_quantitys[$sc];
          $product_data[$bb]['id'] = $b->id;
          $product_data[$bb]['adjustment_stock'] = $b->adjustment_stock;
          // $star[$y]['id'] = $test[$b]['product_id'];
        $db_stock_quantity[] = $b->stock_quantity;
        $adjustment_stock[] = $b->adjustment_stock;
        $db_product_id[] = $b->product_id;

$bb++;
        // Prepare the data for the update
      }



      $rest = min($current_stock);
    //   echo "<pre>";
      $current_stockss[$s]['data'] = $product_data;
      $current_stockss[$s]['min'] = $rest;
// print_r($current_stock);
// echo $rest;
}

      // dd($current_stockss);

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

        if ($current_stockss[$rr]['min'] == $current_stockss[$rr]['data'][$y]['current_stock']) {

          $star[0]['product_id'] = $current_stockss[$rr]['data'][$y]['product_id'];
          $star[0]['current_stock'] = $current_stockss[$rr]['data'][$y]['current_stock'];
          $star[0]['stock_quantity'] = $current_stockss[$rr]['data'][$y]['stock_quantity'];
          $star[0]['adjustment_stock'] = $current_stockss[$rr]['data'][$y]['adjustment_stock'];
          $star[0]['id'] = $current_stockss[$rr]['data'][$y]['id'];
        }
      }
      // dd($star);
      $z =0;

      if (strpos($request->input('adjustment'), '-')) {
        $currentt =    $star[$z]['current_stock'] - $request->adjustment;
        $totalt =  $star[$z]['stock_quantity'] - $request->adjustment;
        $adjust = $star[$z]['adjustment_stock'] - $request->adjustment;
        // echo $request->input('adjustment');
      } else {

        $currenttt =    $star[$z]['current_stock'] + $request->adjustment;
        $totalt =  $star[$z]['stock_quantity'] + $request->adjustment;
        $adjust =$star[$z]['adjustment_stock'] + $request->adjustment;
        // echo $request->input('adjustment');
      }

      // echo $currenttt;
       $DB_PRODUCT_STOCK[] = $currenttt;
     $DB_PRODUCT_STOCKs = implode(',',$DB_PRODUCT_STOCK);
     // dd($DB_PRODUCT_STOCKs);
     // print_r($DB_PRODUCT_STOCK);

      $product_quant = Product::where('id', $star[$z]['id'])->get(['current_stock']);


      $producttt = Product::where('id', $star[$z]['id'])->update(['current_adjustment'=>$request->adjustment,'current_quantity' => $product_quant[0]->current_stock, 'adjustment_stock' => $adjust, 'current_stock' => $DB_PRODUCT_STOCKs, 'stock_quantity' => $DB_PRODUCT_STOCKs]);



      $update_data = [
        "manage_stock" => true,
        "stock_quantity" => $currenttt
      ];

     

     $result1 = $woocommerce1->put('products/'.$star[$z]['product_id'], $update_data);
     
      $result2 = $woocommerce1->get('products/'.$p_ids[$rr]);
     
     $DB_PRODUCT_STOCKk[] = $result2->bundle_stock_quantity;
     
     $DB_PRODUCT_STOCKks = implode(',',$DB_PRODUCT_STOCKk);

      $test_data = Product::where('id', $star[$z]['id'])->get(['current_stock']);
      
      // print_r($test_data);
//       print_r($request->id);

// print_r($DB_PRODUCT_STOCKks);
      
      $productt = Product::where('id', $request->id)
      ->update(['adjustment_stock' => $adjust, 'current_stock' => $DB_PRODUCT_STOCKks, 'stock_quantity' => $DB_PRODUCT_STOCKks]);

         // $productt = Product::where('id', $request->id)->update(['adjustment_stock' => $adjust, 'current_stock' => $test_data[0]->current_stock, 'stock_quantity' => $test_data[0]->current_stock]);
        //  echo "<pre>";
//   print_r($result1);
  $st++;
    }
      
// dd();
    return redirect()->back()->with('message', 'Inventory updated successfully!');
    }


    return redirect()->back()->with('message', 'Inventory updated successfully!');
}
  }





  public function overview_filerdata_expenses(Request $request)
  {

    $expenses_Start_calenderDate = $request->input('expenses_Start_calenderDate');
    $expenses_End_calenderDate = $request->input('expenses_End_calenderDate');

    $store = Store::all();
    for ($i = 0; $i < count($store); $i++) {
      $woocommerce = new Client(
        $store[$i]->store_url,
        $store[$i]->key,
        $store[$i]->secret_key,
        [
          'version' => 'wc/v3',
        ]
      );


      $date_query = [
        'date_min' => $expenses_Start_calenderDate,
        'date_max' => $expenses_End_calenderDate,
      ];

      $date = $woocommerce->get('reports/sales', $date_query);
    }

    $test = json_decode(json_encode($date[0]->totals), true);
    $dates = [];
    $dates[] = array_keys($test);

    $get_date = $dates[0][0];


    $dateString = $get_date; // Replace this with your date string
    $format = "Y-m"; // Replace this with your expected date format

    $dateTime = DateTime::createFromFormat($format, $dateString);

    $dateString1 = $get_date; // Replace this with your date string
    $format1 = "Y-m-d"; // Replace this with your expected date format

    $dateTime1 = DateTime::createFromFormat($format1, $dateString1);

    if ($dateTime !== false && $dateTime->format($format) === $dateString) {
      for ($z = 0; $z < count($dates[0]); $z++) {
        $monthName[] = date('M', strtotime($dates[0][$z]));
      }
    } elseif ($dateTime1 !== false && $dateTime1->format($format1) === $dateString1) {
      for ($z = 0; $z < count($dates[0]); $z++) {
        $monthName[] = date('j M', strtotime($dates[0][$z]));
      }
    }
    $labels = array_values($test);

    for ($c = 0; $c < count($labels); $c++) {

      $custom_sales[] =  $labels[$c]['sales'];

      $custom_items[] =  $labels[$c]['items'];
    }


    return response()->json([
      'success' => true, 'total_sales' => $date[0]->total_sales, 'average_sales' => $date[0]->average_sales,
      'net_sales' => $date[0]->net_sales, 'total_orders' => $date[0]->total_orders,
      'total_items' => $date[0]->total_items, 'total_refunds' => $date[0]->total_refunds, 'total_shipping' => $date[0]->total_shipping, 'total_discount' => $date[0]->total_discount,
      'custom_month' => $monthName, 'custom_sales' => $custom_sales, 'custom_items' => $custom_items
    ]);
  }





public function quatation() {


    return view('Admin.quatation');
}

public function add_quatation(Request $request) {

// dd($request->all());

$product_id = $request->product_id;
$product_price = $request->product_price;
$product_quantity = $request->product_quantity;


$grand_total = $request->grandtotal;


$product_total = $request->product_total;

$subtotal = $grand_total;
// dd($subtotal);
// $sum = array_sum($arr);

$discountPercentage = $request->discount;

// Convert $total to a numeric value (in case it's a string)
$total = floatval($subtotal);
$discountPercentage = floatval($discountPercentage);


$discountAmount = ($discountPercentage / 100) * $subtotal;

$grandTotal = $subtotal - $discountAmount;


$gst = $grandTotal / 11;

$roundedGst = round($gst, 0);

    if($request->freight){
    
    $formattedNumber=$subtotal - $discountAmount + $request->freight; //   freght add if exsit
    
    }else{
    
      $formattedNumber=$subtotal-$discountAmount; //   freght add if exsit
    }

$grandtotal = number_format($formattedNumber, 2);


$pp  =Product::where('id',$product_id[0])->get(['title','current_stock']);

$randomNumber = rand(1000, 9999);
// echo $randomNumber;

 $user = new Quantation;
            $user->name = $request->name;
            $user->bussiness_name = $request->bname;
            $user->email = $request->email;
            $user->address = $request->address;
            $user->phone_number = $request->phone;
            $user->freight = $request->freight;
            $user->notes = $request->notes;
            $user->discount = $request->discount;
            $user->product_id = $product_id[0];
            $user->product_name = $pp[0]->title;
            $user->misc_item = $request->miscellaneous;
            $user->misc_value = $request->miscvalue;
            $user->parent_key = '0';
            $user->product_price = $product_price[0];
            $user->product_quantity = $product_quantity[0];
            $user->total = $product_total[0];
            $user->grand_total =$grand_total;
            $user->date = $request->date;
            $user->quote_id =$randomNumber;
            $user->save ();
            $status = $user->status;
            
$product_name = [];
$product_name[0] = $pp[0]->title;
for($i= 1 ;$i<count($product_id);$i++){
$p  =Product::where('id',$product_id[$i])->get(['title']);
$product_name[$i] = $p[0]->title;
            $users = new Quantation;
            $users->name = $request->name;
            $users->email = $request->email;
            $users->product_id = $product_id[$i];
            $users->product_name = $p[0]->title;
            $users->parent_key = $user->id;
            $users->product_price = $product_price[$i];
            $users->product_quantity = $product_quantity[$i];
            $users->total = $product_total[$i];
            $users->date = $request->date;
            $users->save ();
}


// $prices_without_dollar = array_map(function($price) {
//     return (float)str_replace('$', '', $price);
// }, $product_price);

// Calculate the sum of the prices
$sum = array_sum($product_total);

// dd(number_format($sum, 2));
if($request->freight){



           $details = [

        'title' => 'Mail from Inventory.com',

        'name' => $request->name,

        'bussiness_name' => $request->bname,

        'email' => $request->email,
        
         'misc_item' => $request->miscellaneous,
          
        'misc_value' => $request->miscvalue,
        
         'grand_total' => $grand_total,

         'address' => $request->address,

        'product_name' => $product_name,

        'product_price' => $product_price,

        'product_quantity' => $product_quantity,

        'date' => $request->date,

        'quote_id' => $randomNumber,

        'grand_totall' => $grandtotal,

        'discount_per' => $request->discount,

        'discount_amount' => $discountAmount,

         'gst' => $roundedGst,

        'freight' => $request->freight,

        'notes' => $request->notes,
       
        'total' => number_format($sum, 2),
        
        'product_total' => $product_total,
        
        'status' => $status,
        
        

    ];


  }else{

           $details = [

        'title' => 'Mail from Inventory.com',

        'name' => $request->name,

        'bussiness_name' => $request->bname,

        'email' => $request->email,
        
          'misc_item' => $request->miscellaneous,
          
            'misc_value' => $request->miscvalue,
        
        
         'grand_total' => $grand_total,

         'address' => $request->address,

        'product_name' => $product_name,

        'product_price' => $product_price,

        'product_quantity' => $product_quantity,

        'date' => $request->date,

        'quote_id' => $randomNumber,

         'grand_totall' => $grandtotal,

        'discount_per' => $request->discount,

        'discount_amount' => $discountAmount,
        
        'gst' => $roundedGst,
        
         'notes' => $request->notes,

         'freight' => null,

        'total' => number_format($sum, 2),
        
        'product_total' => $product_total,
        
        'status' => $status,
        
        

    ];

  }

// dd($details);
 $pdf = PDF::loadView('testmail', ['details'=>$details]);
   
      Mail::send('quotationusermail', $details, function($message)use($details, $pdf) {
                    $message->to($details['email'])
                       ->subject($details["title"])
                    ->attachData($pdf->output(), "text.pdf");
                });


$pdf = PDF::loadView('Admin.quotation2-pdf', ['details'=>$details]);

     // $arr=Quantation::where('id',$id)->get();

     //  $pdf = PDF::loadView('Admin.quotation-pdf', ['arr' => $arr]);

      ini_set('memory_limit', '2048M');

      return $pdf->download('inventory-management.pdf');


       // return redirect()->back()->with('message', 'Quote Added successfully!');
       
     
}





public function pending_quatation_list(Request $request) {
    $orders = Quantation::where('parent_key', '0')
                        ->where(function ($query) {
                            $query->where('status', '1')
                                  ->orWhere('status', '2');
                        })
                        ->orderBy('created_at', 'desc') // Order by id in descending order
                        ->get();

    $data = [];
    foreach($orders as $order) {
        $data[] = [
            'id' => $order->id,
            'name' => $order->name,
            'bussiness_name' => $order->bussiness_name,
            'email' => $order->email,
            'quote_id' => $order->quote_id,
            'address' => $order->address,
            'date' => $order->date,
            'status' => $order->status,
            'total' => $order->total, // Assuming you have total column directly accessible
        ];

        $arr = [$order->total]; // Initialize array with total of current order
        $orderss = Quantation::where('parent_key', $order->id)->get(['total']);
        foreach($orderss as $orderItem) {
            $arr[] = $orderItem->total; // Add totals of child orders to array
        }
        $sum = array_sum($arr); // Calculate the sum of the prices
        $data[count($data) - 1]['total'] = $sum; // Update total for current order
    }

// dd($data);
    return view('Admin.pendingquotation', ['data' => $data]);
}




public function quatation_list(Request $request) {
    $orders = Quantation::where('parent_key', '0')
                        ->where('status', '!=', 2)
                        ->orderBy('id', 'desc') // Order by id in descending order
                        ->get();

    $data = [];
    foreach($orders as $order) {
        $data[] = [
            'id' => $order->id,
            'name' => $order->name,
            'bussiness_name' => $order->bussiness_name,
            'email' => $order->email,
            'quote_id' => $order->quote_id,
            'address' => $order->address,
            'date' => $order->date,
            'status' => $order->status,
            'total' => $order->total, // Assuming you have total column directly accessible
        ];

        $arr = [$order->total]; // Initialize array with total of current order
        $orderss = Quantation::where('parent_key', $order->id)->get(['total']);
        foreach($orderss as $orderItem) {
            $arr[] = $orderItem->total; // Add totals of child orders to array
        }
        $sum = array_sum($arr); // Calculate the sum of the prices
        $data[count($data) - 1]['total'] = $sum; // Update total for current order
    }

    return view('Admin.quatationlist', ['data' => $data]);
}


public function reject_quatation_list(Request $request) {
$orders = Quantation::where('parent_key','0')->where('status','2')->get();
$data = [];
for($i=0;$i<count($orders);$i++){
  $data[$i]['name'] =  $orders[$i]->name;
  $data[$i]['email'] =  $orders[$i]->email;
    $data[$i]['date'] =  $orders[$i]->date;
    $data[$i]['reason'] =  $orders[$i]->reason;
  $arr=[];
$arr[$i] = $orders[$i]->total;
$orderss = Quantation::where('parent_key',$orders[$i]->id)->get(['total']);
for($j=0;$j<count($orderss);$j++){
         $arr[$j+1] = $orderss[$j]->total;

  }
// $prices_without_dollar = array_map(function($price) {
//     return (float)str_replace('$', '', $price);
// }, $arr);

// Calculate the sum of the prices
$sum = array_sum($arr);
// echo $sum;
$data[$i]['total'] =  $sum;
}



     return view('Admin.rejectedquotation',['data'=>$data]);

}


////////////////////////////////////////////////////invoice pdf in quotatin list//////////////////////////////////////////////////////////////

public function quatation_pdf($id) {


  $User = Quantation::where('quote_id',$id)->get();

   $Invoice_count = Invoice::where('quote_id',$id)->count();


   if($Invoice_count == 0){


  $User2 = Quantation::where('quote_id',$id)->update(['status'=>'1']); 

  

    // $data = Quantation::where('quote_id',$request->id)->get();
  
$orders = Quantation::where('quote_id',$id)->get();

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
  $data['title'] =  'Mail from Inventory.com';
  $data['bussiness_name'] =  $orders[$i]->bussiness_name;

  $data['address'] =  $orders[$i]->address;

  $data['discount_per'] =  $orders[$i]->discount;
  
  $data['misc_item'] =  $orders[$i]->misc_item;
  
  $data['misc_value'] =  $orders[$i]->misc_value;

        if($orders[$i]->freight!=null){
        
          $data['freight'] =  $orders[$i]->freight;
        }else{
           $data['freight'] =  null;
        }


$data['notes'] =  $orders[$i]->notes;

$data['grand_total'] =  $orders[$i]->grand_total;




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

 $randomNumbers = rand(100000, 999999);

$currentDate = date("Y-m-d");




 $user = new Invoice;
            $user->invoice_id = $randomNumbers;
            $user->quote_id = $id;
            $user->email = $User[0]->email;
            $user->date = $currentDate;
            $user->total_price = $sum;      

            $user->save();

 $invoice = Invoice::where('quote_id',$id)->get();  

      // dd($invoice);

$data['total'] =  $sum;
$data['product_name'] =  $product_name;
$data['product_price'] =  $product_price;
$data['product_quantity'] =  $product_quantity;
$data['quote_id'] = $id ;
$data['product_total'] =  $product_total;
$data['invoice_id'] =  $invoice[0]->invoice_id;
$data['invoice_date'] =  $invoice[0]->date;
}






$product_stocks = Product::whereIn('id',$product_ids)->get();
 
for($k=0;$k<count($product_ids);$k++){

 $product_stock = Product::where('id',$product_ids[$k])->get();  

 // echo $product_ids[$k];

if($product_stock->isNotEmpty()) {
     
     $store = $product_stock[0]->store;

     // echo $store;
    
    $product_id = explode(',',$product_stock[0]->product_id);
  
    $stores = explode(',',$store);
    //  dd($stores);
    $count = count($stores);

    if($count < 2){

    if($product_stock[0]->product_type == 'simple'){
      // echo 'simple';
   $storess = Store::where('id',$stores[0])->get();
        // print_r($stores);
            $woocommerce = new Client(
              $storess[0]->store_url,
              $storess[0]->key,
              $storess[0]->secret_key,
               [
          'version' => 'wc/v3',
            'timeout' => 400 // curl timeout
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
      // echo "bundle";
        $pids = explode(',',$product_stock[0]->bundle_child_id);
                $storess = Store::where('id',$stores[0])->get();
        // print_r($stores);
            $woocommerce = new Client(
              $storess[0]->store_url,
              $storess[0]->key,
              $storess[0]->secret_key,
               [
          'version' => 'wc/v3',
            'timeout' => 400 // curl timeout
        ]
            );
            $min = [];
           $product_bund_qty =  Product::where('id', $product_ids[$k])->get();
           $bqty = explode(',',$product_bund_qty[0]->product_quantity);


           $currents = [];
              
                foreach($bqty as $bqtymax){
                            $cu =  $bqtymax * $product_quantity[$k];
                           $currents[] = $cu ;
                           

                }
 
           // dd($currents);

            for($d=0;$d<count($pids);$d++){
                $p_id = Product::where('product_id',$pids[$d])->get();
              $current = $p_id[0]->current_stock - $currents[$d];
       
      $total = $p_id[0]->stock_quantity - $currents[$d];
      
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

}                   //  COUNT BRACKET//

else{

   if($product_stock[0]->product_type == 'simple'){               

  // echo "both simple";
  
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

  
  
  }                  ////////BOTH IF SIMPLE BRACKET/////////////

  else{
   // echo"both bundle";
    
    
    $stores = explode(',', $product_stock[0]->store);

    // dd($stores);
    //   $product_idss = explode(',', $product_stock[0]->product_id);
    $store = Store::whereIn('id', $stores)->get();
    $ss = 0;
$current_stockss = [];

$restp = [];
$rests = [];
$pDB_PRODUCT_STOCKk=[]; 
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

           $product_bund_qty =  Product::where('id', $product_ids[$k])->get();
           $bqty = explode(',',$product_bund_qty[0]->product_quantity);


           $currents = [];
              
                foreach($bqty as $bqtymax){
                            $cu =  $bqtymax * $product_quantity[$k];
                           $currents[] = $cu ;
                           

                }



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
        $current_stock[] =   $array32[$sc]-$currents[$bb];
        // print_r($array32[0]);
      
     $tests[] = $b->current_stock;
    //  $stests[] = $b->current_stock;
      $btest[] = $b->stock_quantity ;
        $productids[] =   $productid[$sc];
        $stock_quantity[]=$stock_quantitys[$sc]-$currents[$bb];

          $product_data[$bb]['current_stock'] = $array32[$sc]-$currents[$bb];
          $product_data[$bb]['product_id'] = $productid[$sc];
          $product_data[$bb]['stock_quantity'] = $stock_quantitys[$sc]-$currents[$bb];
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

// dd()

      $rest = min($current_stock);
         $rest1 = min($stock_quantity);
    //   echo "<pre>";
      $current_stockss[$s]['data'] = $product_data;
      $current_stockss[$s]['min'] = $rest;

// dd($current_stockss);

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
 //}

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
    $newNumber1 = $number1 - $currents[$o];
    $newNumber2 = $number2 - $currents[$o];
    
    $newNumber11 = $number11 - $currents[$o];
    $newNumber22 = $number22 - $currents[$o];
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

// print_r($resultArray);


// for($aa = 0 ; $aa < count($resultArray1);$aa++) {
//      Product::where('id',$btestid[$aa])->update(['current_stock'=>$resultArray[$aa],'stock_quantity'=>$resultArray1[$aa]]);
// }

// Product::where('id',$product_ids[$k])->update(['current_stock'=>$current_min,'stock_quantity'=>$stock_min]);
    
  
 // }


//}   
$bpqty = explode(',',$product_bund_qty[0]->product_id);

// print_r($bpqty);

    
     $result22 = $woocommerce->get('products/'.$bpqty[$sc]);


     


     $pDB_PRODUCT_STOCKk[] = $result22->bundle_stock_quantity;
     
     $pDB_PRODUCT_STOCKks = implode(',',$pDB_PRODUCT_STOCKk);
} 


for($aa = 0 ; $aa < count($resultArray1);$aa++) {

     Product::where('id',$btestid[$aa])->update(['current_stock'=>$resultArray[$aa],'stock_quantity'=>$resultArray1[$aa]]);
}



Product::where('id',$product_ids[$k])->update(['current_stock'=>$pDB_PRODUCT_STOCKks,'stock_quantity'=>$pDB_PRODUCT_STOCKks]);
 
}
}          ////////ELSE BOTH SIMLE BRACKET/////////////




}

}

$subtotal =$data['grand_total'];
// dd($subtotal);
// $sum = array_sum($arr);

$discountPercentage = $data['discount_per'];

// Convert $total to a numeric value (in case it's a string)
$total = floatval($subtotal);
$discountPercentage = floatval($discountPercentage);


$discountAmount = ($discountPercentage / 100) * $subtotal;

$grandTotal = $subtotal - $discountAmount;


$gst = $grandTotal / 11;

$roundedGst = round($gst, 0);
if($data['freight']){

$formattedNumber=$subtotal - $discountAmount + $data['freight']; //   freght add if exsit

}else{

  $formattedNumber=$subtotal-$discountAmount; //   freght add if exsit
}

$grandtotal = number_format($formattedNumber, 2);



$data['grand_totall'] =$grandtotal;


$data['discount_amount'] =$discountAmount;

$data['gst'] =$roundedGst;

$details=$data;

     $pdf = PDF::loadView('Admin.quotation-pdf', ['details'=>$details]);
   
         Mail::send('quotationusermail', $details, function($message)use($details, $pdf) {
                    $message->to($details['email'])
                       ->subject($details["title"])
                    ->attachData($pdf->output(), "text.pdf");
                });


 return redirect()->back()->with('message', 'Invoice Created successfully!');

}
return redirect()->back()->with('message', 'Invoice Already Created!');
}


  public function quatation_product(Request $request)

  {
    $id = $request->input('id');

    $price = [];
    $ids = [];
    $stock = [];

      
         $category = Product::where('id', $id)->get(['sales_price','id','product_id','current_stock','store']);
         
         $stores = explode(',',$category[0]->store);
         
         $count = count($stores);
         
         $s = explode(',',$category[0]->current_stock);
         
         if($count > 1){
                for ($i = 0; $i < count($category); $i++) {
      array_push($ids, $category[$i]->id);
      array_push($price, $category[$i]->sales_price);
      array_push($stock, min($s));
    }
      // array_push(min(explode(',',$category[0]->current_stock)));
         }else{
       
    for ($i = 0; $i < count($category); $i++) {
      array_push($ids, $category[$i]->id);
      array_push($price, $category[$i]->sales_price);
      array_push($stock, $category[$i]->current_stock);
    }
         }            
          
    return response()->json(['success' => true, 'price' => $price, 'ids' => $id,'stock'=>$stock]);
  }




public function invoice_pdf($id) {


  $User = Quantation::where('quote_id',$id)->get();



   // $User2 = Quantation::where('quote_id',$id)->update(['status'=>'1']);

        

    // $data = Quantation::where('quote_id',$request->id)->get();
  
$orders = Quantation::where('quote_id',$id)->get();

$invoice = Invoice::where('quote_id',$User[0]->quote_id)->get();
// dd($invoice);

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
  $data['bussiness_name'] =  $orders[$i]->bussiness_name;

   $data['address'] =  $orders[$i]->address;
$data['discount_per'] =  $orders[$i]->discount;

  $data['misc_item'] =  $orders[$i]->misc_item;
  
  $data['misc_value'] =  $orders[$i]->misc_value;

if($orders[$i]->freight!=null){

  $data['freight'] =  $orders[$i]->freight;
}else{
   $data['freight'] =  null;
}


$data['notes'] =  $orders[$i]->notes;

$data['grand_total'] =  $orders[$i]->grand_total;

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
$data['quote_id'] = $id ;
$data['product_total'] =  $product_total;
$data['invoice_id'] =  $invoice[0]->invoice_id;
$data['invoice_date'] =  $invoice[0]->date;
}

$subtotal =$data['grand_total'];
// dd($subtotal);
// $sum = array_sum($arr);

$discountPercentage = $data['discount_per'];

// Convert $total to a numeric value (in case it's a string)
$total = floatval($subtotal);
$discountPercentage = floatval($discountPercentage);


$discountAmount = ($discountPercentage / 100) * $subtotal;

$grandTotal = $subtotal - $discountAmount;


$gst = $grandTotal / 11;

$roundedGst = round($gst, 0);
if($data['freight']){

$formattedNumber=$subtotal - $discountAmount + $data['freight']; //   freght add if exsit

}else{

  $formattedNumber=$subtotal-$discountAmount; //   freght add if exsit
}

$grandtotal = number_format($formattedNumber, 2);



$data['grand_totall'] =$grandtotal;


$data['discount_amount'] =$discountAmount;

$data['gst'] =$roundedGst;



// dd($data);

$pdf = PDF::loadView('Admin.quotation-pdf', ['details'=>$data]);

     // $arr=Quantation::where('id',$id)->get();

     //  $pdf = PDF::loadView('Admin.quotation-pdf', ['arr' => $arr]);

      ini_set('memory_limit', '2048M');

      return $pdf->download('inventory-management.pdf');

}

public function edit_quote($id) {


    $data = Quantation::where('quote_id',$id)->get();

     $data1 = Quantation::where('parent_key',$data[0]->id)->orWhere('id',$data[0]->id)->get();
     
    //  dd($data1);

    return view('Admin.editquote', ['data' => $data,'data1' => $data1]);
}


public function update_quote(Request $request) {

// dd($request->all());


$product_id = $request->product_id;
$product_price = $request->product_price;
$product_quantity = $request->product_quantity;
$product_total = $request->product_total;
$grand_total = $request->grandtotal;

 $user = Quantation::where('quote_id',$request->quote_id)->get();

$data1 = Quantation::where('parent_key',$user[0]->id)->orWhere('id',$user[0]->id)->get();

$pp  = Product::where('id',$product_id[0])->get(['title','current_stock']);


$subtotal =$grand_total;
// dd($subtotal);
// $sum = array_sum($arr);

$discountPercentage = $request->discount;

// Convert $total to a numeric value (in case it's a string)
$total = floatval($subtotal);
$discountPercentage = floatval($discountPercentage);


$discountAmount = ($discountPercentage / 100) * $subtotal;

$grandTotal = $subtotal - $discountAmount;


$gst = $grandTotal / 11;

$roundedGst = round($gst, 0);

if($request->freight){

$formattedNumber=$subtotal - $discountAmount + $request->freight; //   freght add if exsit

}else{

  $formattedNumber=$subtotal-$discountAmount; //   freght add if exsit
}

$grandtotal = number_format($formattedNumber, 2);



$status = $user[0]->status;

$user2 = Quantation::where('quote_id',$request->quote_id)->update([
  'product_id' =>$product_id[0],'product_name' => $pp[0]->title,'product_price' => $product_price[0],'phone_number' => $request->phone,'discount' => $request->discount,'grand_total' => $grand_total,'freight' => $request->freight,'notes' =>$request->notes,
 'product_quantity' => $product_quantity[0],'total' => $product_total[0],'date' => $request->date,'status' =>'0']);

$user3 = Invoice::where('quote_id',$request->quote_id)->delete();

$available_array = Quantation::where('parent_key', $user[0]->id)->delete();


$randomNumber = rand(1000, 9999);




$product_name = [];

$product_name[0] = $pp[0]->title;

for($i= 1 ;$i<count($product_id);$i++){

$p  =Product::where('id',$product_id[$i])->get(['title']);

$product_name[$i] = $p[0]->title;
            $users = new Quantation;
            $users->name = $request->name;
            $users->email = $request->email;
            $users->product_id = $product_id[$i];
            $users->product_name = $p[0]->title;
            $users->parent_key = $user[0]->id;
            $users->product_price = $product_price[$i];
            $users->product_quantity = $product_quantity[$i];
            $users->total = $product_total[$i];
            $users->date = $request->date;
            $users->save ();
}


// $prices_without_dollar = array_map(function($price) {
//     return (float)str_replace('$', '', $price);
// }, $product_price);

// Calculate the sum of the prices
$sum = array_sum($product_total);

// dd(number_format($sum, 2));

         if($request->freight){



           $details = [

        'title' => 'Mail from Inventory.com',

        'name' => $request->name,

        'bussiness_name' => $request->bname,

        'email' => $request->email,

         'address' => $request->address,
         
         'misc_item' => $request->miscellaneous,
          
        'misc_value' => $request->miscvalue,
        
         'grand_total' => $grand_total,
         

        'product_name' => $product_name,

        'product_price' => $product_price,

        'product_quantity' => $product_quantity,

        'date' => $request->date,

        'quote_id' => $randomNumber,

        'grand_totall' => $grandtotal,

        'discount_per' => $request->discount,

        'discount_amount' => $discountAmount,

         'gst' => $roundedGst,

        'freight' => $request->freight,

        'notes' => $request->notes,
       
        'total' => number_format($sum, 2),
        
        'product_total' => $product_total,
        
        'status' => $status,
        
        

    ];


  }else{

           $details = [

        'title' => 'Mail from Inventory.com',

        'name' => $request->name,

        'bussiness_name' => $request->bname,

        'email' => $request->email,

         'address' => $request->address,
         
           'misc_item' => $request->miscellaneous,
          
        'misc_value' => $request->miscvalue,
        
         'grand_total' => $grand_total,

        'product_name' => $product_name,

        'product_price' => $product_price,

        'product_quantity' => $product_quantity,

        'date' => $request->date,

        'quote_id' => $randomNumber,

         'grand_total' => $grandtotal,

        'discount_per' => $request->discount,

        'discount_amount' => $discountAmount,
        
        'gst' => $roundedGst,

         'notes' => $request->notes,

         'freight' => null,

        'total' => number_format($sum, 2),
        
        'product_total' => $product_total,
        
        'status' => $status,
        
        

    ];

  }

 $pdf = PDF::loadView('testmail', ['details'=>$details]);
   
      Mail::send('quotationusermail', $details, function($message)use($details, $pdf) {
                    $message->to($details['email'])
                       ->subject($details["title"])
                    ->attachData($pdf->output(), "text.pdf");
                });



$pdf = PDF::loadView('Admin.quotation2-pdf', ['details'=>$details]);

     // $arr=Quantation::where('id',$id)->get();

     //  $pdf = PDF::loadView('Admin.quotation-pdf', ['arr' => $arr]);

      ini_set('memory_limit', '2048M');

      return $pdf->download('inventory-management.pdf');



       return redirect()->back()->with('message', 'Quote Edited successfully!');
     
}



  public function invoice()
  {
    
    return view('Admin.add-invoice');
  }


   public function add_invoice(Request $request) {

// dd($request->all());

$product_id = $request->product_id;
$product_price = $request->product_price;
$product_quantity = $request->product_quantity;

$product_total = $request->product_total;
$grand_total = $request->grandtotal;

$subtotal = array_sum($product_total);
// dd($subtotal);
// $sum = array_sum($arr);

$discountPercentage = $request->discount;

// Convert $total to a numeric value (in case it's a string)
$total = floatval($subtotal);
$discountPercentage = floatval($discountPercentage);


$discountAmount = ($discountPercentage / 100) * $subtotal;

$grandTotal = $subtotal - $discountAmount;


$gst = $grandTotal / 11;

$roundedGst = round($gst, 0);

if($request->freight){

$formattedNumber=$subtotal - $discountAmount + $request->freight; //   freght add if exsit

}else{

  $formattedNumber=$subtotal-$discountAmount; //   freght add if exsit
}

$grandtotal = number_format($formattedNumber, 2);


$pp=Product::where('id',$product_id[0])->get(['title','current_stock']);

$randomNumber = rand(1000, 9999);
// echo $randomNumber;

 $user = new Quantation;
            $user->name = $request->name;
            $user->bussiness_name = $request->bname;
            $user->email = $request->email;
            $user->address = $request->address;
            $user->phone_number = $request->phone;
            $user->freight = $request->freight;
            $user->notes = $request->notes;
            $user->discount = $request->discount;
            $user->product_id = $product_id[0];
            $user->product_name = $pp[0]->title;
              $user->misc_item = $request->miscellaneous;
            $user->misc_value = $request->miscvalue;
            $user->parent_key = '0';
            $user->product_price = $product_price[0];
            $user->product_quantity = $product_quantity[0];
            $user->total = $product_total[0];
            $user->grand_total =$grand_total;
            $user->date = $request->date;
            $user->quote_id =$randomNumber;
            $user->status="2";
            $user->save ();
            $status = $user->status;
$product_name = [];
$product_name[0] = $pp[0]->title;
for($i= 1 ;$i<count($product_id);$i++){
$p  =Product::where('id',$product_id[$i])->get(['title']);
$product_name[$i] = $p[0]->title;
            $users = new Quantation;
            $users->name = $request->name;
            $users->email = $request->email;
            $users->product_id = $product_id[$i];
            $users->product_name = $p[0]->title;
            $users->parent_key = $user->id;
            $users->product_price = $product_price[$i];
            $users->product_quantity = $product_quantity[$i];
            $users->total = $product_total[$i];
            $users->date = $request->date;
            $users->save ();
}


// $prices_without_dollar = array_map(function($price) {
//     return (float)str_replace('$', '', $price);
// }, $product_price);

// Calculate the sum of the prices
$sum = array_sum($product_total);

$User = Quantation::where('quote_id',$randomNumber)->get();

   $Invoice_count = Invoice::where('quote_id',$randomNumber)->count();


  


  // $User2 = Quantation::where('quote_id',$randomNumber)->update(['status'=>'1']); 

  

    // $data = Quantation::where('quote_id',$request->id)->get();
  
$orders = Quantation::where('quote_id',$randomNumber)->get();

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
        $data['title'] =  'Mail from Inventory.com';
      $data['bussiness_name'] =  $orders[$i]->bussiness_name;

  $data['address'] =  $orders[$i]->address;
$data['discount_per'] =  $orders[$i]->discount;

$data['misc_item'] =  $orders[$i]->misc_item;
  
  $data['misc_value'] =  $orders[$i]->misc_value;

if($orders[$i]->freight!=null){

  $data['freight'] =  $orders[$i]->freight;
}else{
   $data['freight'] =  null;
}


$data['notes'] =  $orders[$i]->notes;

$data['grand_total'] =  $orders[$i]->grand_total;

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

 $randomNumbers = rand(100000, 999999);

$currentDate = date("Y-m-d");




 $user = new Invoice;
            $user->invoice_id = $randomNumbers;
            $user->quote_id = $randomNumber;
            $user->email = $User[0]->email;
            $user->date = $currentDate;
            $user->total_price = $grand_total;      

            $user->save();

 $invoice = Invoice::where('quote_id',$randomNumber)->get();  

      // dd($invoice);

$data['total'] =  $sum;
$data['product_name'] =  $product_name;
$data['product_price'] =  $product_price;
$data['product_quantity'] =  $product_quantity;
$data['quote_id'] = $randomNumber ;
$data['product_total'] =  $product_total;
$data['invoice_id'] =  $invoice[0]->invoice_id;
$data['invoice_date'] =  $invoice[0]->date;
}






$product_stocks = Product::whereIn('id',$product_ids)->get();
 
for($k=0;$k<count($product_ids);$k++){

 $product_stock = Product::where('id',$product_ids[$k])->get();  

 // echo $product_ids[$k];

if($product_stock->isNotEmpty()) {
     
     $store = $product_stock[0]->store;

     // echo $store;
    
    $product_id = explode(',',$product_stock[0]->product_id);
  
    $stores = explode(',',$store);
    //  dd($stores);
    $count = count($stores);

    if($count < 2){

    if($product_stock[0]->product_type == 'simple'){
      // echo 'simple';
   $storess = Store::where('id',$stores[0])->get();
        // print_r($stores);
            $woocommerce = new Client(
              $storess[0]->store_url,
              $storess[0]->key,
              $storess[0]->secret_key,
              [
          'version' => 'wc/v3',
            'timeout' => 400 // curl timeout
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
      // echo "bundle";
        $pids = explode(',',$product_stock[0]->bundle_child_id);
                $storess = Store::where('id',$stores[0])->get();
        // print_r($stores);
            $woocommerce = new Client(
              $storess[0]->store_url,
              $storess[0]->key,
              $storess[0]->secret_key,
                [
          'version' => 'wc/v3',
            'timeout' => 400 // curl timeout
        ]
            );
            $min = [];
           $product_bund_qty =  Product::where('id', $product_ids[$k])->get();
           $bqty = explode(',',$product_bund_qty[0]->product_quantity);


           $currents = [];
              
                foreach($bqty as $bqtymax){
                            $cu =  $bqtymax * $product_quantity[$k];
                           $currents[] = $cu ;
                           

                }
 
           // dd($currents);

            for($d=0;$d<count($pids);$d++){
                $p_id = Product::where('product_id',$pids[$d])->get();
              $current = $p_id[0]->current_stock - $currents[$d];
       
      $total = $p_id[0]->stock_quantity - $currents[$d];
      
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

}                   //  COUNT BRACKET//

else{

   if($product_stock[0]->product_type == 'simple'){               

  // echo "both simple";
  
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
            'timeout' => 400 // curl timeout
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
            'timeout' => 400 // curl timeout
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

  
  
  }                  ////////BOTH IF SIMPLE BRACKET/////////////

  else{
   // echo"both bundle";
    
    
    $stores = explode(',', $product_stock[0]->store);

    // dd($stores);
    //   $product_idss = explode(',', $product_stock[0]->product_id);
    $store = Store::whereIn('id', $stores)->get();
    $ss = 0;
$current_stockss = [];

$restp = [];
$rests = [];
$pDB_PRODUCT_STOCKk=[]; 
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
            'timeout' => 400 // curl timeout
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

           $product_bund_qty =  Product::where('id', $product_ids[$k])->get();
           $bqty = explode(',',$product_bund_qty[0]->product_quantity);


           $currents = [];
              
                foreach($bqty as $bqtymax){
                            $cu =  $bqtymax * $product_quantity[$k];
                           $currents[] = $cu ;
                           

                }



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
        $current_stock[] =   $array32[$sc]-$currents[$bb];
        // print_r($array32[0]);
      
     $tests[] = $b->current_stock;
    //  $stests[] = $b->current_stock;
      $btest[] = $b->stock_quantity ;
        $productids[] =   $productid[$sc];
        $stock_quantity[]=$stock_quantitys[$sc]-$currents[$bb];

          $product_data[$bb]['current_stock'] = $array32[$sc]-$currents[$bb];
          $product_data[$bb]['product_id'] = $productid[$sc];
          $product_data[$bb]['stock_quantity'] = $stock_quantitys[$sc]-$currents[$bb];
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

// dd()

      $rest = min($current_stock);
         $rest1 = min($stock_quantity);
    //   echo "<pre>";
      $current_stockss[$s]['data'] = $product_data;
      $current_stockss[$s]['min'] = $rest;

// dd($current_stockss);

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
 //}

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
    $newNumber1 = $number1 - $currents[$o];
    $newNumber2 = $number2 - $currents[$o];
    
    $newNumber11 = $number11 - $currents[$o];
    $newNumber22 = $number22 - $currents[$o];
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

// print_r($resultArray);


// for($aa = 0 ; $aa < count($resultArray1);$aa++) {
//      Product::where('id',$btestid[$aa])->update(['current_stock'=>$resultArray[$aa],'stock_quantity'=>$resultArray1[$aa]]);
// }

// Product::where('id',$product_ids[$k])->update(['current_stock'=>$current_min,'stock_quantity'=>$stock_min]);
    
  
 // }


//}   
$bpqty = explode(',',$product_bund_qty[0]->product_id);

// print_r($bpqty);

    
     $result22 = $woocommerce->get('products/'.$bpqty[$sc]);


     


     $pDB_PRODUCT_STOCKk[] = $result22->bundle_stock_quantity;
     
     $pDB_PRODUCT_STOCKks = implode(',',$pDB_PRODUCT_STOCKk);
} 


for($aa = 0 ; $aa < count($resultArray1);$aa++) {

     Product::where('id',$btestid[$aa])->update(['current_stock'=>$resultArray[$aa],'stock_quantity'=>$resultArray1[$aa]]);
}



Product::where('id',$product_ids[$k])->update(['current_stock'=>$pDB_PRODUCT_STOCKks,'stock_quantity'=>$pDB_PRODUCT_STOCKks]);
 
}
}          ////////ELSE BOTH SIMLE BRACKET/////////////




}

}

$subtotal =$grand_total;
// dd($subtotal);
// $sum = array_sum($arr);

$discountPercentage = $data['discount_per'];

// Convert $total to a numeric value (in case it's a string)
$total = floatval($subtotal);
$discountPercentage = floatval($discountPercentage);


$discountAmount = ($discountPercentage / 100) * $subtotal;

$grandTotal = $subtotal - $discountAmount;


$gst = $grandTotal / 11;

$roundedGst = round($gst, 0);
if($data['freight']){

$formattedNumber=$subtotal - $discountAmount + $data['freight']; //   freght add if exsit

}else{

  $formattedNumber=$subtotal-$discountAmount; //   freght add if exsit
}


$grandtotal = number_format($formattedNumber, 2);

$data['grand_totall'] =$grandtotal;

$data['discount_amount'] =$discountAmount;

$data['gst'] =$roundedGst;
$details=$data;

// dd($data);

         $pdf = PDF::loadView('Admin.quotation-pdf', ['details'=>$details]);
   
         Mail::send('quotationusermail', $details, function($message)use($details, $pdf) {
                    $message->to($details['email'])
                       ->subject($details["title"])
                    ->attachData($pdf->output(), "text.pdf");
                });

// $pdf = PDF::loadView('Admin.quotation-pdf', ['details'=>$details]);

//       ini_set('memory_limit', '2048M');
//       return $pdf->download('inventory-management.pdf');

return redirect()->back()->with('message', 'Invoice Created successfully!');



     
}


 public function purchase()
  {
    
    return view('Admin.add-purchase');
  }

  public function purchase_product(Request $request)

  {
    $id = $request->input('id');

$price = [];
    $ids = [];
    $stock = [];

      
         $category = Product::where('id', $id)->get(['sales_price','id','product_id','current_stock','store','sku']);
         
         $stores = explode(',',$category[0]->store);
         
         $count = count($stores);
         
         $s = explode(',',$category[0]->current_stock);
         
         if($count > 1){
                for ($i = 0; $i < count($category); $i++) {
      array_push($ids, $category[$i]->id);
      array_push($price, $category[$i]->sku);
      array_push($stock, min($s));
    }
      // array_push(min(explode(',',$category[0]->current_stock)));
         }else{
       
    for ($i = 0; $i < count($category); $i++) {
      array_push($ids, $category[$i]->id);
      array_push($price, $category[$i]->sku);
      array_push($stock, $category[$i]->current_stock);
    }
         }            
          
    return response()->json(['success' => true, 'price' => $price, 'ids' => $id,'stock'=>$stock]);
  }


     public function add_purchase(Request $request) {

// dd($request->all());

$product_id = $request->product_id;
$product_sku = $request->product_sku;
$product_quantity = $request->product_quantity;
$product_cost = $request->product_cost;

  $id =[];
    for($i=0;$i<count($product_id);$i++){
    $products = Product::where('id',$product_id[$i])->get();
    $meta = Meta_details::where('p_id',$product_id[$i])->get(['product_id','store_id']);
  $stores = explode(',',$products[0]->store);
  
$current_stock = $products[0]->current_stock; // Assuming $products[0]->current_stock is "40,40,40"

// Explode the current stock string into an array
$current_array = explode(',', $current_stock);
$totals = [];
// Loop through each element of the array and add 1
foreach ($current_array as $value) {
  $totals[]=  $value + $product_quantity[$i]; // Convert to integer and add 1
}
// dd($totals);

$unique=array_unique($totals);


      $data = [
        'sku' => $product_sku[$i],
        'manage_stock' => true,
        'stock_quantity'=>$unique[0]
      ];
      

    
 try {
     
 for($s=0;$s<count($meta);$s++){
             $store = Store::where('id',$meta[$s]->store_id)->get();
         $woocommerce = new Client(
        $store[0]->store_url,
        $store[0]->key,
        $store[0]->secret_key,
        [
          'version' => 'wc/v3',
            'timeout' => 400 // curl timeout
        ]
      );
     
   
 
   
      $result = $woocommerce->put('products/' . $meta[$s]->product_id, $data);
}
 
           if($i==0){
                $product = new Purchase;
    $product->purchase_from = $request->purchase_from;
    $product->product_id = $product_id[$i];
    $product->invoice_id = $request->invoice_id;
    $product->cost_price = $product_cost[$i];
    $product->product_sku = $product_sku[$i];
    $product->date = $request->date;
    $product->product_quantity = $product_quantity[$i];
    $product->status = '0';
    $product->save();
    $id[0]=$product->id;
    
    
    
           }else{
                 $product = new Purchase;
    $product->purchase_from = $request->purchase_from;
    $product->product_id = $product_id[$i];
    $product->invoice_id = $request->invoice_id;
    $product->cost_price = $product_cost[$i];
    $product->product_sku = $product_sku[$i];
    $product->date = $request->date;
    $product->product_quantity = $product_quantity[$i];
    $product->status = $id[0];
    $product->save();
    
           }
           
    
    $count = count($stores);
    if($count == 1){
    $totals1=    array_unique($totals);
        $totals2=   $totals1[0];
        
    Product::where('id',$product_id[$i])->update(['cost_price'=>$product_cost[$i],'current_quantity'=>$totals2,'current_stock'=>$totals2,'stock_quantity'=>$totals2]);
    }elseif($count == 2){
            $totals1=    array_unique($totals);
        $totals2=   $totals1[0];
        
         $quantity[0]= $totals2;
          $quantity[1]=$totals2;
          Product::where('id',$product_id[$i])->update(['cost_price'=> $product_cost[$i],'current_quantity'=>implode(',',$quantity),'current_stock'=>implode(',',$quantity),'stock_quantity'=>implode(',',$quantity)]);
    }elseif($count == 3){
            $totals1=    array_unique($totals);
        $totals2=   $totals1[0];
        
       $quantity[0]= $totals2;
          $quantity[1]=$totals2;
         $quantity[2]= $totals2;

          Product::where('id',$product_id[$i])->update(['cost_price'=> $product_cost[$i],'current_quantity'=>implode(',',$quantity),'current_stock'=>implode(',',$quantity),'stock_quantity'=>implode(',',$quantity)]);
    }

 }
 catch (\Exception $e) {
      throw new \Exception("The error is " . $e->getMessage(), 1);
    }
     }
     

       return redirect()->back()->with('message', 'Purchase Successfully Added!');
     
}


public function edit_purchase($id) {


    $data = Purchase::where('id',$id)->get();

    //  $data1 = Purchase::where('status',$id)->get();
     
          $data1 = Purchase::where('status',$id)->orWhere('id',$id)->get();
    //  dd($data1);

    return view('Admin.edit-purcahse', ['data' => $data,'data1' => $data1]);
}

public function update_purchase(Request $request) {
    
    // dd($request->all());
    
    try {
        
       $id = $request->id;
$product_ids = $request->product_id;
$invoice_id = $request->invoice_id;
$product_quantity = $request->product_quantity;
$product_sku = $request->product_sku;

// Update existing purchase with the given invoice_id
$user2 = Purchase::where('invoice_id', $invoice_id)->update([
    'product_sku' => $product_sku[0],
    'product_quantity' => $product_quantity[0],
    'cost_price' => $request->product_cost[0],
    'date' => $request->date,
    'status' => '0'
]);

// Remove existing purchases associated with the given status
Purchase::where('status', $id)->delete();

// Insert new purchases
for ($index = 1; $index < count($product_ids); $index++) {
    $new_purchase = new Purchase;
    $new_purchase->purchase_from = $request->purchase_from;
    $new_purchase->product_id = $product_ids[$index];
    $new_purchase->invoice_id = '';
    $new_purchase->cost_price = $request->product_cost[$index];
    $new_purchase->product_sku = $product_sku[$index];
    $new_purchase->date = $request->date;
    $new_purchase->product_quantity = $product_quantity[$index];
    $new_purchase->status = $id;
    $new_purchase->save();
}

// Update products in WooCommerce
for ($i = 0; $i < count($product_ids); $i++) {
    $productss = Product::where('id', $product_ids[$i])->get();
    $meta = Meta_details::where('p_id', $product_ids[$i])->get(['product_id', 'store_id']);
    $stores = explode(',', $productss[0]->store);
    
    
    $current_stock = $productss[0]->current_stock; // Assuming $products[0]->current_stock is "40,40,40"

// Explode the current stock string into an array
$current_array = explode(',', $current_stock);
$totals = [];
// Loop through each element of the array and add 1
foreach ($current_array as $value) {
  $totals[]=  $value + $product_quantity[$i]; // Convert to integer and add 1
}
// dd($totals);

$unique=array_unique($totals);
    
    
//   dd($unique);
    
    
    
    
    
    $data = [
        'sku' => $product_sku[$i],
        'manage_stock' => true,
        'stock_quantity' => $unique[0]
    ];

    for ($s = 0; $s < count($meta); $s++) {
        $store = Store::where('id', $meta[$s]->store_id)->get();
        $woocommerce = new Client(
            $store[0]->store_url,
            $store[0]->key,
            $store[0]->secret_key,
            [
                'version' => 'wc/v3',
                'timeout' => 400 // curl timeout
            ]
        );
        $result = $woocommerce->put('products/' . $meta[$s]->product_id, $data);
    }

    $count = count($stores);
    if ($count == 1) {
         $totals1=    array_unique($totals);
        $totals2=   $totals1[0];
        Product::where('id', $product_ids[$i])->update([
            'cost_price' => $request->product_cost[$i],
            'current_quantity' => $totals2,
            'current_stock' => $totals2,
            'stock_quantity' => $totals2
        ]);
    } elseif($count == 2) {
                $totals1=    array_unique($totals);
        $totals2=   $totals1[0];
        
         $quantity[0]= $totals2;
          $quantity[1]=$totals2;
        Product::where('id', $product_ids[$i])->update([
            'cost_price' => $request->product_cost[$i],
            'current_quantity' => implode(',', $quantity),
            'current_stock' => implode(',', $quantity),
            'stock_quantity' => implode(',', $quantity)
        ]);
    }elseif($count == 3) {
        
        
            $totals1=    array_unique($totals);
        $totals2=   $totals1[0];
        
       $quantity[0]= $totals2;
          $quantity[1]=$totals2;
         $quantity[2]= $totals2;
         
         
         
         
        Product::where('id', $product_ids[$i])->update([
            'cost_price' => $request->product_cost[$i],
            'current_quantity' => implode(',', $quantity),
            'current_stock' => implode(',', $quantity),
            'stock_quantity' => implode(',', $quantity)
        ]);
    }
}
        
        
         return redirect()->back()->with('message', 'Purchase Successfully Updated!');    


    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
}







     
 public function purchase_list()
  {
      
$orders = Purchase::where('status', '0')->get();


    // dd($orders);
    return view('Admin.purchase-list',['data'=>$orders]);
  }


public function purchase_pdf($id) {
    $orders = Purchase::where('status', $id)->orWhere('id', $id)->get();
    $orderss = Purchase::where('status', 0)->get(); // I'm assuming this is another part of your logic, where you fetch orders with status 0.

    $data = [];
    $product_name = [];
    $product_price = [];
    $product_quantity = [];
  $product_sku = [];
    foreach ($orders as $order) {
        $data['purchase_from'] = $order->purchase_from;
        $data['invoice_id'] = $order->invoice_id;
        $data['date'] = $order->date;

        // Fetch product name for the current order
        $product = Product::find($order->product_id);
        if ($product) {
            $product_name[] = $product->title;
        } else {
            $product_name[] = 'Product Not Found'; // Or handle the case where product is not found
        }

        $product_price[] = $order->cost_price;
        $product_quantity[] = $order->product_quantity;
        $product_sku[] = $order->product_sku;
    }

    $data['product_name'] = $product_name;
    $data['product_price'] = $product_price;
    $data['product_quantity'] = $product_quantity;
    $data['invoice_id'] = $orderss[0]->invoice_id; // Are you sure you want to override invoice_id here? It's already set above.
    $data['invoice_date'] = $orderss[0]->date;
    $data['product_sku'] = $product_sku;

    // dd($data);

    $pdf = PDF::loadView('Admin.purchase-pdf', ['details'=>$data]);

     // $arr=Quantation::where('id',$id)->get();

     //  $pdf = PDF::loadView('Admin.quotation-pdf', ['arr' => $arr]);

      ini_set('memory_limit', '2048M');

      return $pdf->download('inventory-management.pdf');
}












}










