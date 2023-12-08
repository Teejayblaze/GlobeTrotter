@extends('template.master')

@section('content')
    <style>
    
        .search-criteria-sidebar .boxes {
            padding: 15px;
            width: inherit;
            background-color: #fff;
            margin: 12px 0 20px;
        }

        .search-criteria-sidebar .boxes h3 {
            padding: 10px 0;
            margin-bottom: 10px;
            text-align: left;
        }

        .nice-select .nice-select-search-box:before {
            bottom: 12px;
            left: 12px;
        }

        span.current {
            width: 170px;
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .search-heading {
            text-align: end;
            padding: 15px;
            background-color: #fff;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            border-bottom: 1px solid #f3f3f3;
        }

        .filter-tags input, .custom-form .filter-tags input {
            border-radius: 200px;
        }

        .two-cols li {
            display: block;
            position: relative;
            float: left;
            width: 148px;
        }

        a.more-proximity {
            display: block;
            clear: both;
            padding: 8px;
            background-color: #f8f8f8ee;
        }

        .board-result {
            padding-top: 10px;
            float: left;
            position: relative;
            display: block;
            width: 895px;
        }

        .double-column .column-1, .double-column .column-2{
            width: 50%;
            float: left;
        }

        .add-to-campaign {
            position: fixed;
            bottom: 220px;
            right: 40px;
            text-align: center;
            border-radius: 200px;
            width: 60px;
            height: 60px;
            line-height: 68px;
            z-index: 9999999999;
            background-color: #330065c4;
            cursor: pointer;
            animation: pulse 4.2s ease-in 1s infinite;
        }

        .add-to-campaign i {
            color: #edededd9;
            font-size: 24px;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 rgb(241, 9, 9);
            }

            30% {
                box-shadow: 0 0 50px rgb(10, 218, 62);
            }

            70% {
                box-shadow: 0 0 70px rgb(47, 11, 207);
            }

            100% {
                box-shadow: 0 0 0 rgb(241, 9, 9);
            }
        }
        
        .add-to-campaign .tooltip {
            position: absolute;
            top: -36px;
            font-size: 10px;
            white-space: nowrap;
            padding: 14px;
            background-color: #383434;
            color: #fff;
            line-height: 0;
            border-radius: 2px;
            font-weight: 100;
            right: 20px;
            display: none;
        }

        .add-to-campaign .tooltip .tooltip-tail {
            position: absolute;
            border-top: 12px solid #383434;
            border-bottom: 12px solid transparent;
            border-left: 12px solid transparent;
            border-right: 12px solid transparent;
            right: 0;
            top: 23px;
        }

        .add-to-campaign:hover .tooltip {
            display: block;
        }

        .cart-items {
            margin-bottom: 10px;
        }

        .cart-items table tr td {
            font-weight: 100;
            text-align: left;
            border: 1px solid #acacac69;
            padding: 5px;
        }

        .cart-items table tr th {
            text-align: left;
            border: 1px solid #acacac69;
            padding: 5px;
        }

        .scrollable {
            height: 270px;
            overflow-y: auto;
        }

        .profile-edit-container .custom-form label {
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
            cursor:pointer;
        }

        .profile-edit-container .custom-form .quarter-tags label {width: 128px;}

        #asset_types_cat .quarter-tags {
            float: left;
            display: block;
            width: 50%;
        }

        .campaign-overlay {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            transform: scale(0);
            transition: all .4s ease-in 0s;
            display: none;
        }

        .show-campaign-overlay {
            transform: scale(1);
            display: block;
        }

        .campaign-overlay .campaign-content {
            background-color: #fff;
            width: 450px;
            height: auto;
            position: sticky;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border-radius: 8px;
        }

        .campaign-content .cheader {
            padding: 9px;
            text-align: left;
            box-shadow: 0 0px 5px 1px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .campaign-content .cfooter {
            padding: 5px;
            border-top: 1px solid #e1e1e1ab;
            overflow: hidden;
        }

        .campaign-content .cfooter .btn {
            background-color: #330065;
            padding: 0 57px 0 11px;
            height: 38px;
            line-height: 38px;
            float: left;
        }

        .campaign-content .cfooter .btn i {
            height: 38px;
            line-height: 38px;
        }

        .campaign-content .cbody {
            padding: 13px 0 10px;
        }
        
        .campaign-content .cbody ul {
            padding: 0 20px;
        }

        .campaign-content .cbody ul li {
            display: block;
            text-align: left;
            margin-bottom: 10px;
            cursor: pointer;
        }

        .campaign-content .cbody ul li:hover {
            background-color: #eee;
        }

        .campaign-content .cbody ul li p {
            display: inline;
            padding-bottom: 0;
            color: #333;
        }

        .campaign-content .cbody ul li p.label {
            float: right;
            border-radius: 200px;
            background-color: #db1313;
            padding: 5px;
            line-height: 10px;
            color: #fff;
        }

        .campaign-content.campaign-lists {
            display:none;
        }

        .closex {
            position: absolute;
            right: 8px;
            top: 9px;
            font-size: 15px;
            background-color: #333;
            color: #eee;
            border-radius: 200px;
            line-height: 25px;
            display: block;
            width: 25px;
            height: 25px;
            cursor: pointer;
            text-indent: 9px;
        }

        .bigger {
            padding: 0 45px 0;
            height: 54px;
            line-height: 54px;
            width: 100%;
            text-align: center;
        }

        .bigger i {
            height: 54px;
            line-height: 54px;
        }

        .smaller:after, .bigger:after {
            position: unset;
        }

        .smaller.red, .bigger.red {
            background: #d72828;
        }

        .cbody .custom-form {
            padding: 0 20px;
        }

        .custom-form label {
            padding: 5px 0;
        }

        .custom-form label i {
            top: 41px;
        }

        .lds-ring {
            display: block;
            position: relative;
            width: 64px;
            height: 64px;
            margin: 90px auto 0;
        }
        .lds-ring div {
            box-sizing: border-box;
            display: block;
            position: absolute;
            width: 51px;
            height: 51px;
            margin: 6px;
            border: 6px solid #000;
            border-radius: 50%;
            animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
            border-color: #000 transparent transparent transparent;
        }
        .lds-ring div:nth-child(1) {
            animation-delay: -0.45s;
        }
        .lds-ring div:nth-child(2) {
            animation-delay: -0.3s;
        }
        .lds-ring div:nth-child(3) {
            animation-delay: -0.15s;
        }
        @keyframes lds-ring {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        .campaign-loader {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            text-align: center;
            background-color: #fff;
            z-index: 9;
            line-height: 80px;
            display: none;
        }

        .campaign-result-loader {
            display: none;
            height: 251px !important;
            line-height: 50px;
            padding-top: 1px;
        }

        .login-form .form-group i {
            top: 56px !important;
        }

        .custom-btn {
            padding: 5px 20px !important;
            font-size: .9rem;
        }

        .profile-name.cart-headline {
            font-size: 22px;
            text-align: left;
        }

        .add-property-form .form-group {
            text-align: left;
            margin-bottom: 5px;
        }

        .add-property-form .form-control {
            padding: 10px 15px;
        }

        .form-check {
            min-height: 1rem;
            padding-left: 2.1em;
            margin-bottom: 7px !important;
        }
        .add-property-form .form-check label {
            font-size: .7rem;
        }

        .hide {
            display: none;
        }

        .search-campaign, .search-campaign:hover, .search-campaign:active, .search-campaign:visited {
            color: #fff;
        }

        .hover-board {
            position: relative;
            padding: 15px;
            background-color: #fff;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 0 40px 5px rgb(0 0 0 / 5%);
            transition: all .5s ease-in-out;
        }
        .listing-item {
            background: #fff;
            border-radius: 0;
            margin-bottom: 0;
            box-shadow: none;
            padding: 0;
        }
        .dynamic-content {
            position: absolute;
            width: 100%;
            left: 0;
            display: none;
            padding: 15px;
            background-color: #fff;
            z-index: 99999999;
            margin-top: -23px;
        }

        .hover-board:hover .dynamic-content {
            display: block;
        }

        .hover-board label {
            width: 100%;
        }

        .hover-board input[type="checkbox"]:checked + label .img-holder span.check-correct {
            transform: scale(1);
        }

        .hover-board .img-holder span.check-correct {
            display: block;
            position: absolute;
            right: 10px;
            top: 10px;
            border-radius: 200px;
            width: 33px;
            height: 33px;
            background-color: #0c0f7aa6;
            color: #fff;
            border: 2px solid #fff;
            transform: scale(0);
            transition: transform .1s linear 0s;
            z-index: 9;
        }

        .hover-board input[type="checkbox"]:checked + label .img-holder span.check-correct::before {
            content: '';
            width: 3px;
            height: 10px;
            background-color: #fff;
            z-index: 999;
            position: absolute;
            left: 8px;
            transform: rotate(126deg);
            top: 15px;
            border-radius: 8px;
        }

        .hover-board input[type="checkbox"]:checked + label .img-holder span.check-correct::after {
            content: '';
            width: 3px;
            height: 19px;
            background-color: #fff;
            z-index: 999;
            position: absolute;
            left: 15px;
            transform: rotate(207deg);
            top: 5px;
            border-radius: 8px;
        }
    </style>
    <div class="campaign-overlay">
        <div class="campaign-content campaign-lists">
            <span class="closex closex-overlay">&times;</span>
            <div class="cheader">Choose an existing campaign to add your selected site.</div>
            <div class="cbody">
                <ul>
                    @if (count($campaigns_found))
                        @foreach($campaigns_found as $key => $campaigns)
                            <li data-id="{{$campaigns->id}}"><p>{{$campaigns->name}} ({{$campaigns->start_date}})</p><p class="label">{{$campaigns->campaign_count}}</p></li>
                        @endforeach
                    @endif
                </ul>
            </div>
            <div class="campaign-loader">
                <div class="lds-ring" style="height: 39px;margin: 20px auto 0;"><div></div><div></div><div></div><div></div></div>
                sending your request, please wait...
            </div>
            <div class="cfooter"> &nbsp; </div>
        </div>

        <div class="campaign-content campaign-form">
            <div class="cheader">Campaign Form</div>
            <div class="cbody">
                <div class="custom-form">
                    <div class="login-form login-form-two">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Campaign Name</label>
                                    <input type="text" placeholder="Your campaign name" class="form-control" name="campaign_name" id="campaign_name" value="" class="required">                                                  
                                    <i class="fal fa-road"></i> 
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Campaign Start Date</label>
                                    <input type="text" placeholder="Your campaign date" class="form-control" name="campaign_date" id="campaign_date" autocomplete="off" value="" class="required">                                                  
                                    <i class="fal fa-calendar"></i> 
                                </div>
                            </div>
                            <input type="hidden" name="user_id" value="{{$user->user_id}}">
                            <input type="hidden" name="user_type_id" value="{{$user->user_type_id}}">
                            <input type="hidden" name="corp_id" value="{{$user->corp_id}}">
                        </div>
                    </div>
                </div>
            </div>
                <div class="campaign-loader">
                    <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                    sending your request, please wait...
                </div>
            <div class="cfooter"> <a href="javascript://" class="theme-btn custom-btn save-campaign">Save Campaign<i class="fal fa-save"></i></a></div>
        </div>

        <div class="campaign-content campaign-result-loader">
            <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
            Kindly wait a moment, while we send your request...
        </div>
    </div>

    <div class="site-breadcrumb login-breadcrumb dashboard-breadcrumb">
        <div class="container">
            <h2 class="breadcrumb-title dashboard-breadcrumb-menu-left">Campaign Cart</h2>
            <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
                <li><a href="{{ url('/advertiser/individual/dashboard') }}">Dashboard</a></li>
                <li>Campaigns</li>
                <li class="active-nav-dashboard">Campaign Cart</li>
            </ul>
        </div>
    </div>
    <section id="advertiser-campaign-shopping-cart">
        <input type="hidden" name="campaign_id" value="{{$campaign_id}}">
        <div class="user-profile pt-40 pb-40 dashboard-bg-color">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="user-profile-sidebar wow slideInLeft" data-wow-duration="2.5s" data-wow-delay=".25s">
                            <div class="user-profile-sidebar-top mt-1">
                                <h3 class="profile-name cart-headline">Campaign Cart</h3>
                                <div class="cart-placement" style="margin-top:0;overflow: hidden;text-align: left;font-size: .7rem;"></div>
                            </div>
                            
                            <div class="user-profile-sidebar-top profile-detail mt-20">
                                <h4 class="profile-name dashboard-search-title">Wide Range Search</h4>
                                <div class="add-property-form">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="asset_location_state">State of Choice</label>
                                                <select data-placeholder="Your Asset State" class="form-control form-control-sm required" name="asset_location_state" id="asset_location_state">
                                                    <option value="">-- Select State --</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="asset_location_lga">LGA of Choice</label>
                                                <select data-placeholder="Your Asset LGA" class="form-control form-control-sm required" name="asset_location_lga" id="asset_location_lga">
                                                    <option value="">-- Select LGA --</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="user-profile-sidebar-top profile-detail mt-20">
                                <h4 class="profile-name dashboard-search-title">Site Board Type</h4>
                                <div class="add-property-form">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <ul class="row fl-wrap filter-tags">
                                                    <li class="col-lg-6 form-check">
                                                        <input id="check-site_board_type_1" type="radio" name="site_board_type" value="static" class="form-check-input board_types" data-id="1">
                                                        <label for="check-site_board_type_1">Static</label>
                                                    </li>
                                                    <li class="col-lg-6 form-check">
                                                        <input id="check-site_board_type_2" type="radio" name="site_board_type" value="dynamic" class="form-check-input board_types" data-id="2">
                                                        <label for="check-site_board_type_2">Dynamic</label>
                                                    </li>
                                                    <li class="col-lg-6 form-check">
                                                        <input id="check-site_board_type_3" type="radio" name="site_board_type" value="digital" class="form-check-input board_types" data-id="3">
                                                        <label for="check-site_board_type_3">Digital</label>
                                                    </li>
                                                    <li class="col-lg-6 form-check">
                                                        <input id="check-site_board_type_4" type="radio" name="site_board_type" value="mobile" class="form-check-input board_types" data-id="4">
                                                        <label for="check-site_board_type_4">Mobile</label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="user-profile-sidebar-top profile-detail mt-20">
                                <h4 class="profile-name dashboard-search-title">Site Category</h4>
                                <div class="add-property-form">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div id="asset_types_cat" class="row"  data-layout="2" data-layout-column="6" style="overflow-y: auto;max-height: 270px;"></div>
                                            <script>
                                                var asset_types_arry = <?php echo $asset_types ?>
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="user-profile-sidebar-top profile-detail mt-20">
                                <h4 class="profile-name dashboard-search-title">Site Substrate</h4>
                                <div class="add-property-form">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <ul class="row fl-wrap filter-tags">
                                                    <li class="col-lg-4 form-check">
                                                        <input id="check-substrate1" type="radio" name="substrate" value="SAV" class="form-check-input">
                                                        <label for="check-substrate1">SAV</label>
                                                    </li>
                                                    <li class="col-lg-4 form-check">
                                                        <input id="check-substrate4" type="radio" name="substrate" value="Mesh" class="form-check-input">
                                                        <label for="check-substrate4">Mesh</label>
                                                    </li>
                                                    <li class="col-lg-4 form-check">                                       
                                                        <input id="check-substrate5" type="radio" name="substrate" value="Flex" class="form-check-input">
                                                        <label for="check-substrate5">Flex</label>
                                                    </li>
                                                </ul>
                                                <ul class="row fl-wrap filter-tags">
                                                    <li class="dynamic hide col-lg-4 form-check">
                                                        <input id="check-substrate2" type="radio" name="substrate" value="PVC Strips" class="form-check-input">
                                                        <label for="check-substrate2">PVC Strips</label>
                                                    </li>
                                                    <li class="dynamic hide col-lg-4 form-check">
                                                        <input id="check-substrate3" type="radio" name="substrate" value="Canvas" class="form-check-input">
                                                        <label for="check-substrate3">Canvas</label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="user-profile-sidebar-top profile-detail mt-20">
                                <h4 class="profile-name dashboard-search-title">Site Orientation</h4>
                                <div class="add-property-form">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <ul class="row fl-wrap filter-tags">
                                                    <li class="form-check col-lg-6">
                                                        <input id="check-orientation_1" type="radio" name="orientation" value="Landscape" class="form-check-input">
                                                        <label for="check-orientation_1">Landscape</label>
                                                    </li>
                                                    <li class="form-check col-lg-6">
                                                        <input id="check-orientation_2" type="radio" name="orientation" value="Portrait" class="form-check-input">
                                                        <label for="check-orientation_2">Portrait</label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="user-profile-sidebar-top profile-detail mt-20">
                                <h4 class="profile-name dashboard-search-title">Site Faces</h4>
                                <div class="add-property-form">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <ul class="row fl-wrap filter-tags">
                                                    <li class="form-check col-lg-3">
                                                        <input id="check-face_count1" type="radio" name="face_count" value="1" class="form-check-input">
                                                        <label for="check-face_count1">1</label>
                                                    </li>
                                                    <li class="form-check col-lg-3">
                                                        <input id="check-face_count2" type="radio" name="face_count" value="2" class="form-check-input">
                                                        <label for="check-face_count2">2</label>
                                                    </li>
                                                    <li class="form-check col-lg-3">                                       
                                                        <input id="check-face_count3" type="radio" name="face_count" value="3" class="form-check-input">
                                                        <label for="check-face_count3">3</label>
                                                    </li>
                                                    <li class="form-check col-lg-3">                                       
                                                        <input id="check-face_count4" type="radio" name="face_count" value="4" class="form-check-input">
                                                        <label for="check-face_count4" class="form-check-label">4</label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="user-profile-sidebar-top profile-detail mt-20">
                                <h4 class="profile-name dashboard-search-title">Site Proximities</h4>
                                <div class="add-property-form">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group"  style="overflow-y: auto;max-height: 270px;">
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
                                                <ul class="row fl-wrap filter-tags">
                                                    @for($outer = 0; $outer < $iter_ceil; $outer++) 
                                                        <div class="col-6 col-md-6">
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
                                                <ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="#" class="btn bigger red search-campaign">SEARCH FOR CAMPAIGN&nbsp;<i class="fal fa-search"></i></a>    
                        </div>
                    </div>
                    <div class="col-lg-9">
                    <div class="user-profile-wrapper wow slideInRight" data-wow-duration="2.5s" data-wow-delay=".25s">
                        <div class="user-profile-card add-property">
                            <h4 class="user-profile-card-title">Assets</h4>
                                <div class="row">
                                    @foreach($assets as $key => $asset) 
                                        <?php
                                            $tmp = $key;
                                            $images = $asset->assetImagesRecord->toArray();
                                            if(count($images)){
                                            $rand = array_rand($images);
                                        ?>

                                        <div class="col-md-6 col-lg-4">
                                            <div class="hover-board">
                                                <input type="checkbox" name="assets-check" id="check-asset-{{$key}}" style="display:none;" data-name="{{$asset->name}}" data-price="{{$asset->max_price}}" data-id="{{$asset->id}}">
                                                <label for="check-asset-{{$key}}">
                                                    <div class="img-holder listing-item wow fadeIn" data-wow-duration="2.5s" data-wow-delay=".25s">
                                                        <span class="listing-badge">Available</span>
                                                        <div class="listing-img">
                                                            <img src="{{ Storage::url($asset->assetImagesRecord[$rand]->image_path) }}" alt="Featured image">
                                                        </div>
                                                        <div class="listing-content">
                                                            <h4 class="listing-title result-listing-title"><a href="{{ url('asset/'. $asset->id .'/detail') }}">{{ $asset->name }}</a></h4>
                                                            <p class="listing-sub-title mb-4 result-listing-address"><i class="far fa-location-dot"></i>{{ $asset->address }}</p>
                                                        </div>
                                                        <span class="check-correct"></span>
                                                    </div>
                                                    <div class="dynamic-content">
                                                        <div class="listing-bottom">
                                                            <div class="listing-price-info mb-10">
                                                                <p class="listing-price-title fs-13 mb-10">This site is owned and managed by <strong>{{$asset->owner}}</strong>, it is ver affordable and it has proximity to</p>
                                                                @foreach($asset->assetProximityRecords as $proxid => $proximity) 
                                                                <p class="fb fs-13">{{ $proximity->proximity_type .': '. $proximity->proximity_name }}</p>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 col-6">
                                                                <p>
                                                                    <i class="asset-info-icon fal fa-text-width"></i><span class="asset-info-title fb fs-14">Dimension</span>
                                                                </p>
                                                                <p class="fs-13">{{$asset->asset_dimension_width .'m x '. $asset->asset_dimension_width .'m'}}</p>
                                                            </div>
                                                            @if ($asset->asset_category == 'digital' || ($asset->asset_category == 'mobile' && $asset->advert_type == 'digital'))
                                                                <div class="col-md-6 col-6">
                                                                    <p>
                                                                        <i class="asset-info-icon fal fa-hdd"></i><span
                                                                        class="asset-info-title fb fs-14">Maximum Slots</span>
                                                                    </p>
                                                                    <p class="fs-13">{{$asset->num_slides}}</p>
                                                                </div>
                                                            @else
                                                                <div class="col-md-6 col-6">
                                                                    <p>
                                                                        <i class="asset-info-icon fal fa-print"></i><span
                                                                        class="asset-info-title fb fs-14">Print Dimension</span>
                                                                    </p>
                                                                    <p class="fs-13">{{$asset->print_dimension?$asset->print_dimension:'N/A'}}</p>
                                                                </div>
                                                            @endif
                                                            <div class="col-md-6 col-6">
                                                                <p><i class="asset-info-icon fal fa-money-bill"></i><span
                                                                    class="asset-info-title fb fs-14">Price</span></p>
                                                                <p class="fs-13">&#8358;{{ number_format(str_replace(',', '', $asset->max_price), 2, '.', ',') }}</p>
                                                            </div>
                                                            <div class="col-md-6 col-6">
                                                                <p><i class="asset-info-icon fal fa-donate"></i><span
                                                                    class="asset-info-title fb fs-14">Pay Frequency</span></p>
                                                                <p class="fs-13">{{$asset->payment_freq}}</p>
                                                            </div>
                                                            <div class="col-md-6 col-6">
                                                                <p><i class="asset-info-icon fas fa-chalkboard"></i><span
                                                                    class="asset-info-title fb fs-14"> Board Type</span></p>
                                                                    @if ($asset->asset_category == 'mobile') 
                                                                        <p class="fs-13">{{ ucwords($asset->advert_type .' '. $asset->asset_category) }}</p>
                                                                    @else 
                                                                    <p class="fs-13">{{ ucwords($asset->asset_category) }}</p>
                                                                    @endif
                                                            </div>
                                                            <div class="col-md-6 col-6">
                                                                <p><i class="asset-info-icon fal fa-cubes"></i><span class="asset-info-title fb fs-14">
                                                                    Asset Type</span></p>
                                                                <p class="fs-13">{{$asset->assetTypeRecord->type}}</p>
                                                            </div>
                                                            <div class="col-md-6 col-6">
                                                                <p><i class="asset-info-icon fal fa-compass"></i><span class="asset-info-title fb fs-14">
                                                                    Orientation</span></p>
                                                                <p class="fs-13">{{ $asset->orientation }}</p>
                                                            </div>
                                                            @if ($asset->asset_category == 'digital' || ($asset->asset_category == 'mobile' && $asset->advert_type == 'digital'))
                                                                <div class="col-md-6 col-6">
                                                                    <p><i class="asset-info-icon fal fa-clock"></i><span class="asset-info-title fb fs-14">Seconds Per Slot</span></p>
                                                                    <p class="fs-13">{{$asset->num_slides_per_secs}}</p>
                                                                </div>
                                                            @else
                                                                <div class="col-md-6 col-6">
                                                                    <p><i class="asset-info-icon fal fa-cubes"></i><span class="asset-info-title fb fs-14">Material Type</span></p>
                                                                    <p class="fs-13">{{$asset->substrate?$asset->substrate:'N/A'}}</p>
                                                                </div>
                                                            @endif
                                                            <div class="col-md-6 col-6">
                                                                <p><i class="asset-info-icon fal fa-compress"></i><span class="asset-info-title fb fs-14">
                                                                    Number of Faces</span></p>
                                                                <p class="fs-13">{{ $asset->face_count }}</p>
                                                            </div>
                                                            <div class="col-md-6 col-6">
                                                                <p><i class="asset-info-icon fal fa-file-pdf"></i><span class="asset-info-title fb fs-14">
                                                                    File Format</span></p>
                                                                <p class="fs-13">{{$asset->file_format}}</p>
                                                            </div>
                                                        </div>
                                                        <div class="listing-bottom-two mt-10">
                                                            <p class="fs-13">Site is located at the  <span class="fb">{{ $asset->lcda && $asset->lcda->lcda_name ? $asset->lcda->lcda_name : '' }}</span></p>
                                                            <p class="fs-13 fb mt-10">Population Density: {{ $asset->lcda && $asset->lcda->lcda_population ? $asset->lcda->lcda_population : '12345' }}</p>
                                                        </div>
                                                        <hr class="divider">
                                                        <div class="listing-details-btn">
                                                            <a href="{{ url('asset/'. $asset->id .'/detail') }}" class="theme-btn dashboard-search-btn fs-13 asset-detail-btn">Site Details<span class="asset-detail-icon fal fa-paper-plane"></span></a>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>

                                        <?php } ?>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="add-to-campaign">
        <i class="fal fa-cart-plus"></i>
        <div class="tooltip">
            Click to add selected site to cart.
            <div class="tooltip-tail"></div>
        </div>
    </div>
@endsection