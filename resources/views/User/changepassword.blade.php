@extends('User.layouts.app')


@section('content')
    <main class="main-dashboard">
      @if(Session::has('message'))
  <p class="alert alert-info">{{ Session::get('message') }}</p>
  @endif
        <section class="add-category products-add change-password">
            <form class="category-form" action="{{url('forgot-password')}}" method="post" >
                @csrf
                <h2 class="font-26">Change Password</h2>
                <div class="main-input-container">
                    <div class="input__field">
                        <h2 class="input-heading">Current Password</h2>
                        <input type="text"  placeholder="Enter your current password" name="oldpass">
                        <div class="row">
                          @if ($errors->has('oldpass'))
                          <span class="text-danger">{{ $errors->first('oldpass') }}</span><br>
                          @endif
                        </div>
                    </div>
                    <div class="input__field">
                        <h2 class="input-heading">New Password</h2>
                        <input type="text"  placeholder="Confirm new password" name="newpass">
                         <div class="row">
                          @if ($errors->has('newpass'))
                          <span class="text-danger">{{ $errors->first('newpass') }}</span><br>
                          @endif
                        </div>
                    </div>
                    <div class="input__field">
                        <h2 class="input-heading">Confirm Password</h2>
                        <input type="text"  placeholder="Confirm your new password" name="cnewpass">
                         <div class="row">
                          @if ($errors->has('cnewpass'))
                          <span class="text-danger">{{ $errors->first('cnewpass') }}</span><br>
                          @endif
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