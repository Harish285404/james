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
            <form class="category-form" method="POST" action="{{ url('admin/add-quatation') }}" >
                @csrf
                <div class="profile-flex-box">
                    <h2 class="font-26">Quotation</h2>
                </div>

                <div class="main-input-container">
                    <div class="input__field">
                        <h2 class="input-heading">Name</h2>
                        <input type="text" id="name" placeholder="Name" name="name">
               
                    </div>
                       <div class="input__field">
                        <h2 class="input-heading">Business Name</h2>
                   <input type="text" id="bname" placeholder="Business Name" name="bname">
               
                    </div>
                        
                    <div class="input__field">
                        <h2 class="input-heading">Email</h2>
                        <input type="text" id="email" placeholder="Email" name="email">
               
                    </div>
                    <div class="input__field">
                        <h2 class="input-heading">Address</h2>
                   <input type="text" id="address" placeholder="Address" name="address">
               
                    </div>

                    <?php
             $category = App\Models\Product::get(['id','title','sales_price']);
                    ?>

    <div class="edit_-form-bottom">
 
                       <div class="row">
                            <div class="col-lg-12">
                                <div id="inputFormRow">
                                    <div class="input-group mb-3">
                                        <div class="add-content">
                                            <div class=" add-content-flex second-add">
                                                <div class="field d-flex">
                                              <div class="input__field">

                                                 <h2 class="input-heading">Product Name</h2>
                                                    <select name="product_id[]" id="product" class="product product_name" required>
                                                             <option value="">Select Products.....</option>
                                                        @foreach($category as $s)
                                                        <option value="{{$s->id}}">{{$s->title}}</option>

                                                        @endforeach
                                                      </select>
 <script>
    $(document).ready(function() {
      $('#product').select2();
    });
  </script>
                                                   </div>
                        
    </div>
    </div>
                                            <div class=" add-content-flex second-add">
                                                  
                                               <div class="input__field" id="input__field_abc_simple_product" >
                    <h2 class="input-heading">Unit Price</h2>
               <input id="product_price" type="text" placeholder="Product Price" class="product_price" name="product_price[]" readonly>
                    </div>
 
                                            </div>
                                            <div class=" add-content-flex second-add">
                                                <div class="field d-flex">
                                              <div class="input__field">
                        <h2 class="input-heading">Quantity</h2>
                        <input type="text" id="product_quantity" placeholder="Product Quantity" class="product_quantity" name="product_quantity[]" >
               
                    </div>
                                                </div>
                                            </div>
                                             <div class=" add-content-flex second-add">
                                                <div class="field d-flex">
                                              <div class="input__field">
                        <h2 class="input-heading">Total</h2>
                        <input type="text" id="result" placeholder="Total Price" name="product_total[]" readonly>
               
                    </div>
                                                </div>
                                            </div>
                                            <div class="submit-btn">
                           
                                                <button type="button" id="addRow" class="new-btn">
                                                    <svg width="13" height="12" viewBox="0 0 13 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M12 6.78571H7.28571V11.5H5.71429V6.78571H1V5.21429H5.71429V0.5H7.28571V5.21429H12V6.78571Z" fill="white" stroke="white"></path>
                                                    </svg>
                                                     Add 
                                                 </button>
                                             </div>
                                        </div>
                                        <div id="newRow" class="newRow"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                     <!--    <div class="submit-btn">
                            <button type="submit" class="new-btn">Save </button>
                        </div> -->
                    </div>


                       <div class="input__field">
                        <h2 class="input-heading">Date</h2>
                        <input type="date" id="currentDate" name="date">
               
                    </div>
 
                <div class="main-button">
                    <button href="javascript: void(0)" class="main-btn">Submit</button>
                </div>
            </form>
        </section>
    </main>
   <script>

  // var today = new Date();
  // var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
  // document.getElementById("currentDate").value = date;
</script>
@endsection