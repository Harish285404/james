@extends('Admin.layouts.app')


@section('content')
          <?php
  //          $a=1; 

  // dd($data[4]);
                 ?>

<main class="main-dashboard">
  <section class="categories-list product-list report-overview">

    <div class="recent-sales">

      <div class="input-search-box-container">
        <h2 class="font-26">Reports Overview</h2>
      </div>

      <div class="submit_section">
        <div class="date">
          <form class="search-form-box" action="{{url('admin/reports')}}" method="get">
            <input type="date" id="calender" name="start_date" placeholder="select date">
            <input type="date" id="calender" name="end_date">
            <button class="main-btn search-btn" type="submit">Search</button>
          </form>
        </div>
        <div class="date">
          <!-- <a class="main-btn" href="{{url('admin/generate-pdf')}}">Download PDF</a> -->
        </div>
      </div>

      <section>


        <div class="table-tabing">
          <!-- Nav tabs -->
          <ul class="nav nav-tabs" role="tablist" id="bologna-list">
            <li class="nav-item">
              <a class="nav-link active" data-toggle="tab" href="#home" role="tab">stocktake report</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#profile" role="tab">stock reconciliation report</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#messages" role="tab">inventory report</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#settings" role="tab">sales report</a>
            </li>
          </ul>
        </div>
        <!-- Tab panes -->
        <div class="tab-content">
          <div class="tab-pane active" id="home" role="tabpanel">
            <div class="input-search-box-container">
              <h2 class="font-26 second">Stocktake Report</h2>
              <a class="pdf-btn main-btn" href="{{url('admin/generate-pdf')}}">Download PDF</a>
            </div>
                                                                      <div class="sorting_tab">
                <label for="sortSelect">Sort By:</label>
<select id="sortSelect">
     <option value="product_name_asc">Product Name (Asc)</option>
    <option value="product_name_desc">Product Name (Desc)</option>
    <option value="sku_asc">Sku (Asc)</option>
    <option value="sku_desc">Sku (Desc)</option>
    <option value="current_qty_asc">Current Qty (Asc)</option>
    <option value="current_qty_desc">Current Qty (Desc)</option>

  </select>

</div>
            <div class="main-table">
              <table id="yourDataTable1" class="display table table-striped table-bordered">
                <thead>
                  <tr>
                 
                    <th>Product Name</th>
                    <th>Sku</th>
                    <th>Location</th>
                    <th>Quantity</th>
                     <th>Cost price</th> 
                     <th>Sale price</th> 
                  </tr>
                </thead>
                <tbody>
           
       
                  @foreach($data as $product)

           
                  <?php
                

           $array = explode(',', $product->category_id);

           $store = explode(',', $product->store);
            
    if(count($store) > 1){
       $array = explode('-', $product->varient);
            $cids = [];
        for ($j = 0; $j < count($array); $j++) {
    
          array_push($cids, $array[$j]);
        }
    }else{
            $array = explode(',', $product->category_id);
            $cids = [];
              for ($j = 0; $j < count($array); $j++) {
          
                array_push($cids, $array[$j]);
              }
          } 

      $category = App\Models\Categories::whereIn('category_id',$cids)->get(['name']);



                  ?>
 
            
                  <tr>

            



                    <td>{{$product->title}}</td>
                    <td>{{$product->sku}}</td>

                    <td>
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

$stock = explode(',',$product->current_stock);
$stocks = array_unique($stock);
                        ?>
               

                    </td>
                 
                    <td><?php echo implode(',',$stocks) ?></td>
            <td>{{ isset($product->cost_price) ? $product->cost_price : '-' }}</td>

                    <td>{{$product->sales_price}}</td>

                  </tr>
                              @endforeach
                  
                </tbody>
                <tfooter></tfooter>
              </table>


            </div>

          </div>

          <div class="tab-pane" id="profile" role="tabpanel">
            <div class="input-search-box-container">
              <h2 class="font-26 second">Stock Reconciliation Report</h2>
              <a class="main-btn" href="{{url('admin/reconciliation-pdf')}}">Download PDF</a>
            </div>
        <div class="sorting_tab">
                <label for="sortSelect1">Sort By:</label>
  <select id="sortSelect1">
    <option value="product_name_asc">Product Name (Asc)</option>
    <option value="product_name_desc">Product Name (Desc)</option>
    <option value="sku_asc">Sku (Asc)</option>
    <option value="sku_desc">Sku (Desc)</option>
 <!--    <option value="current_qty_asc">Current Quantity (Asc)</option>
    <option value="current_qty_desc">Current Quantity (Desc)</option>
    <option value="actual_count_asc">Actual Count (Asc)</option>
    <option value="actual_count_desc">Actual Count (Desc)</option> -->
   <!-- <option value="location_asc">Location Asc</option> -->
  
    <!-- <option value="location_desc">Location Desc</option> -->

  </select>
</div>

            <div class="main-table">
              <table id="table1" class="display table table-striped table-bordered">
                <thead>
                  <tr>
                 
                    <th>Product Name</th>
                    <th>Sku</th>
                    <th>Location</th>
                    <th>Quantity</th>
                    <th>Adjustment</th>
                    <!-- <th>Action</th> -->
                  </tr>
                </thead>
                <tbody>
             
                  @foreach($data as $product)

                  <tr>

                   



                    <td>{{$product->title}}</td>
                    <td>{{$product->sku}}</td>

                    <td>  
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
         $stock = explode(',',$product->current_stock);
$stocks = array_unique($stock);
                                ?>
                          
                        </td>
                     <td><?php echo implode(',',$stocks) ?></td>
                    <td>{{$product->adjustment_stock}}</td>



                    <!--  <td>
  <button type="button" class="btn btn-info" data-toggle="modal" value="{{$product->id}}" data-target="#edit-modal">
                edit
              </button>
</td> -->

                  </tr>
             
                  @endforeach
                </tbody>
                <tfooter></tfooter>
              </table>
            </div>
          </div>
          <div class="tab-pane" id="messages" role="tabpanel">
            <div class="input-search-box-container">
              <h2 class="font-26 second">Inventory Report</h2>
              <a class="pdf-btn main-btn" href="{{url('admin/Inventory-pdf')}}">Download PDF</a>
            </div>
      <div class="sorting_tab">
                <label for="sortSelect2">Sort By (Table 2):</label>
  <select id="sortSelect2">

    <option value="product_name_asc">Product Name (Asc)</option>
       <option value="product_name_desc">Product Name (Desc)</option>
    <option value="sku_asc">Sku (Asc)</option>
    <option value="sku_desc">Sku (Desc)</option>
    <option value="opening_stock_asc">Opening Stock (Asc)</option>
    <option value="opening_stock_desc">Opening Stock (Desc)</option>
    <option value="stock_in_asc">Stock In (Asc)</option>
    <option value="stock_in_desc">Stock In (Desc)</option>
    <option value="stock_out_asc">Stock Out (Asc)</option>
    <option value="stock_out_desc">Stock Out (Desc)</option>
    <option value="price_asc">Price (Asc)</option>
    <option value="price_desc">Price (Desc)</option>
    <option value="value_asc">Value (Asc)</option>
    <option value="value_desc">Value (Desc)</option> 
     </select>

</div>
            <div class="main-table">
              <table id="table2" class="display table table-striped table-bordered">
                <thead>
                  <tr>
           
                    <th>Product Name</th>
                    <th>Sku</th>
                    <th>Opening Stock</th>
                    <th>Stock In</th>
                    <th>Stock Out</th>
                    <!--<th>Closing Stock</th>-->
                    <th>Cost Price</th>
                    <th>Value<br>(Stock in*Cost Price)</th>


                  </tr>
                </thead>
                <tbody>
          
                  @foreach($data as $product)

                  <tr>
<?php

  $stock = explode(',',$product->current_stock);
$stocks = array_unique($stock);

$qaun = explode(',',$product->stock_quantity);
$qauns = array_unique($qaun);

$tot = explode(',',$product->total_quantity);
$tots = array_unique($tot);
?>
              
                    <td>{{$product->title}}</td>
                    <td>{{$product->sku}}</td>
                    <td><?php echo implode(',',$qauns) ?></td>
                     <!--<td>{{$product->adjustment_stock}}</td>-->
                      <td><?php echo implode(',',$stocks) ?></td>
                     <td><?php echo implode(',',$tots) ?></td>
                    <!--<td>{{$product->current_stock}}</td>-->
                            
   
                    <td>
@if($product->cost_price==null)

<?php echo"-"; ?>

@else
 ${{$product->cost_price}}
@endif



                    </td>



                                             <td>


<?php  
if($product->sales_price === '') {
    // No sales price, so calculate total without sales
    $total = $product->current_stock;

    // Explode the total_quantity string into an array
    $a = explode(',', $total);

    // Get the cost price
    $b = $product->cost_price;

    $result = [];

    // Multiply corresponding elements
    foreach ($a as $value) {
        $result[] = $value * $b;
    }
 $results = array_unique($result);
    // Output the result
    $abc = implode(',', $results);
    

  echo '$' . $abc;
} else {
    // If sales price is set, calculate total with sales
   $total = $product->current_stock;
$numbers = explode(',', $total);

// Get the sales price
$multiplier = $product->cost_price;

$result = array();

// Multiply each quantity by sales price
foreach ($numbers as $number) {
    // Check if $number is numeric before performing multiplication
    if (is_numeric($number) && is_numeric($multiplier)) {
        $result[] = $number * $multiplier;
    } else {
        // Handle non-numeric values, or skip if appropriate
        // For example, you could set a default value or log an error
        $result[] = 0; // Set a default value of 0
        // Or skip this iteration and continue with the loop
        // continue;
    }
}
$results = array_unique($result);
// Implode the array into a string
$pp = implode(',', $results);

echo '$' . $pp;

}
?>

                       


                     </td>

                  </tr>
          
                  @endforeach
                </tbody>
                <tfooter></tfooter>
              </table>
            </div>
          </div>








          <div class="tab-pane" id="settings" role="tabpanel">
           <div class="graphs-main-tabbing">
                   <div class="input__field">
                       <form action="{{url('admin/reports')}} ">
                        <h2 class="input-heading">Store:</h2>
                        <select name="store" id="cstore" required>
                                 <option>Store</option>
                            <option value="1">store 1</option>
                            <option value="2">store 2</option>
                            <option value="3">store 3</option>
                            
                          </select>
                            <button type="submit">Go</button>
                             </form>
                    </div>
                  
                   
          
<div class="tab">
  <button class="tablinks" onclick="openCity(event, 'graph-tab-1')">Year</button>
  <button class="tablinks" onclick="openCity(event, 'graph-tab-2')">Last month</button>
  <button class="tablinks" onclick="openCity(event, 'graph-tab-3')">This month</button>
  <button class="tablinks " onclick="openCity(event, 'graph-tab-4')">Last 7 days</button>
 
  <div class="input-date-field">
  	<span>Custom:</span>
  <form class="search-form-box aee">

        <input class="calender date_ex" type="date"  name="calender" id="expenses_Start_calenderDate" placeholder="dd-mm-yyyy">
          <input class="calender date_ex" type="date" name="calender" id="expenses_End_calenderDate" placeholder="dd-mm-yyyy">
          <button class="tablinks active go-btn" onclick="openCity(event, 'graph-tab-5')" id="expenses_search" type="button">go</button>
       </form>
</div>

</div>

</div>
<!-- year -->
 

<div id="graph-tab-1" class="tabcontent" style="display: block;">
<div class="table-graph-section-main">
              
                 <div class="left-section">
                <ul class="graph-list-items">
                  <li>
                    <p>${{$year[0]->total_sales}}</p>
                      <span>gross sales in this period</span>
                  </li>
                  <li>
                    <p>${{$year[0]->average_sales}}</p>
                      <span>average gross daily sales</span>
                  </li>
                  <li>
                    <p>${{$year[0]->net_sales}}</p>
                      <span>net sales in this period</span>
                  </li>
                  <li>
                    <p>${{$year[0]->average_sales}}</p>
                      <span>average net daily sales</span>
                  </li>
                  <li>
                    <p>{{$year[0]->total_orders}}</p>
                        <span>orders places</span>
                  </li>
                  <li>
                    <p>{{$year[0]->total_items}}</p>
                      <span>items purchased</span>
                  </li>
                  <li>
                    <p>${{$year[0]->total_refunds}}</p>
                      <span>refunded 0 orders (0 graph-list-items)</span>
                  </li>
                  <li>
                    <p>${{$year[0]->total_shipping}}</p>
                    <span>charged for shipping</span>
                  </li>
                  <li>
                    <p>${{$year[0]->total_discount}}</p>
                      <span>worth of coupens used</span>
                  </li>
                </ul>
              </div>

              <div class="right-section">
              	<div class="graph-image">
              	   <div>
                      <canvas id="chart1"></canvas>
                    </div>
              	</div>
              </div>

              </div>
</div>

<!-- last month -->

<div id="graph-tab-2" class="tabcontent" style="display: none;">
<div class="table-graph-section-main">
              
           <div class="left-section">
                <ul class="graph-list-items">
                  <li>
                    <p>${{$last_month[0]->total_sales}}</p>
                      <span>gross sales in this period</span>
                  </li>
                  <li>
                    <p>${{$last_month[0]->average_sales}}</p>
                      <span>average gross daily sales</span>
                  </li>
                  <li>
                    <p>${{$last_month[0]->net_sales}}</p>
                      <span>net sales in this period</span>
                  </li>
                  <li>
                    <p>${{$last_month[0]->average_sales}}</p>
                      <span>average net daily sales</span>
                  </li>
                  <li>
                    <p>{{$last_month[0]->total_orders}}</p>
                        <span>orders places</span>
                  </li>
                  <li>
                    <p>{{$last_month[0]->total_items}}</p>
                      <span>items purchased</span>
                  </li>
                  <li>
                    <p>${{$last_month[0]->total_refunds}}</p>
                      <span>refunded 0 orders (0 graph-list-items)</span>
                  </li>
                  <li>
                    <p>${{$last_month[0]->total_shipping}}</p>
                    <span>charged for shipping</span>
                  </li>
                  <li>
                    <p>${{$last_month[0]->total_discount}}</p>
                      <span>worth of coupens used</span>
                  </li>
                </ul>
              </div>

              <div class="right-section">
              	<div class="graph-image">
              		   <div>
                        <canvas id="chart2"></canvas>
                    </div>
              	</div>
              </div>

              </div>
</div>

<!-- month -->

<div id="graph-tab-3" class="tabcontent" style="display: none;">
 <div class="table-graph-section-main">
              
               <div class="left-section">
                <ul class="graph-list-items">
                  <li>
                    <p>${{$month[0]->total_sales}}</p>
                      <span>gross sales in this period</span>
                  </li>
                  <li>
                    <p>${{$month[0]->average_sales}}</p>
                      <span>average gross daily sales</span>
                  </li>
                  <li>
                    <p>${{$month[0]->net_sales}}</p>
                      <span>net sales in this period</span>
                  </li>
                  <li>
                    <p>${{$month[0]->average_sales}}</p>
                      <span>average net daily sales</span>
                  </li>
                  <li>
                    <p>{{$month[0]->total_orders}}</p>
                        <span>orders places</span>
                  </li>
                  <li>
                    <p>{{$month[0]->total_items}}</p>
                      <span>items purchased</span>
                  </li>
                  <li>
                    <p>${{$month[0]->total_refunds}}</p>
                      <span>refunded 0 orders (0 graph-list-items)</span>
                  </li>
                  <li>
                    <p>${{$month[0]->total_shipping}}</p>
                    <span>charged for shipping</span>
                  </li>
                  <li>
                    <p>${{$month[0]->total_discount}}</p>
                      <span>worth of coupens used</span>
                  </li>
                </ul>
              </div>

              <div class="right-section">
              	<div class="graph-image">
                          	   <div>
                    <canvas id="chart3"></canvas>
                </div>
              	</div>
              </div>

              </div>
</div>

<!-- last 7 days -->

<div id="graph-tab-4" class="tabcontent" style="display: none;">
 <div class="table-graph-section-main">
              
              <div class="left-section">
              	<ul class="graph-list-items">
              		<li>
              			<p>${{$days[0]->total_sales}}</p>
              				<span>gross sales in this period</span>
              		</li>
              		<li>
              			<p>${{$days[0]->average_sales}}</p>
              				<span>average gross daily sales</span>
              		</li>
              		<li>
              			<p>${{$days[0]->net_sales}}</p>
              				<span>net sales in this period</span>
              		</li>
              		<li>
              			<p>${{$days[0]->average_sales}}</p>
              				<span>average net daily sales</span>
              		</li>
              		<li>
              			<p>{{$days[0]->total_orders}}</p>
              			    <span>orders places</span>
              		</li>
              		<li>
              			<p>{{$days[0]->total_items}}</p>
              				<span>items purchased</span>
              		</li>
              		<li>
              			<p>${{$days[0]->total_refunds}}</p>
              				<span>refunded 0 orders (0 graph-list-items)</span>
              		</li>
              		<li>
              			<p>${{$days[0]->total_shipping}}</p>
              			<span>charged for shipping</span>
              		</li>
              		<li>
              			<p>${{$days[0]->total_discount}}</p>
              				<span>worth of coupens used</span>
              		</li>
              	</ul>
              </div>

              <div class="right-section">
              	<div class="graph-image">
                  
                  <!--<canvas id="myChart"></canvas>-->
                   <div>
                      <canvas id="chart4"></canvas>
                    </div>
              	</div>
              </div>

              </div>
			</div>
      <div id="graph-tab-5" class="tabcontent" style="display: none;">
      <div class="table-graph-section-main">
              
              <div class="left-section">
                <ul class="graph-list-items filter-expenses">
                 
                </ul>
              </div>

              <div class="right-section">
                <div class="graph-image" id="graph">

                     <div>
                      <canvas id="chart5"></canvas>
                    </div>
                </div>
              </div>

              </div>
      </div>
			</div>
			   </div>
         
          </div>
        </div>
      </section>
    </div>
    </div>
  </section>
</main>
<script>
  $('#bologna-list a').on('click', function(e) {
    e.preventDefault()
    $(this).tab('show')
  })
</script>

<script src="https://cdn.datatables.net/v/bs-3.3.7/jq-2.2.4/jszip-3.1.3/pdfmake-0.1.27/dt-1.10.15/af-2.2.0/b-1.3.1/b-colvis-1.3.1/b-flash-1.3.1/b-html5-1.3.1/b-print-1.3.1/cr-1.3.3/fc-3.2.2/fh-3.1.2/kt-2.2.1/r-2.1.1/rg-1.0.0/rr-1.2.0/sc-1.4.2/se-1.2.2/datatables.js"></script>
<link href="https://cdn.datatables.net/v/bs-3.3.7/jq-2.2.4/jszip-3.1.3/pdfmake-0.1.27/dt-1.10.15/af-2.2.0/b-1.3.1/b-colvis-1.3.1/b-flash-1.3.1/b-html5-1.3.1/b-print-1.3.1/cr-1.3.3/fc-3.2.2/fh-3.1.2/kt-2.2.1/r-2.1.1/rg-1.0.0/rr-1.2.0/sc-1.4.2/se-1.2.2/datatables.css" rel="stylesheet" />
</script>




<script>
function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
   <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>

   <script>
        // Data for Chart 1
        const chart1Data = {
            labels:<?php  echo json_encode($year_dates_Name);?>,
            datasets: [
                {
                   label: 'Item Purchased',
                    type: 'line',
                    data: <?php  echo json_encode($year_items);?>,
                    yAxisID: 'y-axis-1', // Assign to the first Y-axis
                },
                {
                    label: 'Sales',
                    type: 'bar',
                    data: <?php  echo json_encode($year_sales);?>,
                    yAxisID: 'y-axis-2', // Assign to the second Y-axis
                },
            ],
        };

        // Data for Chart 2
        const chart2Data = {
          labels:<?php  echo json_encode($last_month_dates_Name);?>,
            datasets: [
              {
                    label: 'Item Purchased',
                    type: 'line',
                      data: <?php  echo json_encode($last_month_items);?>,
              
                    yAxisID: 'y-axis-1', // Assign to the first Y-axis
                },
                {
                    label: 'Sales',
                    type: 'bar',
                      data: <?php  echo json_encode($last_month_sales);?>,
                    yAxisID: 'y-axis-2', // Assign to the second Y-axis
                },
            ],
        };

        // Chart 1
        const ctx1 = document.getElementById('chart1').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: chart1Data,
            options: {
                plugins: {
                    datalabels: {
                        display: false,
                    },
                },
                scales: {
                    x: {
                        stacked: true,
                    },
                    'y-axis-1': {
                        position: 'left',
                        beginAtZero: true,
                    },
                    'y-axis-2': {
                        position: 'right',
                        beginAtZero: true,
                    },
                },
            },
        });

        // Chart 2
        const ctx2 = document.getElementById('chart2').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: chart2Data,
            options: {
                plugins: {
                    datalabels: {
                        display: false,
                    },
                },
                scales: {
                    x: {
                        stacked: true,
                    },
                    'y-axis-1': {
                        position: 'left',
                        beginAtZero: true,
                    },
                    'y-axis-2': {
                        position: 'right',
                        beginAtZero: true,
                    },
                },
            },
        });
    </script>
    
    
     <script>
        // Data for Chart 3
        const chart3Data = {
            labels:   <?php  echo json_encode($month_Name);?>,
            datasets: [
               {
                    label: 'Item Purchased',
                    type: 'line',
                      data: <?php  echo json_encode($month_items);?>,
                    yAxisID: 'y-axis-1', // Assign to the first Y-axis
                },
                {
                    label: 'Sales',
                    type: 'bar',
                     data: <?php  echo json_encode($month_sales);?>,
                    yAxisID: 'y-axis-2', // Assign to the second Y-axis
                },
            ],
        };

        // Data for Chart 4
        const chart4Data = {

            labels:   <?php  echo json_encode($monthName);?>,
            datasets: [
                {
                    label: 'Item Purchased',
                    type: 'line',
                    data:   <?php  echo json_encode($days_items);?>,
                    yAxisID: 'y-axis-1', // Assign to the first Y-axis
                },
                {
                    label: 'Sales',
                    type: 'bar',
                    data:  <?php  echo json_encode($days_sales);?>,
                    yAxisID: 'y-axis-2', // Assign to the second Y-axis
                },
            ],
        };

        // Chart 3
        const ctx3 = document.getElementById('chart3').getContext('2d');
        new Chart(ctx3, {
            type: 'bar',
            data: chart3Data,
            options: {
                plugins: {
                    datalabels: {
                        display: false,
                    },
                },
                scales: {
                    x: {
                        stacked: true,
                    },
                    'y-axis-1': {
                        position: 'left',
                        beginAtZero: true,
                    },
                    'y-axis-2': {
                        position: 'right',
                        beginAtZero: true,
                    },
                },
            },
        });

        // Chart 4
        const ctx4 = document.getElementById('chart4').getContext('2d');
        new Chart(ctx4, {
            type: 'bar',
            data: chart4Data,
            options: {
                plugins: {
                    datalabels: {
                        display: false,
                    },
                },
                scales: {
                    x: {
                        stacked: true,
                    },
                    'y-axis-1': {
                        position: 'left',
                        beginAtZero: true,
                    },
                    'y-axis-2': {
                        position: 'right',
                        beginAtZero: true,
                    },
                },
            },
        });
    </script>





     <script>
        // Data for Chart 5
        const chart5Data = {
         labels: [],
            datasets: [
               {
                    label: 'Item Purchased',
                    type: 'line',
                    data: [],
                    yAxisID: 'y-axis-1', // Assign to the first Y-axis
                },
                {
                    label: 'Sales',
                    type: 'bar',
                    data: [],
                    yAxisID: 'y-axis-2', // Assign to the second Y-axis
                },
            ],
        };


        // Chart 3
        const ctx5 = document.getElementById('chart5').getContext('2d');
        var chart5= new Chart(ctx5, {
            type: 'bar',
            data: chart5Data,
            options: {
                plugins: {
                    datalabels: {
                        display: false,
                    },
                },
                scales: {
                    x: {
                        stacked: true,
                    },
                    'y-axis-1': {
                        position: 'left',
                        beginAtZero: true,
                    },
                    'y-axis-2': {
                        position: 'right',
                        beginAtZero: true,
                    },
                },
            },
        });

    </script>

     <script>
    $(document).ready(function() {
      var table = $('#yourDataTable1').DataTable({
        "order": [[0, "asc"]] // Initial sorting by the first column (Sr. No.)
      });

      $('#sortSelect').change(function() {
        var selectedValue = $(this).val();
        var columnIndex;
        var sortDirection = 'asc';

        switch (selectedValue) {
          case 'product_name_asc':
            columnIndex = 0; // Index of the "Product Name" column
            break;
          case 'product_name_desc':
            columnIndex = 0; // Index of the "Product Name" column
            sortDirection = 'desc';
            break;
          case 'sku_asc':
            columnIndex = 1; // Index of the "Sku" column
            break;
          case 'sku_desc':
            columnIndex = 1; // Index of the "Sku" column
            sortDirection = 'desc';
            break;
          case 'current_qty_asc':
            columnIndex = 5; // Index of the "Current Qty" column
            break;
          case 'current_qty_desc':
            columnIndex = 5; // Index of the "Current Qty" column
            sortDirection = 'desc';
            break;
          case 'actual_count_asc':
            columnIndex = 6; // Index of the "Actual Count" column
            break;
          case 'actual_count_desc':
            columnIndex = 6; // Index of the "Actual Count" column
            sortDirection = 'desc';
            break;
          default:
            columnIndex = 0; // Default to sorting by the first column (Sr. No.)
            break;
        }

        table.order([columnIndex, sortDirection]).draw(); // Sorting by the selected column and direction
      });
    });
  </script> 

 <script>
    $(document).ready(function() {
      // Apply DataTable to the first table
      var table1 = $('#table1').DataTable({
        "order": [[0, "asc"]] // Initial sorting by the first column (Product Name)
      });

      // Apply DataTable to the second table
      var table2 = $('#table2').DataTable({
        "order": [[1, "asc"]] // Initial sorting by the second column (Sr. No.)
      });

      // Add sorting to the first table when selecting an option
      $('#sortSelect1').change(function() {
        var selectedValue = $(this).val();
        var columnIndex;
        var order;

        switch (selectedValue) {
          case 'product_name_asc':
            columnIndex = 0; // Index of the "Product Name" column
            order = 'asc';
            break;
          case 'sku_asc':
            columnIndex = 1; // Index of the "Sku" column
            order = 'asc';
            break;
          case 'location_asc':
            columnIndex = 2; // Index of the "Location" column
            order = 'asc';
            break;
          case 'current_qty_asc':
            columnIndex = 3; // Index of the "Current Quantity" column
            order = 'asc';
            break;
          case 'actual_count_asc':
            columnIndex = 4; // Index of the "Actual Count" column
            order = 'asc';
            break;
          case 'product_name_desc':
            columnIndex = 0; // Index of the "Product Name" column
            order = 'desc';
            break;
          case 'sku_desc':
            columnIndex = 1; // Index of the "Sku" column
            order = 'desc';
            break;
          case 'location_desc':
            columnIndex = 2; // Index of the "Location" column
            order = 'desc';
            break;
          case 'current_qty_desc':
            columnIndex = 3; // Index of the "Current Quantity" column
            order = 'desc';
            break;
          case 'actual_count_desc':
            columnIndex = 4; // Index of the "Actual Count" column
            order = 'desc';
            break;
          default:
            columnIndex = 0; // Default to sorting by the first column (Product Name)
            order = 'asc';
            break;
        }

        table1.order([columnIndex, order]).draw(); // Sorting by the selected column and order
      });

      // Add sorting to the second table when selecting an option
      $('#sortSelect2').change(function() {
        var selectedValue = $(this).val();
        var columnIndex;
        var order;

        switch (selectedValue) {
          
          case 'product_name_asc':
            columnIndex = 0; // Index of the "Product Name" column
            order = 'asc';
            break;
          case 'sku_asc':
            columnIndex = 1; // Index of the "Sku" column
            order = 'asc';
            break;
          case 'opening_stock_asc':
            columnIndex = 2; // Index of the "Opening Stock" column
            order = 'asc';
            break;
          case 'stock_in_asc':
            columnIndex = 3; // Index of the "Stock In" column
            order = 'asc';
            break;
          case 'stock_out_asc':
            columnIndex = 4; // Index of the "Stock Out" column
            order = 'asc';
            break;
          case 'price_asc':
            columnIndex = 5; // Index of the "Price" column
            order = 'asc';
            break;
          case 'value_asc':
            columnIndex = 6; // Index of the "Value" column
            order = 'asc';
            break;
         
          case 'product_name_desc':
            columnIndex = 0; // Index of the "Product Name" column
            order = 'desc';
            break;
          case 'sku_desc':
            columnIndex = 1; // Index of the "Sku" column
            order = 'desc';
            break;
          case 'opening_stock_desc':
            columnIndex = 2; // Index of the "Opening Stock" column
            order = 'desc';
            break;
          case 'stock_in_desc':
            columnIndex = 3; // Index of the "Stock In" column
            order = 'desc';
            break;
          case 'stock_out_desc':
            columnIndex = 4; // Index of the "Stock Out" column
            order = 'desc';
            break;
          case 'price_desc':
            columnIndex = 5; // Index of the "Price" column
            order = 'desc';
            break;
          case 'value_desc':
            columnIndex = 6; // Index of the "Value" column
            order = 'desc';
            break;
          default:
            columnIndex = 0; // Default to sorting by the second column (Product Name)
            order = 'asc';
            break;
        }

        table2.order([columnIndex, order]).draw(); // Sorting by the selected column and order
      });
    });
  </script>
@endsection
