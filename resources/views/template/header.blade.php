<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ config('app.name') }} is a platform for Out Of Home advertising">
    <meta name="keywords" content="GlobeTrotter, DataShare, {{ config('app.name') }}, {{ config('app.name') }} DataShare">
    <meta name="author" content="{{ config('app.name') }} DataShare">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - {{$title??"Out Of Home Advertisement Platform"}}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset("img/logo/favicon.png")}}">
    <link rel="stylesheet" href="{{ asset("css/bootstrap.min.css")}}">
    <link rel="stylesheet" href="{{ asset("css/all-fontawesome.min.css")}}">
    <link rel="stylesheet" href="{{ asset("css/flaticon.css")}}">
    <link rel="stylesheet" href="{{ asset("css/animate.min.css")}}">
    <link rel="stylesheet" href="{{ asset("css/magnific-popup.min.css")}}">
    <link rel="stylesheet" href="{{ asset("css/owl.carousel.min.css")}}">
    <link rel="stylesheet" href="{{ asset("css/nice-select.min.css")}}">
    <link rel="stylesheet" href="{{ asset("css/jquery-ui.min.css")}}">
    <link rel="stylesheet" href="{{ asset("css/style.css")}}">
    <link rel="stylesheet" href="{{ asset("css/custom.css")}}">
    <link rel="stylesheet" href="{{ asset("css/ion-range-slider.min.css")}}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
      .header-flex-container {
        background: #d6bf3045;
        font-size: 14px;
      }
      .exit-fast-track {
        font-size: 12px;
        color: #ea1717;
        margin-left: 14px;
      }
      .header-flex-container .fast-track-mode {
        display: flex;
        width: 100%;
        flex-flow: row nowrap;
        color: #333;
        align-items: baseline;
        justify-content: flex-start;
      }

      .home-3 .search-area {
        margin-top:-135px;
      }

      .slider-sub-title.modify {
        margin: 0;
        background-color: transparent;
        padding: 0 20px;
        border-radius: 5px;
      }

      .partner-wrapper.partner-sliderx {
        display: flex;
        justify-content: center;
      }

      .partner-wrapper.partner-sliderx img {
        padding: 5px 40px;
        width: 155px;
      }
      #slider-text {
        right: 50%;
        transform: translate(50%, 50%);
      }
      ul.search-results {
        background: #fff !important;
        border: 1px solid #e8e8e8 !important;
        border-radius: 8px !important;
        margin-top: 3px !important;
        height: auto !important;
      }
      ul.search-results li {
        padding: 4px 15px !important;
        margin: 0 !important;
        cursor: pointer !important;
      }
      ul.search-results li:hover {
        color: #fff !important;
        background: #333 !important;
      }

      .search-area-section {
        position: relative;
        z-index: 99;
      }
      .ui-autocomplete-loading {
        background: white url('https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/images/ui-anim_basic_16x16.gif') right center no-repeat;
      }
    </style>
  </head>

<body class="home-3">
   <div class="preloader">
      <div class="loader">
         <div class="dots"> <img src="{{ asset("img/logo/logo.png") }}" alt="Company logo"><br>
            {{ config('app.name') }}
         </div>
      </div>
   </div>
   <header class="header">
     @if(session()->has('fast-track-mode'))
      <div class="header-top-contact header-flex-container">
        <div class="container">
          <div class="fast-track-mode">
              <p> <i class="fal fa-info-circle" style="color: #ea1717;margin-right: 4px;"></i> You are currently running on fast track mode.</p>
              <a href="{{ url('/advertiser/individual/fast-track/exit') }}" class="exit-fast-track">Exit Fast-Track mode</a>
              <div class="clearfix"></div>
          </div>
        </div>
      </div>
      @endif
      <div class="header-top">
         <div class="container">
            <div class="header-top-wrapper">
               <div class="header-top-left">
                  <div class="header-top-contact">
                     <ul>
                        <li>
                           <div class="header-top-contact-info">
                              <a href=""><i class="far fa-envelopes"></i>
                                 <span class="__cf_email__" data-cfemail="">{{env('APP_CUSTOMER_CARE_EMAIL', 'info@globetrotter.com.ng')}}</span></a>
                           </div>
                        </li>
                        <li>
                           <div class="header-top-contact-info">
                              <a href="tel:+21236547898"><i class="far fa-phone-arrow-down-left"></i>
                                 {{env('APP_CUSTOMER_CARE_PHONE', '+234-813-2614337')}}</a>
                           </div>
                        </li>
                     </ul>
                  </div>
               </div>
               <div class="header-top-right">
                  <div class="header-top-social">
                     <a href="#"><i class="fab fa-facebook-f"></i></a>
                     <a href="#"><i class="fab fa-twitter"></i></a>
                     <a href="#"><i class="fab fa-instagram"></i></a>
                     <a href="#"><i class="fab fa-linkedin-in"></i></a>
                  </div>
               </div>
            </div>

         </div>
      </div>
      <?php 
        $segment = \Request::segment(1, 'home');
        $segmentQuery = \Request::segment(2);
      ?>
      <div class="main-navigation">
         <nav class="navbar navbar-expand-lg">
            <div class="container">
               <a class="navbar-brand" href="{{url("/")}}">
                  <img src="{{ asset("img/logo/logo.png")}}" alt="logo">
               </a>
               <div class="mobile-menu-right">
                  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main_nav"
                     aria-expanded="false" aria-label="Toggle navigation">
                     <span class="navbar-toggler-btn-icon"><i class="far fa-bars"></i></span>
                  </button>
               </div>
               <div class="collapse navbar-collapse" id="main_nav">
                  <ul class="navbar-nav">
                    @if (!\Request::get('user'))
                      <li class="nav-item top-header-nav"><a class="nav-link @if($segment && $segment === 'home') active @endif" href="{{url("/")}}">Home</a></li>
                      @if (!\session()->has('corporate') && !\session()->has('individual') && !\session()->has('operator'))
                      <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle @if(($segment && $segmentQuery === 'login')) active @endif" href="#" data-bs-toggle="dropdown">Login</a>
                        <ul class="dropdown-menu fade-down">
                            <li><a class="dropdown-item" href="{{url("/advertiser/login")}}">Advertiser</a>
                            </li>
                            <li><a class="dropdown-item" href="{{url("/operator/login")}}">Asset Owner</a>
                            </li>
                        </ul>
                      </li>
                      <li class="nav-item top-header-nav"><a class="nav-link @if($segment && (($segment === 'signup' &&$segmentQuery === 'category') || ($segment === 'advertiser' && $segmentQuery === 'individual') || ($segment === 'operator' && $segmentQuery === 'signup') || ($segment === 'advertiser' && $segmentQuery === 'corporate'))) active @endif" href="{{url("/signup/category")}}">Register</a></li>
                      @endif
                      <li class="nav-item top-header-nav"><a class="nav-link @if(($segment && $segment === 'faq')) active @endif" href="{{url("/faq")}}">FAQ</a></li>
                      <li class="nav-item top-header-nav"><a class="nav-link @if(($segment && $segment === 'contact')) active @endif" href="{{url("/contact")}}">Contact</a></li>
                      
                      @if (\session()->has('corporate') || \session()->has('individual'))
                      <li class="nav-item top-header-nav"><a class="nav-link" href="{{ url('/advertiser/individual/dashboard') }}">Back to Dashboard <i class="far fa-share"></i></a></li>
                      @elseif(\session()->has('operator'))
                      <li class="nav-item top-header-nav"><a class="nav-link" href="{{ url('/operator/dashboard') }}">Back to Dashboard <i class="far fa-share"></i></a></a></li>
                      @endif
                    @elseif (\Request::get('user'))
                      @if ( \Request::get('user')->operator === 0 )
                        <?php
                            $segment = Request::segment(3);
                            $validAssetSeg = ['myassets', 'material'];   
                            $validTranSeg = ['historical', 'pending']; 
                        ?>
                        
                        <li class="nav-item top-header-nav">
                            <a class="nav-link @if($segment === 'dashboard') active @endif" href="{{ url('/advertiser/individual/dashboard') }}">Dashboard</a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle @if(in_array($segment, $validAssetSeg)) active @endif" href="#" data-bs-toggle="dropdown">Advert Manager</a>
                            <ul class="dropdown-menu fade-down">
                                <li>
                                    <a class="dropdown-item sm-fs-13" href="{{ url('/advertiser/individual/search/advanced') }}">Book a Site</a>
                                </li>
                                <li>
                                    <a class="dropdown-item sm-fs-13" href="{{ url('/advertiser/individual/fast-track') }}">FastTrack Booking</a>
                                </li>
                                <li>
                                    <a class="dropdown-item sm-fs-13" href="{{ url('/advertiser/individual/view/campaign') }}">Create Campaign</a>
                                </li>
                                <li>
                                    <a class="dropdown-item sm-fs-13" href="{{ url('/advertiser/individual/material') }}"> Material Upload</a>
                                </li>
                                <li>
                                    <a class="dropdown-item sm-fs-13" href="{{ url('/advertiser/individual/myassets') }}">Booked Site</a>
                                </li>
                                
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle @if($segment === 'profile') active @endif"  data-bs-toggle="dropdown"> Profile </i></a>
                            <ul class="dropdown-menu fade-down">
                                <li>
                                    <a class="dropdown-item sm-fs-13" href="{{url('/advertiser/individual/profile/edit')}}">Edit Profile</a>
                                </li>
                                <li>
                                    <a class="dropdown-item sm-fs-13" href="{{url('/advertiser/individual/profile/changepassword')}}"> Change Password</a>
                                </li>
                            </ul>
                        </li>           
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle @if(in_array(Request::segment(4), $validTranSeg)) active @endif" href="#" data-bs-toggle="dropdown"> Transactions</a>
                            <ul class="dropdown-menu fade-down">
                                <li>
                                    <a class="dropdown-item sm-fs-13" href="{{ url('/advertiser/individual/transactions/pending') }}">Pending Transactions</a>
                                </li>
                                <li>
                                    <a class="dropdown-item sm-fs-13" href="{{ url('/advertiser/individual/transactions/pending/campaign') }}">Campaign Transactions</a>
                                </li>
                                <li>
                                    <a class="dropdown-item sm-fs-13" href="{{ url('/advertiser/individual/transactions/historical') }}">Historical Transactions</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item top-header-nav">
                            <a class="nav-link @if($segment === 'payment-history') active @endif" href="{{ url('/advertiser/individual/payment-history') }}">Payment History </a>
                        </li>
                       
                        <li class="nav-item dropdown">
                          @if ($user->corp_id && $user->admin === 1)
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                Administrator Menu
                            </a>
                            <ul class="dropdown-menu fade-down">
                              <li>
                                @if ( $user->corp_id )
                                    <a class="dropdown-item sm-fs-13" href="{{url('/advertiser/individual/corporate/dashboard')}}">Administrative Dashboard</a>
                                @else 
                                    <a class="dropdown-item sm-fs-13" href="{{url('/advertiser/individual/dashboard')}}"> My Dashboard</a>
                                @endif
                              </li>
                              <li>
                                <a class="dropdown-item sm-fs-13" href="{{url('/advertiser/individual/corporate/bookings')}}">Corporate Transactions</a>
                              </li>
                              <li>
                                <a class="dropdown-item sm-fs-13" href="{{url('/advertiser/individual/corporate/staffs')}}"> Registered Staff</a>
                              </li>
                              <li>
                                <a class="dropdown-item sm-fs-13" href="{{ url('/advertiser/individual/logout') }}">Logout</a>
                              </li>
                            </ul>
                            @elseif ($user->corp_id && $user->admin === 0)
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                              My Account
                            </a>
                            <ul class="dropdown-menu fade-down">
                              <li><span class="dropdown-item sm-fs-13"><strong>{{$user->work_with->name}}</strong></span></li>
                              <li>
                                  <a class="dropdown-item sm-fs-13" href="{{ url('/advertiser/individual/logout') }}">Logout</a>
                              </li>
                            </ul>
                            @else
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                              My Account
                            </a>
                            <ul class="dropdown-menu fade-down">
                              <li>
                                  <a class="dropdown-item sm-fs-13" href="{{ url('/advertiser/individual/logout') }}">Logout</a>
                              </li>
                            </ul>
                          @endif
                        </li>
                      @elseif( \Request::get('user')->operator === 1 )
                        <?php
                            $segment = Request::segment(2);
                            $validAssetSeg = ['vacantasset', 'bookedasset'];
                        ?>
                        <li class="nav-item top-header-nav">
                            <a class="nav-link @if(Request::segment(2) === 'dashboard') active @endif" href="{{ url('/operator/dashboard') }}">Dashboard </a>
                        </li>
                        <li class="nav-item top-header-nav">
                          <a class="nav-link @if(Request::segment(2) === 'materials') active @endif" href="{{ url('/operator/materials') }}"> Uploaded Materials </a>
                        </li>
                        {{-- <li class="nav-item dropdown">
                            <a  class="nav-link dropdown-toggle @if(in_array($segment, $validAssetSeg)) active @endif"  data-bs-toggle="dropdown">Total Assets</a>
                            <ul class="dropdown-menu fade-down">
                                <li>
                                    <a class="dropdown-item sm-fs-13" href="{{ url('/operator/vacantasset') }}">Vacant Assets </a>
                                </li>
                                <li>
                                    <a class="dropdown-item sm-fs-13" href="{{ url('/operator/bookedasset') }}">Booked Assets</a>
                                </li>
                                <li>
                                    <a class="dropdown-item sm-fs-13" href="{{ url('/operator/materials') }}">Uploaded Materials</a>
                                </li>
                            </ul>
                        </li>  --}}
                        <li class="nav-item top-header-nav">
                            <a class="nav-link @if(Request::segment(2) === 'assetupload') active @endif" href="{{ url('/operator/assetupload') }}"> Upload an Asset </a>
                        </li>
                        @if (\Request::get('user')->admin)
                        <li class="nav-item top-header-nav">
                            <a class="nav-link @if(Request::segment(2) === 'subscription') active @endif"  href="{{ url('/operator/subscription') }}">{{env("APP_OWNER_NAME")}} Subscription </a>
                        </li>
                        @endif
                        <li class="nav-item top-header-nav">
                            <a class="nav-link @if($segment === 'payment-history') active @endif"  href="{{ url('/operator/payment-history') }}">Payment History  </a>
                        </li>
                        @if (\Request::get('user')->admin)
                        <li class="nav-item top-header-nav">
                            <a class="nav-link @if(Request::segment(2) === 'staffs') active @endif" href="{{ url('/operator/staffs') }}">Staffs </a>
                        </li>
                        @endif
                        <li class="nav-item dropdown">
                          <a class="nav-link dropdown-toggle @if(Request::segment(2) === 'bank-account-setup') active @endif" href="#" data-bs-toggle="dropdown">
                            My Account
                          </a>
                          <ul class="dropdown-menu fade-down">
                            @if (\Request::get('user')->admin)
                            <li><a class="dropdown-item sm-fs-13" href="/operator/bank-account-setup"> Disbursement Bank Setup</a></li>
                            @endif
                            <li>
                              <a class="dropdown-item sm-fs-13" href="{{ url('/operator/logout') }}">Logout</a>
                            </li>
                          </ul>
                        </li>
                      @endif

                      @if (\Request::get('user')->admin_group_id === 3)
                        <?php
                            $segment = Request::segment(3);
                            $segmentx = Request::segment(2);
                            $validDisbursed = ['payment', 'pending', 'payment-schedule'];   
                            $validAssets = ['bookings', 'materials']
                        ?>
                        <li class="nav-item top-header-nav">
                            <a class="nav-link @if($segmentx === 'dashboard') active @endif" href="{{ url('/admin/dashboard') }}">Dashboard </a>
                        </li>
                        <li class="nav-item top-header-nav">
                            <a class="nav-link @if($segment === 'users') active @endif" href="{{ url('/admin/platform/users') }}">Platform Users </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle @if(in_array($segment, $validAssets)) active @endif"  data-bs-toggle="dropdown">Asset Management</a>
                            <ul class="dropdown-menu fade-down">
                                <li>
                                    <a class="dropdown-item sm-fs-13" href="{{ url('/admin/platform/assets') }}">Assets</a>
                                </li>
                                <li>
                                    <a class="dropdown-item sm-fs-13" href="{{ url('/admin/platform/bookings') }}">Bookings</a>
                                </li>
                                <li>
                                    <a class="dropdown-item sm-fs-13" href="{{ url('/admin/platform/batch-asset-upload') }}">Batch Asset Upload</a>
                                </li>
                                <li>
                                    <a class="dropdown-item sm-fs-13" href="{{ url('/admin/platform/materials') }}">Material Uploads</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle @if(in_array($segment, $validDisbursed)) active @endif" >Payment Disbursement</a>
                            <ul class="dropdown-menu fade-down">
                                <li>
                                    <a class="dropdown-item sm-fs-13" href="{{ url('/admin/disbursed/payment') }}">Disbursed Payment </a>
                                </li>
                                <li>
                                    <a class="dropdown-item sm-fs-13" href="{{ url('/admin/disbursed/pending') }}">Pending Disbursed Payment</a>
                                </li>
                                <li>
                                    <a class="dropdown-item sm-fs-13" href="{{ url('/admin/pending/payment-schedule') }}">Pending Payment Schedule</a>
                                </li>
                            </ul>
                        </li> 
                        <li class="nav-item top-header-nav">
                            <a class="nav-link @if($segmentx === 'users') active @endif"  href="{{ url('/admin/users') }}">Manage Admin Users </a>
                        </li>
                        <li class="nav-item top-header-nav">
                            <a class="nav-link @if($segment === 'settings') active @endif"  href="{{ url('/admin/settings') }}">Platform Settings </a>
                        </li>
                        <li class="nav-item top-header-nav">
                          <a class="nav-link" href="{{ url('/admin/logout') }}">Logout</a>
                        </li>
                      @endif
                    @endif
                  </ul>
               </div>
            </div>
         </nav>
      </div>
   </header>