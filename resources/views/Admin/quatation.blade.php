@extends('Admin.layouts.app')


@section('content')

<main class="main-dashboard">
@if(session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
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
        <input type="text" id="names" name="name" required>
        <div class="error-message" id="nameErrors"></div>
    </div>
    <div class="input__field">
        <h2 class="input-heading">Business Name</h2>
        <input type="text" id="bnames" name="bname" required>
        <div class="error-message" id="bnameErrors"></div>
    </div>
    <div class="input__field">
        <h2 class="input-heading">Email</h2>
        <input type="email" id="emails" name="email" required>
        <div class="error-message" id="emailErrors"></div>
    </div>
    <div class="input__field">
        <h2 class="input-heading">Address</h2>
        <input type="text" id="addresss" name="address" required>
        <div class="error-message" id="addressErrors"></div>
    </div>
    <div class="input__field">
        <h2 class="input-heading">Contact Number</h2>
        <input type="text" id="phones" name="phone" required pattern="[0-9]{10}">
        <div class="error-message" id="phoneErrors"></div>
    </div>
    <div class="input__field_Sales">
        <div class="input__field">
            <h2 class="input-heading">Discount(%)</h2>
            <input type="text" id="discounts" name="discount" pattern="[0-9]+">
            <div class="error-message" id="discountErrors"></div>
        </div>
        <div class="input__field">
            <h2 class="input-heading">Freight</h2>
            <input type="text" id="freights" name="freight" pattern="[0-9]+">
            <div class="error-message" id="freightErrors"></div>
        </div>
    </div>
    <div class="input__field_Sales">
        <div class="input__field">
            <h2 class="input-heading">Miscellaneous items</h2>
            <input type="text" id="miscellaneous" name="miscellaneous">
        </div>
        <div class="input__field">
            <h2 class="input-heading">Value</h2>
            <input type="text" id="miscvalue" name="miscvalue">
        </div>
    </div>
                     
                     

                  

                    <?php
             $category = App\Models\Product::get(['id','title','sales_price']);
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
                        <input type="text" id="result" class="result" placeholder="Total Price" name="product_total[]" readonly>
               
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
                                         <h6 class="input-headingg">Running total</h6>
                                        <input type="text" id="totalSum" name="grandtotal">
                                    </div>
                                </div>
                                <div class="new-add-row-button">
                          <!-- Display total result -->
<!-- <p>Total Price: <span id="totalPrice">0</span></p> -->

<!-- Input field to show the total dynamically -->
<!-- <input type="text" id="totalPriceInput" placeholder="Total Price" readonly> -->
  <button type="button" id="addRow" class="new-btn" >
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
                    
                       <div class="input__field">
                        <h2 class="input-heading">Notes</h2>
                   <input type="text" id="address"  name="notes">
               
                    </div>
 
                <div class="main-button">
                    <button href="javascript: void(0)" class="main-btn" id="inovide-dataa">Submit</button>
                </div>
            </form>
        </section>
    </main>

<script>
    $(document).ready(function() {
        $('#inovide-dataa').click(function() {
        var isValid = true;

        if (!document.getElementById('names').value.trim()) {
            document.getElementById('nameErrors').innerHTML = 'Name is required';
            isValid = false;
        } else {
            document.getElementById('nameErrors').innerHTML = '';
        }

        if (!document.getElementById('bnames').value.trim()) {
            document.getElementById('bnameErrors').innerHTML = 'Business Name is required';
            isValid = false;
        } else {
            document.getElementById('bnameErrors').innerHTML = '';
        }

        if (!document.getElementById('emails').value.trim()) {
            document.getElementById('emailErrors').innerHTML = 'Email is required';
            isValid = false;
        } else {
            document.getElementById('emailErrors').innerHTML = '';
        }

        if (!document.getElementById('addresss').value.trim()) {
            document.getElementById('addressErrors').innerHTML = 'Address is required';
            isValid = false;
        } else {
            document.getElementById('addressErrors').innerHTML = '';
        }

        if (!document.getElementById('phones').value.trim()) {
            document.getElementById('phoneErrors').innerHTML = 'Contact Number is required';
            isValid = false;
        } else if (!document.getElementById('phones').value.match(/^\d{10}$/)) {
            document.getElementById('phoneErrors').innerHTML = 'Contact Number must be 10 digits';
            isValid = false;
        } else {
            document.getElementById('phoneErrors').innerHTML = '';
        }

        if (!document.getElementById('discounts').value.trim()) {
            document.getElementById('discountErrors').innerHTML = 'Discount is required';
            isValid = false;
        } else if (!document.getElementById('discounts').value.match(/^\d+$/)) {
            document.getElementById('discountErrors').innerHTML = 'Discount must be a number';
            isValid = false;
        } else {
            document.getElementById('discountErrors').innerHTML = '';
        }

        if (!document.getElementById('freights').value.trim()) {
            document.getElementById('freightErrors').innerHTML = 'Freight is required';
            isValid = false;
        } else if (!document.getElementById('freights').value.match(/^\d+$/)) {
            document.getElementById('freightErrors').innerHTML = 'Freight must be a number';
            isValid = false;
        } else {
            document.getElementById('freightErrors').innerHTML = '';
        }

        return isValid;
        });
    });
</script>
@endsection