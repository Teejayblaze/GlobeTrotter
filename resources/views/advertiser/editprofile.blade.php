@extends('template.master')

@section('content')
<div class="site-breadcrumb login-breadcrumb dashboard-breadcrumb">
    <div class="container">
        <h2 class="breadcrumb-title dashboard-breadcrumb-menu-left">Edit Profile</h2>
        <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
            <li><a href="{{ url('/advertiser/individual/dashboard') }}">Dashboard</a></li>
            <li>Profile</li>
            <li class="active-nav-dashboard">Edit</li>
        </ul>
    </div>
</div>
<section id="advertiser-dashboard">
    <div class="user-profile pt-40 pb-40 dashboard-bg-color">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="user-profile-sidebar wow slideInLeft" data-wow-duration="2.5s" data-wow-delay=".25s">
                        <div class="user-profile-sidebar-top mt-40">
                        <p class="welcome-text">Welcome</p>
                            <h3 class="profile-name">{{ $edit_user->firstname?$edit_user->lastname:$edit_user->corporate_name }}</h3>
                        </div>
                        <div class="edit-profile-div text-center mt-20">
                            <a href="{{url('/advertiser/individual/profile/edit')}}"><button class="theme-btn edit-profile-btn">Edit Profile</button></a>
                        </div>
                        <div class="user-profile-sidebar-top profile-detail mt-40">
                            @if ($user->corp_id)
                            <h6 class="profile-position">{{$user->designation}} <br><br> <span>@</span></h6>
                            <h3 class="profile-company mt-20">{{$user->work_with->name}}</h3>
                            @endif
                        </div>
                        <div class="profile-stats text-center">
                        <div class="row">
                            <div class="col-lg-6 profile-stats-divider">
                            <div class="profile-stats-border-right mb-10">
                            <div class="profile-stats-title">Paid</div>
                            <div class="profile-stats-value">{{ $paid_tranx_count }}</div>
                            </div>
                            </div>
                            <div class="col-lg-6 profile-stats-divider">
                            <div class="mb-10">
                            <div class="profile-stats-title">Bookings</div>
                            <div class="profile-stats-value">{{ $booked_asset_count }}</div>
                            </div>
                            </div>
                            <div class="col-lg-12">
                            <div class="mt-10">
                                <div class="profile-stats-title">Pending</div>
                            <div class="profile-stats-value">{{ $pending_tranx_count }}</div>
                            </div>
                            </div>
                        </div>

                        </div>
                        <ul class="user-profile-sidebar-list text-center">
                            <li><a class="profile-logout-btn" href="{{ url('/advertiser/individual/logout') }}"><i class="far fa-sign-out"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9">
                <div class="user-profile-wrapper wow slideInRight" data-wow-duration="2.5s" data-wow-delay=".25s">
                    <div class="user-profile-card add-property">
                        <h4 class="user-profile-card-title">Your Profile</h4>
                            <div class="col-lg-12">
                                <div class="login-form login-form-two">
                                    <form action="{{ url('advertiser/individual/profile/edit') }}" method="post" role="form">
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

                                        @if (session()->has('edit-success'))
                                            <div class="alert alert-success">
                                                <span>{{session()->get('edit-success')}}</span>
                                            </div>
                                        @endif
                                        <input type="hidden" name="user_id" id="user_id" value="{{ $edit_user->id }}" required>
                                        <input type="hidden" name="user_type_id" id="user_type_id" value="{{ $user->user_type_id }}" required>  

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                <label>First Name</label>
                                                <input type="text" name="firstname" id="firstname" class="form-control" value="{{ $edit_user->firstname }}" required>
                                                <i class="far fa-user"></i>
                                            </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                <label>Last Name</label>
                                                <input type="text" class="form-control" name="lastname" id="lastname" value="{{ $edit_user->lastname }}" required>
                                                <i class="far fa-user"></i>
                                            </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                <label>Email Address</label>
                                                <input type="email" class="form-control" name="email" id="email" value="{{ $edit_user->email }}" required>
                                                <i class="far fa-envelope"></i>
                                            </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                <label>Phone</label>
                                                <input type="tel" class="form-control" name="phone" id="phone" value="{{ $edit_user->phone }}" required>
                                                <i class="far fa-phone"></i>
                                            </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                <label>Address</label>
                                                <input type="text" class="form-control" placeholder="Your Address" name="address" id="address" value="{{ $edit_user->address }}" required>
                                                <i class="fal fa-road"></i>
                                            </div>
                                            </div>
                                            <p class="mb-20">Kindly ensure the information you are changing are valid and concise.</p>
                                            <div class="d-flex justify-content-end justify-content-xm-center">
                                                <button type="submit" class="theme-btn update-profile-btn"><i class="far fa-sign-in"></i>Update Profile</button>
                                            </div>
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