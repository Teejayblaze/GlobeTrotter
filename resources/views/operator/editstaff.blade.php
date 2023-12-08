@extends('template.master')

@section('content')
<div class="site-breadcrumb login-breadcrumb dashboard-breadcrumb">
    <div class="container">
        <h2 class="breadcrumb-title dashboard-breadcrumb-menu-left">Edit {{$edit_user->lastname}} Details</h2>
        <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
            <li><a href="{{url('/operator/dashboard')}}">Dashboard</a></li>
            <li><a href="{{url()->previous()}}">Staffs</a></li>
            <li class="active-nav-dashboard">Edit Staff</li>
        </ul>
    </div>
</div>
<section id="advertiser-dashboard">
    <div class="user-profile pt-40 pb-40 dashboard-bg-color">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="user-profile-sidebar operator-profile-sidebar wow slideInLeft" data-wow-duration="2.5s" data-wow-delay=".25s">
                        <div class="user-profile-sidebar-top mt-20">
                            <img class="operator-image" src="{{ $user->profile_img ? Storage::url($user->profile_img) : asset('/img/operators/e-motion.png') }}" alt="Operator Image">
                            <p class="welcome-text fs-14 mt-10">Welcome</p>
                            <h3 class="profile-name operator-profile-name">{{ $user->firstname?$user->lastname:$user->corporate_name }}</h3>
                        </div>
                            @if ($user->corp_id)
                            <div class="user-profile-sidebar-top profile-detail mt-40">
                                <h6 class="profile-position">{{$user->designation}} <br><br> <span>@</span></h6>
                                <h3 class="profile-company mt-20">{{$user->work_with->corporate_name}}</h3>
                            </div>
                            @endif
                        <div class="edit-profile-div text-center mt-20 mb-30">
                            <a href=""><button class="theme-btn edit-profile-btn">SUBSCRIPTION STATUS: {{$user->subscription == 1 ? 'POSITIVE':'NEGATIVE'}}</button></a>
                        </div>
                        <div class="profile-stats text-center">
                            <div class="row">
                            <div class="col-lg-6 profile-stats-divider">
                                <div class="profile-stats-border-right mb-10">
                                <div class="profile-stats-title">Total Asset</div>
                                <div class="profile-stats-value">{{ $uploaded_asset_count }}</div>
                                </div>
                            </div>
                            <div class="col-lg-6 profile-stats-divider">
                                <div class="mb-10">
                                <div class="profile-stats-title">Vacant Asset</div>
                                <div class="profile-stats-value">{{ $vacant_asset_count }}</div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mt-10">
                                    <div class="profile-stats-title">Booked Asset</div>
                                <div class="profile-stats-value">{{ $ordered_asset_count }}</div>
                                </div>
                            </div>
                            </div>
    
                        </div>
                        <ul class="user-profile-sidebar-list text-center">
                            <li><a class="profile-logout-btn" href="{{ url('/operator/logout') }}"><i class="far fa-sign-out"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="user-profile-wrapper wow slideInRight" data-wow-duration="2.5s" data-wow-delay=".25s">
                        <div class="user-profile-card add-property">
                            <h4 class="user-profile-card-title">Edit {{$edit_user->lastname}} Details</h4>
                            <div class="col-lg-12">
                                <div class="login-form login-form-two">
                                    <form action="{{ url('operator/edit_staff') }}" method="post" role="form">
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

                                        @if (session()->has('flash_message'))
                                            <div class="alert alert-success">
                                                <span>{{session()->get('flash_message')}}</span>
                                            </div>
                                        @endif
                                        
                                        <input type="hidden" name="indv_id" id="indv_id" value="{{ $edit_user->id }}" required>
                                        <input type="hidden" name="user_type_id" id="user_type_id" value="{{ $user->user_type_id }}" required>  
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="firstname">First Name </label>
                                                    <input type="text" class="form-control" name="firstname" id="firstname" value="{{ $edit_user->firstname }}" placeholder="Staff Firstname" required>
                                                    <i class="far fa-user"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="lastname">Last Name </label>
                                                    <input type="text" placeholder="Staff Lastname" class="form-control" name="lastname" id="lastname" value="{{ $edit_user->lastname }}">
                                                    <i class="far fa-user-plus"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="email">Email Address </label>
                                                    <input type="text" placeholder="staffmail@domain.com" class="form-control" name="email" id="email" value="{{ $edit_user->email }}">
                                                    <i class="fal fa-at"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="phone">Phone Number </label>
                                                    <input type="text" class="form-control" placeholder="+23481 234 567 9990" name="phone" id="phone" value="{{ $edit_user->phone }}">
                                                    <i class="fal fa-mobile"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="address">Address</label>
                                                    <input type="text" class="form-control" placeholder="Staff Address"  name="address" id="address" value="{{ $edit_user->address }}">
                                                    <i class="fal fa-address-card"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="designation">Designation </label>
                                                    <input type="text" class="form-control" placeholder="Staff Designation" name="designation" id="designation" value="{{ $edit_user->designation }}">
                                                    <i class="fal fa-sitemap"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="password">Password</label>
                                                    <input type="password" class="form-control" placeholder="Staff Temporary Password" name="password" id="password" readonly value="{{ $edit_user->token }}">
                                                    <i class="fal fa-lock-keyhole"></i>
                                                </div>
                                            </div>
                                        </div>
                                         <p>Kindly ensure the information you are changing are valid and concise.</p>
                                        <div class="d-flex justify-content-end justify-content-xm-center">
                                            <button type="submit" class="theme-btn update-profile-btn"><i class="far fa-sign-in"></i>Update Profile</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection