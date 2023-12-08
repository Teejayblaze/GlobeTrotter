@extends('reformedtemplate.master')

@section('content')
    <style>
        .dashboard-content {
            padding-left: 262px;
        }

        .action-button {
            float: right;
        }
        
        .alert {
            clear: both;
            margin-bottom: 40px;
            padding: 20px;
            border-radius: 4px;
        }

        .alert ul {
            display: block;
        }

        .alert-success {
            background-color: #d6eafa;
            color: #4b93c8;
        }

        .alert-danger {
            background: #fadde6;
        }

        .alert ul li {
            font-size: 13px;
            line-height: 24px;
            font-weight: 500;
            color: #b80640;
            text-align: left;
        }
        .x1 {bottom: 77px;}
        .x2 {top: 60px;}
        .city-bg {background: url("{{asset('vendor/reformedtemplate/images/city.png')}}") repeat-x 0 194px;}
    </style>
    <div id="wrapper">
        <!-- content-->
        <div class="content">
            <!-- section-->
            <section class="flat-header color-bg adm-header">
                <div class="city-bg"></div>
                <div class="cloud-anim cloud-anim-bottom x1"><i class="fal fa-cloud"></i></div>
                <div class="cloud-anim cloud-anim-top x2"><i class="fal fa-cloud"></i></div>
                <div class="container">
                    <div class="dasboard-wrap fl-wrap">
                        <div class="dasboard-breadcrumbs breadcrumbs"><a href="{{ url("/advertiser/individual/dashboard") }}">Dashboard</a><a href="#">Advert Manager</a><span>Booked Asset</span></div>
                        <!--dasboard-sidebar-->
                        <div class="dasboard-sidebar">
                            <div class="dasboard-sidebar-content fl-wrap scroll-to-fixed-fixed">
                                <div class="dasboard-avatar">
                                    <img src="{{ asset("vendor/reformedtemplate/images/45.jpg") }}" alt="">
                                </div>
                                <div class="dasboard-sidebar-item fl-wrap">
                                    <h3>
                                        <span>Welcome </span>
                                        {{ $user->firstname?$user->lastname:$user->corporate_name }}
                                    </h3>
                                </div>
                                <a href="{{url('/advertiser/individual/profile/edit')}}" class="ed-btn">Edit Profile</a>  
                                @if ($user->corp_id)
                                    <div class="col-md-12 dasboard-sidebar-item fl-wrap">
                                        <h3>
                                            <span style="font-size:14px;">{{$user->designation}} </span>
                                            <span>@ </span>
                                            {{$user->work_with->name}}
                                        </h3>
                                    </div>   
                                @endif                                      
                                <div class="user-stats fl-wrap">
                                    <ul>
                                        <li>
                                            Listings	
                                            <span>4</span>
                                        </li>
                                        <li>
                                            Bookings
                                            <span>32</span>	
                                        </li>
                                        <li>
                                            Reviews	
                                            <span>9</span>	
                                        </li>
                                    </ul>
                                </div>
                                <a href="{{ url('/advertiser/individual/logout') }}" class="log-out-btn color-bg">Log Out <i class="far fa-sign-out"></i></a>
                            </div><div></div>
                        </div>
                        <!--dasboard-sidebar end--> 
                    
                        <!--Tariff Plan menu-->
                        <div class="tfp-btn"><span>Browse Search: </span> <strong>Advance</strong></div>
                        <div class="tfp-det">
                            <p>You can use <a href="#">Advance Search</a> optimization option to streamline interested result on site. </p>
                            <a href="{{ url('/advertiser/individual/search/advanced') }}" class="tfp-det-btn color2-bg">Details</a>
                        </div>
                        <!--Tariff Plan menu end-->
                    </div>
                </div>
            </section>
            <!-- section end-->
            <!-- section-->
            <section class="middle-padding grey-blue-bg" style="padding: 15px 0">
                <div class="container">
                    <div class="row">
                        <div class="dashboard-content fl-wrap">
                            <div class="col-md-12" style="padding-top: 25px; margin-left:10px;">
                                <div class="list-single-main-item fl-wrap hidden-section tr-sec">
                                    <div class="profile-edit-container">
                                        <div class="custom-form">
                                            <form action="{{ url('advertiser/individual/corporate/profile/edit') }}" method="post" role="form">
                                                <fieldset class="fl-wrap">
                                                    <div class="list-single-main-item-title fl-wrap">
                                                        <h3>Your Corporate Profile</h3>
                                                    </div>
                                                        @csrf                           
                                                        @if ( $errors->any() )
                                                            <div class="alert alert-danger">
                                                                <ul>
                                                                    @foreach( $errors->all() as $error )
                                                                        <li>{{ $error }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @endif

                                                        @if (session()->has('corporate-edit-success'))
                                                            <div class="alert alert-success">
                                                                <span>{{session()->get('corporate-edit-success')}}</span>
                                                            </div>
                                                        @endif
                                                    
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <label>Name <i class="far fa-user-alt"></i></label>
                                                            <input type="text" placeholder="Your Corporate Name" name="name" id="name" value="{{ $edit_corp->name }}" required> 
                                                            <input type="hidden" name="corp_id" id="corp_id" value="{{ $edit_corp->id }}" required>                                               
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label>Website <i class="far fa-globe-asia"></i></label>
                                                            <input type="text" placeholder="Your Corporate Website" name="website" id="website" value="{{ $edit_corp->website }}" required>                                        
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <label>Email <i class="far fa-user"></i></label>
                                                            <input type="text" placeholder="yourmail@domain.com" name="email" id="email" value="{{ $edit_corp->email }}" required> 
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <label>Address <i class="fal fa-street-view"></i> </label>
                                                            <input type="text" placeholder="Your Address" name="address" id="address"  value="{{ $edit_corp->address }}" required>                                                  
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label>Phone<i class="far fa-phone"></i>  </label>
                                                            <input type="text" placeholder="87945612233" name="phone" id="phone"  value="{{ $edit_corp->phone }}" required>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <label>RC Number <i class="far fa-file-signature"></i>  </label>
                                                            <input type="text" placeholder="RC012356" name="rc_number" id="rc_number"  value="{{ $edit_corp->rc_number }}" required>                                                  
                                                        </div>
                                                    </div>
                                                    <div class="filter-tags">
                                                        <label>Kindly ensure the information you are changing are valid and concise.</label>
                                                    </div>
                                                    <span class="fw-separator"></span>
                                                    <button type="submit" class="action-button btn color2-bg no-shdow-btn">Update Corporate Profile<i class="fal fa-user-alt"></i></button>
                                                </fieldset>
                                            </form>
                                        </div>
                                    </div>
                                </div>                         
                            </div>  
                            <!-- col-md-12 end -->        
                        </div>
                    </div>
                </div>
            </section>
            <!-- section end-->
            <div class="limit-box fl-wrap"></div>
        </div>
        <!-- content end-->
    </div>
@endsection