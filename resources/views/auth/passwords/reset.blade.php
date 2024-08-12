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
                    <h3>Change Password</h3>
                </div>
                <div class="card-header">
</div>
<div class="foget-content">
    <p>Hey, Please enter your new password</p>
</div>

                <div class="card-body">

                  <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                           <input type="hidden" name="token" value="{{ $token }}">
                    @if (session('status'))
                                    <p class="alert alert-info"> {{ session('status') }}</p>
                                @endif
  
                        <div class="col-field-main">
                      

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

                            <div class="col-field-main">
                            <!-- <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label> -->

                            <div class="col-field">
                            <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M8.75 14.7699V15.5199C8.75 15.7188 8.67098 15.9096 8.53033 16.0502C8.38968 16.1909 8.19891 16.2699 8 16.2699H6.5V17.0199C6.5 17.4177 6.34196 17.7993 6.06066 18.0806C5.77936 18.3619 5.39782 18.5199 5 18.5199H2C1.60218 18.5199 1.22064 18.3619 0.93934 18.0806C0.658035 17.7993 0.5 17.4177 0.5 17.0199V15.0804C0.500085 14.6826 0.658176 14.3011 0.9395 14.0199L6.7445 8.2149C6.38856 7.00625 6.42214 5.71623 6.84048 4.52774C7.25883 3.33925 8.0407 2.31262 9.07523 1.59341C10.1098 0.874208 11.3445 0.498934 12.6042 0.520802C13.864 0.542669 15.0849 0.960568 16.0939 1.71524C17.1028 2.46992 17.8486 3.52307 18.2255 4.72536C18.6023 5.92765 18.5911 7.21807 18.1934 8.41363C17.7957 9.60919 17.0318 10.6492 16.0099 11.3863C14.988 12.1233 13.76 12.5199 12.5 12.5199H10.9985V14.0199C10.9985 14.2188 10.9195 14.4096 10.7788 14.5502C10.6382 14.6909 10.4474 14.7699 10.2485 14.7699H8.7485H8.75ZM14 6.5199C14.3978 6.5199 14.7794 6.36186 15.0607 6.08056C15.342 5.79925 15.5 5.41772 15.5 5.0199C15.5 4.62207 15.342 4.24054 15.0607 3.95924C14.7794 3.67793 14.3978 3.5199 14 3.5199C13.6022 3.5199 13.2206 3.67793 12.9393 3.95924C12.658 4.24054 12.5 4.62207 12.5 5.0199C12.5 5.41772 12.658 5.79925 12.9393 6.08056C13.2206 6.36186 13.6022 6.5199 14 6.5199Z" fill="#0F5132"/>
</svg>


                             <input id="password-confirm" type="password" placeholder="Enter new password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div> 


                        <div class="col-field-main">
                            <!-- <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label> -->

                            <div class="col-field">
                               <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M8.75 14.7699V15.5199C8.75 15.7188 8.67098 15.9096 8.53033 16.0502C8.38968 16.1909 8.19891 16.2699 8 16.2699H6.5V17.0199C6.5 17.4177 6.34196 17.7993 6.06066 18.0806C5.77936 18.3619 5.39782 18.5199 5 18.5199H2C1.60218 18.5199 1.22064 18.3619 0.93934 18.0806C0.658035 17.7993 0.5 17.4177 0.5 17.0199V15.0804C0.500085 14.6826 0.658176 14.3011 0.9395 14.0199L6.7445 8.2149C6.38856 7.00625 6.42214 5.71623 6.84048 4.52774C7.25883 3.33925 8.0407 2.31262 9.07523 1.59341C10.1098 0.874208 11.3445 0.498934 12.6042 0.520802C13.864 0.542669 15.0849 0.960568 16.0939 1.71524C17.1028 2.46992 17.8486 3.52307 18.2255 4.72536C18.6023 5.92765 18.5911 7.21807 18.1934 8.41363C17.7957 9.60919 17.0318 10.6492 16.0099 11.3863C14.988 12.1233 13.76 12.5199 12.5 12.5199H10.9985V14.0199C10.9985 14.2188 10.9195 14.4096 10.7788 14.5502C10.6382 14.6909 10.4474 14.7699 10.2485 14.7699H8.7485H8.75ZM14 6.5199C14.3978 6.5199 14.7794 6.36186 15.0607 6.08056C15.342 5.79925 15.5 5.41772 15.5 5.0199C15.5 4.62207 15.342 4.24054 15.0607 3.95924C14.7794 3.67793 14.3978 3.5199 14 3.5199C13.6022 3.5199 13.2206 3.67793 12.9393 3.95924C12.658 4.24054 12.5 4.62207 12.5 5.0199C12.5 5.41772 12.658 5.79925 12.9393 6.08056C13.2206 6.36186 13.6022 6.5199 14 6.5199Z" fill="#0F5132"/>
</svg>


                                <input id="password" type="password" placeholder="confirm password" class="form-control @error('password') is-invalid @enderror" name="password"  autocomplete="current-password">

                              
                            </div>
                        </div>
 
                  












                        <div class="login-btn">
                            <div class="button">
                                <button type="submit" class="btn btn-primary">
                {{ __('Save Password') }}
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

