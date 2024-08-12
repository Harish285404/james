@extends('Admin.layouts.app')


@section('content')

   <?php $store_id = App\Models\Store::where('id',$data->store)->get(); 
$country = App\Models\Categories::where('category_id',$data->category_id)->get();
// dd($data);
   ?>


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
            <form class="category-form" method="POST" action="{{ url('admin/update-bundle-product') }}"  enctype="multipart/form-data">
                @csrf
                    <input type="hidden" id="cactus" placeholder="" name="sale_from" value="{{$data->sales_from}}">
                  <input type="hidden" id="cactus" placeholder="" name="sale_to" value="{{$data->sales_to}}">
                  
                 <input type="hidden"  name="id" value="{{$data->id}}">
                 <input type="hidden"  name="product_id" id="product_id" value="{{$data->product_id}}">
                    <input type="hidden"  name="store_id" value="{{$data->store}}">
                     <!--<input type="hidden"  name="store" id="store" value="{{$data->store}}">-->
                    
                
                
                <div class="profile-flex-box">
                    <h2 class="font-26">Edit Bundle Products</h2>
                      <div class="user_image-block">
                        <div class="file-upload">
                            <div class="image-upload-wrap">
                                <input class="file-upload-input" type="file" name="image" onchange="readURL(this);" accept="image/*" />
                                <div class="drag-text">
                               <img src="{{asset('Admin/images/'.$data->image)}}"  width="300" height="300">
                                </div>
                            </div>
                            <div class="file-upload-content">
                                <img class="file-upload-image" src="" >
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
                        <input type="text" id="cactus"  name="name" value="{{$data->title}}">
                   
                    </div>
                   
                      <div class="input__field">
                        <h2 class="input-heading">Description</h2>
                      <textarea id="description" name="description" rows="4" cols="30"><?php
                        echo $a=strip_tags($data->description)?></textarea>
                        </div>
                            <div class="input__field">
                        <h2 class="input-heading">Store</h2>
                        <select name="store[]" id="groupstore" disabled="true" multiple>
                            <?php
                            // echo $data->store;
$err=explode(',',$data->store);
 $storess = App\Models\Store::whereIn('id',$err)->get();

                            ?>
                            @foreach($storess as $stores)
                                               <option value="{{$stores->id}}" selected>{{ $stores->name }}</option>
                                                   
                            @endforeach

                          </select>
                                             <script>
    $(document).ready(function() {
      $('#groupstore').select2();
    });
  </script>
                     <div class="input__field" id="input__field_abc" style="display:none;">
                    
              
           </div>
            <div class="new-add-row-button">
             <button type="button" id="addRowew" class="new-btn" >
                                                    <svg width="13" height="12" viewBox="0 0 13 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M12 6.78571H7.28571V11.5H5.71429V6.78571H1V5.21429H5.71429V0.5H7.28571V5.21429H12V6.78571Z" fill="white" stroke="white"></path>
                                                    </svg>
                                                     Add 
                                                 </button>
       </div>
           </div>
                     <div class="input__field">
                        <h2 class="input-heading">Sku</h2>
                                <input name="Sku" type="text" id="Sku" value="{{$data->sku}}">
                    </div>
                <!--      <div class="input__field">
                        <h2 class="input-heading">Varient</h2>
                        <input type="text" name="Varient" id="Varient" value="{{$data->varient}}">
                    </div> -->


                          <!-- <div class="input__field_Sales"> -->
                           <div class="input__field">
                            <h2 class="input-heading">Sales Price</h2>
                         <input type="text" id="cactus" name="sales_price" value="{{$data->sales_price}}" >
                     </div>
                     <!--  <div class="input__field">
                         <h2 class="input-heading">Sale Start Date</h2>
                         <input type="date" id="cactus" placeholder="" name="sale_from" value="{{$data->sales_from}}">
                         
                     </div>
                      <div class="input__field">
                         <h2 class="input-heading">Sale End Date</h2>
                         <input type="date" id="cactus" placeholder="" name="sale_to" value="{{$data->sales_to}}">
              
                       </div> -->
                 
                    <div class="input__field">
                        <h2 class="input-heading">Regular Price</h2>
                        <input type="text" id="cactus"  name="retail_price" value="{{$data->retail_price}}">
                  
                    </div>
               <!-- 
                  <div class="input__field">
                        <h2 class="input-heading">Varient inventory</h2>
                        <input type="text" id="cactus" placeholder="$25.99"  name="total_quantity" value="">
                 
                    </div> -->
                    <div class="input__field">
                        <h2 class="input-heading">Status</h2>
                        <select name="status" id="Status">
                           @if($data->status == 'publish')

                             <option value="publish ">publish</option>
                            <option value="draft">Draft</option>
                             @else
                          
                            <option value="draft">Draft</option>
                               <option value="publish ">publish</option>
                             @endif
                          </select>
                    </div>
                       </div>
          
                
                <div class="main-button">
                    <button href="javascript: void(0)" class="main-btn">Upload</button>
                </div>
                      </div>
            </form>
        </section>
    </main>

    
@endsection