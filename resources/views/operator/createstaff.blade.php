@extends('template.master')

@section('content')
<div class="site-breadcrumb login-breadcrumb dashboard-breadcrumb">
    <div class="container">
        <h2 class="breadcrumb-title dashboard-breadcrumb-menu-left">Create Staff</h2>
        <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
            <li><a href="{{url('/operator/dashboard')}}">Dashboard</a></li>
            <li><a href="{{url()->previous()}}">Staffs</a></li>
            <li class="active-nav-dashboard">Create Staff</li>
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
                            {{-- <img class="operator-image" src="{{ asset('/img/operators/e-motion.png') }}" alt="Operator Image"> --}}
                            <p class="welcome-text fs-18 mt-10">Welcome</p>
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
                                <a href="{{ url('/operator/totalasset') }}" style="width: 100%">
                                 <div class="profile-stats-border-right mb-10">
                                <div class="profile-stats-title">Total Asset</div>
                                 <div class="profile-stats-value">{{ $uploaded_asset_count }}</div>
                                 </div>
                                </a>
                               </div>
                               <div class="col-lg-6 profile-stats-divider">
                                 <a href="{{ url('/operator/vacantasset') }}">
                                     <div class="mb-10">
                                     <div class="profile-stats-title">Vacant Asset</div>
                                      <div class="profile-stats-value">{{ $vacant_asset_count }}</div>
                                     </div>
                                 </a>
                               </div>
                               <div class="col-lg-12">
                                 <a href="{{ url('/operator/bookedasset') }}">
                                     <div class="mt-10">
                                         <div class="profile-stats-title">Booked Asset</div>
                                         <div class="profile-stats-value">{{ $ordered_asset_count }}</div>
                                     </div>
                                 </a>
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
                            <h4 class="user-profile-card-title">Create Staff</h4>
                            <div class="col-lg-12">
                                <div class="login-form login-form-two">
                                    <form action="{{ url('operator/create-staff') }}" method="post" role="form">
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

                                        @if (session()->has('create-success'))
                                            <div class="alert alert-success">
                                                <span>{{session()->get('create-success')}}</span>
                                            </div>
                                        @endif

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="firstname">First Name </label>
                                                    <input type="text" class="form-control" name="firstname" id="firstname" value="{{ old('firstname') }}" placeholder="Staff Firstname" required>
                                                    <i class="far fa-user"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="lastname">Last Name </label>
                                                    <input type="text" placeholder="Staff Lastname" class="form-control" name="lastname" id="lastname" value="{{ old('lastname') }}">
                                                    <i class="far fa-user-plus"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="email">Email Address </label>
                                                    <input type="text" placeholder="staffmail@domain.com" class="form-control" name="email" id="email" value="{{ old("email") }}">
                                                    <i class="fal fa-at"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="phone">Phone Number </label>
                                                    <input type="text" class="form-control" placeholder="+23481 234 567 9990" name="phone" id="phone" value="{{ old("phone") }}">
                                                    <i class="fal fa-mobile"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="address">Address</label>
                                                    <input type="text" class="form-control" placeholder="Staff Address"  name="address" id="address" value="{{ old("address") }}">
                                                    <i class="fal fa-address-card"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="designation">Designation </label>
                                                    <input type="text" class="form-control" placeholder="Staff Designation" name="designation" id="designation" value="{{ old("designation") }}">
                                                    <i class="fal fa-sitemap"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="password">Password</label>
                                                    <input type="text" class="form-control" placeholder="Staff Temporary Password" name="password" id="password" value="{{ old("password") }}">
                                                    <i class="fal fa-lock-keyhole"></i>
                                                </div>
                                            </div>
                                        </div>
                                         <p>You can create your company staff and they will have individual permission.</p>
                                        <div class="d-flex justify-content-end justify-content-xm-center">
                                            <button type="submit" class="theme-btn update-profile-btn"><i class="far fa-sign-in"></i>Create Staff</button>
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