@extends('template.master')

@section('content')

<style>

    .reverse-action {
        padding: 5px 30px 5px 5px;
        border: 1px solid #ddd;
        display: block;
    }

    .reverse-action:hover {
        background-color: #f3f3f3;
    }

    .reverse-action i.fal {
        left: 60px;
        top: 6px;
    }

    .paid-item {
        background: #1e8aed; 
        padding: 3px 10px;
        border-radius: 2px;
        color: #fff;
    }

    .staff-table {
        width: 100%;
        clear: both;
    }

    .hide-staff-table {
       display: none;
    }

    .staff-table table {
        border-collapse:collapse;
        border:1px solid #eee;
        width: 100%;
    }

    .staff-table table th, .staff-table table td {
        padding: 4px;
        font-size: 11px;
        border: 1px solid #eee;
        text-align: left;
    }

    .staff-table table td {
        font-weight: 100;
    }        
</style>

<div class="site-breadcrumb login-breadcrumb dashboard-breadcrumb hide-print">
    <div class="container">
        <h2 class="breadcrumb-title dashboard-breadcrumb-menu-left">Platform Accounts</h2>
        <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
            <li><a href="{{url('/admin/dashboard')}}">Dashboard</a></li>
            <li class="active-nav-dashboard">Platform Accounts</li>
        </ul>
    </div>
</div>
<section id="advertiser-dashboard">
    <div class="user-profile pt-40 pb-40 dashboard-bg-color">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="user-profile-wrapper wow fadeIn" data-wow-duration="2.5s" data-wow-delay=".25s">
                        <div class="login-form add-campaign-div wow fadeInUp hide-print" data-wow-duration="2.5s">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4 class="welcome-text fs-15 create-campaign-text">Platform Accounts</h4>
                                </div>
                            </div>
                        </div>

                        @if ( count($platform_users) )
                        <div class="user-profile-card add-property mt-30 hide-print">
                            <h4 class="user-profile-card-title">Added Staff</h4>
                            <div class="col-lg-12 pending-transactions-table-div pt-10 pb-10">
                                <table class="pending-transactions-table">
                                    <thead class="">
                                        <tr>
                                            <th class="fs-13">S/N</th>
                                            <th class="fs-13">Name</th>
                                            <th class="fs-13">User Type</th>
                                            <th class="fs-13">RC Number</th>
                                            <th class="fs-13">Website</th>
                                            <th class="fs-13">Address</th>
                                            <th class="fs-13">Accepted T&amp;C?</th>
                                            <th class="fs-13">Email</th>
                                            <th class="fs-13">Phone</th>
                                            <th class="fs-13">Active?</th>
                                            <th class="fs-13">Blocked By Platform?</th>
                                            <th class="fs-13">Email Verified?</th>
                                            <th class="fs-13">Date Verified</th>
                                            <th class="fs-13">No of Staff</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($platform_users as $userType => $platform_user)
                                        <?php $count = 0; $advt_arr = ['corporate', 'individual']; $advt_op_arry = ['corporate', 'operator']; ?>
                                            @foreach ($platform_user as $idx => $user)
                                            <?php 
                                                $userTypex = $userType === $advt_op_arry[1] ? 'asset owner': $userType;
                                                $userTypes = ucwords($userTypex);
                                                if ( in_array($userType, $advt_arr) ) $userTypes = ucwords($userType.' advertiser');
                                                $count++;

                                                $name = '';
                                                if ($userType === $advt_arr[0]) {
                                                    $name = $user->name;
                                                } else if (!in_array($userType, $advt_arr)) {
                                                    $name = $user->corporate_name;
                                                } else if ($userType === $advt_arr[1]) {
                                                    $name = $user->lastname.' '.$user->firstname;
                                                }
                                            ?>
                                            <tr>
                                                <td class="fs-14 sm-fs-12">{{($count)}}.</td>
                                                <td class="fs-14 sm-fs-12">{{$name}}</td>
                                                <td class="fs-14 sm-fs-12">{{$userTypes}}</td>
                                                @if (in_array($userType, $advt_op_arry))
                                                <td class="fs-14 sm-fs-12">{{$user->rc_number}}</td>
                                                @endif
                                                @if ($userType === $advt_op_arry[0])
                                                <td class="fs-14 sm-fs-12">{{$user->website}}</td>
                                                @endif
                                                @if (in_array($userType, $advt_arr))
                                                <td class="fs-14 sm-fs-12">{{$user->address}}</td>
                                                @endif
                                                <td class="fs-14 sm-fs-12">
                                                    @if (in_array($userType, $advt_arr))
                                                        @if ($user->tandc)
                                                            <span class="paid-item">YES</span>
                                                        @else
                                                            <span class="new-dashboard-item">NO</span>
                                                        @endif
                                                    @else
                                                        <span class="paid-item">YES</span>
                                                    @endif    
                                                </td>
                                                <td class="fs-14 sm-fs-12">{{$user->email}}</td>
                                                <td class="fs-14 sm-fs-12">{{$user->phone}}</td>
                                                <td class="fs-14 sm-fs-12">
                                                    @if ($user->active)
                                                    <span class="paid-item">YES</span>
                                                    @else
                                                    <span class="new-dashboard-item">NO</span>
                                                    @endif
                                                </td>
                                                <td class="fs-14 sm-fs-12">
                                                    @if ($user->blocked)
                                                        <span class="new-dashboard-item">YES</span>
                                                    @else
                                                        <span class="paid-item">NO</span>
                                                    @endif    
                                                </td>
                                                <td class="fs-14 sm-fs-12">
                                                    @if ($user->email_verified)
                                                    <span class="paid-item">YES</span>
                                                    @else
                                                    <span class="new-dashboard-item">NO</span>
                                                    @endif
                                                </td>
                                                <td class="fs-14 sm-fs-12">
                                                    @if ($user->email_verified_at)
                                                    {{\Carbon\Carbon::parse($user->email_verified_at)->format('jS F Y')}}
                                                    @else
                                                    <span class="new-dashboard-item">Not Verified</span>
                                                    @endif
                                                </td>
                                                <td class="fs-14 sm-fs-12">{{\Carbon\Carbon::parse($user->email_verified_at)->format('jS F Y')}}</td>
                                                <td class="fs-14 sm-fs-12">
                                                    @if (in_array($userType, $advt_op_arry))
                                                    <a href="#" class="edit-staff"><span>{{count($user->staffs)}}</span></a>
                                                    <div class="staff-table hide-staff-table">
                                                        <table>
                                                            <thead>
                                                                <tr>
                                                                    <th>S/N</th>
                                                                    <th>Name</th>
                                                                    <th>Phone</th>
                                                                    <th>Email</th>
                                                                    <th>Designation</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($user->staffs as $indx => $staff)
                                                                    <tr>
                                                                        <td>{{($indx+1)}}.</td>
                                                                        <td>{{$staff->lastname .' '. $staff->firstname}}</td>
                                                                        <td>{{$staff->phone}}</td>
                                                                        <td>{{$staff->email}}</td>
                                                                        <td>{{$staff->designation}}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @else 
                        <div class="login-form add-campaign-div mt-30 wow fadeInUp hide-print" data-wow-duration="2.5s">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="welcome-text fs-15 create-campaign-text">No Platform Accounts Found.</h4>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection