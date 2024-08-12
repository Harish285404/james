@extends('Admin.layouts.app')


@section('content')
<?php
// dd($pname);
?>
<main class="main-dashboard">
    <section class="add-category categories_edit Categories_View single-product-view">
        <form class="category-form">
            <div class="profile-flex-box">
                <h2 class="font-26">Bundle Product View</h2>
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
                        <h4 class="single-categories-text">Title:{{$data->title}}</h4>
                        <h4 class="single-categories-text">Categories:
                         
                            <span>
                    <?php       
$uniqueNames = array_unique($name);
$uniqueNames = array_values($uniqueNames); 

$length = count($uniqueNames);
 for ($t = 0; $t < $length; $t++) {
    echo $uniqueNames[$t];

    if ($t < $length - 1) {
        echo ' , ';
    }else{
        echo ' . ';
    }


 } ?></span>
                         

                        </h4>





                        <h4 class="single-categories-text">Sale Price:<span>{{$data->sales_price}}</span></h4>
                        <h4 class="single-categories-text">Retail Price:<span>{{$data->retail_price}}</span></h4>
                        <h4 class="single-categories-text">Status:
                            <span>
                                 <?php
                                  if($data->status == 'publish'){
                                      echo "Published";
                                      } else{
                                         echo "Draft";
                                      }
                                        ?> 
                            </span>
                        </h4>
                        <div class="box-grid-list">
                            <h4 class="single-categories-text">Bundle product name:</h4>
                            <div class="box-grid-list-inner">
                               
                            <?php $p = 0; 
                             $uniqueNames = array_unique($pname); ?>
                            @foreach($uniqueNames as $pnamee)

                            <div class="drag-text">
                                <img src="{{asset('Admin/images/'.$image[$p])}}">
                                <h4 class="single-categories-text" style="display: flex;"><span>{{ $pnamee }}</span></h4>
                            </div>

                            <?php
                            $p++;
                            ?>
                            @endforeach

                          </div>
              
                        </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="main-button">
                <a href="{{url('admin/product-list')}}" class="main-btn back-btn">Back</a>

            </div>
        </form>
    </section>
</main>
@endsection