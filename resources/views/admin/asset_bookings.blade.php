@extends('template.master')

@section('content')

    <style>
        .paid-item {
            background: #1e8aed; 
            padding: 3px 10px;
            border-radius: 2px;
            color: #fff;
        }

        .new-dashboard-item {
            right: 0;
            top: 0;
            background: #cc2f53;
            position: relative;
            padding: 3px 10px;
            color: #fff;
        }

        .ast-name {
            padding: 5px;
            border: 1px solid #ddd;
            display: block;
            width: fit-content;
        }

        .ast-name:hover {
            background-color: #f3f3f3;
        }

        .nav-holder nav li ul {
            min-width: 210px;
        }
    </style>

<div class="site-breadcrumb login-breadcrumb dashboard-breadcrumb">
    <div class="container">
        <h2 class="breadcrumb-title dashboard-breadcrumb-menu-left">Asset Bookings</h2>
        <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
            <li><a href="{{ url("/admin/dashboard") }}">Dashboard</a></li>
            <li class="active-nav-dashboard">Asset Bookings</li>
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
                    <h4 class="user-profile-card-title">Asset Bookings</h4>
                    @if (count($bookings))
                    <div class="col-lg-12 pending-transactions-table-div pt-10 pb-10">
                        <table class="pending-transactions-table">
                        <thead class="">
                            <tr>
                                <th class="fs-13">S/N</th>
                                <th class="fs-13">Asset Name</th>
                                <th class="fs-13">Start Date</th>
                                <th class="fs-13">End Date</th>
                                <th class="fs-13">Tx ID</th>
                                <th class="fs-13">Payment Status</th>
                                <th class="fs-13">Ad Status</th>
                                <th class="fs-13">Date Booked</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bookings as $key => $booking)
                            <tr>
                                <td class="fs-14 sm-fs-12">{{($key+1)}}</td>
                                <td class="fs-14 sm-fs-12"><a href="{{url('/asset/'.$booking->asset->id.'/detail')}}" target="_blank" class="ast-name">{{$booking->asset->name}}</a></td>
                                <td class="fs-14 sm-fs-12">{{ \Carbon\Carbon::parse($booking->start_date)->format('jS F Y') }}</td>
                                <td class="fs-14 sm-fs-12">{{\Carbon\Carbon::parse($booking->end_date)->format('jS F Y')}}</td>
                                <td class="fs-14 sm-fs-12">{{$booking->trnx_id}}</td>
                                <td class="fs-14 sm-fs-12">
                                    <?php 
                                        $paystatus = '';
                                        if ($booking->paycompleted) $paystatus = '<span class="paid-item">Completed</span>';
                                        else  $paystatus = '<span class="new-dashboard-item">In Progress</span>';

                                        echo $paystatus;
                                    ?>
                                </td>
                                <td class="fs-14 sm-fs-12">
                                    <?php 
                                        $start_date = \Carbon\Carbon::parse($booking->start_date);
                                        $end_date = \Carbon\Carbon::parse($booking->end_date);
                                        $now = \Carbon\Carbon::now();
                                        $ad_started = '';
                                        if ($now->gt($start_date)) {
                                            if (($now->gt($start_date) || $now->eq($start_date)) &&  ($now->lt($end_date) || $now->eq($end_date))) $ad_started = '<span class="paid-item">Running</span>';
                                            else $ad_started = '<span class="new-dashboard-item">Finished</span>';
                                        } else $ad_started = '<span class="paid-item">Scheduled</span>';

                                        echo $ad_started;
                                    ?>
                                </td>
                                <td class="fs-14 sm-fs-12">{{\Carbon\Carbon::parse($booking->created_at)->format('jS F Y')}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table>
                    </div>
                    @else
                    <div class="col-lg-12 pending-transactions-table-div pt-10 pb-10">
                        <p>No Booking records found!</p>
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