@extends('template.master')

@section('content')
    <div class="site-breadcrumb login-breadcrumb dashboard-breadcrumb">
        <div class="container">
            <h2 class="breadcrumb-title dashboard-breadcrumb-menu-left">Dashboard</h2>
            <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
                <li class="active-nav-dashboard">Dashboard</li>
                <li><a href="{{url("/")}}">Home</a></li>
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
                        <div class="user-profile-sidebar wow slideInLeft" data-wow-duration="2.5s" data-wow-delay=".25s">
                            <div class="user-profile-sidebar-top mt-40">
                                <p class="welcome-text">Welcome</p>
                                <h3 class="profile-name">{{ $user->firstname?$user->lastname:$user->corporate_name }}</h3>
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
                                            <div class="profile-stats-title">Pending</div>
                                            <div class="profile-stats-value">{{ $pending_tranx_count }}</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mt-10">
                                            <div class="profile-stats-title">Booked Site</div>
                                            <div class="profile-stats-value">{{ $booked_asset_count }}</div>
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
                        <div class="user-profile-wrapper">
                            <div class="row">
                                <div class="col-md-3 col-lg-3">
                                    <div class="dashboard-widget dashboard-widget-color-1 text-center wow slideInRight"
                                    data-wow-duration="1s" data-wow-delay=".75s">
                                    <!--<div class="dashboard-widget-icon">
                                        <i class="fal fa-home"></i>
                                    </div>-->
                                        <div class="dashboard-icon-widget">
                                            <i class="dashboard-counter-icon fal fa-credit-card-alt"></i>
                                        </div>
                                        <div class="dashboard-widget-info">
                                            <h5 class="dashboard-counter-number">{{ $paid_tranx_count }}</h5>
                                            <span class="dashboard-counter-title">Paid Transactions</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3">
                                    <div class="dashboard-widget dashboard-widget-color-2 text-center wow slideInRight"
                                    data-wow-duration="1s" data-wow-delay="1s">
                                        <div class="dashboard-icon-widget">
                                            <i class="dashboard-counter-icon fal fa-clock"></i>
                                        </div>
                                        <div class="dashboard-widget-info">
                                            <h5 class="dashboard-counter-number"> {{ $pending_tranx_count }}</h5>
                                            <span class="dashboard-counter-title">Pending Transactions</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3">
                                    <div class="dashboard-widget dashboard-widget-color-1 text-center wow slideInRight"
                                    data-wow-duration="1s" data-wow-delay="1.25s">
                                        <div class="dashboard-icon-widget">
                                            <i class="dashboard-counter-icon fal fa-clipboard-check"></i>
                                        </div>
                                        <div class="dashboard-widget-info">
                                            <h5 class="dashboard-counter-number">{{ $booked_asset_count }}</h5>
                                            <span class="dashboard-counter-title">Booked Site</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3">
                                    <div class="dashboard-widget dashboard-widget-color-2 text-center wow slideInRight"
                                    data-wow-duration="1s" data-wow-delay="1.50s">
                                        <div class="dashboard-icon-widget">
                                            <i class="dashboard-counter-icon fal fa-upload"></i>
                                        </div>
                                        <div class="dashboard-widget-info">
                                            <h5 class="dashboard-counter-number">0</h5>
                                            <span class="dashboard-counter-title">Material(s) Uploaded</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="login-form wow fadeIn" data-wow-duration="2.5s" data-wow-delay="1.75s">
                                        <div class="user-profile-sidebar-top">
                                            <h4 class="profile-name dashboard-search-title">Search Available Site</h4>
                                        </div>
                                        <div class="user-profile-sidebar-top">
                                            <p class="welcome-text">Optimize your time by utilizing our intelligient search engine designed to enhance and provide you a satisfactory search experience.

                                                Would you rather start now?</p>
                                            <div class="mt-20 mb-10">
                                                <a href="{{ url('/advertiser/individual/create/campaign') }}" class="theme-btn dashboard-search-btn"><span class="far fa-search"></span>Search</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="property-single-content wow fadeIn" data-wow-duration="2.5s" data-wow-delay="0.25s">
                                        <h4>Trending Searches</h4>
                                        <div class="property-single-info">
                                            <div class="row">
                                                <div class="col-6 col-md-6 col-lg-4">
                                                    <div class="property-single-info-item">
                                                        <i class="far fa-blackboard"></i> Billboards
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-6 col-lg-4">
                                                    <div class="property-single-info-item">
                                                        <i class="far fa-bath"></i> Digital LED
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-6 col-lg-4">
                                                    <div class="property-single-info-item">
                                                        <i class="far fa-expand-arrows"></i> Lamp Post
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-6 col-lg-4">
                                                    <div class="property-single-info-item">
                                                        <i class="far fa-home"></i> Cinemas
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-6 col-lg-4">
                                                    <div class="property-single-info-item">
                                                        <i class="far fa-building"></i> Murals
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-6 col-lg-4">
                                                    <div class="property-single-info-item">
                                                        <i class="far fa-utensils"></i> Roof Top
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-6 col-lg-4">
                                                    <div class="property-single-info-item">
                                                        <i class="far fa-garage"></i> Taxi
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-6 col-lg-4">
                                                    <div class="property-single-info-item">
                                                        <i class="far fa-suitcase-medical"></i> Mobile LED
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <h4>Tags</h4>
                                        <div class="searches-tags">
                                            <div class="row">
                                                <div class="col-6 col-md-3 col-lg-3">
                                                    <div class="tag-links mt-10">
                                                        <a href="#">Mobile Leds</a>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3 col-lg-3">
                                                    <div class="tag-links mt-10">
                                                        <a href="#">Roof Tops</a>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3 col-lg-3">
                                                    <div class="tag-links mt-10">
                                                        <a href="#">Murals</a>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3 col-lg-3">
                                                    <div class="tag-links mt-10">
                                                        <a href="#">Cinema</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="tags-aside wow fadeIn" data-wow-duration="2.5s" data-wow-delay="0.25s">
                                        <div class="tags-aside-paragraph">
                                            <p>Book now and make a 10% payment within 48hrs to enjoy full benefit of this platform.</p>
                                        </div>
                                        <div class="tags-aside-counter mt-20">
                                            <div class="row">
                                                <div class="col-6 col-md-6 col-lg-3">
                                                    <div class="tags-counter-number mt-10">
                                                        <h4>248</h4>
                                                        <p>days</p>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-6 col-lg-3">
                                                    <div class="tags-counter-number mt-10">
                                                        <h4>03</h4>
                                                        <p>hours</p>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-6 col-lg-3">
                                                    <div class="tags-counter-number mt-10">
                                                        <h4>43</h4>
                                                        <p>minutes</p>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-6 col-lg-3">
                                                    <div class="tags-counter-number mt-10">
                                                        <h4>51</h4>
                                                        <p>seconds</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tags-aside-btn mt-20 text-center">
                                            <a href="{{ url('/advertiser/individual/search/advanced') }}">Book Now</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if ($user->corp_id && $user->admin === 1)
                                <div class="col-md-12 mt-40">
                                    <div class="user-profile-card add-property">
                                        <h4 class="user-profile-card-title">Transaction OTP Management</h4>
                                        <div class="listing-features fl-wrap">
                                            <div class="custom-form">
                                                <label class="fs-13">Transactional OTP(One Time Password) are used to grant permission to staff of a company to make payment for a booked site on this platform as a corporate body.</label>
                                                <p>&nbsp;</p>
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
                                                <a href="{{url('/advertiser/individual/generate/otp')}}" class="theme-btn theme-btn-custom">Generate OTP<i class="fal fa-save"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="user-profile-card add-property">
                                        <h4 class="user-profile-card-title">Generated Transaction OTP</h4>
                                        @if ( count($tokens) )
                                        <table class="pending-transactions-table">
                                            <thead class="">
                                                <th class="fs-13">Token</th>
                                                <th class="fs-13">Transaction &nbsp; <a href="javascript://" title="The Transaction this token was used to authorise."><i class="fal fa-info-circle"></i></a></th>
                                                <th class="fs-13">Authorised By</th>
                                                <th class="fs-13">Used By</th>
                                                <th class="fs-13" style="width: 18.8%">Company Name</th>
                                                <th class="fs-13" style="width: 8%">Status</th>
                                                <th class="fs-13">Created Date</th>
                                            </thead>
                                            <tbody>
                                            @foreach ($tokens as $key => $token)
                                                <tr>
                                                    <td class="fs-14 sm-fs-12">{{$token->token}}</td>
                                                    <td class="fs-14 sm-fs-12"><a href="{{url('/advertiser/individual/transactions/pending')}}">{{ $token->trnx_id }}</a></td>
                                                    <td class="fs-14 sm-fs-12">{{ $token->auth_by }}</td>
                                                    <td class="fs-14 sm-fs-12">{{ $token->usedby_name }}</td>
                                                    <td class="fs-14 sm-fs-12">{{ $token->corporate_name }}</td>
                                                    <td class="fs-14 sm-fs-12">{{ ucwords($token->status) }}</td>
                                                    <td class="fs-14 sm-fs-12">{{ \Carbon\Carbon::parse($token->created_at)->format('jS F Y') }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        @else
                                            <div class="listing-features fl-wrap">
                                                <p class="fs-13">No OTP Records Found.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection