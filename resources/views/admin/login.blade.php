@extends('template.master')

@section('content')

<div class="login-area pt-120 pb-120">
    <div class="container">
        <div class="row">
            <div class="col-md-5 mx-auto">
                <div class="login-form login-form-two wow slideInDown" data-wow-duration="2.5s" data-wow-delay=".25s">
                    <div class="login-header">
                        <img src="{{asset("img/logo/logo.png")}}" alt="Company logo">
                        <p class="mt-20 mb-10">Welcome <strong>Administrator</strong></p>
                    </div>
                    @if ( $errors->any() )
                    <div class="alert alert-danger">
                        <ul>
                            @foreach( $errors->all() as $key => $error )
                                <li>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    @if (session()->has('flash_message'))
                    <div class="alert alert-success">{{ session()->get('flash_message') }}</div>
                    @endif
                    <form action="{{ url('admin/login') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="email">Username</label>
                            <input type="text" class="form-control" placeholder="Username" name="email" id="email"  value="{{ old('email') }}">
                            <i class="far fa-user"></i>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" placeholder="Your Password" name="password" id="password">
                            <i class="far fa-lock"></i>
                        </div>
                        <div class="d-flex align-items-center">
                            <button type="submit" class="theme-btn"><i class="far fa-sign-in"></i> Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection