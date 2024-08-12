@extends('Admin.layouts.app')


@section('content')
   <main class="main-dashboard">
        <section class="add-category categories_edit Categories_View">
            <form class="category-form">
                <div class="profile-flex-box">
                    <h2 class="font-26">Category View</h2>
                    <div class="user_image-block">
                        <div class="file-upload">
                            <div class="image-upload-wrap">
                                <input class="file-upload-input" type='file' onchange="readURL(this);" accept="image/*" />
                                <div class="drag-text">
                                    <img src="{{asset('Admin/images/'.$data->image)}}">
                                    
                                </div>
                            </div>
                            <div class="file-upload-content">
                                <img class="file-upload-image" src="#" alt="your image" />
                            </div>
                        </div>
                        <div class="single-categories-text-main">
                            <h4 class="single-categories-text">{{$data->name}}</h4>
                           
                        </div>
                    </div>
                </div>
                <div class="main-button">
                     <a href="{{url('admin/category-list')}}" class="main-btn back-btn">Back</a>
                </div>
            </form>
        </section>
    </main>  
@endsection