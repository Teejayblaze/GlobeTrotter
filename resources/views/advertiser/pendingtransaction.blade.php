@extends('template.master')

@section('content')
<div class="site-breadcrumb login-breadcrumb dashboard-breadcrumb">
    <div class="container">
        <h2 class="breadcrumb-title dashboard-breadcrumb-menu-left">Pending Transactions</h2>
        <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
            <li><a href="{{ url("/advertiser/individual/dashboard") }}">Dashboard</a></li>
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
                        <div class="user-profile-card add-property">
                            <h4 class="user-profile-card-title">{{ucfirst($type) . " "}}Pending Transactions</h4>
                            @if (session()->has('flash_message'))
                                <div class="alert alert-success">
                                    <span>{{session()->get('flash_message')}}</span>
                                </div>
                            @endif
                            @if (session()->has('flash_error'))
                                <div class="alert alert-danger">
                                    <span>{{session()->get('flash_error')}}</span>
                                </div>
                            @endif

                            <div class="col-lg-12 pending-transactions-table-div pt-10 pb-10">
                                @if (count($pending_tranx_recs) && $type === env('SINGLE_BOOKING_TYPE'))
                                    <table class="pending-transactions-table">
                                        <thead class="">
                                            <tr>
                                                <th class="fs-13">Site Name</th>
                                                <th class="fs-13">Reservation ID</th>
                                                <th class="fs-13">Reserved?</th>
                                                <th class="fs-13">Start Date</th>
                                                <th class="fs-13">End Date</th>
                                                <th class="fs-13">Payment Made</th>
                                                <th class="fs-13">Price</th>
                                                <th class="fs-13">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pending_tranx_recs as $key => $pending_tranx_rec)
                                                <tr>
                                                    <td class="fs-14 sm-fs-12">{{$pending_tranx_rec->asset->name}}</td>
                                                    <td class="fs-14 sm-fs-12">{{$pending_tranx_rec->trnx_id}}</td>
                                                    <td class="fs-14 sm-fs-12"><span>{{$pending_tranx_rec->locked ? 'Yes':'No'}}</span></td>
                                                    <td class="fs-14 sm-fs-12">{{ \Carbon\Carbon::parse($pending_tranx_rec->start_date)->format('jS F Y') }}</td>
                                                    <td class="fs-14 sm-fs-12">{{ \Carbon\Carbon::parse($pending_tranx_rec->end_date)->format('jS F Y') }}</td>
                                                    <td class="fs-14 sm-fs-12">&#8358;{{ number_format(str_replace(',', '', $pending_tranx_rec->payment_total), 2, '.', ',') }}</td>
                                                    <td class="fs-14 sm-fs-12">&#8358;{{ number_format(str_replace(',', '', $pending_tranx_rec->asset->max_price), 2, '.', ',') }}</td>
                                                    <td class="fs-14 sm-fs-12">
                                                        <a href="{{url('/advertiser/individual/pending/transaction/payments/detail/single/'.$pending_tranx_rec->id)}}">
                                                            <span class="wow flash" data-wow-duration="2.5s" data-wow-iteration="infinite">Payment Required</span>
                                                            {{-- <span class="wow flash" data-wow-duration="2.5s" data-wow-iteration="infinite">{{$pending_tranx_rec->payment_remaining_perc . '% Payment Required'}}</span> --}}
                                                        </a>
                                                        @if ($pending_tranx_rec->payment_total === 0)
                                                            <?php $id = str_replace("/", "-", $pending_tranx_rec->id); ?>
                                                            <?php $redirectURL = $id . "/single/" . (0); ?>
                                                            <a href="javascript://" data-intended-url="{{url('/advertiser/individual/transactions/pending/'.$redirectURL.'/delete/')}}" class="delete-booking">&times; Delete</a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @elseif (count($campaigns) && $type === env('CAMPAIGN_BOOKING_TYPE'))
                                    <?php //dd($campaigns); ?>
                                    @foreach ($campaigns as $key => $campaign)
                                        @if ($campaign->assetBookings && count($campaign->assetBookings) > 0)
                                            <h4 style="font-size: 16px;padding: 8px 0 8px 8px;background-color: #ededed70;color: #727272;margin-bottom:15px;">{{ $campaign->name }}</h4>
                                            <table class="pending-transactions-table">
                                                <thead class="">
                                                    <tr>
                                                        <th class="fs-13">Asset Reference</th>
                                                        <th class="fs-13">Reservation ID</th>
                                                        <th class="fs-13">Reserved?</th>
                                                        <th class="fs-13">Start Date</th>
                                                        <th class="fs-13">End Date</th>
                                                        <th class="fs-13">Price</th>
                                                        @if ($campaign->total_payment === 0)
                                                        <th class="fs-13">Action</th>
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($campaign->assetBookings as $key => $assetBookings)
                                                        <?php $asset = $assetBookings->asset()->first(); ?>
                                                        <tr>
                                                            <td class="fs-14 sm-fs-12">{{$asset->name}}</td>
                                                            <td class="fs-14 sm-fs-12">{{$assetBookings->trnx_id}}</td>
                                                            <td class="fs-14 sm-fs-12"><span>{{$assetBookings->locked ? 'Yes':'No'}}</span></td>
                                                            <td class="fs-14 sm-fs-12">{{ \Carbon\Carbon::parse($campaign->start_date)->format('jS F Y') }}</td>
                                                            <td class="fs-14 sm-fs-12">{{ \Carbon\Carbon::parse($campaign->end_date)->format('jS F Y') }}</td>
                                                            <td class="fs-14 sm-fs-12">&#8358;{{ number_format(str_replace(',', '', $asset->max_price), 2, '.', ',') }}</td>
                                                            @if ($campaign->total_payment === 0)
                                                            <td class="fs-14 sm-fs-12">
                                                                <?php $redirectURL = $assetBookings->id . "/campaign/" . $campaign->id; ?>
                                                                <a href="javascript://" data-intended-url="{{url('/advertiser/individual/transactions/pending/'.$redirectURL.'/delete/')}}" class="delete-booking">&times; Delete</a>
                                                            </td>
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                    <tr style="padding: 0;height: 10px;display: block;">
                                                        <td colspan="7">&nbsp;</td>
                                                    </tr>   
                                                    <tr>
                                                        <th>Campaign Total Price <span style="margin-left: 10px;">&#8358;{{ number_format(str_replace(',', '', $campaign->total_price), 2, '.', ',') }}</span></th>
                                                        <th>Payment Made <span style="margin-left: 10px;">&#8358;{{ number_format(str_replace(',', '', $campaign->total_payment), 2, '.', ',') }}</span></th>
                                                        <th>Outstanding <span style="margin-left: 10px;">&#8358;{{ number_format(str_replace(',', '', $campaign->total_price - $campaign->total_payment), 2, '.', ',') }}</span></th>
                                                        <th>&nbsp;</th>
                                                        <th>&nbsp;</th>
                                                        <td colspan="2"><a href="{{url('advertiser/individual/pending/transaction/payments/detail/campaign/'. $campaign->id)}}">Make payment</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="7"><p>&nbsp;</p></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        @endif
                                    @endforeach
                                @else
                                    <p>No Transaction Records Found.</p>
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

@section('asset-details-js')
<script type="text/javascript">
    $(document).ready(function () {
        $(".delete-booking").on('click', function() {
            let self = $(this);
            if (confirm('Are you sure you want to delete this booking?')) {
                window.location.href = self.data('intended-url');
            }
        })
    })
</script>
@endsection