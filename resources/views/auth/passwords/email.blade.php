<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Forgot Password</title>
 <link rel="stylesheet" href="{{asset('css/style.css')}}">
</head>
<body >
  <main class="content-main-block login-form">
<div class="login-main-img">
    <!-- <img src="{{asset('images/user/images/login-img.png')}}"> -->
</div>
<div class="login-main-content">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="heading">
                    <h3>Forgot Password?</h3>
                </div>
                <div class="card-header">
</div>
<div class="foget-content">
    <p>Hey, Please enter the email address associated with your account</p>
</div>

                <div class="card-body">

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                    @if (session('status'))
                                  <p class="alert alert-info"> {{ session('status') }}</p>
                                @endif



                        <div class="col-field-main">
                           <!-- <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label> -->

                            <div class="col-field">
                          
<svg width="24" height="19" viewBox="0 0 24 19" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M20.7068 0.52002H2.30684C1.04184 0.52002 0.0183359 1.53252 0.0183359 2.77002L0.00683594 16.27C0.00683594 17.5075 1.04184 18.52 2.30684 18.52H20.7068C21.9718 18.52 23.0068 17.5075 23.0068 16.27V2.77002C23.0068 1.53252 21.9718 0.52002 20.7068 0.52002ZM20.7068 5.02002L11.5068 10.645L2.30684 5.02002V2.77002L11.5068 8.39502L20.7068 2.77002V5.02002Z" fill="#0F5132"/>
</svg>


                            <input id="email" type="email"  placeholder="Enter your email"  class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!--     <div class="col-field-main">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-field">
                              <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8.25 14.25V15C8.25 15.1989 8.17098 15.3897 8.03033 15.5303C7.88968 15.671 7.69891 15.75 7.5 15.75H6V16.5C6 16.8978 5.84196 17.2794 5.56066 17.5607C5.27936 17.842 4.89782 18 4.5 18H1.5C1.10218 18 0.720644 17.842 0.43934 17.5607C0.158035 17.2794 0 16.8978 0 16.5V14.5605C8.49561e-05 14.1627 0.158176 13.7812 0.4395 13.5L6.2445 7.695C5.88856 6.48636 5.92214 5.19633 6.34048 4.00784C6.75883 2.81935 7.5407 1.79272 8.57523 1.07351C9.60977 0.354311 10.8445 -0.0209633 12.1042 0.000904168C13.364 0.0227717 14.5849 0.440671 15.5939 1.19535C16.6028 1.95002 17.3486 3.00317 17.7255 4.20546C18.1023 5.40775 18.0911 6.69817 17.6934 7.89373C17.2957 9.0893 16.5318 10.1293 15.5099 10.8664C14.488 11.6034 13.26 12 12 12H10.4985V13.5C10.4985 13.6989 10.4195 13.8897 10.2788 14.0303C10.1382 14.171 9.94741 14.25 9.7485 14.25H8.2485H8.25ZM13.5 6C13.8978 6 14.2794 5.84196 14.5607 5.56066C14.842 5.27936 15 4.89783 15 4.5C15 4.10218 14.842 3.72065 14.5607 3.43934C14.2794 3.15804 13.8978 3 13.5 3C13.1022 3 12.7206 3.15804 12.4393 3.43934C12.158 3.72065 12 4.10218 12 4.5C12 4.89783 12.158 5.27936 12.4393 5.56066C12.7206 5.84196 13.1022 6 13.5 6V6Z" fill="#A00404"/>
                                </svg>

                             <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div> -->


                      <!--   <div class="col-field-main">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-field">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8.25 14.25V15C8.25 15.1989 8.17098 15.3897 8.03033 15.5303C7.88968 15.671 7.69891 15.75 7.5 15.75H6V16.5C6 16.8978 5.84196 17.2794 5.56066 17.5607C5.27936 17.842 4.89782 18 4.5 18H1.5C1.10218 18 0.720644 17.842 0.43934 17.5607C0.158035 17.2794 0 16.8978 0 16.5V14.5605C8.49561e-05 14.1627 0.158176 13.7812 0.4395 13.5L6.2445 7.695C5.88856 6.48636 5.92214 5.19633 6.34048 4.00784C6.75883 2.81935 7.5407 1.79272 8.57523 1.07351C9.60977 0.354311 10.8445 -0.0209633 12.1042 0.000904168C13.364 0.0227717 14.5849 0.440671 15.5939 1.19535C16.6028 1.95002 17.3486 3.00317 17.7255 4.20546C18.1023 5.40775 18.0911 6.69817 17.6934 7.89373C17.2957 9.0893 16.5318 10.1293 15.5099 10.8664C14.488 11.6034 13.26 12 12 12H10.4985V13.5C10.4985 13.6989 10.4195 13.8897 10.2788 14.0303C10.1382 14.171 9.94741 14.25 9.7485 14.25H8.2485H8.25ZM13.5 6C13.8978 6 14.2794 5.84196 14.5607 5.56066C14.842 5.27936 15 4.89783 15 4.5C15 4.10218 14.842 3.72065 14.5607 3.43934C14.2794 3.15804 13.8978 3 13.5 3C13.1022 3 12.7206 3.15804 12.4393 3.43934C12.158 3.72065 12 4.10218 12 4.5C12 4.89783 12.158 5.27936 12.4393 5.56066C12.7206 5.84196 13.1022 6 13.5 6V6Z" fill="#A00404"/>
                                </svg>

                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"  autocomplete="current-password">

                              
                            </div>
                        </div>
 -->
                     <!--       <div class="col-field-main">
                          <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>


                            <div class="col-field">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8.25 14.25V15C8.25 15.1989 8.17098 15.3897 8.03033 15.5303C7.88968 15.671 7.69891 15.75 7.5 15.75H6V16.5C6 16.8978 5.84196 17.2794 5.56066 17.5607C5.27936 17.842 4.89782 18 4.5 18H1.5C1.10218 18 0.720644 17.842 0.43934 17.5607C0.158035 17.2794 0 16.8978 0 16.5V14.5605C8.49561e-05 14.1627 0.158176 13.7812 0.4395 13.5L6.2445 7.695C5.88856 6.48636 5.92214 5.19633 6.34048 4.00784C6.75883 2.81935 7.5407 1.79272 8.57523 1.07351C9.60977 0.354311 10.8445 -0.0209633 12.1042 0.000904168C13.364 0.0227717 14.5849 0.440671 15.5939 1.19535C16.6028 1.95002 17.3486 3.00317 17.7255 4.20546C18.1023 5.40775 18.0911 6.69817 17.6934 7.89373C17.2957 9.0893 16.5318 10.1293 15.5099 10.8664C14.488 11.6034 13.26 12 12 12H10.4985V13.5C10.4985 13.6989 10.4195 13.8897 10.2788 14.0303C10.1382 14.171 9.94741 14.25 9.7485 14.25H8.2485H8.25ZM13.5 6C13.8978 6 14.2794 5.84196 14.5607 5.56066C14.842 5.27936 15 4.89783 15 4.5C15 4.10218 14.842 3.72065 14.5607 3.43934C14.2794 3.15804 13.8978 3 13.5 3C13.1022 3 12.7206 3.15804 12.4393 3.43934C12.158 3.72065 12 4.10218 12 4.5C12 4.89783 12.158 5.27936 12.4393 5.56066C12.7206 5.84196 13.1022 6 13.5 6V6Z" fill="#A00404"/>
                                </svg>

                          <input id="password-confirm" type="password" class="form-control" name="password_confirmation"  autocomplete="new-password">

                            
                            </div>
                        </div> -->











                        <!-- <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div> -->

                        <div class="login-btn">
                            <div class="button">
                                <button type="submit" class="btn btn-primary">
                {{ __('Submit') }}
                                </button>
                                <div class="remember-pswd-btn">
                                   <a class="btn btn-link" href="{{ route('login') }}">
                                        {{ __('Remember Password?') }}
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

