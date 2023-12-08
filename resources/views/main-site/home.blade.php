@extends('template.master')

@section('content')

    <!--Slider -->
    <section id="mySliders">
       <div class="hero-section">
          <div id="slider-text" class="container">
             <div class="row">
                <div class="col-md-12 col-sm-12 col-12">
                   <div>
                      <div class="hero-content text-center">
                         <div class="hero-content-wrapper">
                            <div class="hero-title-div">
                               <h1 class="hero-title hero-title-animation wow fadeInLeft" data-wow-duration="1s"
                                  data-wow-delay="2s">Advertising At Your Finger Tips</h1>
                            </div>
                            <div class="hero-title-div">
                               <h1 class="hero-title typewriter wow fadeInRightBig" data-wow-duration="1s"
                                  data-wow-delay="2.5s">Start With Any Budget</h1>
                            </div>
                            {{-- <p class="slider-sub-title wow fadeInDownBig" data-wow-duration="1s" data-wow-delay="3s">
                               Choose your boards, upload your ads, make your impression - start with any budget.</p> --}}
                         </div>
                      </div>
                   </div>
                </div>
             </div>
          </div>

          <div class="hero-slider owl-carousel owl-theme">
             <div class="hero-single" style="background: url({{asset('img/hero/one.jpg')}})">
             </div>
             <div class="hero-single" style="background: url({{asset('img/hero/two.jpg')}})">
             </div>
             <div class="hero-single" style="background: url({{asset('img/hero/three.jpg')}})">
             </div>
             <div class="hero-single" style="background: url({{asset('img/hero/four.jpg')}})">
             </div>
             <div class="hero-single" style="background: url({{asset('img/hero/five.jpg')}})">
             </div>
             <div class="hero-single" style="background: url({{asset('img/hero/seven.jpg')}})">
             </div>
          </div>
       </div>
    </section>
    <!--Sider Ends-->

    <!--Search Area-->
    <section class="search-area-section">
       <div class="search-area wow fadeInUp" data-wow-duration="2s" data-wow-delay="2.5s">
          <div class="container">
             <div class="search-wrapper">
                <div class="search-nav">
                </div>
                <div class="tab-content" id="pills-tabContent">
                   <div class="tab-pane fade show active" id="pills-1" role="tabpanel" aria-labelledby="pills-tab-1"
                      tabindex="0">
                      <div class="search-form">
                         <form action="{{url('/asset/available')}}" method="POST">
                           @csrf
                           <p class="slider-sub-title modify">Choose your board</p>
                            <div class="row align-items-center">
                               <div class="col-lg-9">
                                  <div class="form-group">
                                     <input type="text" class="form-control" name="asset_location_search" placeholder="Location">
                                     <i class="far fa-location-dot"></i>
                                  </div>
                               </div>
                               <div class="col-lg-3">
                                  <button type="submit" class="theme-btn"><span class="far fa-search"></span>Search</button>
                               </div>
                            </div>
                         </form>
                      </div>
                   </div>
                </div>
             </div>
          </div>
       </div>
    </section>
    <!--Search Area Ends-->
    <!-- Counter-->
    <section id="counter-section" class="pt-40 pb-40 mt-10">
       <div class="container">
          <div class="row">
             <div class="col-md-12">
                <div class="row">
                   <div class="col-md-3 col-sm-6 col-6">
                      <div class="grid-counter-box slider-counter-box counter-box-odd wow flipInX"
                         data-wow-duration="1s" data-wow-delay=".75s">
                         <div class="slider-icon">
                            <i class="fal fa-user-friends"></i>
                         </div>
                         <div class="slider-icon-text">
                            <span class="counter" data-count="+" data-to="0" data-speed="3000">0</span>
                            <h6 class="title">Number Of Visitors</h6>
                         </div>
                      </div>
                   </div>
                   <div class="col-md-3 col-sm-6 col-6">
                      <div class="grid-counter-box slider-counter-box counter-box-even wow flipInY"
                         data-wow-duration="1s" data-wow-delay="1s">
                         <div class="slider-icon">
                            <i class="fal fa-presentation-screen"></i>
                         </div>
                         <div class="slider-icon-text">
                            <span class="counter" data-count="+" data-to="0" data-speed="3000">0</span>
                           {{-- <span class="counter" data-count="+" data-to="{{$asset_count}}" data-speed="3000">{{$asset_count}} --}}
                            <h6 class="title">Available Advertising Platforms</h6>
                         </div>
                      </div>
                   </div>
                   <div class="col-md-3 col-sm-6 col-6">
                      <div class="grid-counter-box slider-counter-box counter-box-odd wow flipInX"
                         data-wow-duration="1s" data-wow-delay="1.25s">
                         <div class="slider-icon">
                            <i class="fal fa-money-bill"></i>
                         </div>
                         <div class="slider-icon-text">
                            <span class="currency">₦</span><span class="counter currency-amount" data-count="+"
                               data-to="0" data-speed="3000">0</span>
                            {{-- <span class="currency">₦</span><span class="counter currency-amount" data-count="+"
                               data-to="{{$t_sum}}" data-speed="3000">{{number_format($t_sum, 2, '.', ',')}}</span> --}}
                            <h6 class="title">Volume Of Transactions (Naira)</h6>
                         </div>
                      </div>
                   </div>
                   <div class="col-md-3 col-sm-6 col-6">
                      <div class="grid-counter-box slider-counter-box counter-box-even wow flipInY"
                         data-wow-duration="1s" data-wow-delay="1.50s">
                         <div class="slider-icon">
                            <i class="fal fa-screen-users"></i>
                         </div>
                         <div class="slider-icon-text">
                            <span class="counter" data-count="+" data-to="0" data-speed="3000">0</span>
                            {{-- <span class="counter" data-count="+" data-to="{{$advertiser_count}}" data-speed="3000">{{$advertiser_count}}</span> --}}
                            <h6 class="title">Advertisers</h6>
                         </div>
                      </div>
                   </div>
                </div>
             </div>
          </div>
       </div>
    </section>
    <!--About GlobeTrotter-->
    <section id="about_us">
       <div class="about-area mt-60 pt-20 mb-100">
          <div class="container">
             <div class="row align-items-center">
                <div class="col-lg-6">
                   <div class="about-left wow fadeInLeftBig" data-wow-duration="2s" data-wow-delay=".25s">
                      <div class="about-img">
                         <img src="{{asset("img/about/one.jpg")}}" alt="">
                      </div>
                      <div class="about-shape">
                         <img src="{{asset("img/shape/01.svg")}}" alt="">
                      </div>
                   </div>
                </div>
                <div class="col-lg-6">
                   <div class="about-right wow fadeInRightBig" data-wow-duration="2s" data-wow-delay=".25s">
                      <div class="site-heading mb-3">
                         <span class="site-title-tagline pr">About {{ config('app.name') }}</span>
                         <h2 class="site-title">Digital Solution for Out-of-Home Advertising</h2>
                      </div>
                      <p class="about-text"><strong>{{ config('app.name') }}<sup style="font-size: 9px;">TM</sup></strong> is a digital platform by {{env('APP_OWNER_NAME')}} that aggregates over 25,000 billboards and out of home advertising assets of all Outdoor Advertising Association of Nigeria members.
                        {{-- {{number_format($asset_count, 0, '.', ',')}} --}}
                         {{-- and out of home advertising assets of the 165 Outdoor
                         Advertising Association of Nigeria members, Advertisers, media buyers and the General public
                         into a very interactive digital marketplace. --}}
                      </p>
                      <div class="about-list-wrapper">
                         <ul class="about-list list-unstyled">
                            <li>
                               <div class="about-icon"><span class="fas fa-check-circle"></span></div>
                               <div class="about-list-text">
                                  <p>Online access by Advertisers and Asset Owners</p>
                               </div>
                            </li>
                            <li>
                               <div class="about-icon"><span class="fas fa-check-circle"></span></div>
                               <div class="about-list-text">
                                  <p>Uploading of billboard location, availability, and pricing by Asset owners</p>
                               </div>
                            </li>
                            <li>
                               <div class="about-icon"><span class="fas fa-check-circle"></span></div>
                               <div class="about-list-text">
                                  <p>Finding billboards and making payments by Advertisers</p>
                               </div>
                            </li>
                         </ul>
                      </div>
                      <div class="about-bottom">
                         <div class="about-call">
                            <div class="about-call-icon">
                               <i class="fal fa-user-headset"></i>
                            </div>
                            <div class="about-call-content">
                               <span>Call Us Anytime</span>
                               <h5 class="about-call-number"><a href="tel:{{env('APP_CUSTOMER_CARE_PHONE', '+234-813-2614337')}}">{{env('APP_CUSTOMER_CARE_PHONE', '+234-813-2614337')}}</a></h5>
                            </div>
                         </div>
                      </div>
                   </div>
                </div>
             </div>
          </div>
       </div>
    </section>
    <!--About GlobeTrotter Ends-->
    <!--Why Choose Us-->
    <section id="why-choose-us">
       <div class="choose-area pt-40 pb-40">
          <div class="container">
             <div class="row">
                <div class="col-lg-6 mx-auto wow fadeInDown" data-wow-duration="1s" data-wow-delay=".25s">
                   <div class="site-heading text-center">
                      {{-- <span class="site-title-tagline">{{ strtoupper(config('app.name')) }}</span> --}}
                      <h2 class="site-title">WHY USE {{ strtoupper(config('app.name')) }}</h2>
                   </div>
                </div>
             </div>
             <div class="choose-wrapper">
                <div class="row justify-content-center">
                   <div class="col-md-6 col-lg-4">
                      <div class="choose-item wow bounceInLeft" data-wow-duration="2s" data-wow-delay=".25s">
                         <div class="choose-icon">
                            <i class="fal fa-headset"></i>
                         </div>
                         <h4 class="choose-title">Best Service Guarantee</h4>
                         <p>24/7 customer support for client to chat with our
                            representative.</p>
                      </div>
                   </div>
                   <div class="col-md-6 col-lg-4">
                      <div class="choose-item wow bounceInUp" data-wow-duration="2s" data-wow-delay=".50s">
                         <div class="choose-icon">
                            <i class="fal fa-gift"></i>
                         </div>
                         <h4 class="choose-title">Exclusive Media Deals</h4>
                         <p>Take advantage of our exclusive advertising bargain.</p>
                      </div>
                   </div>
                   <div class="col-md-6 col-lg-4">
                      <div class="choose-item wow bounceInRight" style="position: relative;" data-wow-duration="2s" data-wow-delay=".75s">
                         <div class="choose-icon">
                            <i class="fal fa-credit-card"></i>
                         </div>
                         <h4 class="choose-title">Payment Made Easy</h4>
                         <p>Make payment from the comfort of your home.</p>
                         <span style="width: 90px;display: block;position: absolute;right: 17px;bottom: 19px;"><img src="img/partner/1.png" alt="thumb"></span>
                      </div>
                   </div>
                </div>
             </div>
          </div>
       </div>
    </section>
    <!--Why Choose Us Ends-->
    <!--Partners-->
    <section id="partners-slide">
       <div class="partner-area bg bg-new pt-0 pb-0">
          <div class="container">
            {{--  --}}
             <div class="partner-wrapper partner-sliderx">
               <p style="font-size: 18px;font-weight: bolder;color: #112039;position: relative;padding: 13px 40px;">
                  <img style="position: absolute;z-index: 11;left: -69px;top: 6px;" src="img/partner/3.png" alt="thumb">
                  <span style="z-index: 999;position: relative;">Powered by Datashare Services Limited.</span>
               </p>
                {{-- <img src="img/partner/1.png" alt="thumb">
                <img src="img/partner/2.png" alt="thumb"> --}}
                
                <!--<img src="img/partner/04.png" alt="thumb">
             <img src="img/partner/01.png" alt="thumb">
             <img src="img/partner/02.png" alt="thumb">
             <img src="img/partner/03.png" alt="thumb">-->
             </div>
          </div>
       </div>
    </section>
    <!--Partners End-->


@endsection