<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register</title>
 <link rel="stylesheet" href="css/style.css">
</head>
<body >
  <main class="content-main-block login-form register-form">
<div class="login-main-img">
    <!-- <img src="images/user/images/login-img.png"> -->
</div>
<div class="login-main-content">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="heading">
                    <h3>Sign Up And Discover!</h3>
                </div>
                <div class="card-header">
</div>

   @if(Session::has('message'))
<p class="alert alert-info">{{ Session::get('message') }}</p>
@endif

                <div class="card-body">
                     @if(Session::has('success'))
                               <p class="alert alert-info">
                                    {{Session::get('success')}}
                                </p>
                            @endif
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="col-field-main first">
                           <!-- <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('First Name') }}</label> -->

                            <div class="col-field">
                           <svg width="18" height="17" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M17.9077 16.66C17.8469 16.7634 17.7595 16.8493 17.6542 16.9089C17.5489 16.9686 17.4295 17 17.3079 17H0.691636C0.570164 16.9999 0.450863 16.9684 0.345712 16.9086C0.240561 16.8489 0.153261 16.7631 0.0925793 16.6597C0.0318973 16.5564 -3.11627e-05 16.4392 2.2823e-08 16.3199C3.12083e-05 16.2006 0.0320209 16.0834 0.0927569 15.9801C1.41081 13.7424 3.44198 12.1378 5.8124 11.3772C4.63988 10.6917 3.72891 9.64721 3.21938 8.40409C2.70985 7.16098 2.62993 5.78799 2.9919 4.49595C3.35387 3.20392 4.13772 2.0643 5.22306 1.25209C6.3084 0.43988 7.63523 0 8.99978 0C10.3643 0 11.6912 0.43988 12.7765 1.25209C13.8619 2.0643 14.6457 3.20392 15.0077 4.49595C15.3696 5.78799 15.2897 7.16098 14.7802 8.40409C14.2707 9.64721 13.3597 10.6917 12.1872 11.3772C14.5576 12.1378 16.5888 13.7424 17.9068 15.9801C17.9677 16.0834 17.9998 16.2006 18 16.32C18.0002 16.4393 17.9683 16.5566 17.9077 16.66Z" fill="#0F5132"/>
</svg>


              <input id="first_name"  placeholder="First Name" type="text" class="form-control @error('first_name') is-invalid @enderror"
               name="first_name" value="{{ old('first_name') }}" autocomplete="first_name" autofocus>
                                    @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        
                           <!-- <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('LastName') }}</label> -->

                            <div class="col-field">
                          
<svg width="18" height="17" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M17.9077 16.66C17.8469 16.7634 17.7595 16.8493 17.6542 16.9089C17.5489 16.9686 17.4295 17 17.3079 17H0.691636C0.570164 16.9999 0.450863 16.9684 0.345712 16.9086C0.240561 16.8489 0.153261 16.7631 0.0925793 16.6597C0.0318973 16.5564 -3.11627e-05 16.4392 2.2823e-08 16.3199C3.12083e-05 16.2006 0.0320209 16.0834 0.0927569 15.9801C1.41081 13.7424 3.44198 12.1378 5.8124 11.3772C4.63988 10.6917 3.72891 9.64721 3.21938 8.40409C2.70985 7.16098 2.62993 5.78799 2.9919 4.49595C3.35387 3.20392 4.13772 2.0643 5.22306 1.25209C6.3084 0.43988 7.63523 0 8.99978 0C10.3643 0 11.6912 0.43988 12.7765 1.25209C13.8619 2.0643 14.6457 3.20392 15.0077 4.49595C15.3696 5.78799 15.2897 7.16098 14.7802 8.40409C14.2707 9.64721 13.3597 10.6917 12.1872 11.3772C14.5576 12.1378 16.5888 13.7424 17.9068 15.9801C17.9677 16.0834 17.9998 16.2006 18 16.32C18.0002 16.4393 17.9683 16.5566 17.9077 16.66Z" fill="#0F5132"/>
</svg>

                              <input id="last_name" type="text"  placeholder="Last Name"
                                class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}"
                                 autocomplete="last_name" autofocus>
                                    @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                   
                        </div>


                     

                            <div class="col-field-main">
                            <!-- <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email') }}</label> -->

                            <div class="col-field">
                               <svg width="24" height="19" viewBox="0 0 24 19" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M20.7068 0.52002H2.30684C1.04184 0.52002 0.0183359 1.53252 0.0183359 2.77002L0.00683594 16.27C0.00683594 17.5075 1.04184 18.52 2.30684 18.52H20.7068C21.9718 18.52 23.0068 17.5075 23.0068 16.27V2.77002C23.0068 1.53252 21.9718 0.52002 20.7068 0.52002ZM20.7068 5.02002L11.5068 10.645L2.30684 5.02002V2.77002L11.5068 8.39502L20.7068 2.77002V5.02002Z" fill="#0F5132"/>
</svg>


                                <input id="email" type="email"  placeholder="Email"  class="form-control @error('email') is-invalid @enderror"
                                 name="email" value="{{ old('email') }}"  autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                 <div class="col-field-main">
                            <!-- <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email') }}</label> -->

                            <div class="col-field">
                             <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M8.93369 6.45826L7.48626 7.71954C7.67627 8.35604 7.9436 8.96832 8.28261 9.54346C8.63545 10.1146 9.05991 10.6411 9.54627 11.1107L11.4208 10.5498C12.4709 10.2353 13.6173 10.5583 14.3156 11.3657L15.3832 12.5998C15.8155 13.0952 16.0322 13.7346 15.9874 14.3825C15.9427 15.0304 15.64 15.6359 15.1435 16.0708C13.402 17.6143 10.7207 18.1361 8.71317 16.6046C6.94882 15.2553 5.45598 13.6015 4.30962 11.726C3.16075 9.85945 2.39188 7.79584 2.04484 5.64744C1.65979 3.23028 3.48177 1.29587 5.75355 0.636331C7.10822 0.24197 8.5539 0.918504 9.05096 2.17978L9.63728 3.66714C10.0223 4.64624 9.74579 5.75113 8.93369 6.45826Z" fill="#0F5132"/>
</svg>

                                <input id="phone_number" type="text"  placeholder="Phone Number" 
                                 class="form-control @error('phone_number') is-invalid @enderror" name="phone_number"
                                  value="{{ old('phone_number') }}"  autocomplete="phone_number" autofocus>

                                @error('phone_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="col-field-main">
                            <!-- <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label> -->

                            <div class="col-field">
                               <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M8.25677 14.7258V15.4758C8.25677 15.6747 8.17776 15.8655 8.0371 16.0062C7.89645 16.1468 7.70569 16.2258 7.50677 16.2258H6.00677V16.9758C6.00677 17.3737 5.84874 17.7552 5.56743 18.0365C5.28613 18.3178 4.9046 18.4758 4.50677 18.4758H1.50677C1.10895 18.4758 0.727419 18.3178 0.446115 18.0365C0.16481 17.7552 0.0067749 17.3737 0.0067749 16.9758V15.0363C0.00685986 14.6385 0.164951 14.2571 0.446275 13.9758L6.25128 8.17083C5.89533 6.96219 5.92892 5.67216 6.34726 4.48367C6.7656 3.29518 7.54747 2.26855 8.58201 1.54935C9.61654 0.830141 10.8512 0.454867 12.111 0.476734C13.3708 0.498602 14.5917 0.916501 15.6007 1.67118C16.6096 2.42585 17.3554 3.479 17.7322 4.68129C18.1091 5.88358 18.0979 7.174 17.7002 8.36956C17.3025 9.56513 16.5386 10.6052 15.5167 11.3422C14.4947 12.0792 13.2667 12.4759 12.0068 12.4758H10.5053V13.9758C10.5053 14.1747 10.4263 14.3655 10.2856 14.5062C10.145 14.6468 9.95419 14.7258 9.75527 14.7258H8.25527H8.25677ZM13.5068 6.47583C13.9046 6.47583 14.2861 6.31779 14.5674 6.03649C14.8487 5.75519 15.0068 5.37366 15.0068 4.97583C15.0068 4.57801 14.8487 4.19648 14.5674 3.91517C14.2861 3.63387 13.9046 3.47583 13.5068 3.47583C13.109 3.47583 12.7274 3.63387 12.4461 3.91517C12.1648 4.19648 12.0068 4.57801 12.0068 4.97583C12.0068 5.37366 12.1648 5.75519 12.4461 6.03649C12.7274 6.31779 13.109 6.47583 13.5068 6.47583Z" fill="#0F5132"/>
</svg>

                                <input id="password-fields" type="password"  placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password"  autocomplete="current-password">
 <span toggle="#password-field" class="field-icon toggle-password"></span>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                           <div class="col-field-main">
                          <!-- <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label> -->


                            <div class="col-field">
                               <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M8.25677 14.7258V15.4758C8.25677 15.6747 8.17776 15.8655 8.0371 16.0062C7.89645 16.1468 7.70569 16.2258 7.50677 16.2258H6.00677V16.9758C6.00677 17.3737 5.84874 17.7552 5.56743 18.0365C5.28613 18.3178 4.9046 18.4758 4.50677 18.4758H1.50677C1.10895 18.4758 0.727419 18.3178 0.446115 18.0365C0.16481 17.7552 0.0067749 17.3737 0.0067749 16.9758V15.0363C0.00685986 14.6385 0.164951 14.2571 0.446275 13.9758L6.25128 8.17083C5.89533 6.96219 5.92892 5.67216 6.34726 4.48367C6.7656 3.29518 7.54747 2.26855 8.58201 1.54935C9.61654 0.830141 10.8512 0.454867 12.111 0.476734C13.3708 0.498602 14.5917 0.916501 15.6007 1.67118C16.6096 2.42585 17.3554 3.479 17.7322 4.68129C18.1091 5.88358 18.0979 7.174 17.7002 8.36956C17.3025 9.56513 16.5386 10.6052 15.5167 11.3422C14.4947 12.0792 13.2667 12.4759 12.0068 12.4758H10.5053V13.9758C10.5053 14.1747 10.4263 14.3655 10.2856 14.5062C10.145 14.6468 9.95419 14.7258 9.75527 14.7258H8.25527H8.25677ZM13.5068 6.47583C13.9046 6.47583 14.2861 6.31779 14.5674 6.03649C14.8487 5.75519 15.0068 5.37366 15.0068 4.97583C15.0068 4.57801 14.8487 4.19648 14.5674 3.91517C14.2861 3.63387 13.9046 3.47583 13.5068 3.47583C13.109 3.47583 12.7274 3.63387 12.4461 3.91517C12.1648 4.19648 12.0068 4.57801 12.0068 4.97583C12.0068 5.37366 12.1648 5.75519 12.4461 6.03649C12.7274 6.31779 13.109 6.47583 13.5068 6.47583Z" fill="#0F5132"/>
</svg>


                          <input id="password-field" type="password"  placeholder="Confirm Password"  class="form-control" name="password_confirmation"  autocomplete="new-password">
 <span toggle="#password-field" class=" field-icon toggle-password"></span>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>     


                        <div class="login-btn">
                            <div class="button">
                                <button type="submit" class="btn btn-primary">
                            Sign Up
                                </button>
                        <div class="remember-pswd-btn">
                         
                                    <a class="btn btn-link" href="{{route('login')}}">
                                        Already Have An Account? <b>Sign in here</b>
                                    </a>
                                               </div>
                           
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</main>
</body>
</html>


<path d="M4.375 5.625L7.5 8.75L10.625 5.625" stroke="#A40404" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>

 $(document).ready(function() {

$(".toggle-password").click(function() {

  $(this).toggleClass("field-icon field-icon-active");
  var input = $($(this).attr("toggle"));
  if (input.attr("type") == "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }
});
});

  $(document).ready(function() {

$(".toggle-password-1").click(function() {

  $(this).toggleClass("field-icon field-icon-active");
  var input = $($(this).attr("toggle"));
  if (input.attr("type") == "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }
});
});

</script>
