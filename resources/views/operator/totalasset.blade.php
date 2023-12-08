@extends('template.master')

@section('content')
<div class="site-breadcrumb login-breadcrumb dashboard-breadcrumb">
    <div class="container">
        <h2 class="breadcrumb-title dashboard-breadcrumb-menu-left">Total Assets</h2>
        <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
            <li><a href="{{url('/operator/dashboard')}}">Dashboard</a></li>
            <li>Owned Asset</li>
            <li class="active-nav-dashboard">Total Asset</li>
        </ul>
    </div>
</div>
<section id="advertiser-dashboard">
    <div class="user-profile pt-40 pb-40 dashboard-bg-color">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                <div class="user-profile-wrapper wow slideInRight" data-wow-duration="2.5s" data-wow-delay=".25s">
                    <div class="user-profile-card add-property">
                        <h4 class="user-profile-card-title">Total Asset</h4>
                        <div class="col-lg-12 pending-transactions-table-div vacant-asset-table-div pt-10 pb-10">
                        @if ( count($total_assets) )
                        <?php $sn = 0 ?>
                        <table class="pending-transactions-table vacant-asset-table">
                            <thead class="">
                                <tr>
                                <th class="fs-13">S/N</th>
                                <th class="fs-13">Asset Name</th>
                                <th class="fs-13">Asset Price (&#8358;)</th>
                                <th class="fs-13">Asset Type</th>
                                <th class="fs-13">Nearest Location</th>
                                <th class="fs-13">Date Added</th>
                                <th class="fs-13">Board Type</th>
                                <th class="fs-13">Status</th>
                                <th class="fs-13">+ Video</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($total_assets as $total_asset)
                                <?php $sn++ ?>
                                <tr>
                                    <td class="fs-14 sm-fs-12">{{ $sn }}</td>
                                    <td class="fs-14 sm-fs-12"><a href="{{url('/asset/'.$total_asset->id.'/detail')}}" target="_blank"><span>{{ $total_asset->name }}</span></a></td>
                                    <td class="fs-14 sm-fs-12">
                                        @if ($total_asset->min_price || $total_asset->max_price)
                                            Min &#8358;{{ number_format(str_replace(',', '', $total_asset->min_price), 2, '.', ',') }} - Max &#8358;{{ number_format(str_replace(',', '', $total_asset->max_price), 2, '.', ',') }}
                                        @endif
                                    </td>
                                    <td class="fs-14 sm-fs-12">{{ $total_asset->asset_typex }}</td>
                                    <td class="fs-14 sm-fs-12">
                                        Asset is close to the following landmarks <br>
                                        @foreach ($total_asset->proximities as $proximities)
                                            <strong>{{ucwords($proximities->proximity_type)}}:</strong> {{$proximities->proximity_name}} <br>
                                        @endforeach
                                    </td>
                                    <td class="fs-14 sm-fs-12">{{ $total_asset->date_added }}</td>
                                    <td class="fs-14 sm-fs-12">{{ ucwords($total_asset->asset_category) }}</td>
                                    <td class="fs-14 sm-fs-12">
                                        @if ($total_asset->status === "booked")
                                            <span style="color: #c40b0b;" class="fs-12">{{ $total_asset->status }}</span>
                                        @else
                                            <span style="color: rgb(9, 185, 59);" class="fs-12">{{ $total_asset->status }}</span>
                                        @endif
                                    </td>
                                    <td class="fs-14 sm-fs-12"><a href="{{url('operator/edit-asset-video/'.$total_asset->id)}}"><span class="fal fa-video"></span></a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                            <p>No Total Asset Found.</p>
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