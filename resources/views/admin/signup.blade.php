@extends('reformedtemplate.master')

@section('content')

    <style>
        .text-danger {
            font-size: 12px !important;
        }
        .save span {
            color: #FFF !important;
        }
        .action-button {
            float: right;
        }
    
        .previous-form {
            float:left;
        }
    
        .parallax-section {
            padding: 110px 0 !important;
        }
    
        .alert {
            clear: both;
            margin-bottom: 40px;
        }
    
        .alert ul {
            display: block;
            padding: 15px 31px;
        }
    
        .alert ul li {
            font-size: 13px;
            line-height: 24px;
            padding-bottom: 10px;
            font-weight: 500;
            color: #b80640;
            text-align: left;
        }
    </style>

    <section class="color-bg parallax-section">
        <div class="city-bg"></div>
        <div class="cloud-anim cloud-anim-bottom x1"><i class="fal fa-cloud"></i></div>
        <div class="cloud-anim cloud-anim-top x2"><i class="fal fa-cloud"></i></div>
        <div class="overlay op1 color3-bg"></div>
        <br>
        <br>
        <div class="container">
            <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1">
                <br/> 
                <form action="{{ url('admin/signup') }}" method="post">              
                    <div class="list-single-main-item fl-wrap hidden-section tr-sec" style="box-shadow: 0px 0px 0px 4px rgba(0,0,0,0.2);">
                        <div class="profile-edit-container">
                            <div class="custom-form">
                                <fieldset class="fl-wrap">
                                    <div class="list-single-main-item-title fl-wrap">
                                        <h3>Administrator Information</h3>
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
                                    @csrf
                        
                                    <div>
                                        <div class="col-sm-6">
                                            <label for="firstname">Firstname <i class="far fa-user"></i></label>
                                            <input type="text" name="firstname" id="firstname" class="form-control" placeholder="Daniel" value="{{ old('firstname') }}">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="lastname">Lastname <i class="far fa-user"></i></label>
                                            <input type="text" name="lastname" id="lastname" class="form-control" placeholder="Park" value="{{ old('lastname') }}">
                                        </div>
                                    </div>
                                    <div>
                                        <div class="col-sm-12">
                                            <label for="email">Email <i class="far fa-envelope-open"></i></label>
                                            <input type="email" name="email" id="email" class="form-control" placeholder="danielpark@gmail.com" value="{{ old('email') }}">
                                        </div>
                                    </div>

                                    <div>
                                        <div class="col-sm-12">
                                            <label for="phone">Phone <i class="far fa-phone"></i></label>
                                            <input type="text" name="phone" id="phone" class="form-control" placeholder="+2348145678924" value="{{ old('phone') }}">
                                        </div>
                                    </div>

                                    <div>
                                        <div class="col-sm-12">
                                            <label for="address">Address <i class="fal fa-street-view"></i></label>
                                            <input type="text" name="address" id="address" class="form-control" placeholder="Block 234 Plot 6 marylad estate Ikoyi" value="{{ old('address') }}">
                                        </div>
                                    </div>

                                    <div>
                                        <div class="col-sm-12">
                                            <label for="password">Password <i class="fal fa-key"></i></label>
                                            <input type="password" name="password" id="password" placeholder="*************" class="form-control">
                                        </div>
                                    </div>
                                    <div>
                                        <div class="col-sm-12">
                                            <label for="password_confirmation">Repeat Password <i class="fal fa-key"></i></label>
                                            <input type="password" name="password_confirmation" placeholder="*************" id="password_confirmation" class="form-control">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <button type="submit" class="action-button btn no-shdow-btn color-bg" style="float:left;">Register <i class="fal fa-user-plus"></i></button>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection