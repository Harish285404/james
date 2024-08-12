@extends('Admin.layouts.app')


@section('content')
  <meta name="viewport" content="width=device-width, initial-scale=1"><link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css'>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css'>
<main class="main-dashboard">
      @if(Session::has('message'))
<p class="alert alert-info">{{ Session::get('message') }}</p>
@endif

@if ($message = Session::get('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>{{ $message }}</strong>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
        <section class="add-category products-add">
             <!-- <button  type="button" href="#" class="main-btn"  id="wocommerce-button">Upload Wocommerce to store 1</button> -->
             <!-- <button href="{{ url('admin/orders2') }}" class="main-btn">Upload Wocommerce to store 2</button>-->
            <form class="category-form" method="POST" action="{{ url('admin/add_product') }}"  enctype="multipart/form-data" id="edituser">
                @csrf
                      <input type="hidden" id="sale_from"  name="sale_from" >
                <div class="profile-flex-box">
                    <h2 class="font-26">Add Products</h2>
                    <div class="user_image-block">
                        <div class="file-upload">
                            <div class="image-upload-wrap">
                                <input class="file-upload-input" type='file'  name="image" onchange="readURL(this);" accept="image/*" />
                                 <div class="row">
                 @if ($errors->has('image'))
                            <span class="text-danger">{{ $errors->first('image') }}</span><br>
                        @endif
                       </div>
                                <div class="drag-text">
                                    <h2>Upload Image</h2>
                                </div>
                            </div>
                                        
                            <div class="file-upload-content">
                                <img class="file-upload-image" src="#" alt="your image" />
                            </div>
                            <div class="upload-img">
                                <button class="file-upload-btn" type="button" onclick="$('.file-upload-input').trigger( 'click' )">Browse Image</button>
                                <img src="{{asset('Admin/images/OBJECTS.svg')}}">

                            </div>

                        </div>


                    </div>
                </div>

                <div class="main-input-container">
                    <div class="input__field">
                        <h2 class="input-heading">Name of the product</h2>
                        <input type="text" id="name" name="name">
                        <div class="row">
                    @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span><br>
                        @endif
                       </div>
                    </div>

                     <div class="input__field">
                        <h2 class="input-heading">Store</h2>
                       <select name="store[]" id="store" multiple>
                        @foreach($store as $s)
                            <option value="{{$s->id}}" @if($loop->first) selected @endif>{{$s->name}}</option>
                        @endforeach
                    </select>

                        <div class="row">
                    @if ($errors->has('store'))
                            <span class="text-danger">{{ $errors->first('store') }}</span><br>
                        @endif
                       </div>
                    </div>
                      <script>
    $(document).ready(function() {
      $('#store').select2();
    });
  </script>
            <!--    <div class="input__field" id="input__field_abc" style="display:none;">
                       <h2 class="input-heading" id="label_add" >Categories Select</h2>
                <select id="subcategories" name="category" > 
                <option value="">Categories</option>                   
                </select><br>
           </div> -->

 
 <div class="input__field" id="input__field_abc_simple" style="display:none;">
                    
              
           </div>

 
        

                 <!--    <div class="input__field">
                        <h2 class="input-heading">Categories Select</h2>
                        <select name="category" id="cars" required>
                                 <option>Categories</option>
                               @foreach($catname as $data)
                            <option value="{{$data->id}}">{{$data->name}}</option>
                        @endforeach 
                            
                          </select>
                    </div> -->
                        <div class="input__field">
                        <h2 class="input-heading">Description</h2>
                         <textarea id="description" name="description" rows="4" cols="30" ></textarea>
                        <div class="row">
                    @if ($errors->has('description'))
                            <span class="text-danger">{{ $errors->first('description') }}</span><br>
                        @endif
                       </div>
                    </div>
                  
                     <div class="input__field">
                        <h2 class="input-heading">Sku</h2>
                        <input name="Sku" type="text" id="Sku" >
                          
                      <div class="row">
                    @if ($errors->has('Sku'))
                            <span class="text-danger">{{ $errors->first('Sku') }}</span><br>
                        @endif
                       </div>
                    </div>
          
                          <!-- <div class="input__field_Sales"> -->
                           <div class="input__field">
                            <h2 class="input-heading">Sales Price</h2>
                         <input type="text" id="sales_price"  name="sales_price" >
                          <div class="row">
                    @if ($errors->has('sales_price'))
                            <span class="text-danger">{{ $errors->first('sales_price') }}</span><br>
                        @endif
                       </div>
                     </div>



               <!--        <div class="input__field">
                         <h2 class="input-heading">Sale Start Date</h2>
                         <input type="date" id="sale_from"  name="sale_from" >
                          <div class="row">
                    @if ($errors->has('sale_from'))
                            <span class="text-danger">{{ $errors->first('sale_from') }}</span><br>
                        @endif
                       </div>
                     </div>
                      <div class="input__field">
                         <h2 class="input-heading">Sale End Date</h2>
                         <input type="date" id="sale_to" name="sale_to" >
                       <div class="row">
                    @if ($errors->has('sale_to'))
                            <span class="text-danger">{{ $errors->first('sale_to') }}</span><br>
                        @endif
                       </div>
                       </div> -->
                    <!-- </div> -->

                     <!--   <div class="input__field">
                            <h2 class="input-heading">Cost Price</h2>
                         <input type="text" id="cost_price" placeholder="$20.99" name="cost_price" >
                          <div class="row">
                    @if ($errors->has('cost_price'))
                            <span class="text-danger">{{ $errors->first('cost_price') }}</span><br>
                        @endif
                       </div>
                     </div> -->

                    <div class="input__field">
                        <h2 class="input-heading">Regular Price</h2>
                        <input type="number"  id="retail_price"   name="retail_price">
                            <div class="row">
                    @if ($errors->has('retail_price'))
                            <span class="text-danger">{{ $errors->first('retail_price') }}</span><br>
                        @endif
                       </div>
                    </div>
                    <!--  <div class="input__field">-->
                    <!--    <h2 class="input-heading">Quantity</h2>-->
                    <!--    <input type="number" id="total_quantity"   name="total_quantity" >-->
                    <!--        <div class="row">-->
                    <!--@if ($errors->has('total_quantity'))-->
                    <!--        <span class="text-danger">{{ $errors->first('total_quantity') }}</span><br>-->
                    <!--    @endif-->
                    <!--   </div>-->
                    <!--</div>-->
             
                    <div class="input__field">
                        <h2 class="input-heading">Status</h2>
                           <select name="status" id="status">
                            <option value="">Status</option>
                              <option value="publish ">Published</option>
                            <option value="draft">Draft</option>
                          </select>
                           <div class="row">
                    @if ($errors->has('status'))
                            <span class="text-danger">{{ $errors->first('status') }}</span><br>
                        @endif
                       </div>
                    
                    </div>
                
                <div class="main-button">
                    <button href="javascript: void(0)" class="main-btn">Upload</button>
                </div>
            </form>
        </section>
    </main>
  <script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/js/bootstrap-select.min.js'></script>
  
<!-- <script>
    var today = new Date();
    var date = today.toISOString().split('T')[0];
    document.getElementById("sale_from").value = date;
</script>
 -->

@endsection