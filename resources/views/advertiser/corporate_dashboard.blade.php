@extends('template.master')

@section('content')
<style>
    .banner-wdget .overlay {
        opacity: 0.5;
    }

    .btn i, .btn {
        height: 54px;
        line-height: 54px;
    }

    .btn {
        font-size: 15px;
        text-transform: uppercase;
    }

    .flat-header {
        padding-top: 158px !important;
    }

    .dasboard-breadcrumbs {
        top: -32px !important;
    }

    .centralize {
        padding: 40px;
        display: block;
        background: #fff;
    }

    .centralize h3 {
        font-size: 50px;
        color: #a5a5a5;
    }

    .info-boxes {
        padding: 25px 25px; 
        text-align: center;
    }

    .otp-item {
        position: relative;
        box-sizing: border-box;
        background: #ffffff;
    }

    .otp-item p {
        border-top: 1px solid #eee;
        margin: 0 0 0 8px;
        height: 44px;
        line-height: 44px;
    }

    .otp-item:nth-child(1) p {
        border: 0;
    }


    input[name="toggle_otp_item"] {
        display: none;	
    }

    .toggle-otp {
        position: absolute;
        top: 0;
        bottom: 0;
        right: 9px;
        margin: auto;
        width: 51px;
        height: 31px;
    }

    .toggle-otp label,
    .toggle-otp i {
        box-sizing: border-box;
        display: block;
        background: #ffffff;
    }

    .toggle-otp label {
        width: 51px;
        height: 32px;
        border-radius: 32px; 
        border: 2px solid #e5e5e5;
        transition: all 0.30s ease;
        position: static !important;
    }

    .toggle-otp i {
        position: absolute !important;
        top: 2px !important;
        left: 2px !important;
        width: 28px;
        height: 28px;
        border-radius: 28px;
        box-shadow: 0 0 1px 0 rgba(0,0,0, 0.25),
                    0 3px 3px 0 rgba(0,0,0, 0.15);
        background: #ffffff;
        transition: all 0.3s cubic-bezier(0.275, -0.450, 0.725, 1.450);
    }

    input[name="toggle_otp_item"]:active + .toggle-otp i {
        width: 35px !important;
    }

    input[name="toggle_otp_item"]:active + .toggle-otp label,
    input[name="toggle_otp_item"]:checked + .toggle-otp label {
        border: 16px solid #4cd964;
    }

    input[name="toggle_otp_item"]:checked + .toggle-otp i {
        left: 21px !important;
    }

    input[name="toggle_otp_item"]:checked:active + .toggle-otp label {
        border: 16px solid #e5e5e5;
    }

    input[name="toggle_otp_item"]:checked:active + .toggle-otp i {
        left: 14px !important;
    }
</style>

<div class="site-breadcrumb login-breadcrumb dashboard-breadcrumb">
    <div class="container">
        <h2 class="breadcrumb-title dashboard-breadcrumb-menu-left">Corporate Transaction History</h2>
        <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
            <li><a href="{{url()->previous()}}">Back</a></li>
        </ul>
    </div>
</div>
<section id="advertiser-dashboard">
    <div class="user-profile pt-40 pb-40 dashboard-bg-color">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="user-profile-wrapper wow fadeIn" data-wow-duration="2.5s" data-wow-delay=".25s">
                        <div class="user-profile-card add-property">
                            <h4 class="user-profile-card-title">Transaction History</h4>
                            <div class="row">
                                <div class="col-md-12" style="padding-top: 25px; padding-left: 0; padding-right: 2px;">
                                    <div class="list-single-main-item fl-wrap" id="sec3">
                                        <div class="listing-features fl-wrap">
                                            <canvas id="trans-hist-chart" width="800" height="250"></canvas>
                                        </div>
                                        <span class="mb-20"></span>
                                        <p class="fs-13 mt-20">Tracking your expenditure to help you budget more.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 info-boxes">
                                <a href="#" class="centralize">
                                    <h3>{{$paid_trans}}</h3>
                                    <p>Paid Transaction</p>
                                </a>
                            </div>

                            <div class="col-md-3 info-boxes">
                                <a href="#" class="centralize">
                                    <h3>{{$pending_trans}}</h3>
                                    <p>Pending Transaction</p>
                                </a>  
                            </div>

                            <div class="col-md-3 info-boxes">
                                <a href="#" class="centralize">
                                    <h3>{{$booked_sites}}</h3>
                                    <p>Booked Site</p>
                                </a>
                            </div>

                            <div class="col-md-3 info-boxes">
                                <a href="#" class="centralize">
                                    <h3>0</h3>
                                    <p>Material Upload</p>
                                </a>
                            </div>
                        </div>
                        <div class="user-profile-card add-property mt-20">
                            <div class="row">
                                @if ($user->corp_id && $user->admin === 1)
                                <h4 class="user-profile-card-title">Transaction OTP Management</h4>
                                <div class="col-md-12">
                                    <div class="listing-features fl-wrap">
                                        <div class="custom-form">
                                            <label class="fs-14">Transactional OTP(One Time Password) are used to grant permission to staff of a company to make payment for a booked site on this platform as a corporate body.</label>
                                            <p>&nbsp;</p>
                                            <div class="otp-item">
                                                <p class="fs-13">Do you want activate OTP validation for staffs?</p>
                                                <input type="checkbox" id="toggle_otp_item" name="toggle_otp_item" value="Yes">
                                                <div class="toggle-otp">
                                                    <label for="toggle_otp_item"><i></i></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection