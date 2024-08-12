<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css'>
<!-- <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css'> -->

<link rel="stylesheet" href="./style.css">
 <link rel="stylesheet" href="{{asset('css/style.css')}}">
</head>
<body >
  <main class="content-main-block login-form">
<div class="login-main-img">
 
</div>
        <div class="login-main-content">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                          <div class="heading">
                            <h3>Welcome Back!</h3>
                            <p>Hey, Please fill in your credentials to access your admin account.</p>
                        </div>
                        <div class="card-header">
        </div>
         @if(Session::has('message'))
        <p class="alert alert-info">{{ Session::get('message') }}</p>
        @endif
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="col-field-main">
                            <!-- <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email') }}</label> -->

                            <div class="col-field">
                               <svg width="24" height="19" viewBox="0 0 24 19" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M20.7068 0.47583H2.30677C1.04177 0.47583 0.0182749 1.48833 0.0182749 2.72583L0.0067749 16.2258C0.0067749 17.4633 1.04177 18.4758 2.30677 18.4758H20.7068C21.9718 18.4758 23.0068 17.4633 23.0068 16.2258V2.72583C23.0068 1.48833 21.9718 0.47583 20.7068 0.47583ZM20.7068 4.97583L11.5068 10.6008L2.30677 4.97583V2.72583L11.5068 8.35083L20.7068 2.72583V4.97583Z" fill="#0F5132"/>
</svg>


                                <input id="email" type="email"  placeholder="Enter your email"  class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
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

 <input id="password-field" type="password"  placeholder="Enter your password"
   class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

  <!-- <input id="password-field" type="password" class="form-control" name="password" value="secret"> -->
              <span toggle="#password-field" class="field-icon toggle-password"></span>

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                              
                       @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif 
                         
                        </div>
                        
                    

                        <div class="login-btn">
                            <div class="button">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Sign In') }}
                                </button>

                              
                            </div>
                        </div>

                             <!--    <div class="remember-pswd-btn">
                                  @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('register') }}">
                             Don’t have an account? <b>Create one</b>
                                    </a>
                                @endif
                            </div> -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</main>
</body>
</html>

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

</script>

   

