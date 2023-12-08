@extends('template.master')

@section('content')
<style>
    .user-profile-card-title {
        padding-bottom: 2px;
    }
    .body-content .content p {
        color: #000;
        text-align: justify;
    }

    .body-content .copy {
        padding: 3px 0px 3px 10px;
        display: flex;
        flex-flow: row nowrap;
        justify-content: flex-start;
        border: 1px solid #c1c1c1;
        align-items: center;
        color: #fff;
        border-radius: 5px;
    }

    .body-content .copy p {
        margin: 0;
        padding-bottom:0;
        width: 90%;
        text-align: left;
        color: #000;
    }

    .body-content .copy a {
        display: inline-block;
        font-size: 16px;
        width: 38px;
        height: 100%;
        background-color: #080f1c;
        color: #fff;
        margin: 0 5px;
        border-radius: 7px;
        padding: 2px 0;
        float: left;
        text-align: center;
    }

    .hide-defualt {
        display: none;
    }

    .amt-payable-edit {
        float:right;
        border-top:1px solid #d3d3d3;
        border-bottom:1px solid #d3d3d3;
        padding:2px 0;
        min-width:125px;
        max-width:200px;
    }

    .amt-payable-edit:after {
        content: '|';
        animation-name:blink;
        animation-duration:1s;
        animation-iteration-count:infinite;
        animation-timing-function:ease;
    }

    .amt-payable-edit.is-focus:after {
        content: "";
    }

    @keyframes blink {
        0% { opacity: 1 }
        100% { opacity: 0 }
    }

    @media print {
        header, footer, .hide-print, #scroll-top, .hide_print {
            display: none !important;
        }

        .user-profile-card {
            box-shadow: none;
        }

        .dashboard-bg-color {
            background-color: #fff;
        }

        .invoice-wrapper {
            padding-top: 20px !important;
            border: none;
        }
    }
</style>
<?php
    $names = $user->firstname ? $user->lastname.' '.$user->firstname : $user->corporate_name;

    $email = $user->email ? $user->email : env("APP_ALT_CUSTOMER_EMAIL");

    $phone = $user->phone ? $user->phone : env("APP_ALT_CUSTOMER_PHONE");
?>
<div class="site-breadcrumb login-breadcrumb dashboard-breadcrumb hide-print">
    <div class="container">
        <h2 class="breadcrumb-title dashboard-breadcrumb-menu-left">Pending Transactions</h2>
        <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
            <li><a href="{{url('/advertiser/individual/dashboard')}}">Dashboard</a></li>
            <li>Transactions</li>
            <li class="active-nav-dashboard">Pending Transactions</li>
        </ul>
    </div>
</div>
<section id="advertiser-dashboard">
    <div class="user-profile pt-40 pb-40 dashboard-bg-color">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="user-profile-wrapper wow fadeIn" data-wow-duration="2.5s" data-wow-delay=".25s">
                        @if ( count($asset_pending_recs) )
                        <div class="login-form add-campaign-div wow fadeInUp hide-print" data-wow-duration="2.5s">
                            <div class="row">
                            <div class="col-md-8">
                                    <h4 class="welcome-text fs-15 create-campaign-text">Pending Transactions</h4>
                            </div>
                                <div class="col-md-4">
                                    <div class="create-campaign-btn-div text-right text-align-left">
                                        @if ( count($asset_pending_recs[0]->payment_records) || count($asset_pending_recs[0]->pending_payment_records))
                                        <a href="#" class="modal-open-pending-payment-schedule-wrap"
                                            data-booking-id="{{ $asset_pending_recs[0]->id }}"
                                            data-asset-name="{{ $asset_pending_recs[0]->asset->name }}"
                                            data-booked-ref="{{ $asset_pending_recs[0]->trnx_id }}"
                                            data-actualbal="{{ number_format(str_replace(',', '', $asset_pending_recs[0]->payment_remaining), 2, '.', ',') }}">
                                            <button class="theme-btn create-campaign-btn fs-14"><span class="create-campaign-icon fal fa-plus"></span> Generate Payment Schedule</button>
                                        </a>
                                        @else
                                            <a href="#" class="modal-open-payment-schedule-wrap"
                                            data-booking-id="{{ $asset_pending_recs[0]->id }}"
                                            data-asset-name="{{ $asset_pending_recs[0]->asset->name }}"
                                            data-booked-ref="{{ $asset_pending_recs[0]->trnx_id }}"
                                            data-actualbal="{{ number_format(str_replace(',', '', $asset_pending_recs[0]->payment_remaining), 2, '.', ',') }}">
                                                <button class="theme-btn create-campaign-btn fs-14"><span class="create-campaign-icon fal fa-plus"></span> Generate Initial Payment Schedule</button>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ( $errors->any() )
                        <div class="alert alert-danger fs-13 p-3 mb-0 mt-20 hide-print">
                            <ul>
                                @foreach( $errors->all() as $error )
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        @if (session()->has('flash_message'))
                        <div class="alert alert-success fs-13 p-3 mb-0 mt-20 hide-print" style="text-align: left;">
                            <strong>{{session()->get('flash_message')}}</strong>
                        </div>
                        @endif

                        @if ( count($asset_pending_recs[0]->pending_payment_records) )
                        <div class="user-profile-card add-property mt-30 hide-print">
                            <h4 class="user-profile-card-title">Pending Payment Schedule</h4>
                            <div class="col-lg-12 pending-transactions-table-div pt-10 pb-10">
                                <table class="pending-transactions-table">
                                    <thead class="">
                                        <tr>
                                            <th class="fs-13">Payment ID</th>
                                            <th class="fs-13" width="35%">Description</th>
                                            <th class="fs-13">Amount</th>
                                            <th class="fs-13">% Scheduled</th>
                                            <th class="fs-13">First Payment?</th>
                                            <th class="fs-13">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($asset_pending_recs[0]->pending_payment_records as $key => $pending_payment_record)
                                        <tr>
                                            <td class="fs-14 sm-fs-12">{{$pending_payment_record->tranx_id}}</td>
                                            <td class="fs-14 sm-fs-12" style="padding-right: 50px;
                                            text-align: justify;">{{$pending_payment_record->description}}</td>
                                            <td class="fs-14 sm-fs-12">&#8358;{{ number_format(str_replace(',', '', $pending_payment_record->amount), 2, '.', ',') }}</td>
                                            <td class="fs-14 sm-fs-12">{{$pending_payment_record->percentage}}</td>
                                            <td class="fs-14 sm-fs-12"><span>{{$pending_payment_record->first_pay?'Yes':'No'}}</span></td>
                                            <td class="fs-14 sm-fs-12">{{ \Carbon\Carbon::parse($pending_payment_record->created_at)->format('jS F Y') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @else 
                        <div class="login-form add-campaign-div mt-30 wow fadeInUp hide-print" data-wow-duration="2.5s">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="welcome-text fs-15 create-campaign-text">No Pending Payment Schedule Found.</h4>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if ( count($asset_pending_recs[0]->payment_records) )
                        <div class="user-profile-card add-property mt-30 hide-print">
                            <h4 class="user-profile-card-title">Paid Schedule</h4>
                            <div class="col-lg-12 pending-transactions-table-div pt-10 pb-10">
                                <table class="pending-transactions-table">
                                    <thead class="">
                                        <tr>
                                            <th class="fs-13">Payment ID</th>
                                            <th class="fs-13" width="35%">Description</th>
                                            <th class="fs-13">Amount</th>
                                            <th class="fs-13">% Paid</th>
                                            <th class="fs-13">First Payment?</th>
                                            <th class="fs-13">Bank Reference</th>
                                            <th class="fs-13">Date</th>
                                            <th class="fs-13">% Remaining</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($asset_pending_recs[0]->payment_records as $key => $payment_record)
                                        <tr>
                                            <td class="fs-14 sm-fs-12">{{$payment_record->tranx_id}}</td>
                                            <td class="fs-14 sm-fs-12" style="padding-right: 50px;
                                            text-align: justify;">{{$payment_record->description}}</td>
                                            <td class="fs-14 sm-fs-12">&#8358;{{ number_format(str_replace(',', '', $payment_record->amount), 2, '.', ',') }}</td>
                                            <td class="fs-14 sm-fs-12">{{$payment_record->percentage}}</td>
                                            <td class="fs-14 sm-fs-12"><span>{{$payment_record->first_pay?'Yes':'No'}}</span></td>
                                            <td class="fs-14 sm-fs-12">{{$payment_record->bank_ref_number}}</span></td>
                                            <td class="fs-14 sm-fs-12">{{ \Carbon\Carbon::parse($payment_record->created_at)->format('jS F Y') }}</td>
                                            <td class="fs-14 sm-fs-12">{{$asset_pending_recs[0]->payment_remaining_perc . '%'}}</span></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @else 
                        <div class="login-form add-campaign-div wow fadeInUp mt-30 hide-print" data-wow-duration="2.5s">
                            <div class="row">
                                <div class="col-md-8">
                                        <h4 class="welcome-text fs-15 create-campaign-text">No `Paid` Payment Schedule Found.</h4>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if ( count($asset_pending_recs) && count($asset_pending_recs[0]->pending_payment_records) )
                        <?php $asset_pending_rec = $asset_pending_recs[0]; ?>
                        <div class="user-profile-card add-property mt-30">
                            <div class="user-profile-card-title" style="border-bottom: none;">
                                <div class="row">
                                    <div class="col-md-8 hide-print">
                                        <h4>Transaction Deal Slip</h4>
                                    </div>
                                    <div class="col-md-4 hide-print">
                                        <div class="text-right text-align-left">
                                            <a href="#" class="theme-btn theme-btn-custom print-dealslip"><i class="far fa-print"></i> Print Deal Slip</a>
                                        </div>
                                    </div>

                                    <div class="col-md-7">
                                        @foreach ($asset_pending_rec->pending_payment_records as $key => $pending_payment_record)
                                        <div class="invoice-wrapper" id="invoice-wrapper">
                                            <table>
                                                <tbody>
                                                    <tr class="top">
                                                        <td colspan="2">
                                                            <table class="mb-50">
                                                                <tbody><tr>
                                                                    <td class="title">
                                                                        <div style="width: 176px; padding: 11px;">
                                                                            <img src="{{ asset('img/logo/logo.png') }}" style="width:150px; height:auto" alt="">
                                                                        </div>
                                                                    </td>
                                                                    <td class="text-right">
                                                                        Invoice #: {{ str_pad(($key+1), 6, '0', STR_PAD_LEFT) }}<br>
                                                                        Date: {{ \Carbon\Carbon::parse($pending_payment_record->created_at)->format('F j, Y') }}<br>
                                                                    </td>
                                                                </tr>
                                                            </tbody></table>
                                                        </td>
                                                    </tr>
                                                    <tr class="information">
                                                        <td colspan="2">
                                                            <table class="mb-60">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="fs-15">
                                                                            {{config('app.name')}}, Inc.<br>
                                                                            {{env('APP_ADDRESS')}}<br>
                                                                            <a href="#" style="color:#666; text-decoration:none">{{ strtolower(env('APP_CUSTOMER_CARE_EMAIL'))}}</a>
                                                                            <br>
                                                                            <a href="#" style="color:#666; text-decoration:none">{{env("APP_CUSTOMER_CARE_PHONE")}}</a>                                
                                                                        </td>
                                                                        <td class="text-right">
                                                                            {{$names}}<br>
                                                                            <a href="#" style="color:#666; text-decoration:none">{{$email}}</a>
                                                                            <br>
                                                                            @if($phone)
                                                                                <a href="#" style="color:#666; text-decoration:none">{{'+234' . $phone}}</a>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr class="heading">
                                                        <td>Option</td>
                                                        <td class="text-right">Details</td>
                                                    </tr>
                                                    <tr class="item">
                                                        <th class="fs-13">Site Name</th>
                                                        <td class="fs-14">{{$asset_pending_rec->asset->name}}</td>
                                                    </tr>
                                                    <tr class="item">
                                                        <th class="fs-13">Reservation ID</th>
                                                        <td class="fs-14">{{$asset_pending_rec->trnx_id}}</td>
                                                    </tr>
                                                    <tr class="item">
                                                        <th class="fs-13">Merchant Transaction Reference</th>
                                                        <td class="fs-14">{{$pending_payment_record->tranx_id}}</td>
                                                    </tr>
                                                    <tr class="item ">
                                                        <th class="fs-13">Advert Start Date</th>
                                                        <td class="fs-14">{{ \Carbon\Carbon::parse($asset_pending_rec->start_date)->format('jS \o\f F Y') }}</td>
                                                    </tr>
                                                    <tr class="item">
                                                        <th class="fs-13">Advert End Date</th>
                                                        <td class="fs-14">{{ \Carbon\Carbon::parse($asset_pending_rec->end_date)->format('jS \o\f F Y') }}</td>
                                                    </tr>
                                                    <tr class="item">
                                                        <th class="fs-13">Asset Dimension</th>
                                                        <td class="fs-14">{{ $asset_pending_rec->asset->asset_dimension_width . 'm x ' .$asset_pending_rec->asset->asset_dimension_height . 'm' }}</td>
                                                    </tr>
                                                    <tr class="item">
                                                        <th class="fs-13">Actual Price</th>
                                                        <td class="fs-14">&#8358;{{ number_format(str_replace(',', '', $asset_pending_rec->asset->max_price), 2, '.', ',') }}</td>
                                                    </tr>
                                                    <tr class="item last">
                                                        <th class="fs-13">Total Amount Paid</th>
                                                        <td class="fs-14">&#8358;{{ number_format(str_replace(',', '', $asset_pending_rec->payment_total), 2, '.', ',') }}</td>
                                                    </tr>
                                                    <tr class="total">
                                                        <td></td>
                                                        <th class="fs-13 text-right">Total Balance: &#8358;{{ number_format(str_replace(',', '', $asset_pending_rec->payment_remaining), 2, '.', ',') }}</th>
                                                    </tr>
                                                    @if($pending_payment_record->percentage)
                                                    <tr class="last">
                                                        <th class="fs-13">An outstanding payment schedule 
                                                            <small style="font-size:13px;">of {{$pending_payment_record->percentage}} off &#8358;{{ number_format(str_replace(',', '', $asset_pending_rec->payment_remaining), 2, '.', ',') }}
                                                            </small> is ~ &#8358;{{ number_format(str_replace(',', '', $pending_payment_record->amount), 2, '.', ',') }}
                                                        </th>
                                                        <td class="fs-14 text-right mt-5">&nbsp;</td>
                                                    </tr>
                                                    @endif

                                                    <tr class="pt-20">
                                                        <td>
                                                            <a href="{{url('advertiser/individual/pending/transaction/payments/regenerate-reference/' . $asset_pending_rec->id.'/'.$pending_payment_record->id)}}" class="mt-20 theme-btn theme-btn-custom regenerate-trnx hide-print">
                                                            <i class="fal fa-redo"></i> &nbsp;&nbsp;Regenerate Merchant Transaction Reference</a>
                                                        </td>
                                                        <td class="text-right">
                                                            <div class="payment-button hide-print">
                                                                <div class="collapse navbar-collapse" style="display:block">
                                                                    <ul class="navbar-nav">
                                                                        <li class="nav-item dropdown">
                                                                            <a class="nav-link dropdown-toggle active" href="#" data-bs-toggle="dropdown">Make Payment</a>
                                                                            <ul class="dropdown-menu fade-down bank_ul">
                                                                            <li><a class="dropdown-item sm-fs-13" href="javascript://"  data-formid="global-pay">Zenith Bank (Global Pay)</a></li>
                                                                            <li><a class="dropdown-item sm-fs-13" href="javascript://" data-formid="open-payment-token-modal" data-txref="{{$pending_payment_record->tranx_id}}" data-tid="{{$pending_payment_record->id}}">Nibss</a></li>
                                                                            </ul>
                                                                        </li>
                                                                    </ul>
                                                                </div>

                                                                {{-- Process Global Pay Integration. --}}
                                                                <form id="global-pay" target="_blank" action="{{env("APP_GLOBAL_PAY_FORM_PAYMENT_URL")}}" method="post">
                                                                    <input type="hidden" id="names" name="names" value="{{ $names }}">
                                                                    <input type="hidden" id="amount" name="amount" value="{{$pending_payment_record->amount}}">
                                                                    <input type="hidden" id="email_address" name="email_address" value="{{$email}}">
                                                                    <input type="hidden" id="phone_number" name="phone_number" value="{{$phone}}">
                                                                    <!-- Please change the currency as suited to your merchant-->
                                                                    <input type="hidden" id="currency" name="currency" value="{{env("APP_GLOBAL_PAY_CURRENCY")}}">
                                                                    <input type="hidden" id="merch_txnref" name="merch_txnref" value="{{$pending_payment_record->tranx_id}}"><!-- Merchant reference number should be unique per transaction-->
                                                                    <!-- Merchant Id value should be changed to your merchant id-->
                                                                    {{--  --}}
                                                                    <input type="hidden" id="merchantid" name="merchantid" value="{{env("APP_GLOBAL_PAY_MERCHANT_ID")}}">
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @else
                        <div class="user-profile-card add-property">
                            <h4 class="user-profile-card-title">Pending Transactions</h4>
                            <div class="col-lg-12 pending-transactions-table-div pt-10 pb-10">
                                <div>No Booking Record Found.</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Payment Token Generator --}}
    <div class="main-payment-token modal fade" id="main-payment-token" tabindex="-1" role="dialog" aria-labelledby="main-payment-token-generator" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="main-payment-token-generator">Payment Token Generator</h5>
                </div>
                <div class="modal-body body-content">
                    <form id="otp-generator-form" action="{{url('advertiser/individual/generate/nibbs-transaction-code')}}" method="get">
                        <div class="info fs-13 mb-10">You will need to generate a payment token or code to make and confirm your payment on the Nibss ebills pay platform</div>
                        
                        @if ( session()->has('otp-failed') )
                            <div class="alert alert-danger fs-13 p-2">
                                <strong>{{session()->get('otp-failed')}}</strong>
                            </div>
                        @endif

                        @if (session()->has('otp-success'))
                            <div class="alert alert-success fs-13 p-2">
                                <strong>{{session()->get('otp-success')}}</strong>
                            </div>
                        @endif

                        <div class="copy mt-10">
                            @if (session()->has('code'))
                                <p class="fs-13">Copy your token now  &ldrdhar;  {{session()->get('code')}}</p>
                            @else
                                <p class="fs-13">Your generated code or token will appear here</p>
                            @endif
                            <a href="#" id="generate-nibbs-code" title="Click to generate code"><i class="far fa-magic"></i></a>
                            <input type="hidden" id="txref" name="txref" value="{{old('txref')}}">
                            <input type="hidden" id="tid" name="tid" value="{{old('tid')}}">
                            <a href="#" id="copy-nibbs-code" style="display: none;"><i class="far fa-copy"></i></a>
                        </div>

                        <p class="fs-13 text-left pl-20">Kindly refresh or reload to close the token window</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- END Payment Token Generator END --}}

    {{-- First payment schedule --}}
    <div class="main-payment-schedule-wrap modal fade" id="main-payment-schedule" tabindex="-1" role="dialog" aria-labelledby="main-payment-schedule" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Media Purchase Order</h5>
                </div>
                <div class="modal-body body-content">
                    <input type="hidden" name="reserve_id" id="reserve_id">
                    <input type="hidden" name="booking_id" id="booking_id">
                    <input type="hidden" name="actual_bal" id="actual_bal">
                    <div class="content">
                        <div class="law-context hide_print" style="padding: 5px; max-height:450px; min-height:200px; overflow-y:scroll;text-align: justify;">
                            <h2 class="fs-16 mb-10">Asset Media Purchase Order</h2>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Reference</th>
                                        <th>Dimension</th>
                                        <th>Material Type</th>
                                        <th>Board Type</th>
                                        <th>Location</th>
                                        <th>Total Price</th>
                                        <th>Qty</th>
                                        <th>Start - End Date</th>
                                        <th>Sub Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $real_asset_detail = $asset_pending_recs[0]->real_asset_details; ?>
                                    @if ($real_asset_detail)
                                        <tr>
                                            <td>{{ $real_asset_detail->name }}</td>
                                            <td>{{ $real_asset_detail->asset_dimension_width . "m &times; ". $real_asset_detail->asset_dimension_height . "m" }}</td>
                                            <td>{{ $real_asset_detail->substrate }}</td>
                                            <td>{{ $real_asset_detail->asset_category }}</td>
                                            <td>{{ $real_asset_detail->address }}</td>
                                            <td>&#8358;{{ $asset_pending_recs[0]->price }}</td>
                                            <td>1</td>
                                            <td>{{ $asset_pending_recs[0]->start_date . " - " .  $asset_pending_recs[0]->end_date}}</td>
                                            <td>{{ 1 * floatval(str_replace(",", "", $asset_pending_recs[0]->price)) }}</td>
                                        </tr>
                                    @else
                                        <tr><td colspan="9">No Media Purchse Order Found</td></tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="payment-sched-printable-content" id="payment-sched-printable-content"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="hide-defualt">
                        <a href="#" class="action-button btn btn-light hide_dismiss">Close <i class="fal fa-times"></i></a>
                    </div>
                    <div class="hide-defualt">
                        <a href="#" class="action-button btn btn-info payment-sched-printable-btn">Print Payment Slip <i class="fal fa-print"></i></a>
                    </div>
                    <div class="hide_print d-flex justify-content-end">
                        <a href="#" class="action-button btn theme-btn theme-btn-custom generate_tranx"><i class="fal fa-check"></i> I AGREE</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- END First payment schedule END --}}


    <div class="pending-payment-schedule-wrap modal fade" id="pending-payment-schedule" tabindex="-1" role="dialog" aria-labelledby="pending-payment-schedule" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">First Payment Schedule Generator</h5>
                </div>
                <div class="modal-body body-content">
                    <input type="hidden" name="reserve_id" id="reserve_id">
                    <input type="hidden" name="booking_id" id="booking_id">
                    <input type="hidden" name="actual_bal" id="actual_bal">
                    <div class="content">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hide_print">
                            <table style="width: 100%;">
                                <tr>
                                    <th class="fs-14">Asset Name</th>
                                    <td class="fs-14 text-right"><span id="asset_name_read"></span></td>
                                </tr>
                                <tr>
                                    <th class="fs-14">Reserve ID</th>
                                    <td class="fs-14 text-right"><span id="reserve_id_read"></span></td>
                                </tr>
                                <tr>
                                    <th class="fs-14">Balance Amount</th>
                                    <td class="fs-14 text-right"><span id="bal_amt_read"></span></td>
                                </tr>
                                <tr>
                                    <th class="fs-14">Amount Payable (&#8358;)</th>
                                    <td class="fs-14 text-right">
                                        <span contenteditable="true"
                                        class="amt-payable-edit" focus="true"
                                        id="amt_payable" placeholder="0"></span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="payment-sched-printable-content" id="payment-sched-printable-content"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="hide-defualt">
                        <a href="#" class="action-button btn btn-light hide_dismiss">Close <i class="fal fa-times"></i></a>
                    </div>
                    <div class="hide-defualt">
                        <a href="#" class="action-button btn btn-info payment-sched-printable-btn">Print Payment Slip <i class="fal fa-print"></i></a>
                    </div>
                    <div class="hide_print d-flex justify-content-end">
                        <a href="#" class="action-button theme-btn theme-btn-custom generate_tranx"><i class="fal fa-check"></i> Generate Payment Schedule</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>

@endsection