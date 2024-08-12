@extends('Admin.layouts.app')


@section('content')
<?php
// dd($store);
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
        <section class="add-category">
        
            <form class="category-form" method="POST" action="{{ url('admin/add_category') }}"  enctype="multipart/form-data" id="addcategory">
                @csrf
                <div class="profile-flex-box">
                    <h2 class="font-26">Add Category</h2>
                    <div class="user_image-block">
                        <div class="file-upload">
                          
                            <div class="image-upload-wrap">
                                <input class="file-upload-input" type='file' name="image" onchange="readURL(this);" accept="image/*"/>
                                
                                <div class="drag-text">
                                <h2>Upload Image</h2>
                                </div>
                            </div>
                             <div class="row">
                    @if ($errors->has('image'))
                            <span class="text-danger">{{ $errors->first('image') }}</span><br>
                        @endif
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
                        <h2 class="input-heading">Category Name</h2>
                        <input type="text" id="name"  name="name" >
                                  <div class="row">
                    @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span><br>
                        @endif
                       </div>
                    </div>
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
                        <h2 class="input-heading">Store</h2>
                             <select name="store[]" id="store" multiple>
                        @foreach($store as $s)
                            <option value="{{$s->id}}" @if($loop->first) selected @endif>{{$s->name}}</option>
                        @endforeach
                    </select>
                    </div>
                    <script>
    $(document).ready(function() {
      $('#store').select2();
    });
  </script>
                    <!--    <div class="input__field">
                        <h2 class="input-heading">Store</h2>
                        <select name="store" id="store" required>
                                 <option>Store</option>
                            <option value="store 1">store 1</option>
                            <option value="store 2">store 2</option>
                            <option value="store 3">store 3</option>
                            
                          </select>
                    </div> -->
                   <!-- <div class="input__field">-->
                   <!--     <h2 class="input-heading">Status</h2>-->
                   <!--     <select  id="cars" name="status">-->
                   <!--            <option>Status</option>-->
                   <!--         <option value="1" >Live</option>-->
                   <!--         <option value="2" >Hide</option>-->
                   <!--       </select>-->
                   <!--<div class="row">-->
                   <!-- @if ($errors->has('status'))-->
                   <!--         <span class="text-danger">{{ $errors->first('status') }}</span><br>-->
                   <!--     @endif-->
                   <!--    </div>-->
                   <!-- </div>-->
                </div>
                <div class="main-button">
                    <button href="javascript: void(0)" class="main-btn">Upload</button>
                </div>
            </form>
        </section>
    </main> 
@endsection