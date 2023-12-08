@extends('template.master')

@section('content')
<style>
    .dashboard-content {
        padding-left: 273px;
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
</style>

<div class="site-breadcrumb login-breadcrumb dashboard-breadcrumb">
    <div class="container">
        <h2 class="breadcrumb-title dashboard-breadcrumb-menu-left">Platform Settings</h2>
        <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
            <li><a href="{{ url("/admin/dashboard") }}">Dashboard</a></li>
            <li class="active-nav-dashboard">Platform Settings</li>
        </ul>
    </div>
</div>
<section id="advertiser-dashboard">
    <div class="user-profile pt-40 pb-40 dashboard-bg-color">
        <div class="container">
            @if (session()->has('session-mismatch'))
                <div class="alert alert-warning">
                    <span>{{session()->get('session-mismatch')}}</span>
                </div>
            @endif
            <div class="row">
                <div class="col-lg-3">
                    <div class="user-profile-sidebar operator-profile-sidebar wow slideInLeft" data-wow-duration="2.5s" data-wow-delay=".25s">
                        <div class="user-profile-sidebar-top mt-20">
                        <img class="operator-image" src="{{ $user->profile_img ? Storage::url($user->profile_img) : asset('/img/operators/e-motion.png') }}" alt="Operator Image">
                        <p class="welcome-text fs-14 mt-10">Welcome</p>
                            <h3 class="profile-name operator-profile-name">{{ $user->firstname?$user->lastname:$user->corporate_name }}</h3>
                        </div>

                        <div class="edit-profile-div text-center mt-20 mb-30">
                            {{ $department }} DEPARTMENT
                        </div>
                        <div class="profile-stats text-center">
                            <a href="{{ url('/admin/logout') }}" class="log-out-btn color-bg">Log Out <i class="far fa-sign-out"></i></a>
                        </div>
                        
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="user-profile-wrapper">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="login-form wow fadeIn" data-wow-duration="2.5s" data-wow-delay="1.75s">
                                    <div class="user-profile-sidebar-top">
                                        <h4 class="profile-name dashboard-search-title">Platform Settings</h4>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <form action="{{ url('/admin/settings') }}" method="post" role="form">
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

                                                @if (session()->has('success'))
                                                    <div class="alert alert-success">
                                                        <span>{{session()->get('success')}}</span>
                                                    </div>
                                                @endif
                                                <div class="row">
                                                    <div class="col-sm-5">
                                                        <div class="form-group">
                                                            <label for="commission">Percentage Commission</label>
                                                            <input type="text" class="form-control form-control-sm" name="commission" id="commission" value="{{ $settings->commission }}">
                                                            <i class="fal fa-percentage"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <div class="form-group">
                                                            <label for="subscription">OAAN Subscription Setup (Annually)</label>
                                                            <input type="text" class="form-control form-control-sm" name="subscription" id="subscription" value="{{ $settings->subscription }}" placeholder="Flat rate subscription only.">
                                                            <i class="fal fa-handshake"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-5">
                                                        <div class="form-group">
                                                            <label for="distance_km">Distance Difference(km)</label>
                                                            <input type="text" class="form-control form-control-sm"  name="distance_km" id="distance_km" value="{{ $settings->distance_km }}">
                                                            <i class="fal fa-terminal"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <div class="form-group">
                                                            <label for="population_randomness">Increase Population Randomness (%)</label>
                                                            <input type="text" class="form-control form-control-sm"  name="population_randomness" id="population_randomness" value="{{ $settings->population_randomness }}">
                                                            <i class="fal fa-users"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="filter-tags">
                                                    <label class="fs-13">You can always modify the platform settings from here.</label>
                                                </div>
                                                <span class="fw-separator"></span>
                                                <div class="col-md-2">
                                                    <button type="submit" class="action-button theme-btn" style="float:left;">Modify<i class="fal fa-money-bill"></i></button>
                                                </div>
                                                <br>
                                                <br>
                                            </form>
                                            <hr>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <a href="settings/run-utility/population-randomness"  class="action-button theme-btn">Run Population Randomness Utility</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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

@section('asset-details-js')
    <script>
        $(document).ready(function(){
            $("#distance_km, #population_randomness").on("keyup", function() {
                let self = $(this)
                let val = self.val();
                if (isNaN(val)) {
                    alert("Please enter only number into this box");
                    self.val("");
                    return ;
                }
            });
        });
    </script>
@endsection