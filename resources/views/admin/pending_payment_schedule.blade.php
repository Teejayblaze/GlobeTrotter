@extends('template.master')

@section('content')
    <style>
        .alert-success {
            background-color: #8dc6f3;
            color: #065d9e;
        }
        

        .left {
            float: left;
            font-weight: 600;
            font-size: 14px;
        }

        .right {
            float: right;
            font-size: 13px;
        }

        .label-warning {
            background: #1eeda5; 
            padding: 3px 10px;
            border-radius: 2px;
            color: #be1818;
        }

        .asset-payment-schedule, .asset-pending-payment-schedule {
            display: none;
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
        <h2 class="breadcrumb-title dashboard-breadcrumb-menu-left">Pending Payment Schedule</h2>
        <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
            <li><a href="{{ url("/admin/dashboard") }}">Dashboard</a></li>
            <li>Payment Disbursement</li>
            <li class="active-nav-dashboard">Pending Payment Schedule</li>
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
                    <h4 class="user-profile-card-title">Pending Payment Schedule</h4>
                    <div class="col-lg-12 pending-transactions-table-div pt-10 pb-10">
                        @if (session()->has('disbursed-success'))
                        <div class="alert alert-success">
                            <span>{{session()->get('disbursed-success')}}</span>
                        </div>
                        @endif
                        @if (count($transactions))
                            @foreach ($transactions as $key => $transaction)
                            <div class="mb-50 pb-40 border-bottom">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="left"><h2 style="text-align: left;padding: 0 0 10px 0;clear: both;" class="fs-15">Asset Information</h2></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="left"><h2 style="text-align: left;padding: 0 0 10px 0;clear: both;" class="fs-15">Payment Schedule Information</h2></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="left">Asset Name</div><div class="right">{{ @$transaction->asset->name }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="left">Payment ID</div><div class="right">{{ @$transaction->tranx_id }}</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="left">Asset Dimension</div><div class="right">{{ @$transaction->asset->asset_dimension_width }} x {{ @$transaction->asset->asset_dimension_height }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="left">Paid Amount</div><div class="right">@if(@$transaction->amount)&#8358;{{ @number_format(str_replace(',', '', @$transaction->amount), 2, '.', ',') }}@endif</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="left">Asset Category</div><div class="right">{{ ucwords(@$transaction->asset->asset_category) }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="left">Percentage</div><div class="right">{{ $transaction->percentage }}</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="left">Asset Faces</div><div class="right">{{ @$transaction->asset->face_count }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="left">Payment Status</div><div class="right"><span class="label-warning">{{ @$transaction->paid?'Yes':'Pending' }}</span></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="left">Asset Type</div>
                                        <div class="right">{{ @$transaction->asset_type->type }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="left">First Payment?</div><div class="right"><span class="label-warning">{{ @$transaction->first_pay?'Yes':'No' }}</span></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="left">Asset Price (min - max)</div>
                                        <div class="right">
                                            @if(@$transaction->asset->min_price) 
                                            &#8358;{{ number_format(str_replace(',', '', @$transaction->asset->min_price), 2, '.', ',') }} 
                                            @endif
                                            -
                                            @if(@$transaction->asset->max_price) 
                                            &#8358;{{ number_format(str_replace(',', '', @$transaction->asset->max_price), 2, '.', ',') }} 
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="left">Bank Reference</div><div class="right">{{ $transaction->bank_ref_number }}</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="left">Asset Address</div>
                                        <div class="right">{{ @$transaction->asset->address }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="left">Payment Schedule Tx ID</div><div class="right">{{ $transaction->asset_booking_ref }}</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="left">Asset Owner Name</div>
                                        <div class="right">{{ @$transaction->asset->owner->corporate_name }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="left">Date payment schedule was created</div><div class="right">{{ \Carbon\Carbon::parse($transaction->created_at)->format('jS F Y') }}</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">&nbsp;</div>
                                    <div class="col-md-6">
                                        <div class="left">Description</div><div class="right">{{ $transaction->description }}</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="left"><h2 style="text-align: left;padding: 10px 0 10px 0;clear: both;" class="fs-15">Asset Booking Information</h2></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="left">&nbsp;</div>
                                        <div class="right">&nbsp;</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="left">Asset Booking Tx ID</div>
                                        <div class="right">{{ @$transaction->bookings->trnx_id }}</div>
                                    </div>
                                    <div class="col-md-6">&nbsp;</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="left">Asset Reserved?</div>
                                        <div class="right"><span class="label-warning">{{ @$transaction->bookings->locked?'Yes':'No' }}</span></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="left">&nbsp;</div>
                                        <div class="right">&nbsp;</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="left">Advert Started</div>
                                        <div class="right">{{ \Carbon\Carbon::parse(@$transaction->bookings->start_date)->format('jS F Y') }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="left">&nbsp;</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="left">Advert Ends</div>
                                        <div class="right">{{ \Carbon\Carbon::parse(@$transaction->bookings->end_date)->format('jS F Y') }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="left">&nbsp;</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="left">Next Availability Date</div>
                                        <div class="right">{{ \Carbon\Carbon::parse(@$transaction->bookings->next_availability_date)->format('jS F Y') }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="left">&nbsp;</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="left">Finished Payment?</div>
                                        <div class="right"><span class="label-warning">{{ @$transaction->paycompleted?'Yes':'No' }}</span></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="left">&nbsp;</div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else 
                            <p class="fs-13">Apologies, No &ldquo;Pending Disbursed Payment&rdquo; Record Found.</p>
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