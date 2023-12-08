@extends('template.master')

@section('content')

<div class="site-breadcrumb login-breadcrumb dashboard-breadcrumb">
    <div class="container">
        <h2 class="breadcrumb-title dashboard-breadcrumb-menu-left">Dashboard</h2>
        <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
            <li class="active-nav-dashboard">Dashboard</li>
            <li><a href="{{url("/operator/dashboard")}}">Home</a></li>
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
                  <div class="user-profile-wrapper">
                      <div class="row">
                          <div class="col-md-4 col-lg-4">
                            <a href="{{ url('/operator/totalasset') }}" style="width: 100%; display: block;">
                              <div class="dashboard-widget dashboard-widget-color-1 text-center wow slideInRight"
                              data-wow-duration="1s" data-wow-delay=".75s">
                               <!--<div class="dashboard-widget-icon">
                                   <i class="fal fa-home"></i>
                               </div>-->
                               <div class="dashboard-icon-widget">
                                   <i class="dashboard-counter-icon fal fa-sort-amount-up"></i>
                               </div>
                                  <div class="dashboard-widget-info">
                                      <h5 class="dashboard-counter-number">{{ $uploaded_asset_count }}</h5>
                                      <span class="dashboard-counter-title">Total Assets</span>
                                  </div>
                                  
                              </div>
                            </a>
                          </div>
                          <div class="col-md-4 col-lg-4">
                              <a href="{{ url('/operator/vacantasset') }}" style="width: 100%; display: block;">
                              <div class="dashboard-widget dashboard-widget-color-2 text-center wow slideInRight"
                              data-wow-duration="1s" data-wow-delay="1s">
                                    <div class="dashboard-icon-widget">
                                        <i class="dashboard-counter-icon fal fa-battery-empty"></i>
                                    </div>
                                    <div class="dashboard-widget-info">
                                        <h5 class="dashboard-counter-number">{{ $vacant_asset_count }}</h5>
                                        <span class="dashboard-counter-title">Vacant Assets</span>
                                    </div>
                                </div>
                                </a>
                          </div>
                          <div class="col-md-4 col-lg-4">
                            <a href="{{ url('/operator/bookedasset') }}" style="width: 100%; display: block;">
                                <div class="dashboard-widget dashboard-widget-color-1 text-center wow slideInRight"
                                data-wow-duration="1s" data-wow-delay="1.25s">
                                <div class="dashboard-icon-widget">
                                    <i class="dashboard-counter-icon fal fa-address-book"></i>
                                </div>
                                    <div class="dashboard-widget-info">
                                        <h5 class="dashboard-counter-number">{{ $ordered_asset_count }}</h5>
                                        <span class="dashboard-counter-title">Booked Asset</span>
                                    </div>
                                </div>
                            </a>
                          </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="login-form wow fadeIn" data-wow-duration="2.5s" data-wow-delay="1.75s">
                                    <div class="user-profile-sidebar-top">
                                      <h4 class="profile-name dashboard-search-title">Dashboard</h4>
                                    </div>
                                    <div class="user-profile-sidebar-top">
                                        <h6 style="text-align:left">ABOUT {{$user->firstname?$user->lastname:$user->corporate_name}}</h6>
                                        <br> 
                                        <p class="welcome-text text-left">The company is strategically positioned as a one-stop shop for advertisers’ out-of-home advertising solutions company with avalanche of products that offer clients enormous reach and true engagement with their key audiences. This is possible through our various innovative and pace-setting products available pan-Nigeria and in major West African countries. At {{$user->firstname?$user->lastname:$user->corporate_name}}, we are the custodians of small, large, interactive and iconic out-of-home structures which offer high impact solutions to reach your audience demographic. From traditional billboard advertising to multi-format outdoor advertising campaigns, our business is focused on creating platforms that reach a wide range of audiences. Since inception, {{$user->firstname?$user->lastname:$user->corporate_name}} has been revered and recognized as an industry leader with an unrivalled insight and audience measurement tools helping us to plan, manage and evaluate our clients’ outdoor advertising campaigns from conception to completion to ensure advertisers gain the best value from their investments. We can save you time and money with accuracy and efficiency in delivery. We are a registered member of Advertising Practitioners Council of Nigeria (APCON) a regulatory sectoral body, a registered member of Outdoor Advertising Association of Nigeria ({{config('app.name')}}), the umbrella body for all out-of-home practitioners in Nigeria.</p>
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