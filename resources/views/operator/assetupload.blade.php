@extends('template.master')

@section('content')

<style>
    .listing-item {
        padding: 0;
        height: 149px;
        overflow: hidden;
        cursor: pointer;
    }

    .listing-badge {
        position: absolute;
        left: 13px;
        right: auto;
        top: 15px;
        background: #111f39;
        padding: 2px 8px;
        color: #fff;
        border-radius: 8px 8px 8px 8px;
        box-shadow: 0 3px 24px rgb(0 0 0 / 10%);
        z-index: 1;
    }

    .listing-item-cat {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        color: #fff;
        background: linear-gradient(to bottom, rgba(6,27,65,0) 0%,rgba(6,27,65,0.95) 100%);
        border-radius: 0 0 10px 10px;
        padding: 13px;
    }

    .listing-item-cat a {
        color: #fff;
    }

    .fw-bold {
        margin-bottom: 6px;
    }

    #progressbar li {
        color: #333;
        width: calc(100%/6);
        text-align: center;
    }

    #progressbar li:hover, #progressbar li:focus {
        border-top: none;
        border-left: none;
        border-right: none;
    }

    #progressbar li.active {
        border-top: none;
        border-left: none;
        border-right: none;
        border-bottom: 1px solid #112039;
    }

    .hide, .hide-others {
        display: none !important;
    }

    .text-cyan {
        color: #d6bf30;
    }

    .line-height {
        position: absolute;
        top: 40px;
    }

    .success-table-header {
        text-align: left;
        color: #666;
        font-size: 16px;
        font-weight: 400;
        padding: 10px 0 0 70px;
    }

    .success-table-header i.decsth {
        color: #5ECFB1;
        position: absolute;
        font-size: 54px;
        left: 0;
        top: 0;
        line-height: 73px;
    }

    .fuzone {
        position: relative;
        border: 1px solid #eee;
        border-radius: 3px;
        background: #F7F9FB;
        transition: all 0.3s linear;
        margin-bottom: 10px;
        display: inline-block;
        width: 100%;
        height: 100%;
        min-height: 160px;
        margin-top: 0px;
        float: left;
        cursor: pointer;
    }

    .fuzone .fu-text {
        position: absolute;
        bottom: 0;
        background: rgba(0,0,0,.3);
        padding: 11px;
        left: 0;
        margin: 0;
        right: 0;
        color: #fff;
        text-align: left;
        z-index: 999999;
        font-weight: 400;
        font-size: 11px;
    }

    .listing-counter {
        position: absolute;
        left: 15px;
        top: 10px;
        color: #fff;
        z-index: 10;
        font-size: 11px;
        border-radius: 4px;
        background: #330065;
        color: #fff;
        padding: 9px 12px;
        box-shadow: 0px 0px 0px 5px rgba(255,255,255,0.2);
    }

    .fuzone input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        z-index: 100;
        cursor: pointer;
        margin-bottom: 20px;
    }

    #video-player {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        bottom: 0;
        height: 100%;
    }
</style>

<div class="site-breadcrumb login-breadcrumb dashboard-breadcrumb">
    <div class="container">
        <h2 class="breadcrumb-title dashboard-breadcrumb-menu-left">Upload Asset</h2>
        <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
         <li><a href="dashboard.html">Dashboard</a></li>
         <li class="active-nav-dashboard">Upload Asset</li>
        </ul>
    </div>
</div>
<section id="advertiser-dashboard">
    <div class="user-profile pt-40 pb-40 dashboard-bg-color">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div id="asset-upload-container" class="user-profile-wrapper wow slideInRight" data-wow-duration="2.5s" data-wow-delay=".25s">
                        <div class="user-profile-card add-property">
                            <h4 class="user-profile-card-title">Upload Asset</h4>
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div data-title="Upload Static Asset" data-name="static" data-id="1" class="listing-item wow fadeIn" data-wow-duration="2.5s" data-wow-delay=".25s">
                                            <span class="listing-badge fs-14">Static Board</span>
                                            <div class="listing-img">
                                               <img src="{{asset("/img/assets/operator.jpg")}}" alt="Static Board">
                                            </div>
                                            <div class="listing-item-cat">
                                                <h3 class="fw-bold">
                                                    <a href="javascript://" class="fs-13">Static Board</a>
                                                </h3>
                                                <p class="fs-13" style="line-height: 17px;">Upload sites with parameters that are associated with static board.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div data-title="Upload Dynamic Asset" data-name="dynamic" data-id="2" class="listing-item wow fadeIn" data-wow-duration="2.5s" data-wow-delay=".25s">
                                            <span class="listing-badge fs-14">Dynamic Board</span>
                                            <div class="listing-img">
                                               <img src="{{asset("/img/assets/outdoor-trivision-billboard.jpg")}}" alt="Dynamic Board">
                                            </div>
                                            <div class="listing-item-cat">
                                                <h3 class="fw-bold">
                                                    <a href="javascript://" class="fs-13">Dynamic Board</a>
                                                </h3>
                                                <p class="fs-13" style="line-height: 17px;">The surface of a Tri-Vision Billboard is divided into vertical strips.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div data-title="Upload Digital Asset" data-name="digital" data-id="3" class="listing-item wow fadeIn" data-wow-duration="2.5s" data-wow-delay=".25s">
                                            <span class="listing-badge fs-14">Digital Board</span>
                                            <div class="listing-img">
                                               <img src="{{asset("/img/assets/digital.jpg")}}" alt="Digital Board">
                                            </div>
                                            <div class="listing-item-cat">
                                                <h3 class="fw-bold">
                                                    <a href="javascript://" class="fs-13">Digital Board</a>
                                                </h3>
                                                <p class="fs-13" style="line-height: 17px;">Upload sites with parameters that are associated with digital asset.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div data-title="Upload Mobile Asset" data-name="mobile" data-id="4" class="listing-item wow fadeIn" data-wow-duration="2.5s" data-wow-delay=".25s">
                                            <span class="listing-badge fs-14">Mobile Board</span>
                                            <div class="listing-img">
                                               <img src="{{asset("/img/assets/maxresdefault.jpg")}}" alt="Mobile Board">
                                            </div>
                                            <div class="listing-item-cat">
                                                <h3 class="fw-bold">
                                                    <a href="javascript://" class="fs-13">Mobile Board</a>
                                                </h3>
                                                <p class="fs-13" style="line-height: 17px;">Upload sites with parameters that are associated with mobile asset.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="user-profile-wrapper wow fadeIn" data-wow-duration="2.5s" data-wow-delay=".25s">
                        <div class="user-profile-card add-property">
                            <h4 class="user-profile-card-title upload-title">Upload Asset</h4>
                            <div class="user-profile-wrapper wow hide wrapper" data-wow-duration="2.5s" data-wow-delay=".25s">
                                <div id="showboard" class="asset-upload-container booking-form-wrap">
                                    <ul id="progressbar" class="nav nav-tabs" role="tablist">
                                        <li class="nav-link active fs-13" role="presentation">Location Information</li>
                                        <li class="nav-link fs-13" role="presentation">Description Information</li>
                                        <li class="nav-link fs-13" role="presentation">Price Information</li>
                                        <li class="nav-link fs-13" role="presentation">Asset  Photo Upload</li>
                                        <li class="nav-link fs-13" role="presentation">Asset  Video Upload</li>
                                        <li class="nav-link fs-13" role="presentation">Confirm</li>
                                    </ul>

                                    <form name="asset_form" method="POST" class="">
                                        <div class="tab-content mt-30 login-form login-form-two" id="nav-tabContent">
                                            <div class="tab-pane fade show active" id="nav-location-pane" role="tabpanel" aria-labelledby="nav-location-tab" tabindex="0">
                                                <div class="alert alert-danger hide mt-20">
                                                    <ul></ul>
                                                </div>
                                                <div class="row mb-20">
                                                    <div class="col-sm-3">
                                                        <p class="fs-14">Asset Code</p>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <label class="fs-14">{{$asset_name}}</label>
                                                        <input type="hidden" name="asset_name" value="{{$asset_name}}" class="required">
                                                    </div>
                                                </div>
                                                <div class="row mb-20 add-property-form">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="fs-13" for="asset_location_state">State (Where Asset is Located) </label>
                                                            <select data-placeholder="Your Asset State" class="form-control form-control-sm required" name="asset_location_state" id="asset_location_state">
                                                                <option value="">-- Select STATE --</option>
                                                            </select> 
                                                            <i class="far fa-location-dot"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="fs-13" for="asset_location_lga">LGA (Where Asset is Located) </label>
                                                            <select data-placeholder="Your Asset LGA" class="form-control form-control-sm required" name="asset_location_lga" id="asset_location_lga">
                                                                <option value="">-- Select LGA --</option>
                                                            </select> 
                                                            <i class="far fa-location-dot"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="fs-13" for="asset_location_lcda">LCDA (Where Asset is Located)</label>
                                                            <select data-placeholder="Your Asset LCDA" class="form-control form-control-sm required" name="asset_location_lcda" id="asset_location_lcda">
                                                                <option value="">-- Select LCDA --</option>
                                                            </select>  
                                                            <i class="far fa-location-dot"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-20">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label class="fs-13" for="asset_location_address">Address</label>
                                                            <input type="text" placeholder="Address of your Asset" class="required form-control" name="asset_location_address" id="asset_location_address">
                                                            <i class="fal fa-map-marker"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="fs-13" for="long">Longitude (Drag marker on the map)</label>
                                                            <input type="text" placeholder="Map Longitude" id="long" class="required form-control" name="asset_location_longitude">
                                                            <i class="fal fa-long-arrow-alt-right"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="fs-13" for="lat">Latitude (Drag marker on the map) <i class="fal fa-long-arrow-alt-down"></i> </label>
                                                            <input type="text" placeholder="Map Latitude" id="lat" class="required form-control" name="asset_location_latitude">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row check-proximity" style="margin-bottom: 0;">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label class="fs-13">
                                                                <strong>Which of the following landmark is close to your asset?</strong>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <?php
                                                        $proximity = 'Churches,Mosques,Schools,Cinemas,Stadiums,Hotels,Restruants,Markets,Airports,Hospitals,'.
                                                        'Events / Recreation Centers,Filling Stations,Bus stops,Parks,Bridges / FlyOvers,Pedestrian Bridges,'.
                                                        'Round Abouts,Tollgates,Intersections,Tunnels,Train Terminals';
                                                        $more_proximity = ['Markets', 'Airports', 'Hospitals'];
                                                        $proximity = explode(',',$proximity);
                                                        sort($proximity);
                                                        $colum_num = 7;
                                                        $iter = 0;
                                                        $len = count($proximity);
                                                        $iter_ceil = ceil($len / $colum_num);
                                                    ?>
                                                    <!-- Checkboxes -->

                                                    @for($outer = 0; $outer < $iter_ceil; $outer++) 
                                                    <div class="col-6 col-sm-3">
                                                        @for($inner = 0; $inner < $colum_num; $iter++, $inner++)
                                                            @if ($iter < $len)
                                                                @if ( in_array($proximity[$iter], $more_proximity) )
                                                                    <li>                                       
                                                                        <input id="check-proximity{{$iter}}" type="checkbox" name="proximity[]" value="{{$proximity[$iter]}}" data-maps="{{strtolower($proximity[$iter])}}" class="form-check-input map-proxi">
                                                                        <label class="form-check-label fs-13" for="check-proximity{{$iter}}">{{$proximity[$iter]}}</label>
                                                                    </li>
                                                                @else 
                                                                    <li>
                                                                        <input id="check-proximity{{$iter}}" type="checkbox" name="proximity[]" value="{{$proximity[$iter]}}" class="form-check-input">
                                                                        <label class="form-check-label fs-13" for="check-proximity{{$iter}}">{{$proximity[$iter]}}</label>
                                                                    </li> 
                                                                @endif
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    @endfor
                                                    <!-- div for selecting the type of checkboxes -->
                                                    <div class="row mt-20">
                                                        <div class="col-sm-4 hide-more-proximity hide" id="markets">
                                                            <div class="form-group">
                                                                <label class="fs-13" for="markets">Please select the type of <span class="text-cyan">Market</span> close to the asset.</label>
                                                                <select data-placeholder="Market types" class="form-control form-control-sm" name="markets" data-proximity="proximity" id="markets">
                                                                    <option value="">-- Select Market Type --</option>
                                                                    <option value="Super Market">Super Market</option>
                                                                    <option value="Mall">Mall</option>
                                                                    <option value="Glossory Stores">Glossory Stores</option>
                                                                    <option value="Local Market">Local Market</option>
                                                                </select>
                                                                <i class="far fa-location-dot"></i>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 hide-more-proximity hide" id="hospitals">
                                                            <div class="form-group">
                                                                <label class="fs-13" for="hospitals">Please select the type of <span class="text-cyan">Hospitals</span> close to the asset.</label>
                                                                <select data-placeholder="Hospitals" class="form-control form-control-sm" name="hospitals" data-proximity="proximity" id="hospitals">
                                                                    <option value="">-- Select Hospitals Type --</option>
                                                                    <option value="General Hospital">General Hospital</option>
                                                                    <option value="Private Hospital">Private Hospital</option>
                                                                </select> 
                                                                <i class="far fa-location-dot"></i>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 hide-more-proximity hide" id="airports">
                                                            <div class="form-group">
                                                                <label class="fs-13" for="airports">Please select the type of <span class="text-cyan">Airport</span> close to the asset.</label>
                                                                <select data-placeholder="Airport types" class="form-control form-control-sm" name="airports" data-proximity="proximity" id="airports">
                                                                    <option value="">-- Select Airport Type --</option>
                                                                    <option value="International Airport">International Airport</option>
                                                                    <option value="Local Airport">Local Airport</option>
                                                                </select>
                                                                <i class="far fa-location-dot"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row event_center_row"></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-8">&nbsp;</div>
                                                    <div class="col-sm-2">&nbsp;</div>
                                                    <div class="col-sm-2 d-flex justify-content-end">
                                                        <a href="#" class="next-form theme-btn">Proceed <i class="far fa-angle-right"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="nav-desc-pane" role="tabpanel" aria-labelledby="nav-desc-tab" tabindex="0">
                                                <div class="alert alert-danger hide mt-20">
                                                    <ul></ul>
                                                </div>

                                                {{-- Begin Static Board --}}
                                                <div>
                                                    <div class="row">
                                                        <div class="col-sm-4 mb-20">
                                                            <div class="form-group">
                                                                <label class="fs-13" for="asset_type">Asset Type</label>
                                                                <select data-placeholder="Type of Asset" class="form-control form-control-sm required" name="asset_type" id="asset_type">
                                                                    <option value="">-- Select --</option>
                                                                    @foreach ($asset_types as $asset_type)
                                                                        <option value="{{$asset_type->id}}">{{$asset_type->type}}</option>
                                                                    @endforeach
                                                                </select>
                                                                {{-- <i class="far fa-album-collection-circle-user"></i> --}}
                                                                <i class="fal fa-file-text"></i>
                                                            </div>
                                                            <script> var asst_types = {!! $asset_types !!} </script>
                                                        </div>
                                                        <div class="col-sm-6">&nbsp;</div>
                                                    </div>

                                                    <div class="mobile hide-all hide">
                                                        <div class="row mb-20">
                                                            <div class="col-sm-12">
                                                                <div class="col-list-search-input-item fl-wrap">
                                                                    <label class="fs-13">What is the advert type on this <span class="text-cyan">board</span>
                                                                    </label>
                                                                        <!-- Checkboxes -->
                                                                    <ul class="site-orientation-list filter-tags">
                                                                        <li>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input required" id="check-advert_type_1" type="radio" name="advert_type" value="static">
                                                                                <label class="fs-13" for="check-advert_type_1">Static</label>
                                                                            </div>
                                                                        </li>
                                                                        <li>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input required" id="check-advert_type_2" type="radio" name="advert_type" value="digital">
                                                                                <label class="fs-13" for="check-advert_type_2">Digital</label>
                                                                            </div>
                                                                        </li>
                                                                    </ul>
                                                                    <!-- Checkboxes end -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="digital hide-all hide">
                                                        <div class="row mb-20">   
                                                            <div class="col-sm-12">
                                                                <label class="fs-13">How many slots does this asset have?</label>
                                                                <!-- Checkboxes -->
                                                                <ul class="site-orientation-list filter-tags">
                                                                    <li>
                                                                        <div class="form-check">
                                                                            <input id="check-digislide1" class="form-check-input" type="radio" name="number_of_slots" value="5">
                                                                            <label class="fs-13" for="check-digislide1">5</label>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" id="check-digislide2" type="radio" name="number_of_slots" value="others" data-others="others">
                                                                            <label class="fs-13" for="check-digislide2">Others</label>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                                <!-- Checkboxes end -->
                                                            </div>
                                                            <div class="col-sm-4 hide-others">
                                                                <div class="form-group">
                                                                    <label class="fs-13">Number of slots for this Asset </label>
                                                                    <input type="number" class="form-control form-control-sm" id="number_of_slots_others" min="1" max="12" step="1" value="1" maxlength="2" name="number_of_slots_others" style="width: 100px;">
                                                                    <i class="fal fa-balance-scale"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">   
                                                            <div class="col-sm-12 mb-20">
                                                                <label class="fs-13">How long (in seconds) does a slot stay before proceeding to the next slot?</label>
                                                                <!-- Checkboxes -->
                                                                <ul class="site-orientation-list filter-tags">
                                                                    <li>
                                                                        <div class="form-check">
                                                                            <input id="check-diginum_of_sec_per_slide1" type="radio" name="number_of_seconds_per_slot" value="10" class="form-check-input">
                                                                            <label class="fs-13" for="check-diginum_of_sec_per_slide1">10 seconds</label>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="form-check">
                                                                            <input id="check-diginum_of_sec_per_slide2" type="radio" name="number_of_seconds_per_slot" value="others" data-others="others" class="form-check-input">
                                                                            <label class="fs-13" for="check-diginum_of_sec_per_slide2">Others</label>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                                <!-- Checkboxes end -->
                                                            </div>
                                                            <div class="col-sm-2 hide-others">
                                                                <div class="form-group">
                                                                    <label class="fs-13" for="number_of_seconds_per_slot_others">Seconds per slot <i class="fal fa-clock" style="top: 40px;"></i></label>
                                                                    <input class="form-control form-control-sm" id="number_of_seconds_per_slot_others" type="text" name="number_of_seconds_per_slot_others" placeholder="8" maxlength="2" style="width: 100px;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-20">
                                                        <div class="col-sm-12">
                                                            <div class="col-list-search-input-item fl-wrap">
                                                                <label class="fs-13">Asset Dimension (Width &times; Height)</label>
                                                                <div class="row">
                                                                    <div class="col-5 col-sm-2">
                                                                        <div class="form-group">
                                                                            <label class="fs-13" for="asset_dimension_width">Width (meters)</label>
                                                                            <input id="asset_dimension_width" type="text" name="asset_dimension_width" class="form-control form-control-sm required" placeholder="1.02" maxlength="4" style="width: 100px;">
                                                                            <i class="fal fa-text-width"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-2 col-sm-1" style="position: relative">
                                                                        <span class="line-height">&times;</span>
                                                                    </div>
                                                                    <div class="col-5 col-sm-2">
                                                                        <div class="form-group">
                                                                            <label class="fs-13" for="asset_dimension_height">Height (meters)</label>
                                                                            <input id="asset_dimension_height" type="text" name="asset_dimension_height" class="form-control form-control-sm required" placeholder="1.52" maxlength="4" style="width: 100px;">
                                                                            <i class="fal fa-text-height"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-20">
                                                        <div class="col-sm-12">
                                                            <div class="col-list-search-input-item fl-wrap">
                                                                <label class="fs-13">Asset Orientation</label>
                                                                <!-- Checkboxes -->
                                                                <ul class="site-orientation-list filter-tags half-tags">
                                                                    <li>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input required" id="check-aaao5" type="radio" name="orientation" value="Landscape">
                                                                            <label for="check-aaao5" class="fs-13">Landscape</label>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input required" id="check-bbo5" type="radio" name="orientation" value="Portrait">
                                                                            <label for="check-bbo5" class="fs-13">Portrait</label>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input required" id="check-ddo5" type="radio" name="orientation" value="others" data-others="others">
                                                                            <label for="check-ddo5">Others</label>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                                <!-- Checkboxes end -->
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2 hide-others">
                                                            <div class="form-group">
                                                                <label class="fs-13" for="asset_orientation_others">Others (Please specify)</label>
                                                                <input class="form-control form-control-sm" id="asset_orientation_others" type="text" name="asset_orientation_others" placeholder="Orientation">
                                                                <i class="fal fa-object-ungroup"></i>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-20">
                                                        <div class="col-sm-12">
                                                            <label class="fs-13">Face Count</label>
                                                            <!-- Checkboxes -->
                                                            <ul class="site-orientation-list filter-tags">
                                                                <li>
                                                                    <div class="form-chek">
                                                                        <input class="form-check-input" id="check-aaaaf3" type="radio" name="face_count" value="1">
                                                                        <label class="fs-13" for="check-aaaaf3">1</label>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="form-chek">
                                                                        <input class="form-check-input" id="check-bbb0f3" type="radio" name="face_count" value="2">
                                                                        <label class="fs-13" for="check-bbb0f3">2</label>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="form-chek">
                                                                        <input class="form-check-input" id="check-ddd0f5" type="radio" name="face_count" value="3">
                                                                        <label class="fs-13" for="check-ddd0f5">3</label>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="form-chek">
                                                                        <input class="form-check-input" id="check-ddd0f45" type="radio" name="face_count" value="4">
                                                                        <label class="fs-13" for="check-ddd0f45">4</label>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                            <!-- Checkboxes end -->
                                                        </div>
                                                    </div>

                                                    <div class="static dynamic hide-all hide">
                                                        <div class="row mb-20">
                                                            <div class="col-sm-12">
                                                                <div class="col-list-search-input-item fl-wrap">
                                                                    <label class="fs-13">Asset Substrate</label>
                                                                    <!-- Checkboxes -->
                                                                    <ul class="site-orientation-list filter-tags">
                                                                        <li>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" id="check-aaaa3" type="radio" name="substrate" value="SAV">
                                                                                <label for="check-aaaa3" class="fs-13">SAV</label>
                                                                            </div>
                                                                        </li>
                                                                        <li class="dynamic hide">
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" id="check-substrate_002" type="radio" name="substrate" value="PVC Strips">
                                                                                <label for="check-substrate_002" class="fs-13">PVC Strips</label>
                                                                            </div>
                                                                        </li>
                                                                        <li class="dynamic hide">
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" id="check-substrate_003" type="radio" name="substrate" value="Canvas">
                                                                                <label for="check-substrate_003" class="fs-13">Canvas</label>
                                                                            </div>
                                                                        </li>
                                                                        <li>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" id="check-bbb03" type="radio" name="substrate" value="Mesh">
                                                                                <label for="check-bbb03" class="fs-13">Mesh</label>
                                                                            </div>
                                                                        </li>
                                                                        <li>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" id="check-ddd05" type="radio" name="substrate" value="Flex">
                                                                                <label for="check-ddd05" class="fs-13">Flex</label>
                                                                            </div>
                                                                        </li>
                                                                    </ul>
                                                                    <!-- Checkboxes end -->
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-20">   
                                                            <div class="col-sm-12">
                                                                <label class="fs-13">The file format accepted by this <span class="board-plh text-cyan"></span> board.</label>
                                                                <!-- Checkboxes -->
                                                                <ul class="site-orientation-list filter-tags">
                                                                    <li>
                                                                        <div class="form-check">
                                                                            <input id="check-file_format01" type="radio" name="file_format" value="JPG" class="form-check-input">
                                                                            <label class="fs-13" for="check-file_format01">JPG</label>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" id="check-file_format02" type="radio" name="file_format" value="PDF">
                                                                            <label class="fs-13" for="check-file_format02">PDF</label>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" id="check-file_format04" type="radio" name="file_format" value="CDR">
                                                                            <label class="fs-13" for="check-file_format04">CDR</label>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                                <!-- Checkboxes end -->
                                                            </div>
                                                        </div>

                                                        <div class="row mb-20">   
                                                            <div class="col-sm-12">
                                                                <label class="fs-13">Print Dimension</label>
                                                                <!-- Checkboxes -->
                                                                <ul class="site-orientation-list filter-tags">
                                                                    <li>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" id="check-print_dimension04" type="radio" name="print_dimension" value="Board Size">
                                                                            <label class="fs-13" for="check-print_dimension04">Board Size</label>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" id="check-print_dimension03" type="radio" name="print_dimension" value="Image Area">
                                                                            <label class="fs-13" for="check-print_dimension03">Image Area</label>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"  id="check-print_dimension02" type="radio" name="print_dimension" value="Pocket Size">
                                                                            <label class="fs-13" for="check-print_dimension02">Pocket Size</label>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"  id="check-print_dimension01" type="radio" name="print_dimension" value="Bleed">
                                                                            <label class="fs-13"  for="check-print_dimension01">Bleed</label>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                                <!-- Checkboxes end -->
                                                            </div>
                                                        </div>

                                                        <div class="row">   
                                                            <div class="col-sm-12">
                                                                <div class="col-list-search-input-item fl-wrap">
                                                                    <label class="fs-13">Pixels Resolution (Width &times; Height)</label>
                                                                    <div class="row">
                                                                        <div class="col-5 col-sm-2">
                                                                            <div class="form-group">
                                                                                <label class="fs-13" for="pixel_resolution_width">Width (meters)</label>
                                                                                <input id="pixel_resolution_width" class="form-control form-control-sm" type="text" name="pixel_resolution_width" placeholder="1.02" maxlength="4" style="width: 100px;">
                                                                                <i class="fal fa-text-width"></i>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-2 col-sm-1" style="position: relative">
                                                                            <span class="line-height">&times;</span>
                                                                        </div>
                                                                        <div class="col-5 col-sm-2">
                                                                            <div class="form-group">
                                                                                <label class="fs-13" for="pixel_resolution_height">Height (meters) </label>
                                                                                <input id="pixel_resolution_height" class="form-control form-control-sm" type="text" name="pixel_resolution_height" placeholder="1.52" maxlength="4" style="width: 100px;">
                                                                                <i class="fal fa-text-height"></i>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="digital hide-all hide">
                                                        <div class="row mb-20">   
                                                            <div class="col-sm-12">
                                                                <label class="fs-13">The file format accepted by this <span class="board-plh text-cyan"></span> board.</label>
                                                                <div class="search-opt-container fl-wrap">
                                                                    <!-- Checkboxes -->
                                                                    <ul class="site-orientation-list filter-tags half-tags">
                                                                        <li>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" id="check-digit01" type="radio" name="file_format" value="MP4">
                                                                                <label class="fs-13" for="check-digit01">MP4 (Moving Pictures Expert Group 4)</label>
                                                                            </div>
                                                                        </li>
                                                                        <li>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" id="check-digit02" type="radio" name="file_format" value="AVI">
                                                                                <label class="fs-13" for="check-digit02">AVI (Audio Video Interleave)</label>
                                                                            </div>
                                                                        </li>
                                                                        <li>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" id="check-digit03" type="radio" name="file_format" value="FLV">
                                                                                <label class="fs-13" for="check-digit03">FLV (Flash Video Format)</label>
                                                                            </div>
                                                                        </li>
                                                                        <li>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" id="check-digit10" type="radio" name="file_format" value="others" data-others="others">
                                                                                <label class="fs-13" for="check-digit10">Others</label>
                                                                            </div>
                                                                        </li>
                                                                    </ul>
                                                                    <!-- Checkboxes end -->
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-2 hide-others" style="width: 30.7%;">
                                                                <div class="form-group">
                                                                    <label for="other_file_format" class="fs-13">Others (Please specify) </label>
                                                                    <input id="other_file_format" class="form-control form-control-sm" type="text" name="other_file_format" placeholder="HDV" maxlength="4" style="width: 100px;">
                                                                    <i class="fal fa-file-video" style="top: 40px;"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-20">   
                                                            <div class="col-sm-12">
                                                                <label class="fs-13">File size restriction</label>
                                                                <!-- Checkboxes -->
                                                                <ul class="site-orientation-list filter-tags">
                                                                    <li>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" id="check-file_size00" type="radio" name="file_size" value="1MB">
                                                                            <label class="fs-13" for="check-file_size00">0MB to 1MB </label>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" id="check-file_size01" type="radio" name="file_size" value="5MB">
                                                                            <label class="fs-13" for="check-file_size01">1MB to 5MB </label>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" id="check-file_size02" type="radio" name="file_size" value="10MB">
                                                                            <label class="fs-13" for="check-file_size02">5MB to 10MB</label>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" id="check-file_size03" type="radio" name="file_size" value="15MB">
                                                                            <label class="fs-13" for="check-file_size03">10MB to 15MB</label>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" id="check-file_size04" type="radio" name="file_size" value="20MB">
                                                                            <label class="fs-13" for="check-file_size04">15MB to 20MB</label>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                                <!-- Checkboxes end -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-2">
                                                        <a href="#" class="back-form btn btn-warning"><i class="far fa-angle-left"></i> Back</a>
                                                    </div>
                                                    <div class="col-sm-6">&nbsp;</div>
                                                    <div class="col-sm-2">&nbsp;</div>
                                                    <div class="col-sm-2 d-flex justify-content-end">
                                                        <a href="#" class="next-form theme-btn">Proceed <i class="far fa-angle-right"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="nav-price-pane" role="tabpanel" aria-labelledby="nav-price-tab" tabindex="0">
                                                <div class="alert alert-danger hide mt-20">
                                                    <ul></ul>
                                                </div>
                                                <div class="row mb-20">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label class="fs-13" for="payment_freq">Advert Session</label>
                                                            <select data-placeholder="Payment Frequency" class="form-control form-control-sm required" name="payment_freq" id="payment_freq"></select>
                                                            <i class="far fa-sign-language"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-20">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label class="fs-13">Price range (MIN &times; MAX)</label>
                                                            <div class="range-slider-wrap fl-wrap">
                                                                <input class="payment_freq_price_range-slider required"  name="price_range" data-from="1000.00" data-to="25000000.00" data-min="1000.00" data-max="25000000.00" data-prefix="₦">
                                                            </div>
                                                        </div>                                                  
                                                    </div>
                                                </div>
                                                <div class="row mb-20">
                                                    <div class="col-sm-12">
                                                        <label class="fs-13">Apply Promo?</label>
                                                        <!-- Checkboxes -->
                                                        <ul class="site-orientation-list filter-tags">
                                                            <li>
                                                                <div class="form-check">    
                                                                    <input id="check-apply_promo01" type="radio" name="apply_promo" class="form-check-input" value="YES" data-promo="promo">
                                                                    <label for="check-apply_promo01" class="fs-13">YES</label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="form-check">
                                                                    <input id="check-apply_promo02" type="radio" name="apply_promo" value="NO" class="form-check-input" data-promo="promo">
                                                                    <label for="check-apply_promo02" class="fs-13">NO</label>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                        <!-- Checkboxes end -->                                         
                                                    </div>
                                                </div>

                                                <div class="row mb-70 promo-settings hide">
                                                    <label class="fs-15">Promo Settings</label>
                                                    <div class="apply-promo">
                                                        <div class="row promo-copy mb-20">
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label class="fs-13">Festive Period</label>
                                                                    <select data-placeholder="Festive Periods" class="form-control form-control-sm festive_periods" name="festive_periods_0" data-festive_promo="yes">
                                                                        <option value="">-- Select --</option>
                                                                        <option value="New Years Day">New Year's Day</option>
                                                                        <option value="Good Friday">Good Friday</option>
                                                                        <option value="Easter Monday">Easter Monday</option>
                                                                        <option value="Workers Day">Workers' Day</option>
                                                                        <option value="Childrens Day">Childrens Day</option>
                                                                        <option value="Eid-el-fitri Sallah">Eid-el-fitri Sallah</option>
                                                                        <option value="Democracy Day">Democracy Day</option>
                                                                        <option value="Id el Kabir">Id el Kabir</option>
                                                                        <option value="Independence Day">Independence Day</option>
                                                                        <option value="Christmas Day">Christmas Day</option>
                                                                        <option value="Boxing Day">Boxing Day</option>
                                                                        <option value="Valentines Day">Valentines' Day</option>
                                                                    </select>
                                                                    <i class="far fa-ticket"></i>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label class="fs-13">Price range (MIN &times; MAX)</label>
                                                                    <input class="range-slider promo_price_range"  name="promo_price_range_0" data-from="1000.00" data-to="25000000.00" data-min="1000.00" data-max="25000000.00" data-prefix="₦" data-price_promo="yes">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="col-sm-2">
                                                            <a href="javascript://" class="btn btn-success add-promo" style="float: left;">Add More Promo<i class="fal fa-plus"></i></a>
                                                        </div>
                                                        <div class="col-sm-10">&nbsp;</div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-2">
                                                        <a href="#" class="back-form btn btn-warning"><i class="far fa-angle-left"></i> Back</a>
                                                    </div>
                                                    <div class="col-sm-6">&nbsp;</div>
                                                    <div class="col-sm-2">&nbsp;</div>
                                                    <div class="col-sm-2 d-flex justify-content-end">
                                                        <a href="#" class="next-form theme-btn">Proceed <i class="far fa-angle-right"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="nav-photo-pane" role="tabpanel" aria-labelledby="nav-photo-tab" tabindex="0" data-attrib="image">
                                                <div class="success-table-container mb-30">
                                                    <div class="success-table-header fl-wrap">
                                                        <i class="fal fa-image decsth"></i>
                                                        <h4 class="fs-16">Kindly choose the faces of your asset as depicted on each provided boxes.</h4>
                                                        <h4 class="fs-14">Also ensure the dimension is 800 &times; 530 (width &times; height) or larger.</h4>
                                                        <div class="clearfix"></div>
                                                        <p class="fs-13">You can as well upload more photo of your asset as see fit.</p>
                                                    </div>

                                                    <div class="alert alert-danger hide mt-20">
                                                        <ul></ul>
                                                    </div>

                                                    <div class="upload-faces-holder mt-30"></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-2">
                                                        <a href="#" class="back-form btn btn-warning"><i class="far fa-angle-left"></i> Back</a>
                                                    </div>
                                                    <div class="col-sm-6">&nbsp;</div>
                                                    <div class="col-sm-2">&nbsp;</div>
                                                    <div class="col-sm-2 d-flex justify-content-end">
                                                        <a href="#" class="next-form theme-btn">Proceed <i class="far fa-angle-right"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="nav-video-pane" role="tabpanel" aria-labelledby="nav-video-tab" tabindex="0">
                                                <div class="success-table-container mb-30">
                                                    <div class="success-table-header fl-wrap">
                                                        <i class="fal fa-video decsth"></i>
                                                        <h4 class="fs-16">Kindly choose a video for your asset for better representation and visual.</h4>
                                                        <h4 class="fs-14">Also ensure the video size is maximum of 3MB.</h4>
                                                        <div class="clearfix"></div>
                                                        <p class="fs-13">You can as only upload one video for this asset.</p>
                                                    </div>

                                                    <div class="upload-video-holder" style="margin-top: 30px;">
                                                        <div class="row clearfix">
                                                            <div class="col-sm-6">
                                                                <div class="add-list-media-wrap" style="height:400px;width:500px;">
                                                                    <div class="fuzone" style="background-color:#878c9f0d;">
                                                                        <div class="fu-text">
                                                                            <span>Click to upload your video</span>
                                                                        </div>
                                                                        <input type="file" class="upload-video" accept="video/mp4,video/x-m4v,video/*">
                                                                        <video loop autoplay id="video-player">
                                                                            <source id="video-source" src="">
                                                                                Your browser does not support HTML5 video.
                                                                        </video>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-2">
                                                        <a href="#" class="back-form btn btn-warning"><i class="far fa-angle-left"></i> Back</a>
                                                    </div>
                                                    <div class="col-sm-6">&nbsp;</div>
                                                    <div class="col-sm-1">&nbsp;</div>
                                                    <div class="col-sm-3 d-flex justify-content-end">
                                                        <a href="#" class="next-form theme-btn submit-asset">Confirm &amp; Submit<i class="fal fa-save"></i></a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="nav-confirm-pane" role="tabpanel" aria-labelledby="nav-confirm-tab" tabindex="0">
                                                <div class="success-table-container mb-30">
                                                    <div class="success-table-header fl-wrap">
                                                        <i class="fal fa-check-circle decsth"></i>
                                                        <h4 class="fs-16">Thank you. Your asset has been created successfully.</h4>
                                                        <div class="clearfix"></div>
                                                        <p class="fs-13">Advertisers can now shop for this asset on our platform, while you get notified of payment receipts.</p>
                                                        <a href="{{ url('/operator/vacantasset') }}" class="color-bg text-cyan">View Asset</a>
                                                    </div>
                                                </div>
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