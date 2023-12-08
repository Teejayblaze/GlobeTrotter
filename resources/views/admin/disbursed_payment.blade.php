@extends('template.master')

@section('content')

<style>
    .left {
        float: left;
        font-weight: 600;
        font-size: 14px;
    }

    .right {
        float: right;
        font-size: 13px;
    }
    
    .reverse-action {
        padding: 0 5px 0 5px;
        border: 1px solid #85c1ef;
        display: block;
        color: #333;
    }

    .reverse-action:hover {
        background-color: #f3f3f3;
    }

    .reverse-action i.fal {
        left: 60px;
        top: 6px;
    }

    .success {
        border: 1px solid #09895d;
        color: #09895d;
        padding: 0 3px 0 3px;
    }

    .success-borderless {
        color: #09895d;
        font-weight: 600;
    }

    .error {
        border: 1px solid #be1818;
        color: #be1818;
        padding: 0 3px 0 3px;
    }

    .error-borderless {
        color: #be1818;
        font-weight: 600;
    }

    .alert.alert-warning {
        color: #856404;
        background-color: #fff3cd;
        border-color: #ffeeba;
    }

    .border-bottom {
        border-bottom: 2px solid rgba(0, 0, 0, .1)
    }

</style>

<div class="site-breadcrumb login-breadcrumb dashboard-breadcrumb">
    <div class="container">
        <h2 class="breadcrumb-title dashboard-breadcrumb-menu-left">Disbursed Payment</h2>
        <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
            <li><a href="{{ url("/admin/dashboard") }}">Dashboard</a></li>
            <li>Payment Disbursement</li>
            <li class="active-nav-dashboard">Disbursed Payment</li>
        </ul>
    </div>
</div>
<section id="advertiser-dashboard">
    <div class="user-profile pt-40 pb-40 dashboard-bg-color">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                <div class="user-profile-wrapper wow fadeIn" data-wow-duration="2.5s" data-wow-delay=".25s">
                    <div class="user-profile-card add-property" id="sec3">
                    <h4 class="user-profile-card-title">Disbursed Payment</h4>
                    <div class="col-lg-12 pending-transactions-table-div pt-10 pb-10">
                        @if (session()->has('reverse-disburse-success'))
                        <div class="alert alert-success">
                            <span>{{session()->get('reverse-disburse-success')}}</span>
                        </div>
                        @endif

                        @if (session()->has('disbursed-pending'))
                        <div class="alert alert-warning">
                            <span>{{session()->get('disbursed-pending')}}</span>
                        </div>
                        @endif

                        @if (count($transactions))
                            @foreach ($transactions as $key => $transaction)
                            <div class="mb-50 pb-40 border-bottom">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="left">S/N</div><div class="right">{{(@$key+1)}}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="left">Date Paid</div><div class="right">{{\Carbon\Carbon::parse(@$transaction->updated_at)->format('jS F Y')}}</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="left">Tx ID</div><div class="right">{{@$transaction->bookings->trnx_id}}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="left">Status</div><div class="right {{@$transaction->disbursed?'success':'error'}}">{{@$transaction->disbursed?'Disbursed':'Pending'}}</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="left">Payment ID</div><div class="right">{{@$transaction->tranx_id}}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="left">Date Disbursed</div><div class="right">{{\Carbon\Carbon::parse(@$transaction->date_disbursed)->format('jS F Y')}}</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="left">Paid Amount</div><div class="right">@if(@$transaction->amount)
                                            &#8358;{{ number_format(str_replace(',', '', @$transaction->amount), 2, '.', ',') }}@endif</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="left">Expect Pay Date</div><div class="right"><span class="label-warning">{{\Carbon\Carbon::parse(@$transaction->expect_pay_date)->format('jS F Y')}}</span></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="left">Percentage</div>
                                        <div class="right">{{@$transaction->percentage}}</div>
                                        </div>
                                    <div class="col-md-6">
                                        <div class="left">Reconciliation Status</div><div class="right {{@$transaction->disburse_status === 'pending' ? 'error-borderless' : 'success-borderless'}}">{{ucfirst(@$transaction->disburse_status)}}</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="left">Disbursement Reference</div>
                                        <div class="right">{{@$transaction->disbursed_bank_ref_number}}</div>
                                    </div>
                                    <div class="col-md-6">
                                        @if (@$transaction->disburse_status === 'pending')
                                        <div class="left">Action</div><div class="right"><a href="{{url('/admin/disbursed/payment/requery/'.@$transaction->id)}}" class="reverse-action">ReQuery <i class="fal fa-redo"></i></a></span></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else 
                            <p class="fs-13">No disbursed transaction records found!</p>
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