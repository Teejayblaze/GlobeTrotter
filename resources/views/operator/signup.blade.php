@extends('template.master')

@section('content')
<div class="login-area pt-60 pb-100">
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="login-form individual-login-form wow slideInLeft" data-wow-duration="2.5s" data-wow-delay=".25s">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="login-header custom-login-header">
                                <p><strong>Your {{ env('APP_OWNER_NAME') }} Information</strong></p>
                                <div class="fs-14">Please verify that your displayed information is valid.</div>
                                <hr>
                            </div>
                        </div>
                        @if ($op_verify_det)
                            
                            @if ($op_verify_det->status)

                            <form action="{{ url('/operator/signup') }}" method="POST">
                                @csrf
                                <div class="list-single-main-item-title fl-wrap mb-20">
                                    @if ( $errors->any() )
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach( $errors->all() as $error )
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <h5><strong>Corporate Name</strong></h5>
                                            <p>{{ $op_verify_det->success->corporate_name }}</p>
                                            <input type="hidden" name="corporate_name" id="corporate_name" value="{{ $op_verify_det->success->corporate_name }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <h5><strong>Corporate Email</strong></h5>
                                            <p>{{ $op_verify_det->success->email }}</p>
                                            <input type="hidden" name="email" id="email" value="{{ $op_verify_det->success->email }}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <h5><strong>RC Number</strong></h5>
                                            <p>{{ $op_verify_det->success->rc_number }}</p>
                                            <input type="hidden" name="rc_number" id="rc_number" value="{{ $op_verify_det->success->rc_number }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <h5><strong>OAAN Number</strong></h5>
                                            <p>{{ $op_verify_det->success->oaan_number }}</p>
                                            <input type="hidden" name="oaan_number" id="oaan_number" value="{{ $op_verify_det->success->oaan_number }}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-20">
                                    <div class="divider col-md-12">
                                        <h4><strong>Credentials</strong></h4>
                                        <p>Please note that you require your email and password for valid Registration.</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="phone">Phone <i class="far fa-phone"></i></label>
                                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">Password <i class="fal fa-lock-alt"></i></label>
                                            <input type="password" name="password" id="password" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password_confirmation">Password Confirmation <i class="fal fa-lock-alt"></i></label>
                                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <button type="submit" class="theme-btn action-button btn no-shdow-btn color-bg save" style="float: left;">
                                            <i class="fal fa-check"></i>
                                            <span> REGISTER</span>
                                        </button>
                                    </div>
                                </div>
                            </form>

                            @else

                            <div class="error-wrap" style="color:#333 !important;">
                                <h2 style="color:#333 !important;">404</h2>
                                <p>{{ $op_verify_det->errors }}</p>
                                <br>
                                <p><strong> </a> </strong></p>
                                <div class="clearfix"></div>
                                <a href="{{ url('/operator/signup') }}" class="btn color2-bg flat-btn">Want to Retry?<i class="fal fa-home"></i></a>
                            </div>

                            @endif
                        @else
                            <p>Your session for this confirmation has expired. <br><a href="{{url("/operator/signup")}}">Kindly Go Back to verify your number again.</a></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
