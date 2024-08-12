@extends('Admin.layouts.app')


@section('content')

<main class="main-dashboard">
      @if(Session::has('message'))
<p class="alert alert-info">{{ Session::get('message') }}</p>
@endif
  <?php
 // $category = App\Models\Product::where('id','1')->get();
 // dd($category);
                    ?>
        <section class="add-category products-add">
             <!-- <button  type="button" href="#" class="main-btn"  id="wocommerce-button">Upload Wocommerce to store 1</button> -->
             <!-- <button href="{{ url('admin/orders2') }}" class="main-btn">Upload Wocommerce to store 2</button>-->
            <form class="category-form" method="POST" action="{{ url('admin/add-purchase') }}" >
                @csrf
                <div class="profile-flex-box">
                    <h2 class="font-26">Purchase</h2>
                </div>

                <div class="main-input-container">
                    <div class="input__field">
                        <h2 class="input-heading">Purchase From</h2>
                        <input type="text" id="name"  name="purchase_from" required>
               
                    </div>
                       <div class="input__field">
                        <h2 class="input-heading">Invoice Id</h2>
                   <input type="text" id="bname"  name="invoice_id" required>
               
                    </div>
                        
                

                    <?php
             $category = App\Models\Product::where('product_type','simple')->get(['id','title','sales_price']);
            //  dd($category);
                    ?>

    <div class="edit_-form-bottom ">
 
                       <div class="row">
                            <div class="col-lg-12">
                                <div id="inputFormRow">
                                    <div class="input-group mb-3 test">
                                        
                                        <div class="add-content tests">
                                            <div class=" add-content-flex second-add">
                                                <div class="field d-flex">
                                              <div class="input__field">

                                                 <h2 class="input-heading">Product Name</h2>
                                                    <select name="product_id[]" id="Purchase" class="product purchase_name" required>
                                                             <option value="">Select Products.....</option>
                                                        @foreach($category as $s)
                                                        <option value="{{$s->id}}">{{$s->title}}</option>

                                                        @endforeach
                                                      </select>
 <script>
    $(document).ready(function() {
      $('#Purchase').select2();
    });
  </script>
                                                   </div>
                        
    </div>
    </div>
                                            <div class=" add-content-flex second-add">
                                                  
                                               <div class="input__field" id="input__field_abc_simple_product" >
                    <h2 class="input-heading">Sku</h2>
                        <input type="text" id="product_sku" placeholder="Product Sku" class="product_sku" name="product_sku[]" required>
                    </div>
 
                                            </div>
                                            <div class=" add-content-flex second-add">
                                                <div class="field d-flex">
                                              <div class="input__field">
                        <h2 class="input-heading">Quantity</h2>
                        <input type="text" id="product_quantity" placeholder="product quantity" class="product_sku" name="product_quantity[]" required>
               
                    </div>
                                                </div>
                                            </div>
                                             <div class=" add-content-flex second-add">
                                                <div class="field d-flex">
                                              <div class="input__field">
                        <h2 class="input-heading">Cost Price</h2>
                        <input type="text" id="cost" placeholder="product cost" name="product_cost[]" class="product_cost" required>
               
                    </div>
                                                </div>
                                            </div>
                                            <div class="submit-btn">
                            <button type="button" id="remove" class="new-btn remove-btn ">
                                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.47217 10.4445L6.13867 7.11102L2.80517 10.4445L1.694 9.33336L5.0275 5.99985L1.694 2.66635L2.80517 1.55518L6.13867 4.88869L9.47218 1.55518L10.5833 2.66635L7.24984 5.99985L10.5833 9.33336L9.47217 10.4445Z" fill="#FF1010" stroke="#FF1010"></path></svg>
                                               Remove
                                                 </button>
                                             
                                                 
                                             </div>
                                        </div>
                                        <div id="newRow" class="newRow"></div>
                                    </div>
                                </div>
                                <div class="new-add-row-button">
                                   <button type="button" id="addpurchaserow" class="new-btn" >
                                                    <svg width="13" height="12" viewBox="0 0 13 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M12 6.78571H7.28571V11.5H5.71429V6.78571H1V5.21429H5.71429V0.5H7.28571V5.21429H12V6.78571Z" fill="white" stroke="white"></path>
                                                    </svg>
                                                     Add 
                                                 </button>
                                             </div>
                            </div>
                        </div>

                     <!--    <div class="submit-btn">
                            <button type="submit" class="new-btn">Save </button>
                        </div> -->
                    </div>


                       <div class="input__field">
                        <h2 class="input-heading">Date</h2>
                                      <input type="date" id="currentDatee" name="date">

<script>
    var today = new Date();
    var date = today.toISOString().split('T')[0];
    document.getElementById("currentDatee").value = date;
</script>
               
                    </div>
 
                <div class="main-button">
                    <button href="javascript: void(0)" class="main-btn">Submit</button>
                </div>
            </form>
        </section>
    </main>

@endsection