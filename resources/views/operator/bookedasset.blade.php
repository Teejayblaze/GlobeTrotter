@extends('template.master')

@section('content')
<style>
    .left {
        float: left;
        font-weight: 600;
    }

    .right {
        float: right;
    }
    .new-dashboard-item {
        right: 0;
        top: 0;
        background: #cc2f53;
        position: relative;
        padding: 0px 10px;
        font-size: 13px;
        color: #fff;
    }

    .label-warning {
        background: #1eeda5; 
        padding: 3px 10px;
        border-radius: 2px;
        color: #be1818;
        font-size: 13px;
    }

    .asset-payment-schedule, .asset-pending-payment-schedule {
        display: none;
    }

    .paid-item {
        background: #1e8aed; 
        font-size: 13px;
        padding: 0px 10px;
        border-radius: 2px;
        color: #fff;
    }

    .asset-content {
        max-height: 400px;
        overflow-y: scroll;
    }
</style>

<div class="site-breadcrumb login-breadcrumb dashboard-breadcrumb">
    <div class="container">
        <h2 class="breadcrumb-title dashboard-breadcrumb-menu-left">Booked Asset</h2>
        <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
            <li><a href="{{url('/operator/dashboard')}}">Dashboard</a></li>
            <li>Owned Asset</li>
            <li class="active-nav-dashboard">Booked Asset</li>
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
                    <h4 class="user-profile-card-title">Booked Asset</h4>
                    <div class="col-lg-12 pending-transactions-table-div pt-10 pb-10">
                        @if ( count($booked_assets) )
                            <?php $sn = 0 ?>
                            @foreach ($booked_assets as $asssetkey => $booked_asset)
                                <?php $sn++ ?>
                                <h6 style="text-align: left;padding: 0 0 25px 0;clear: both;">{{ $sn .'.' }}  Asset Booking Information</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="left">Asset Name</div><div class="right">{{ $booked_asset->name }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="left">Advert Start Date</div><div class="right">{{ \Carbon\Carbon::parse($booked_asset->start_date)->format('jS F Y') }}</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="left">Tx ID</div><div class="right">{{ $booked_asset->trnx_id }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="left">Advert End Date</div><div class="right">{{ \Carbon\Carbon::parse($booked_asset->end_date)->format('jS F Y') }}</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="left">Asset Price</div><div class="right">&#8358;{{ number_format(str_replace(',', '', $booked_asset->price), 2, '.', ',') }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="left">Next Availability Date</div><div class="right">{{ \Carbon\Carbon::parse($booked_asset->next_availability_date)->format('jS F Y') }}</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="left">Asset Type</div><div class="right">{{ $booked_asset->asset_typex }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="left">Completed Payment?</div><div class="right"><span class="label-warning">{{ $booked_asset->paycompleted?'Yes':'No' }}</span></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="left">Payments</div>
                                        <div class="right">
                                            @if ($booked_asset->paid_count)
                                                <a href="#" class="paid-item show_paid_det" data-paid_el="el-paid-{{$asssetkey}}" data-toggle="modal" data-target="#assetpayment">
                                                    <span>{{ $booked_asset->paid_count }}</span>
                                                </a>
                                            @else 
                                                <span class="paid-item">{{ $booked_asset->paid_count }}</span>
                                            @endif
                                        </div>
                                     </div>
                                    <div class="col-md-6">
                                        <div class="left">Booked Date</div><div class="right">{{ \Carbon\Carbon::parse($booked_asset->created_at)->format('jS F Y') }}</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="left">Pending Payments</div>
                                        <div class="right">
                                            @if ($booked_asset->pending_count)
                                                <a href="#" class="new-dashboard-item show_pending_det" data-pending_el="el-pending-{{$asssetkey}}" data-toggle="modal" data-target="#assetpayment">
                                                    <span>{{ $booked_asset->pending_count }}</span>
                                                </a>
                                            @else 
                                                <span class="label-warning">{{ $booked_asset->pending_count }}</span>
                                            @endif
                                        </div>
                                     </div>
                                    <div class="col-md-6">
                                        <div class="left">Reserved?</div><div class="right"><span class="label-warning">{{ $booked_asset->locked?'Yes':'No' }}</span></div>
                                    </div>
                                </div>

                                <hr style="margin-top:25px; margin-bottom:25px;"/>


                                @if (count($booked_asset->payments))
                                    <div class="asset-payment-schedule" id="el-paid-{{$asssetkey}}">
                                        <div class="col-lg-12 pending-transactions-table-div pt-10 pb-10">
                                        @foreach ($booked_asset->payments as $key => $payment)
                                            <div class="asset-payment-schedule-child">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="left">Payment ID</div><div class="right">{{ $payment->tranx_id }}</div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="left">First Payment?</div><div class="right"><span class="label-warning">{{ $payment->first_pay?'Yes':'No' }}</span></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="left">Tx ID</div><div class="right">{{ $payment->asset_booking_ref }}</div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="left">Bank Reference</div><div class="right">{{ $payment->bank_ref_number }}</div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="left">Paid Amount</div><div class="right">&#8358;{{ number_format(str_replace(',', '', $payment->amount), 2, '.', ',') }}</div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="left">Expect Payment (Date)</div>
                                                            <div class="right">
                                                                @if ($payment->disbursed)
                                                                    {{ \Carbon\Carbon::parse($payment->expect_pay_date)->format('jS F Y') }}
                                                                @else
                                                                    Pending
                                                                @endif
                                                            </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="left">Percentage</div><div class="right">{{ $payment->percentage }}</div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="left">Schedule Created</div><div class="right">{{ \Carbon\Carbon::parse($payment->created_at)->format('jS F Y') }}</div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="left">Paid?</div><div class="right"><span class="label-warning">{{ $payment->paid?'Yes':'No' }}</span></div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="left">Date Paid</div><div class="right">{{ \Carbon\Carbon::parse($payment->updated_at)->format('jS F Y') }}</div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="right">{{ $payment->description }}</div>
                                                    </div>
                                                </div>
                                                <hr style="margin-top:25px; margin-bottom:25px;"/>
                                            </div>
                                        @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if (count($booked_asset->pending_payments))
                                    <div class="asset-pending-payment-schedule" id="el-pending-{{$asssetkey}}">
                                        <div class="col-lg-12 pending-transactions-table-div pt-10 pb-10">
                                        @foreach ($booked_asset->pending_payments as $key => $pending_payment)
                                            <div class="asset-pending-payment-schedule-child">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="left">Payment ID</div><div class="right">{{ $pending_payment->tranx_id }}</div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="left">First Payment?</div><div class="right"><span class="label-warning">{{ $pending_payment->first_pay?'Yes':'No' }}</span></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="left">Tx ID</div><div class="right">{{ $pending_payment->asset_booking_ref }}</div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="left">Bank Reference</div><div class="right">{{ $pending_payment->bank_ref_number??"NIL" }}</div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="left">Paid Amount</div><div class="right">&#8358;{{ number_format(str_replace(',', '', $pending_payment->amount), 2, '.', ',') }}</div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="left">Expect Payment (Date)</div>
                                                            <div class="right">
                                                                @if ($pending_payment->disbursed)
                                                                    {{ \Carbon\Carbon::parse($pending_payment->expect_pay_date)->format('jS F Y') }}
                                                                @else
                                                                    Pending
                                                                @endif
                                                            </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="left">Percentage</div><div class="right">{{ $pending_payment->percentage }}</div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="left">Schedule Created</div><div class="right">{{ \Carbon\Carbon::parse($pending_payment->created_at)->format('jS F Y') }}</div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="left">Paid?</div><div class="right"><span class="label-warning">{{ $pending_payment->paid?'Yes':'No' }}</span></div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="left">Date Paid</div><div class="right">{{ \Carbon\Carbon::parse($pending_payment->updated_at)->format('jS F Y') }}</div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="right">{{ $pending_payment->description }}</div>
                                                    </div>
                                                </div>
                                                <hr style="margin-top:25px; margin-bottom:25px;"/>
                                            </div>
                                        @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @else 
                            <p>No Booked Asset Found.</p>
                        @endif
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="assetpayment" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <div class="asset-content" style="padding: 0 25px;"></div>
      </div>
    </div>
</div>
@endsection