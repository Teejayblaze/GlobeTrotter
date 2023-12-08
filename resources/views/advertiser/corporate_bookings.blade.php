@extends('template.master')

@section('content')
<div class="site-breadcrumb login-breadcrumb dashboard-breadcrumb">
    <div class="container">
        <h2 class="breadcrumb-title dashboard-breadcrumb-menu-left">Asset Booking Transaction</h2>
        <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
            <li><a href="{{ url("advertiser/individual/corporate/dashboard") }}">Dashboard</a></li>
            <li>Administrator Menu</li>
            <li class="active-nav-dashboard">Asset Booking Transaction</li>
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
                        <h4 class="user-profile-card-title">Asset Booking Transaction</h4>
                        @if(count($bookings['staff_details']) && count($bookings['corporate_bookings']))
                        <div class="col-lg-12 pending-transactions-table-div pt-10 pb-10">
                            <table class="pending-transactions-table">
                                <thead class="">
                                    <tr>
                                        <th class="fs-13">S/N</th>
                                        <th class="fs-13">Site Name</th>
                                        <th class="fs-13">Booked By</th>
                                        <th class="fs-13">Amount</th>
                                        <th class="fs-13">Dimension (Width x Height)</th>
                                        <th class="fs-13">Address</th>
                                        @if ( $asset_category === 'static' || $asset_category === 'dynamic' || ($asset_category === 'mobile' && $advert_type === 'static'))
                                        <th class="fs-13">Print Dimension</th>
                                        <th class="fs-13">Substrate</th>
                                        @else 
                                        <th class="fs-13">Number of Slots</th>
                                        <th class="fs-13">Seconds of Slot</th>
                                        @endif
                                        <th class="fs-13">File Format</th>
                                        <th class="fs-13">Board Type</th>
                                        <th class="fs-13">Type</th>
                                        <th class="fs-13">Orientation</th>
                                        <th class="fs-13">Site Faces</th>
                                        <th class="fs-13">Payment Frequency</th>
                                        <th class="fs-13">Site Location</th>
                                        <th class="fs-13">Population</th>
                                        <th class="fs-13">Date Booked</th>
                                        <th class="fs-13">Total Payment</th>
                                        <th class="fs-13">Payment Count</th>
                                        <th class="fs-13">Pending Payment</th>
                                        <th class="fs-13">Balance Remaining</th>
                                        <th class="fs-13">Site Proximities</th>
                                        <th class="fs-13">Deal Slip</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings['staff_details'] as $index => $staff)
                                        @foreach($bookings['corporate_bookings'] as $key => $asset_booking)
                                        <?php 
                                            $asset_category = $asset_booking->asset->asset_category; 
                                            $advert_type = $asset_booking->asset->advert_type; 
                                        ?>
                                        <tr>
                                            <td class="fs-14 sm-fs-12">{{$key+1}}</td>
                                            <td class="fs-14 sm-fs-12">{{$asset_booking->asset->name}}</td>
                                            <td class="fs-14 sm-fs-12">{{$staff[2] .' '. $staff[1]}}</td>
                                            <td class="fs-14 sm-fs-12">&#8358;{{number_format(str_replace(',', '', $asset_booking->asset->max_price), 2, '.', ',')}}</td>
                                            <td class="fs-14 sm-fs-12">{{$asset_booking->asset->asset_dimension_width.'m x '.$asset_booking->asset->asset_dimension_height.'m'}}</td>
                                            <td class="fs-14 sm-fs-12"><span>{{$asset_booking->asset->address}}</span></td>
                                            @if ( $asset_category === 'static' || $asset_category === 'dynamic' || ($asset_category === 'mobile' && $advert_type === 'static'))
                                            <td class="fs-14 sm-fs-12">{{$asset_booking->asset->print_dimension}}</td>
                                            <td class="fs-14 sm-fs-12">{{$asset_booking->asset->substrate}}</td>
                                            @else 
                                            <td class="fs-14 sm-fs-12">{{$asset_booking->asset->num_slides.' slots'}}</td>
                                            <td class="fs-14 sm-fs-12">{{$asset_booking->asset->num_slides_per_secs.' seconds'}}</td>
                                            @endif
                                            <td class="fs-14 sm-fs-12">{{$asset_booking->asset->file_format}}</td>
                                            <td class="fs-14 sm-fs-12">{{ucwords($asset_category)}}</td>
                                            <td class="fs-14 sm-fs-12">{{$asset_booking->asset->assetTypeRecord->type}}</td>
                                            <td class="fs-14 sm-fs-12">{{$asset_booking->asset->orientation}}</td>
                                            <td class="fs-14 sm-fs-12">{{$asset_booking->asset->face_count}}</td>
                                            <td class="fs-14 sm-fs-12">{{$asset_booking->asset->payment_freq}}</td>
                                            <td class="fs-14 sm-fs-12">{{$asset_booking->asset->assetLGARecords->lga_name.', '.$asset_booking->asset->assetStateRecords->state_name.' State'}}</td>
                                            <td class="fs-14 sm-fs-12">{{$asset_booking->asset->assetLCDARecords->lcda_population}}</td>
                                            <td class="fs-14 sm-fs-12">{{ \Carbon\Carbon::parse($asset_booking->created_at)->format('jS F Y') }}</td>
                                            <td class="fs-14 sm-fs-12">&#8358;{{ number_format(str_replace(',', '', $asset_booking->payment_total), 2, '.', ',') }}</td>
                                            <td class="fs-14 sm-fs-12">{{ $asset_booking->payment_records_count }}</td>
                                            <td class="fs-14 sm-fs-12">{{ count($asset_booking->pending_payment_records) }}</td>
                                            <td class="fs-14 sm-fs-12">&#8358;{{ number_format(str_replace(',', '', $asset_booking->payment_remaining), 2, '.', ',') }}</td>
                                            <td class="fs-14 sm-fs-12">
                                                @foreach($asset_booking->asset->assetProximityRecords as $ind => $proximity)
                                                    <a href="#">{{ $proximity->proximity_type }}</a>
                                                @endforeach
                                            </td>
                                            <td class="fs-14 sm-fs-12">
                                                <a href="{{url('/advertiser/individual/corporate/dealslip/'.$asset_booking->id.'/'.$asset_booking->booked_by_user_id)}}" class="btn color2-bg">Checkout Deal slip <i class="fal fa-save"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="col-lg-12 pending-transactions-table-div pt-10 pb-10">
                            <p>No Asset Booking Transaction Found.</p>
                        </div>
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection