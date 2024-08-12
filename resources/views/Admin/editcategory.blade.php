@extends('Admin.layouts.app')


@section('content')
<?php
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
        <section class="add-category categories_edit">
                <form class="category-form" method="POST" action="{{ url('admin/update-category') }}"  enctype="multipart/form-data" id="acategory">
                @csrf
                 <input type="hidden"  name="id" value="{{$data->id}}">
                 <input type="hidden" id="artificial-trees"  name="category_id" value="{{$data->category_id}}">
                <div class="profile-flex-box">
                    <h2 class="font-26">Edit Category</h2>
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
                        <h2 class="input-heading">Category Name</h2>
                        <input type="text" id="name"  name="name" value="{{$data->name}}">
                    </div>
                       <div class="input__field">
                        <h2 class="input-heading">Description</h2>
                        <textarea id="description" name="description" rows="4" cols="30">
                            <?php
                        echo $a=strip_tags($data->description);

                    ?>
                        
                        </textarea>
                        </div>
                    
                                <div class="input__field">
                        <h2 class="input-heading">Store</h2>
                        <select name="store[]" id="store" multiple   disabled="true" >
                            <?php
$err=explode(',',$data->store);
 $storess = App\Models\Store::whereIn('id',$err)->get();

                            ?>
                            @foreach($storess as $stores){
                                               <option value="{{$stores->id}}" selected>{{ $stores->name }}</option>
                                                   
                            @endforeach

                          </select>
                                             <script>
    $(document).ready(function() {
      $('#store').select2();
    });
  </script>
                    </div>
                    <!--<div class="input__field">-->
                    <!--    <h2 class="input-heading">Status</h2>-->
                    <!--    <select name="status" id="cars">-->
                    <!--        @if($data->status == 1)-->

                    <!--        <option value="1">Live</option>-->

                    <!--        <option value="2">Hide</option>-->
                    <!--         @else-->
                    <!--         <option value="2">Hide</option>-->
                    <!--        <option value="1">Live</option>-->
                    <!--         @endif-->
                    <!--      </select>-->
                    <!--</div>-->
                </div>
                <div class="main-button">
                    <button href="javascript: void(0)" class="main-btn">Upload</button>
                </div>
            </form>
        </section>
    </main>
@endsection