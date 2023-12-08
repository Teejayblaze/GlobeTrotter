@extends('reformedtemplate.master')

@section('content')
<link type="text/css" rel="stylesheet" href="{{ asset('vendor/reformedtemplate/css/invoice.css') }}">
    <style>
        .listing-features li {
            width: 12.5%;
            padding-left: 0;
            padding-right: 8px;
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

        .main-register-holder {
            max-width: 540px;
        }

        .body-content .content {
            padding: 25px 8px 0 8px;
        }
        .hide-defualt {
            display: none;
        }

        .body-content .content h2 {
            margin-bottom: 16px;
            font-size: 17px;
            margin-top: 10px;
        }

        .body-content .content p {
            color: #000;
        }

        .h2 {
            position: relative;
            float: left;
            margin-bottom: 0;
            font-size: 14px;
        }

        .bank_ul {
            margin: 30px 0 0 0;
            opacity: 0;
            visibility: hidden;
            position: absolute;
            min-width: 150px;
            top: -2px;
            right: 0;
            z-index: 1;
            padding: 10px 0;
            background: #fff;
            border-radius: 6px;
            border: 1px solid #eee;
            transition: all .2s ease-in-out;
        }

        .bank_ul li {
            text-align: left;
            padding: 5px 8px;
        }

        .bank_ul li a {
            display: block;
        }

        .bank_ul li:hover {
            background-color: #eee;
        }

        .bank-open {
            opacity: 1;
            visibility: visible;
        }

        .main-payment-schedule-wrap, .pending-payment-schedule-wrap {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1000;
            overflow: auto;
            display: none;
            -webkit-transform: translate3d(0,0,0);
            background: rgba(0,0,0,0.81);
        }

        .flat-header {
            padding-top: 158px !important;
        }

        .dasboard-breadcrumbs {
            top: -32px !important;
        }
        
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
                        <div class="dasboard-breadcrumbs breadcrumbs"><a href="{{url()->previous()}}">Back</a></div>
                    </div>
                </div>
            </section>
            <!-- section end-->
            <!-- section-->
            @if ( count($pending_tranx_recs) )
                <section class="middle-padding grey-blue-bg" style="padding: 15px 0">
                    <div class="container">
                        <div class="row">
                            <div class="dashboard-content fl-wrap">
                                <div class="col-md-12" style="padding-top: 25px;">
                                    <div class="list-single-main-item fl-wrap hide-print" id="sec3">
                                        <div class="list-single-main-item-title fl-wrap">
                                            <h3>Pending Transaction</h3>
                                            @if ( count($pending_tranx_recs[0]->payment_records) )
                                                <a href="#" class="new-dashboard-item modal-open-pending-payment-schedule-wrap"
                                                data-booking-id="{{ $pending_tranx_recs[0]->id }}"
                                                data-asset-name="{{ $pending_tranx_recs[0]->asset->name }}"
                                                data-booked-ref="{{ $pending_tranx_recs[0]->trnx_id }}"
                                                data-actualbal="{{ number_format(str_replace(',', '', $pending_tranx_recs[0]->payment_remaining), 2, '.', ',') }}"
                                                ><i class="fal fa-plus"></i> Generate Payment Schedule</a>
                                            @endif
                                        </div>

                                        @if ( count($pending_tranx_recs[0]->pending_payment_records) )
                                            <h2 class="h2">Pending Payment Schedule</h2>
                                            <span class="fw-separator"></span>
                                            <div class="listing-features fl-wrap">
                                                <ul>
                                                    <li>Payment ID</li>
                                                    <li style="width:34%;">Description</li>
                                                    <li>Amount</li>
                                                    <li style="display: flex; justify-content:center;">% Scheduled</li>
                                                    <li style="display: flex; justify-content:center;">First Pay?</li>
                                                    <li>Date</li>
                                                </ul>
                                            </div>
                                            <span class="fw-separator" style="margin-top:3px; margin-bottom:25px;"></span>
                                            
                                            @foreach ($pending_tranx_recs[0]->pending_payment_records as $key => $pending_payment_record)
                                                <div class="listing-features fl-wrap lighter">
                                                    <ul>
                                                        <li>{{$pending_payment_record->tranx_id}}</li>
                                                        <li style="width:34%;">{{$pending_payment_record->description}}</li>
                                                        <li>&#8358;{{ number_format(str_replace(',', '', $pending_payment_record->amount), 2, '.', ',') }}</li>
                                                        <li style="display: flex; justify-content:center;">{{$pending_payment_record->percentage}}</span></li>
                                                        <li style="display: flex; justify-content:center;">{{$pending_payment_record->first_pay?'Yes':'No'}}</li>
                                                        <li>{{ \Carbon\Carbon::parse($pending_payment_record->created_at)->format('jS F Y') }}</li>
                                                    </ul>
                                                </div>
                                            @endforeach
                                        @else 
                                            <div class="listing-features fl-wrap">
                                                <div>No "Pending" Payment Schedule Found.</div>
                                                <span class="fw-separator"></span>
                                                @if ( count($pending_tranx_recs[0]->payment_records) == 0 )
                                                    <a href="#" style="float:left" class="action-button btn no-shdow-btn flat-btn float-btn color-bg">Cancel <i class="fal fa-times"></i></a>
                                                    <a href="#" style="float:right" class="next action-button btn no-shdow-btn color2-bg modal-open-payment-schedule-wrap"
                                                        data-booking-id="{{ $pending_tranx_recs[0]->id }}"
                                                        data-asset-name="{{ $pending_tranx_recs[0]->asset->name }}"
                                                        data-booked-ref="{{ $pending_tranx_recs[0]->trnx_id }}"
                                                        data-actualbal="{{ number_format(str_replace(',', '', $pending_tranx_recs[0]->payment_remaining), 2, '.', ',') }}"
                                                    >Next <i class="fal fa-angle-right"></i></a>
                                                @endif
                                            </div>
                                        @endif
                                        
                                        @if ( count($pending_tranx_recs[0]->payment_records) )
                                            <span class="fw-separator"></span>
                                            <h2 class="h2" style="margin-top: 50px;">Paid Payment Schedule</h2>
                                            <span class="fw-separator"></span>
                                            <div class="listing-features fl-wrap">
                                                <ul>
                                                    <li>Payment ID</li>
                                                    <li>Description</li>
                                                    <li>Amount</li>
                                                    <li style="display: flex; justify-content:center;">% Paid</li>
                                                    <li style="display: flex; justify-content:center;">First Pay?</li>
                                                    <li>Bank Ref</li>
                                                    <li>Date</li>
                                                    <li style="display: flex; justify-content:center;">% Remaining</li>
                                                </ul>
                                            </div>
                                            <span class="fw-separator" style="margin-top:3px; margin-bottom:25px;"></span>
                                            
                                            @foreach ($pending_tranx_recs[0]->payment_records as $key => $payment_record)
                                                <div class="listing-features fl-wrap lighter">
                                                    <ul>
                                                        <li>{{$payment_record->tranx_id}}</li>
                                                        <li>{{$payment_record->description}}</li>
                                                        <li>&#8358;{{ number_format(str_replace(',', '', $payment_record->amount), 2, '.', ',') }}</li>
                                                        <li style="display: flex; justify-content:center;">{{$payment_record->percentage}}</span></li>
                                                        <li style="display: flex; justify-content:center;">{{$payment_record->first_pay?'Yes':'No'}}</li>
                                                        <li>{{$payment_record->bank_ref_number}}</li>
                                                        <li>{{ \Carbon\Carbon::parse($payment_record->created_at)->format('jS F Y') }}</li>
                                                        <li style="display: flex; justify-content:center;">{{$pending_tranx_recs[0]->payment_remaining_perc . '%'}}</li>
                                                    </ul>
                                                </div>
                                            @endforeach
                                        @else 
                                            <div class="listing-features fl-wrap">
                                                <span class="fw-separator"></span>
                                                <div>No "Paid" Payment Schedule Found.</div>
                                            </div>
                                        @endif
                                    </div>  

                                    <div class="list-single-main-item fl-wrap" id="sec4">
                                        <div class="list-single-main-item-title fl-wrap hide-print">
                                            <h3>Transaction Deal Slip</h3>
                                            @if ( count($pending_tranx_recs) )
                                                <a href="#" class="new-dashboard-item print-dealslip" style="right: 150px;"><i class="fal fa-print"></i> Print Deal Slip</a>
                                                <a href="#" style="color:white" class="new-dashboard-item make_payment_link"><i class="fal fa-credit-card"></i> Make Payment</a>
                                                <ul class="bank_ul">
                                                    <li><a href="#" target="_blank">UBA Bank</a></li>
                                                    <li><a href="http://localhost:8001/bank/zenith/" target="_blank">Zenith Bank</a></li>
                                                    <li><a href="#" target="_blank">Union Bank</a></li>
                                                </ul>
                                            @endif
                                        </div>
                                        <div class="listing-features fl-wrap">
                                            @if ( count($pending_tranx_recs) )
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
                                                                                Invoice #: 000001<br>
                                                                                Created: {{ \Carbon\Carbon::parse($pending_tranx_recs[0]->created_at)->format('F j, Y') }}<br>
                                                                                @if ( count($pending_tranx_recs[0]->payment_records) )
                                                                                Due: {{ \Carbon\Carbon::parse($pending_tranx_recs[0]->payment_records[0]->created_at)->format('F j, Y') }}
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
                                                                                {{ count($booked_by_user) ? $booked_by_user[2].' '.$booked_by_user[1] : $user->corporate_name}}<br>
                                                                                <a href="#" style="color:#666; text-decoration:none">{{$booked_by_user[3]}}</a>
                                                                                <br>
                                                                                @if(count($booked_by_user) && $booked_by_user[4])
                                                                                    <a href="#" style="color:#666; text-decoration:none">{{'+234' . $booked_by_user[4]}}</a>
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    </tbody></table>
                                                                </td>
                                                            </tr>
                                                            {{-- <tr class="heading">
                                                                <td>Payment Method</td>
                                                                <td>Check #</td>
                                                            </tr>
                                                            <tr class="details">
                                                                <td>Visa ending **** 4242</td>
                                                                <td>Check</td>
                                                            </tr> --}}
                                                            <tr class="heading">
                                                                <td>Option</td>
                                                                <td>Details</td>
                                                            </tr>
                                                            <tr class="item">
                                                                <td>Site Name</td>
                                                                <td>{{$pending_tranx_recs[0]->asset->name}}</td>
                                                            </tr>
                                                            <tr class="item">
                                                                <td>Reservation ID</td>
                                                                <td>{{$pending_tranx_recs[0]->trnx_id}}</td>
                                                            </tr>
                                                            <tr class="item ">
                                                                <td>Start Date</td>
                                                                <td>{{ \Carbon\Carbon::parse($pending_tranx_recs[0]->start_date)->format('jS \o\f F Y') }}</td>
                                                            </tr>
                                                            <tr class="item">
                                                                <td>End Date</td>
                                                                <td>{{ \Carbon\Carbon::parse($pending_tranx_recs[0]->end_date)->format('jS \o\f F Y') }}</td>
                                                            </tr>
                                                            <tr class="item">
                                                                <td>Price</td>
                                                                <td>&#8358;{{ number_format(str_replace(',', '', $pending_tranx_recs[0]->asset->max_price), 2, '.', ',') }}</td>
                                                            </tr>
                                                            <tr class="item last">
                                                                <td>Total Amount Paid</td>
                                                                <td>&#8358;{{ number_format(str_replace(',', '', $pending_tranx_recs[0]->payment_total), 2, '.', ',') }}</td>
                                                            </tr>
                                                            <tr class="total">
                                                                <td></td>
                                                                <td style="padding-top:50px;">Total Balance: &#8358;{{ number_format(str_replace(',', '', $pending_tranx_recs[0]->payment_remaining), 2, '.', ',') }}</td>
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
            @else 
                <section class="middle-padding grey-blue-bg" style="padding: 15px 0;">
                    <div class="container">
                        <div class="row">
                            <div class="dashboard-content fl-wrap">
                                <div class="col-md-12" style="padding-top: 25px;">
                                    <div class="list-single-main-item fl-wrap hide-print" id="sec3">
                                        <div class="list-single-main-item-title fl-wrap">
                                            <h3>Pending Transaction</h3>
                                        </div>
                                        <div class="listing-features fl-wrap">
                                            <div>No Booking Record Found.</div>
                                            <span class="fw-separator"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- section end-->
            @endif
            <div class="limit-box fl-wrap"></div>
        </div>
        <!-- content end-->
    </div>

    <!--register form -->
    <div class="main-payment-schedule-wrap modal" style="height: -webkit-fill-available;">
        <div class="reg-overlay"></div>
        <div class="main-register-holder">
            <div class="main-register fl-wrap">
                {{-- <div class="close-reg color-bg"><i class="fal fa-times"></i></div> --}}
                <ul class="tabs-menu">
                    <li class=""><a><i class="fal fa-broom"></i> Payment Schedule Generator</a></li>
                </ul>
                <!--tabs -->
                <div>
                    <div class="body-content">
                        <input type="hidden" name="reserve_id" id="reserve_id">
                        <input type="hidden" name="booking_id" id="booking_id">
                        <input type="hidden" name="actual_bal" id="actual_bal">
                        <div class="content">
                            <div class="law-context hide_print" style="padding: 5px; max-height:450px; min-height:200px; overflow-y:scroll;text-align: justify;">
                               
                               <h2>What is Lorem Ipsum?</h2> 
                               <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>  
                                
                               <h2>Why do we use it?</h2>
                               <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>
                                
                                
                               <h2>Where does it come from?</h2>
                               <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.</p>
                                
                               <p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>
                                
                               <h2>Where can I get some?</h2>
                               <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p> 
                                                        
                            </div>
                            <div class="payment-sched-printable-content" id="payment-sched-printable-content" style="padding: 5px;">
                                                        
                            </div>
                            <div class="modal-footer row">
                                <div class="col-md-6 hide-defualt"><a href="#" style="float:left" class="action-button btn no-shdow-btn flat-btn float-btn color-bg hide_dismiss">Close <i class="fal fa-times"></i></a></div>
                                <div class="col-md-12 hide_print"><a href="#" style="float:right;background: #5ECFB1;" class="action-button btn no-shdow-btn flat-btn float-btn generate_tranx"><i class="fal fa-check"></i> I AGREE</a></div>
                                <div class="col-md-6 hide-defualt"><a href="#" style="float:right" class="action-button btn no-shdow-btn flat-btn float-btn color2-bg payment-sched-printable-btn">Print Payment Slip <i class="fal fa-print"></i></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--register form end -->


    <!--register form -->
    <div class="pending-payment-schedule-wrap modal" style="height: -webkit-fill-available;">
            <div class="reg-overlay"></div>
            <div class="main-register-holder">
                <div class="main-register fl-wrap">
                    {{-- <div class="close-reg color-bg"><i class="fal fa-times"></i></div> --}}
                    <ul class="tabs-menu">
                        <li class=""><a><i class="fal fa-broom"></i> Payment Schedule Generator</a></li>
                    </ul>
                    <!--tabs -->
                    <div>
                        <div class="body-content">
                            <input type="hidden" name="reserve_id" id="reserve_id">
                            <input type="hidden" name="booking_id" id="booking_id">
                            <input type="hidden" name="actual_bal" id="actual_bal">
                            <div class="content">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hide_print">
                                    <table style="width: 100%;">
                                        <tr>
                                            <th style="width: 30%; text-align:left;padding: 13px 0px;">Asset Name</th>
                                            <td style="text-align: right;padding: 13px 0px;"><span style="float:right;" id="asset_name_read"></span></td>
                                        </tr>
                                        <tr>
                                            <th style="width: 30%; text-align:left;padding: 13px 0px;">Reserve ID</th>
                                            <td style="text-align: right;padding: 13px 0px;"><span style="float:right;" id="reserve_id_read"></span></td>
                                        </tr>
                                        <tr>
                                            <th style="width: 30%; text-align:left;padding: 13px 0px;">Balance Amount</th>
                                            <td style="text-align: right;padding: 13px 0px;"><span style="float:right;" id="bal_amt_read"></span></td>
                                        </tr>
                                        <tr>
                                            <th style="width: 30%; text-align:left;padding: 13px 0px;">Amount Payable (&#8358;)</th>
                                            <td style="text-align: right;padding: 13px 0px;"><span contenteditable="true"
                                                style="float:right;border-top:1px solid #d3d3d3;border-bottom:1px solid #d3d3d3;padding:2px 0;min-width:125px;max-width:200px;" 
                                                id="amt_payable">0</span></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="payment-sched-printable-content" id="payment-sched-printable-content" style="padding: 5px;">
                                                        
                                </div>
                                <div class="modal-footer row">
                                    <div class="col-md-6 hide-defualt"><a href="#" style="float:left" class="action-button btn no-shdow-btn flat-btn float-btn color-bg hide_dismiss">Close <i class="fal fa-times"></i></a></div>
                                    <div class="col-md-12 hide_print"><a href="#" style="float:right;background: #5ECFB1;" class="action-button btn no-shdow-btn flat-btn float-btn generate_tranx"><i class="fal fa-check"></i> Generate Payment Schedule</a></div>
                                    <div class="col-md-6 hide-defualt"><a href="#" style="float:right" class="action-button btn no-shdow-btn flat-btn float-btn color2-bg payment-sched-printable-btn">Print Payment Slip <i class="fal fa-print"></i></a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--register form end -->
@endsection