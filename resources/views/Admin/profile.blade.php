@extends('Admin.layouts.app')


@section('content')
   <main class="main-dashboard">
        @if(Session::has('message'))
<p class="alert alert-info">{{ Session::get('message') }}</p>
@endif
<?php
// dd($data);
?>
        <section class="add-category products-add profile-details">
          
                 <form class="category-form" method="POST" action="{{ url('admin/profile-update') }}"  enctype="multipart/form-data">
                @csrf
                 <input type="hidden"  name="id" value="{{$data[0]->id}}">
                <div class="profile-flex-box">
                    <h2 class="font-26">Profile Details</h2>
                    <div class="user_image-block">
                        <div class="file-upload">
                            <div class="image-upload-wrap">
                                <input class="file-upload-input" type='file' name="image" onchange="readURL(this);" accept="image/*" />
                                <div class="drag-text">
                               <img src="{{asset('Admin/images/'.$data[0]->image)}}"  width="300" height="300">
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
                    <div class="input__field_main_box">
                        <div class="input__field">
                            <h2 class="input-heading">First Name</h2>
                            <input type="text" id="fname" placeholder="First Name" value="{{$data[0]->first_name}}"  name="first_name"> 
                            <div class="row">
                    @if ($errors->has('first_name'))
                            <span class="text-danger">{{ $errors->first('first_name') }}</span><br>
                        @endif
                       </div>
                        </div>
                        <div class="input__field">
                            <h2 class="input-heading">Last Name</h2>
                            <input type="text" id="lname" placeholder="Last Name" value="{{$data[0]->last_name}}"  name="last_name">
                            <div class="row">
                    @if ($errors->has('last_name'))
                            <span class="text-danger">{{ $errors->first('last_name') }}</span><br>
                        @endif
                       </div>
                        </div>
                    </div>
                    <div class="input__field_main_box">
                        <div class="input__field">
                            <h2 class="input-heading">Phone Number</h2>
                            <input type="number" id="ph-num" placeholder="Phone Number" value="{{$data[0]->phone_number}}"  name="phone_number">
                            <div class="row">
                    @if ($errors->has('phone_number'))
                            <span class="text-danger">{{ $errors->first('phone_number') }}</span><br>
                        @endif
                       </div>
                        </div>
                        <div class="input__field">
                            <h2 class="input-heading">Email Address</h2>
                            <input type="text" id="email" placeholder="Email Address" value="{{$data[0]->email}}"  name="email">
                            <div class="row">
                    @if ($errors->has('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span><br>
                        @endif
                       </div>
                        </div>
                    </div>
                </div>
                <div class="main-button">
                    <button type="submit" href="javascript: void(0)" class="main-btn">Save</button>
                </div>
            </form>
        </section>
    </main>

@endsection