@extends('template.master')

@section('content')

    <style>
        .alert {
            clear: both;
        }
    </style>

    <div class="login-area operator-login-area pt-80 pb-80">
        <div class="container">
              <div class="col-md-5 mx-auto">
                 <div class="login-form login-form-two wow slideInDown" data-wow-duration="2.5s" data-wow-delay=".25s">
                     <div class="login-header">
                         <img src="{{asset("img/logo/logo.png")}}" alt="">
                         <p>Log in as an <strong>Asset Onwer</strong></p>
                     </div>
                    @if ( $errors->any() )
                    <div class="alert alert-danger">
                        <ul>
                            @foreach( $errors->all() as $error )
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if (session()->has('flash_message'))
                    <div class="alert alert-success">{{ session()->get('flash_message') }}</div>
                    @endif

                    <form action="{{ url('operator/login') }}" method="post">
                         @csrf
                         <div class="form-group">
                             <label>Username</label>
                             <input type="text" class="form-control" placeholder="Username" name="email" id="email" value="{{ old('email') }}">
                             <i class="far fa-user"></i>
                         </div>
                         <div class="form-group">
                             <label>Password</label>
                             <input type="password" class="form-control" placeholder="Your Password" name="password" id="password">
                             <i class="far fa-lock"></i>
                         </div>
                         <div class="d-flex justify-content-between mb-3">
                             <div class="form-check">
                                 <input class="form-check-input" type="checkbox" value="" id="remember">
                                 <label class="form-check-label" for="remember">
                                    Remember Me
                                 </label>
                             </div>
                             {{-- <a href="forgot-password.html" class="forgot-pass">Forgot Password?</a> --}}
                         </div>
                         <div class="d-flex align-items-center">
                             <button type="submit" class="theme-btn"><i class="far fa-sign-in"></i> Login</button>
                         </div>
                     </form>
                 </div>
             </div>
        </div>
    </div>
@endsection