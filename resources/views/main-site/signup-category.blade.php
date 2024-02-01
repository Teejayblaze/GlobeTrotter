@extends('template.master')

@section('content')
    <section id="category-section" class="login-area category-page pt-200 pb-200">
        <div class="category-area">
        <div class="container category-div">
            <div class="row">
                <div class="col-lg-6 mx-auto wow fadeInDown" data-wow-duration="1s" data-wow-delay=".25s">
                    <div class="site-heading text-center category-site-heading">
                    <h2 class="site-title category-site-title">Click To Select Account Type</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="category-item wow rotateIn" data-wow-duration="1s" data-wow-delay=".50s">
                    <a href="{{ url('/operator/signup') }}">
                        <div class="category-icon">
                            <i class="fal fa-user"></i>
                        </div>
                        <div class="category-content">
                            <h4 class="category-title">{{env("APP_OWNER_NAME")}} Member</h4>
                        </div>
                    </a>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="category-item wow fadeInLeftBig" data-wow-duration="1s" data-wow-delay="1s">
                    <a href="{{ url('/advertiser/individual') }}">
                        <div class="category-icon">
                            <i class="fal fa-image-user"></i>
                        </div>
                        <div class="category-content">
                            <h4 class="category-title">Individual Advertiser</h4>
                        </div>
                    </a>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="category-item wow flipInX" data-wow-duration="1s" data-wow-delay="1.25s">
                    <a href="{{ url('/advertiser/corporate') }}">
                        <div class="category-icon">
                            <i class="fal fa-images-user"></i>
                        </div>
                        <div class="category-content">
                            <h4 class="category-title">Corporate Advertiser</h4>
                        </div>
                    </a>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="category-item wow fadeInRightBig" data-wow-duration="1s" data-wow-delay="1.50s">
                    <a href="{{ url('/advertiser/government') }}">
                        <div class="category-icon">
                            <i class="fal fa-users"></i>
                        </div>
                        <div class="category-content">
                            <h4 class="category-title">Government</h4>
                        </div>
                    </a>
                    </div>
                </div>
                </div>
        </div>
        </div>
    </section>
@endsection