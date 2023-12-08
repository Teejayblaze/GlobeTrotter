@extends('template.master')

@section('content')

<style>
    .smaller {
        padding: 0 10px 0 10px;
        height: 24px;
        line-height: 24px;
        background: #5ecfb1;
    }

    .smaller:after {
        position: unset;
    }

    .smaller.red {
        background: #d72828;
    }

    .smaller.blue {
        background: #49ceff;
    }

    .pending-transactions-table tbody tr td:last-of-type a {
        vertical-align: bottom;
    }
</style>


<div class="site-breadcrumb login-breadcrumb dashboard-breadcrumb">
    <div class="container">
        <h2 class="breadcrumb-title dashboard-breadcrumb-menu-left">Corporate Staff</h2>
        <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
            <li><a href="{{ url("/advertiser/individual/corporate/dashboard") }}">Dashboard</a></li>
            <li>Administrator Menu</li>
            <li class="active-nav-dashboard">Corporate Staff</li>
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
                            <h4 class="user-profile-card-title">Corporate Staff</h4>
                            @if(count($staffs))
                            <div class="col-lg-12 pending-transactions-table-div pt-10 pb-10">
                                <table class="pending-transactions-table">
                                    <thead class="">
                                        <tr>
                                            <th class="fs-13">S/N</th>
                                            <th class="fs-13">Name</th>
                                            <th class="fs-13">Designation</th>
                                            <th class="fs-13">Phone</th>
                                            <th class="fs-13">Email</th>
                                            <th class="fs-13">Activated Account?</th>
                                            <th class="fs-13">Platform Active?</th>
                                            <th class="fs-13">Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($staffs as $index => $staff)
                                        <tr>
                                            <td class="fs-14 sm-fs-12">{{($index+1)}}</td>
                                            <td class="fs-14 sm-fs-12">{{$staff->lastname .' '. $staff->firstname}}</td>
                                            <td class="fs-14 sm-fs-12">{{$staff->designation}}</td>
                                            <td class="fs-14 sm-fs-12">{{$staff->phone?$staff->phone:'NIL'}}</td>
                                            <td class="fs-14 sm-fs-12">{{$staff->email?$staff->email:'NIL'}}</td>
                                            <td class="fs-14 sm-fs-12">{{$staff->active?'YES':'NO'}}</td>
                                            <td class="fs-14 sm-fs-12">
                                                @if ($staff->blocked)
                                                <span class="btn smaller red fs-12">BLOCKED</span>
                                                @else 
                                                <span class="btn smaller blue fs-12">ACTIVE</span>
                                                @endif
                                            </td>
                                            <td class="fs-14 sm-fs-12">
                                                <a href="{{url('/advertiser/individual/corporate/staff/'.$staff->id)}}" class="theme-btn theme-btn-custom fs-12">Details</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="col-lg-12 pending-transactions-table-div pt-10 pb-10">
                                <p class=" fs-13">No Staff Records Found.</p>
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