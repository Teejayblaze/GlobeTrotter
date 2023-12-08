@extends('template.master')

@section('content')

<style>

    .smaller {
        padding: 0 10px 0 10px;
        height: 24px;
        line-height: 24px;
        background: #5ecfb1;
    }

    .bigger {
        padding: 0 45px 0;
        height: 54px;
        line-height: 54px;
        margin: 25px 0 45px 0;
    }

    .smaller:after, .bigger:after {
        position: unset;
    }

    .smaller.red, .bigger.red {
        background: #d72828;
    }

    .smaller.blue, .bigger.blue {
        background: #49ceff;
    }

</style>

<div class="site-breadcrumb login-breadcrumb dashboard-breadcrumb">
    <div class="container">
        <h2 class="breadcrumb-title dashboard-breadcrumb-menu-left">Corporate Staff</h2>
        <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
            <li><a href="{{ url("/advertiser/individual/corporate/dashboard") }}">Dashboard</a></li>
            <li>Administrator Menu</li>
            <li>Corporate Staff</li>
            <li class="active-nav-dashboard">Single Corporate Staff</li>
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
                            <h4 class="user-profile-card-title">Staff Details</h4>
                            @if($staff_details)     
                            <div class="col-lg-12 pending-transactions-table-div pt-10 pb-10">
                                <table class="pending-transactions-table">
                                    <thead class="">
                                        <tr>
                                            <th class="fs-13">Name</th>
                                            <th class="fs-13">Address</th>
                                            <th class="fs-13">Phone</th>
                                            <th class="fs-13">Email</th>
                                            <th class="fs-13">Designation</th>
                                            <th class="fs-13">Activated Account?</th>
                                            <th class="fs-13">Platform Active?</th>
                                            <th class="fs-13">Date Joined</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="fs-14 sm-fs-12">{{$staff_details->lastname .' '. $staff_details->firstname}}</td>
                                            <td class="fs-14 sm-fs-12">{{$staff_details->address?$staff_details->address:'NIL'}}</td>
                                            <td class="fs-14 sm-fs-12">{{$staff_details->phone?$staff_details->phone:'NIL'}}</td>
                                            <td class="fs-14 sm-fs-12">{{$staff_details->email?$staff_details->email:'NIL'}}</td>
                                            <td class="fs-14 sm-fs-12">{{$staff_details->designation}}</td>
                                            <td class="fs-14 sm-fs-12">
                                                @if (!$staff_details->active)
                                                <span class="btn smaller red fs-12">YET TO CONFIRM EMAIL</span>
                                                @else 
                                                <span class="btn smaller blue fs-12">YES</span>
                                                @endif
                                            </td>
                                            <td class="fs-14 sm-fs-12">
                                                @if ($staff_details->blocked)
                                                <span class="btn smaller red fs-12">BLOCKED</span>
                                                @else 
                                                <span class="btn smaller blue fs-12">ACTIVE</span>
                                                @endif
                                            </td>
                                            <td class="fs-14 sm-fs-12">
                                                {{\Carbon\Carbon::parse($staff_details->created_at)->format('jS F Y')}}</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @endif
                        </div>
                        <div class="user-profile-card add-property">
                            <h4 class="user-profile-card-title">Booking Details</h4>
                            <div class="col-lg-12 pending-transactions-table-div pt-10 pb-10">
                                @if(count($booking_details))  
                                <table class="pending-transactions-table">
                                    <thead class="">
                                        <tr>
                                            <th class="fs-13">S/N</th>
                                            <th class="fs-13">Site Name</th>
                                            <th class="fs-13">Site Type</th>
                                            <th class="fs-13">Board Type</th>
                                            <th class="fs-13">Price</th>
                                            <th class="fs-13">Site Location</th>
                                            <th class="fs-13">Site Dimension (width x height)</th>
                                            <th class="fs-13">Advert Start</th>
                                            <th class="fs-13">Advert End</th>
                                            <th class="fs-13">Site Booked At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($booking_details as $index => $booking)
                                        <tr>
                                            <td class="fs-14 sm-fs-12">{{($index+1)}}</td>
                                            <td class="fs-14 sm-fs-12">><a href="{{url('/asset/'.$booking->asset_id.'/detail')}}" style="color: #0ca4d8;border: 1px solid #87d7f0; padding: 7px;" target="_blank">{{$booking->asset->name}}</a></td>
                                            <td class="fs-14 sm-fs-12">{{$booking->asset->assetTypeRecord->type}}</td>
                                            <td class="fs-14 sm-fs-12">{{ucwords($booking->asset->asset_category)}}</td>
                                            <td class="fs-14 sm-fs-12">&#8358;{{ number_format(str_replace(',', '', $booking->asset->max_price), 2, '.', ',') }}</td>
                                            <td class="fs-14 sm-fs-12">{{$booking->asset->address}}</td>
                                            <td class="fs-14 sm-fs-12">{{$booking->asset->asset_dimension_width.'m x '.$booking->asset->asset_dimension_height.'m'}}</td>
                                            <td class="fs-14 sm-fs-12">{{\Carbon\Carbon::parse($booking->start_date)->format('jS F Y')}}</td>
                                            <td class="fs-14 sm-fs-12">{{\Carbon\Carbon::parse($booking->end_date)->format('jS F Y')}}</td>
                                            <td class="fs-14 sm-fs-12">{{\Carbon\Carbon::parse($booking->created_at)->format('jS F Y')}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @else
                                    <p class="fs-13">No Booking Records found for this account.</p>
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