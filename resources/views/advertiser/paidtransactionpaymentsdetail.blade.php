@extends('reformedtemplate.master')

@section('content')
<link type="text/css" rel="stylesheet" href="{{ asset("vendor/reformedtemplate/css/invoice.css") }}">
    <style>
        .listing-features li {
            width: 12.5%;
            padding-left: 0;
        }

        .lighter ul li {
            font-weight: 100;
        }

        .new-dashboard-item {
            right: 0;
            top: -4px;
        }
        .x1 {bottom: 77px;}
        .x2 {top: 60px;}
        .city-bg {background: url("{{asset('vendor/reformedtemplate/images/city.png')}}") repeat-x 0 194px;}

    </style>
    <div id="wrapper">
        <!-- content-->
        <div class="content">
            <!-- section-->
            <section class="flat-header color-bg adm-header hide-print">
                <div class="city-bg"></div>
                <div class="cloud-anim cloud-anim-bottom x1"><i class="fal fa-cloud"></i></div>
                <div class="cloud-anim cloud-anim-top x2"><i class="fal fa-cloud"></i></div>
                <div class="container">
                    <div class="dasboard-wrap fl-wrap">
                        <div class="dasboard-breadcrumbs breadcrumbs"><a href="{{ url("/advertiser/individual/dashboard") }}">Dashboard</a><a href="{{ url("/advertiser/individual/transactions/historical") }}">Historical Transaction</a><span>Payments</span></div>
                    
                    </div>
                </div>
            </section>
            <!-- section end-->
            <!-- section-->
            <section class="middle-padding grey-blue-bg" style="padding: 15px 0">
                <div class="container">
                    <div class="row">
                        <div class="dashboard-content fl-wrap">
                            <div class="col-md-12" style="padding-top: 25px;">
                                <div class="list-single-main-item fl-wrap hide-print" id="sec3">
                                    <div class="list-single-main-item-title fl-wrap">
                                        <h3>Historical Transactions</h3>
                                    </div>
                                    @if ( count($paid_tranx_recs[0]->payment_records) )
                                        <div class="listing-features fl-wrap">
                                            <ul>
                                                <li>Payment ID</li>
                                                <li>Description</li>
                                                <li>Amount</li>
                                                <li style="display: flex; justify-content:center;">% Paid</li>
                                                <li style="display: flex; justify-content:center;">First Pay?</li>
                                                <li>Bank Ref</li>
                                                <li>Date Paid</li>
                                                <li style="display: flex; justify-content:center;">% Completed</li>
                                            </ul>
                                        </div>
                                        <span class="fw-separator" style="margin-top:3px; margin-bottom:25px;"></span>
                                        
                                        @foreach ($paid_tranx_recs[0]->payment_records as $key => $payment_record)
                                            <div class="listing-features fl-wrap lighter">
                                                <ul>
                                                    <li>{{$payment_record->tranx_id}}</li>
                                                    <li>{{$payment_record->description}}</li>
                                                    <li>&#8358;{{ number_format(str_replace(',', '', $payment_record->amount), 2, '.', ',') }}</li>
                                                    <li style="display: flex; justify-content:center;">{{$payment_record->percentage}}</span></li>
                                                    <li style="display: flex; justify-content:center;">{{$payment_record->first_pay?'Yes':'No'}}</li>
                                                    <li>{{$payment_record->bank_ref_number}}</li>
                                                    <li>{{ \Carbon\Carbon::parse($payment_record->created_at)->format('jS F Y') }}</li>
                                                    <li style="display: flex; justify-content:center;">{{$paid_tranx_recs[0]->payment_remaining_perc . '%'}}</li>
                                                </ul>
                                            </div>
                                        @endforeach
                                    @else 
                                        <div class="listing-features fl-wrap">
                                            <div>No Payment Schedule Found.</div>
                                            <a href="#" class="next-form action-button btn no-shdow-btn flat-btn float-btn color-bg">Cancel <i class="fal fa-times"></i></a>
                                            <a href="#" class="next-form action-button btn no-shdow-btn color2-bg">Next <i class="fal fa-angle-right"></i></a>
                                        </div>
                                    @endif
                                </div>  

                                <div class="list-single-main-item fl-wrap" id="sec4">
                                    <div class="list-single-main-item-title fl-wrap hide-print">
                                        <h3>Transaction Deal Slip</h3>
                                        @if ( count($paid_tranx_recs) )
                                            <a href="#" class="new-dashboard-item print-dealslip"><i class="fal fa-print"></i> Print Deal Slip</a>
                                        @endif
                                    </div>
                                    <div class="listing-features fl-wrap">
                                        @if ( count($paid_tranx_recs) )
                                            <div class="invoice-box" id="invoice-box">
                                                <table>
                                                    <tbody>
                                                        <tr class="top">
                                                            <td colspan="2">
                                                                <table>
                                                                    <tbody><tr>
                                                                        <td class="title">
                                                                            <div style="background-color:#000; width: 176px; padding: 11px;">
                                                                                <img src="{{ asset('vendor/reformedtemplate/images/logoo.png') }}" style="width:150px; height:auto" alt="">
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            Invoice #: 000002<br>
                                                                            Created: {{ \Carbon\Carbon::parse($paid_tranx_recs[0]->created_at)->format('F j, Y') }}<br>
                                                                            @if ( count($paid_tranx_recs[0]->payment_records) )
                                                                            Due: {{ \Carbon\Carbon::parse($paid_tranx_recs[0]->payment_records[0]->created_at)->format('F j, Y') }}
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                </tbody></table>
                                                            </td>
                                                        </tr>
                                                        <tr class="information">
                                                            <td colspan="2">
                                                                <table>
                                                                    <tbody><tr>
                                                                        <td>
                                                                            {{config('app.name')}}, Inc.<br>
                                                                            {{config('app.address')}}<br>
                                                                            <a href="#" style="color:#666; text-decoration:none">{{ 'support@'. strtolower(config('app.name')) . '.com'}}</a>
                                                                            <br>
                                                                            <a href="#" style="color:#666; text-decoration:none">+4(333)123456</a>                                
                                                                        </td>
                                                                        <td>
                                                                            {{$user->firstname?$user->lastname.' '.$user->firstname:$user->corporate_name}}<br>
                                                                            <a href="#" style="color:#666; text-decoration:none">{{$user->email}}</a>
                                                                            <br>
                                                                            @if($user->phone)
                                                                                <a href="#" style="color:#666; text-decoration:none">{{'+234' . $user->phone}}</a>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                </tbody></table>
                                                            </td>
                                                        </tr>
                                                        <tr class="heading">
                                                            <td>Option</td>
                                                            <td>Details</td>
                                                        </tr>
                                                        <tr class="item">
                                                            <td>Site Name</td>
                                                            <td>{{$paid_tranx_recs[0]->asset->name}}</td>
                                                        </tr>
                                                        <tr class="item">
                                                            <td>Reservation ID</td>
                                                            <td>{{$paid_tranx_recs[0]->trnx_id}}</td>
                                                        </tr>
                                                        <tr class="item ">
                                                            <td>Start Date</td>
                                                            <td>{{ \Carbon\Carbon::parse($paid_tranx_recs[0]->start_date)->format('jS \o\f F Y') }}</td>
                                                        </tr>
                                                        <tr class="item">
                                                            <td>End Date</td>
                                                            <td>{{ \Carbon\Carbon::parse($paid_tranx_recs[0]->end_date)->format('jS \o\f F Y') }}</td>
                                                        </tr>
                                                        <tr class="item">
                                                            <td>Price</td>
                                                            <td>&#8358;{{ number_format(str_replace(',', '', $paid_tranx_recs[0]->asset->max_price), 2, '.', ',') }}</td>
                                                        </tr>
                                                        <tr class="item last">
                                                            <td>Total Amount Paid</td>
                                                            <td>&#8358;{{ number_format(str_replace(',', '', $paid_tranx_recs[0]->payment_total), 2, '.', ',') }}</td>
                                                        </tr>
                                                        <tr class="total">
                                                            <td></td>
                                                            <td style="padding-top:50px;">Total Balance: &#8358;{{ number_format(str_replace(',', '', $paid_tranx_recs[0]->payment_remaining), 2, '.', ',') }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>  
                            <!-- col-md-12 end -->        
                        </div>
                    </div>
                </div>
            </section>
            <!-- section end-->
            <div class="limit-box fl-wrap"></div>
        </div>
        <!-- content end-->
    </div>
@endsection