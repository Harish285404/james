<!DOCTYPE html>
<html lang="en">

<head>
    <title>PDF</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{asset('Admin/css/style.css')}}" rel="stylesheet">
</head>
<style>
 @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

  .main-dashboard{
    padding: 0;
    margin: 0;
  }

table {
    border-collapse: collapse;
}
</style>
<body>
    <main class="">
        <section class="categories-list product-list user-list">
                <div class="input-search-box-container">
                   
                </div>
                <div class="main-table">
                    <table style="width: 100%; padding: 30px 16px; text-align: left;">
                      
                        <tbody style="text-align: left;">
                           <td style="text-align: left; color: #0F5132;font-size: 24px;font-weight: 700; padding-bottom: 24px;">Stock Reconciliation report</td>
                            <tr>
                    <th style="text-align:left; background-color: #D9D9D9;border: 0 !important;color: #666 !important;padding: 12px !important; font-size: 13px; font-family: 'Poppins', sans-serif;">Sr. No.</th>
                    <th style="text-align:left; background-color: #D9D9D9;border: 0 !important;color: #666 !important;padding: 12px !important; font-size: 13px; font-family: 'Poppins', sans-serif;">Product Name</th>
                    <th style="text-align:left; background-color: #D9D9D9;border: 0 !important;color: #666 !important;padding: 12px !important; font-size: 13px; font-family: 'Poppins', sans-serif;">Sku</td>
                    <th style="text-align:left; background-color: #D9D9D9;border: 0 !important;color: #666 !important;padding: 12px !important; font-size: 13px; font-family: 'Poppins', sans-serif;">Location</th>
                    <th style="text-align:left; background-color: #D9D9D9;border: 0 !important;color: #666 !important;padding: 12px !important; font-size: 13px; font-family: 'Poppins', sans-serif;">Quantity</th>
                    <th style="text-align:left; background-color: #D9D9D9;border: 0 !important;color: #666 !important;padding: 12px !important; font-size: 13px; font-family: 'Poppins', sans-serif;">Adjustment</th> 
                   
                            </tr>
                                         <?php $i=1;?>
       @foreach($data as $product)
                             <tr>
       <td style="
    padding: 8px !important;
    border: #D9D9D9 1px solid !important; font-size: 10px; color: #666; font-weight: 700; "><?php echo $i;?></td>

          <td style="
    padding: 8px !important;
    border: #D9D9D9 1px solid !important; font-size: 10px; color: #666; font-weight: 700; ">{{$product->title}}</td>

          <td style="
    padding: 8px !important;
    border: #D9D9D9 1px solid !important; font-size: 10px; color: #666; font-weight: 700; ">{{$product->sku}}</td>

          <td style="
    padding: 8px !important;
    border: #D9D9D9 1px solid !important; font-size: 10px; color: #666; font-weight: 700; ">                             
    <?php
$array=explode(',',$product->store);
// dd($array);

 $storee = App\Models\Store::whereIn('id',$array)->get(['name']);
 $leng = count($storee);
$y=0;
 foreach ($storee as $store){

 echo $store->name;

 if ($y < $leng - 1) {
        echo ', ';
    } 
$y++;
 }


                        ?></td>

          <td style="
    padding: 8px !important;
    border: #D9D9D9 1px solid !important; font-size: 10px; color: #666; font-weight: 700; ">   {{ implode(',', array_unique(explode(',', $product->current_stock))) }}</td>

          <td style="
    padding: 8px !important;
    border: #D9D9D9 1px solid !important; font-size: 10px; color: #666; font-weight: 700; ">{{$product->adjustment_stock}}</td>

    
    
        </tr>
  <?php $i++;?>
         @endforeach
                        </tbody>
                    </table>
                </div>
        </section>
    </main>

</body>

</html>