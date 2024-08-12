@extends('Admin.layouts.app')


@section('content')

<main class="main-dashboard">
      @if(Session::has('message'))
<p class="alert alert-info">{{ Session::get('message') }}</p>
@endif
  <?php
 // $category = App\Models\Product::where('id','1')->get();
//   dd($data);
                    ?>
        <section class="add-category products-add">
             <!-- <button  type="button" href="#" class="main-btn"  id="wocommerce-button">Upload Wocommerce to store 1</button> -->
             <!-- <button href="{{ url('admin/orders2') }}" class="main-btn">Upload Wocommerce to store 2</button>-->
            <form class="category-form" method="POST" action="{{ url('admin/update-quote') }}" >
                @csrf

                 <input type="hidden"  name="quote_id" value="{{$data[0]->quote_id}}">
                <div class="profile-flex-box">
                    <h2 class="font-26"> Edit Quotation</h2>
                </div>

                <div class="main-input-container">
                    <div class="input__field">
                        <h2 class="input-heading">Name</h2>
                        <input type="text" id="name"  name="name" value="{{$data1[0]->name}}">
               
                    </div>
                       <div class="input__field">
                        <h2 class="input-heading">Business Name</h2>
                   <input type="text" id="bname" name="bname" value="{{$data1[0]->bussiness_name}}">
               
                    </div>
                        
                    <div class="input__field">
                        <h2 class="input-heading">Email</h2>
                        <input type="text" id="email"  name="email" value="{{$data1[0]->email}}">
               
                    </div>
                    <div class="input__field">
                        <h2 class="input-heading">Address</h2>
                   <input type="text" id="address"  name="address" value="{{$data1[0]->address}}">
               
                    </div>
                      <div class="input__field">
                        <h2 class="input-heading">Contact Number</h2>
                   <input type="number" id="address"  name="phone"  value="{{$data1[0]->phone_number}}">
               
                    </div>
                       <div class="input__field_Sales">
                      <div class="input__field">
                        <h2 class="input-heading">Discount(%)</h2>
                   <input type="text" id="address"  name="discount" value="{{$data1[0]->discount}}">
               
                    </div>
                     <div class="input__field">
                        <h2 class="input-heading">Freight</h2>
                   <input type="text" id="address"  name="freight" value="{{$data1[0]->freight}}">
               
                    </div>
                     </div>
                     
                     
                         
                            <div class="input__field_Sales">
                      <div class="input__field">
                        <h2 class="input-heading">Miscellaneous items</h2>
                   <input type="text" id="miscellaneous"  name="miscellaneous" value="{{$data1[0]->misc_item}}">
               
                    </div>
                     <div class="input__field">
                        <h2 class="input-heading">value</h2>
                   <input type="text" id="miscvaluee"  name="miscvalue" value="{{$data1[0]->misc_value}}">
               
                    </div>
                     </div>
                     
                     
                     
                   

                    <?php
             $category = App\Models\Product::get(['id','title','sales_price']);
                     $a=1;
                    ?>
                    
 @foreach($data1 as $index =>$ss)
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
                                <select name="product_id[]" id="product{{$a}}" class="product product_namee"  data-id="{{$a}}" required>

                                                             <option value="">Select Products.....</option>

                                                        @foreach($category as $s)
                                                        @if($ss->product_id == $s->id)
                                                         <option value="{{$s->id}}" selected>{{$s->title}}</option>
                                                        @else
                                                        <option value="{{$s->id}}">{{$s->title}}</option>
                                                     @endif
                                                        @endforeach
                                                      </select>
    <script>
 $(document).ready(function() {
    @foreach($data1 as $index)
        $('#product{{$a}}').select2();
     
    @endforeach
});
  </script>
                                                   </div>
                        
    </div>
    </div>
                                            <div class=" add-content-flex second-add">
                                                  
                                               <div class="input__field" id="input__field_abc_simple_product" >
                    <h2 class="input-heading">Unit Price</h2>
               <input id="product_pricee{{$a}}" type="text" placeholder="Product Price" class="product_price" name="product_price[]" value="{{$ss->product_price}}"readonly>
                    </div>
 
                                            </div>
                                            <div class=" add-content-flex second-add">
                                                <div class="field d-flex">
                                              <div class="input__field">
                        <h2 class="input-heading">Quantity</h2>
                        <input type="text" id="product_quantityy{{$a}}" placeholder="Product Quantity" class="product_quantity" name="product_quantity[]" value="{{$ss->product_quantity}}">
               
                    </div>
                                                </div>
                                            </div>
                                             <div class=" add-content-flex second-add">
                                                <div class="field d-flex">
                                              <div class="input__field">
                        <h2 class="input-heading">Total</h2>
                        <input type="text" class="result" id="resultt{{$a}}" placeholder="Total Price" name="product_total[]" value="{{$ss->total}}" readonly>
               
                    </div>
                                                </div>
                                            </div>
                                            <div class="submit-btn">
                           
                                                <button type="button" id="remove" class="new-btn remove-btn removeRow">
                                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.47217 10.4445L6.13867 7.11102L2.80517 10.4445L1.694 9.33336L5.0275 5.99985L1.694 2.66635L2.80517 1.55518L6.13867 4.88869L9.47218 1.55518L10.5833 2.66635L7.24984 5.99985L10.5833 9.33336L9.47217 10.4445Z" fill="#FF1010" stroke="#FF1010"></path></svg>
                                               Remove
                                                 </button>
                                             </div>
                                        </div>
                                    
                                    </div>
                                </div>
                            </div>
                        </div>

              
                    </div>

<?php   $a++; ?>
    @endforeach


 <div class="edit_-form-bottom">
 
                       <div class="row">
                            <div class="col-lg-12">
                                <div id="inputFormRow">
                                    <div class="input-group mb-3">
                                        
                               
                                        <div id="newRow" class="newRow"></div>
                                          <h6 class="input-headingg">Running total</h6>
                                             <input type="text" id="running_total" name="grandtotal">
                                    </div>
                                </div>
                                    <div class="new-add-row-button">
                                 <button type="button" id="addRow2" class="new-btn" >
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


 <script>
    $(document).ready(function() {

           $('#product').select2();
    });
  </script>
               




   <div class="input__field">
                        <h2 class="input-heading">Notes</h2>
                   <input type="text" id="address"  name="notes" value="{{$data1[0]->notes}}">
               
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