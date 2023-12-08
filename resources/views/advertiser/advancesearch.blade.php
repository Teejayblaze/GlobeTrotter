@extends('template.master')

@section('content')
<style>
    .hide {
        display: none;
    }
</style>


<div class="site-breadcrumb login-breadcrumb dashboard-breadcrumb">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-12">
                <div>
                    <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
                    <li><a href="{{url('advertiser/individual/dashboard')}}">Dashboard</a></li>
                    <li>Book a Site</li>
                    <li class="active-nav-dashboard">Search</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6 col-md-4 col-12"></div>
            <div class="col-lg-2 col-md-2 col-12">
                <div class="dashboard-breadcrumb-menu-right text-align-left breadcrumb-logout-btn-div">
                    <a class="profile-logout-btn breadcrumb-logout-btn" href="{{ url('/advertiser/individual/logout') }}"><i class="far fa-sign-out"></i>
                    Logout</a>
                </div>
            </div>
        </div>
     </div>
</div>
<section id="site-location-search">
    <div class="user-profile pt-40 pb-40 dashboard-bg-color">
        <form action="{{url('advertiser/individual/search/advanced/results')}}" method="post">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="user-profile-wrapper">
                            <div class="login-form add-campaign-div wow fadeInUp" data-wow-duration="2.5s">
                               <div class="row">
                                  <div class="col-md-3">
                                     <div>
                                        <p class="welcome-text fs-15 create-campaign-text">
                                            <strong>Search Criteria</strong>
                                        </p>
                                     </div>
                                  </div>
                                  <div class="col-md-9">
                                    <div class="form-group mb-0 d-flex justify-content-end search-check-form">
                                        <ul class="site-price-filter-options">
                                            <li>
                                                <div class="form-check">
                                                    <input class="form-check-input" name="search_criteria[1]" @if(old("search_criteria[1]")) checked @endif type="checkbox"
                                                        value="1" id="check-criteria-1" data-search_criteria="asset-owner">
                                                    <label class="form-check-label" for="check-criteria-1">
                                                        Search By Asset Owner
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check">
                                                    <input class="form-check-input" name="search_criteria[2]" @if(old("search_criteria[2]")) checked @endif type="checkbox"
                                                        value="2" id="check-criteria-2"  data-search_criteria="proximity">
                                                    <label class="form-check-label" for="check-criteria-2">
                                                        Search By Proximity
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check">
                                                    <input class="form-check-input" name="search_criteria[3]" @if(old("search_criteria[3]")) checked @endif type="checkbox"
                                                        value="3" id="check-criteria-3" data-search_criteria="state">
                                                    <label class="form-check-label" for="check-criteria-3">
                                                        Search By State
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check">
                                                    <input class="form-check-input" name="search_criteria[4]" @if(old("search_criteria[4]")) checked @endif type="checkbox"
                                                        value="4" id="check-criteria-4" data-search_criteria="location-search">
                                                    <label class="form-check-label" for="check-criteria-4">
                                                        Search By Location
                                                    </label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                     {{-- <div class="create-campaign-btn-div text-right text-align-left">
                                        <a href="create_campaign.html"><button class="theme-btn create-campaign-btn fs-14">Create Campaign <span class="create-campaign-icon fal fa-plus"></span></button></a>
                                     </div> --}}
                                  </div>
                               </div>
                            </div>
                        </div>

                        <div class="login-form search-page wow fadeIn" data-wow-duration="2.5s" data-wow-delay=".75s">
                            {{-- <div class="user-profile-sidebar-top">&nbsp;</div> --}}
                            <div class="user-profile-sidebar-top text-left borderless-bottom padding-margin-bottom-none">
                                <p class="welcome-text search-welcome-text">Site Board Type (The type of site you wish to acquire)</p>

                                <div class="search-check-form">
                                    <div class="form-check search-check">
                                        <input class="form-check-input board_types" name="site_board_type[]" type="checkbox"
                                            value="static" id="check-site_board_type_1" data-id="1" checked>
                                        <label class="form-check-label" for="check-site_board_type_1">
                                            Static
                                        </label>
                                    </div>
                                    <div class="form-check search-check">
                                        <input class="form-check-input board_types" name="site_board_type[]" type="checkbox"
                                            value="dynamic" id="check-site_board_type_2" data-id="2" checked>
                                        <label class="form-check-label" for="check-site_board_type_2">
                                            Dynamic
                                        </label>
                                    </div>
                                    <div class="form-check search-check">
                                        <input class="form-check-input board_types" name="site_board_type[]" type="checkbox"
                                            value="digital" id="check-site_board_type_3" data-id="3" checked>
                                        <label class="form-check-label" for="check-site_board_type_3">
                                            Digital
                                        </label>
                                    </div>
                                    <div class="form-check search-check">
                                        <input class="form-check-input board_types" name="site_board_type[]" type="checkbox"
                                            value="mobile" id="check-site_board_type_4" data-id="4" checked>
                                        <label class="form-check-label" for="check-site_board_type_4">
                                            Mobile
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--Static Board-->
            <div class="container show-site-location wrap-sub-search">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="user-profile-wrapper">
                            <div class="login-form wow fadeInUp" data-wow-duration="2.5s">
                                <div class="user-profile-sidebar-top borderless-bottom">
                                    <h4 class="profile-name dashboard-search-title">Site Location Search</h4>
                                </div>
                                <div class="add-property-form">
                                    @csrf
                                    @if ( \session()->has('flash_message') )
                                    <div class="alert alert-danger fs-13 p-3 mb-10 mt-20">
                                        {{ \session()->get('flash_message') }}
                                    </div>
                                    @endif
                                    <div class="row">
                                        <div class="row state hide">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label><strong>Site state of choice</strong></label>
                                                    <select data-placeholder="Your Asset State" class="form-control form-control-sm required" name="asset_location_state">
                                                        <option value="">-- Select State --</option>
                                                    </select>
                                                    <i class="far fa-location-dot"></i>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label><strong>Site LGA of choice</strong></label>
                                                    <select data-placeholder="Your Asset LGA" class="form-control form-control-sm required" name="asset_location_lga">
                                                        <option value="">-- Select LGA --</option>
                                                    </select>
                                                    <i class="far fa-location-dot"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row asset-owner hide">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label><strong>Asset Owner</strong></label>
                                                    <select data-placeholder="Asset Owner" class="form-control form-control-sm required" name="asset_owner">
                                                        <option value="">-- Select Asset Owners Name --</option>
                                                        @foreach ($operators as $operator)
                                                        <option value="{{$operator->id}}">{{$operator->corporate_name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <i class="far fa-user-secret"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row location-search hide">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label><strong>&nbsp;</strong></label>
                                                    <input type="text" data-placeholder="Seacrh Location" class="form-control form-control-sm required" name="asset_location_search" placeholder="Search for asset around anywhere such as bus stops, hotels, airport, junctions etc">
                                                    <i class="far fa-location-dot"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row proximity hide">
                                            <div class="form-group">
                                                <label><strong>Sites close to</strong></label>
                                            </div>
                                            <?php
                                                $proximity = 'Churches,Mosques,Schools,Cinemas,Stadiums,Hotels,Restruants,Markets,Airports,Hospitals,'.
                                                'Events / Recreation Centers,Filling Stations,Bus stops,Parks,Bridges / FlyOvers,Pedestrian Bridges,'.
                                                'Round Abouts,Tollgates,Intersections,Tunnels,Train Terminals';
                                                $more_proximity = ['Markets', 'Airports', 'Hospitals'];
                                                $proximity = explode(',',$proximity);
                                                sort($proximity);
                                                $colum_num = 5;
                                                $iter = 0;
                                                $len = count($proximity);
                                                $iter_ceil = ceil($len / $colum_num);
                                            ?>
                                            @for($outer = 0; $outer < $iter_ceil; $outer++) 
                                            <div class="col-6 col-md-3">
                                                @for($inner = 0; $inner < $colum_num; $iter++, $inner++)
                                                    @if ($iter < $len)
                                                        @if ( in_array($proximity[$iter], $more_proximity) )
                                                        <div class="form-check">
                                                            <input id="check-proximity{{$iter}}" type="checkbox" name="proximity[]" value="{{$proximity[$iter]}}" data-maps="{{strtolower($proximity[$iter])}}" class="form-check-input map-proxi">
                                                            <label class="form-check-label" for="check-proximity{{$iter}}">{{strtoupper($proximity[$iter])}}</label>
                                                        </div>
                                                        @else 
                                                        <div class="form-check">
                                                            <input class="form-check-input" id="check-proximity{{$iter}}" type="checkbox" name="proximity[]" value="{{$proximity[$iter]}}">
                                                            <label class="form-check-label" for="check-proximity{{$iter}}">{{strtoupper($proximity[$iter])}}</label>
                                                        </div> 
                                                        @endif
                                                    @endif
                                                @endfor
                                            </div>
                                            @endfor
                                        </div>
                                        <div class="row markets hide">
                                            <div class="col-md-12 form-check-label">Market Proximities</div>
                                            <ul class="site-orientation-list">
                                                <li class="col-2 col-md-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="market_types" type="radio" value="Super Market" id="check-market_types_1">
                                                        <label class="form-check-label" for="check-market_types_1">
                                                            Super Market
                                                        </label>
                                                    </div>
                                                </li>
                                                <li class="col-2 col-md-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="market_types" type="radio" value="Mall" id="check-market_types_2">
                                                        <label class="form-check-label" for="check-market_types_2">
                                                            Mall
                                                        </label>
                                                    </div>
                                                </li>
                                                <li class="col-2 col-md-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="market_types" type="radio" value="Glossory Stores" id="check-market_types_3">
                                                        <label class="form-check-label" for="check-market_types_3">
                                                            Glossory Stores
                                                        </label>
                                                    </div>
                                                </li>
                                                <li class="col-2 col-md-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="market_types" type="radio" value="Local Market" id="check-market_types_4">
                                                        <label class="form-check-label" for="check-market_types_4">
                                                            Local Market
                                                        </label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="row airports hide">
                                            <div class="col-md-12 form-check-label">Airports Proximities</div>
                                            <ul class="site-orientation-list">
                                                <li class="col-2 col-md-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="airport_types" type="radio" value="International Airport" id="check-airport_types_1">
                                                        <label class="form-check-label" for="check-airport_types_1">
                                                            International Airport
                                                        </label>
                                                    </div>
                                                </li>
                                                <li class="col-2 col-md-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="airport_types" type="radio" value="Local Airport" id="check-airport_types_2">
                                                        <label class="form-check-label" for="check-airport_types_2">
                                                            Local Airport
                                                        </label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="row hospitals hide">
                                            <div class="col-md-12 form-check-label">Hospitals Proximities</div>
                                            <ul class="site-orientation-list">
                                                <li class="col-2 col-md-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="hospital_types" type="radio" value="General Hospital" id="check-hospital_types_1">
                                                        <label class="form-check-label" for="check-hospital_types_1">
                                                            General Hospital
                                                        </label>
                                                    </div>
                                                </li>
                                                <li class="col-2 col-md-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="hospital_types" type="radio" value="Local Airport" id="check-hospital_types_2">
                                                        <label class="form-check-label" for="check-hospital_types_2">
                                                            Private Hospital
                                                        </label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <h4 class="profile-name dashboard-search-title mb-30 mt-10">Site Descriptive Search
                                        </h4>
                                        <div class="form-group">
                                            <label><strong>Site Category (The category of site you wish to acquire)</strong></label>
                                            <div id="asset_types_cat" class="row"></div>
                                            <script>
                                                var asset_types_arry = <?php echo $asset_types ?>
                                            </script>
                                        </div>
                                        <div class="form-group dynamic static hide">
                                            <label><strong>Site Substrate (The substrate of site)</strong></label>
                                            <ul class="site-orientation-list">
                                                <li>
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="substrate" value="SAV" type="radio" id="check-substrate1">
                                                        <label class="form-check-label" for="check-substrate1">
                                                            SAV
                                                        </label>
                                                    </div>
                                                </li>
                                                <li class="dynamic hide">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="substrate" value="PVC Strips" type="radio" id="check-substrate2">
                                                        <label class="form-check-label" for="check-substrate2">
                                                            PVC Strips
                                                        </label>
                                                    </div>
                                                </li>
                                                <li class="dynamic hide">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="substrate" value="CANVAS" type="radio" id="check-substrate3">
                                                        <label class="form-check-label" for="check-substrate3">
                                                            CANVAS
                                                        </label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="substrate" value="MESH" type="radio" id="check-substrate4">
                                                        <label class="form-check-label" for="check-substrate4">
                                                            MESH
                                                        </label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="substrate" value="FLEX" type="radio" id="check-substrate5">
                                                        <label class="form-check-label" for="check-substrate5">
                                                            FLEX
                                                        </label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Site Orientation</strong></label>
                                            <ul class="site-orientation-list">
                                                <li>
                                                    <div class="form-check">
                                                    <input class="form-check-input" name="orientation" type="radio"
                                                        value="Landscape" id="check-orientation_1">
                                                    <label class="form-check-label" for="check-orientation_1">
                                                        LANDSCAPE
                                                    </label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-check">
                                                    <input class="form-check-input" name="orientation" type="radio"
                                                        value="Portrait" id="check-orientation_2">
                                                    <label class="form-check-label" for="check-orientation_2">
                                                        PORTRAIT
                                                    </label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Site Faces</strong></label>
                                            <ul class="site-faces-options">
                                                <li>
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="face_count" type="radio" value="1" id="check-face_count1">
                                                        <label class="form-check-label" for="check-face_count1">1</label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="face_count" type="radio" value="2" id="check-face_count2">
                                                        <label class="form-check-label" for="check-face_count2">2</label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="face_count" type="radio" value="3" id="check-face_count3">
                                                        <label class="form-check-label" for="check-face_count3">3</label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="face_count" type="radio" value="4" id="check-face_count2">
                                                        <label class="form-check-label" for="check-face_count2">4</label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Asset Dimension (Width × Height)</strong></label>
                                            <!-- <ul class="site-measurement-options">
                                                <li>
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="dimension" type="radio"
                                                            value="1.02 &times; 1.52" id="check-aaa5">
                                                        <label class="form-check-label" for="check-aaa5">
                                                            1.02 &times; 1.52 m
                                                        </label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-check">
                                                    <input class="form-check-input" name="dimension" type="radio"
                                                        value="1.20 &times; 1.80" id="check-bb5">
                                                    <label class="form-check-label" for="check-bb5">
                                                        1.20 &times; 1.80 m
                                                    </label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-check">
                                                    <input class="form-check-input" name="dimension" type="radio"
                                                        value="3.05 &times; 1.52" id="check-dd5">
                                                    <label class="form-check-label" for="check-dd5">
                                                        3.05 &times; 1.52 m
                                                    </label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-check">
                                                    <input class="form-check-input" name="dimension" type="radio"
                                                        value="2.03 &times; 3.05" id="check-cc5">
                                                    <label class="form-check-label" for="check-cc5">
                                                        2.03 &times; 3.05 m
                                                    </label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-check">
                                                    <input class="form-check-input" name="dimension" type="radio"
                                                        value="4.06 &times; 3.05" id="check-ff5">
                                                    <label class="form-check-label" for="check-ff5">
                                                        4.06 &times; 3.05 m
                                                    </label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-check">
                                                    <input class="form-check-input" name="dimension" type="radio"
                                                        value="6.10 &times; 3.05" id="check-c4">
                                                    <label class="form-check-label" for="check-c4">
                                                        6.10 &times; 3.05 m
                                                    </label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-check">
                                                    <input class="form-check-input" name="dimension" type="radio"
                                                        value="8.13 &times; 3.05" id="check-d4">
                                                    <label class="form-check-label" for="check-d4">
                                                        8.13 &times; 3.05 m
                                                    </label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-check">
                                                    <input class="form-check-input" name="dimension" type="radio"
                                                        value="12.19 &times; 3.05" id="check-e4">
                                                    <label class="form-check-label" for="check-e4">
                                                        12.19 &times; 3.05 m
                                                    </label>
                                                    </div>
                                                </li>
                                            </ul> -->
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <div class="form-group">
                                                        <label class="fs-13">Width</label>
                                                        <input type="range" class="asset_dimension"  name="asset_dimension_width" data-postfix="m" data-prefix=" " value="">
                                                    </div>
                                                </div>
                                                <div class="col-sm-2" style="display: flex;justify-content: center;align-items: center;">
                                                    <div class="form-group" style="display: flex;flex-flow: column;justify-content: center;align-items: center;">
                                                        <label class="fs-13"><h4>×</h4></label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-5">
                                                    <div class="form-group">
                                                        <label class="fs-13">Height</label>
                                                        <input type="range" class="asset_dimension"  name="asset_dimension_height" data-postfix="m" data-prefix=" " value="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <h4 class="profile-name dashboard-search-title mb-30 mt-10">Site Price Search</h4>
                                        <div class="form-group">
                                            <label><strong>Site price from</strong></label>
                                            <ul class="site-price-filter-options">
                                                <li>
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="price_range" type="radio"
                                                            value="max" id="check-price1">
                                                        <label class="form-check-label" for="check-price1">
                                                            HIGHEST TO LOWEST
                                                        </label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-check">
                                                    <input class="form-check-input" name="price_range" type="radio"
                                                        value="min" id="check-price2">
                                                    <label class="form-check-label" for="check-price2">
                                                        LOWEST TO HIGHEST
                                                    </label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Payment Frequency</strong></label>
                                            <ul class="payment-frequency-options">
                                                <li>
                                                    <div class="form-check">
                                                    <input class="form-check-input" name="payment_freq" type="radio"
                                                        value="Annually" id="check-payfreq1">
                                                    <label class="form-check-label" for="check-payfreq1">
                                                        ANNUALLY
                                                    </label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-check">
                                                    <input class="form-check-input" name="payment_freq" type="radio"
                                                        value="Quarterly" id="check-payfreq2">
                                                    <label class="form-check-label" for="check-payfreq2">
                                                        QUARTERLY
                                                    </label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-check">
                                                    <input class="form-check-input" name="payment_freq" type="radio"
                                                        value="Monthly" id="check-payfreq3">
                                                    <label class="form-check-label" for="check-payfreq3">
                                                        MONTHLY
                                                    </label>
                                                    </div>
                                                </li>
                                                <li class="digital hide">
                                                    <div class="form-check">
                                                    <input class="form-check-input" name="payment_freq" type="radio"
                                                        value="Weekly" id="check-payfreq4">
                                                    <label class="form-check-label" for="check-payfreq4">
                                                        WEEKLY
                                                    </label>
                                                    </div>
                                                </li>
                                                <li class="digital hide">
                                                    <div class="form-check">
                                                    <input class="form-check-input" name="payment_freq" type="radio"
                                                        value="Daily" id="check-payfreq5">
                                                    <label class="form-check-label" for="check-payfreq5">
                                                        DAILY
                                                    </label>
                                                    </div>
                                                </li>
                                                <li class="digital hide">
                                                    <div class="form-check">
                                                    <input class="form-check-input" name="payment_freq" type="radio"
                                                        value="Hourly" id="check-payfreq6">
                                                    <label class="form-check-label" for="check-payfreq6">
                                                        HOURLY
                                                    </label>
                                                    </div>
                                                </li>
                                                <li class="digital hide">
                                                    <div class="form-check">
                                                    <input class="form-check-input" name="payment_freq" type="radio"
                                                        value="Minute" id="check-payfreq7">
                                                    <label class="form-check-label" for="check-payfreq7">
                                                        MINUTE
                                                    </label>
                                                    </div>
                                                </li>
                                                <li class="digital hide">
                                                    <div class="form-check">
                                                    <input class="form-check-input" name="payment_freq" type="radio"
                                                        value="Second" id="check-payfreq8">
                                                    <label class="form-check-label" for="check-payfreq8">
                                                        SECOND
                                                    </label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <h4 class="profile-name dashboard-search-title mb-30 mt-10">Keyword Search</h4>
                                        <div class="col-6 col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" name="keywords[]" type="checkbox" value="keyword:affordable" id="check-keywords1">
                                                <label class="form-check-label" for="check-keywords1">
                                                    Site with most affordable prices
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" name="keywords[]" type="checkbox" value="keyword:strategic-location" id="check-keywords2">
                                                <label class="form-check-label" for="check-keywords2">
                                                    Frequently booked sites
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" name="keywords[]" type="checkbox" value="keyword:frequently-viewed" id="check-keywords3">
                                                <label class="form-check-label" for="check-keywords3">
                                                    Site situated at strategic location
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" name="keywords[]" type="checkbox" value="keyword:frequently-booked" id="check-keywords4">
                                                <label class="form-check-label" for="check-keywords4">
                                                    Site with high traffic
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" name="keywords[]" type="checkbox" value="keyword:high-traffic" id="check-keywords5">
                                                <label class="form-check-label" for="check-keywords5">
                                                    Frequently viewed sites
                                                </label>
                                            </div>
                                        </div>
                                        <div class="mt-20">
                                            <button type="submit" class="theme-btn dashboard-search-btn"><span class="far fa-search"></span>Search</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
    
@endsection