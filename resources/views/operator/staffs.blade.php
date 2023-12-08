@extends('template.master')

@section('content')

<div class="site-breadcrumb login-breadcrumb dashboard-breadcrumb hide-print">
    <div class="container">
        <h2 class="breadcrumb-title dashboard-breadcrumb-menu-left">Staff Details</h2>
        <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
            <li><a href="{{url('/operator/dashboard')}}">Dashboard</a></li>
            <li class="active-nav-dashboard">Staff</li>
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
                                    <h4 class="welcome-text fs-15 create-campaign-text">Staff Details</h4>
                                </div>
                                @if ($user->admin)
                                <div class="col-md-4">
                                    <div class="create-campaign-btn-div text-right text-align-left">
                                        <a href="{{url('/operator/create-staff')}}">
                                            <button class="theme-btn create-campaign-btn fs-14">
                                                <span class="create-campaign-icon fal fa-plus"></span>
                                                Create Staff
                                            </button>
                                        </a>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        @if ( count($staffs) )
                        <div class="user-profile-card add-property mt-30 hide-print">
                            <h4 class="user-profile-card-title">Added Staff</h4>
                            <div class="col-lg-12 pending-transactions-table-div pt-10 pb-10">
                                <table class="pending-transactions-table">
                                    <thead class="">
                                        <tr>
                                            <th class="fs-13">S/N</th>
                                            <th class="fs-13">Firstname</th>
                                            <th class="fs-13">Lastname</th>
                                            <th class="fs-13">Email</th>
                                            <th class="fs-13">Phone</th>
                                            <th class="fs-13">Designation</th>
                                            <th class="fs-13">Status</th>
                                            <th class="fs-13">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($staffs as $key => $staff)
                                        <tr>
                                            <td class="fs-14 sm-fs-12">{{($key+1)}}.</td>
                                            <td class="fs-14 sm-fs-12">{{$staff->firstname}}</td>
                                            <td class="fs-14 sm-fs-12">{{$staff->lastname}}</td>
                                            <td class="fs-14 sm-fs-12">{{$staff->email}}</td>
                                            <td class="fs-14 sm-fs-12">{{$staff->phone}}</td>
                                            <td class="fs-14 sm-fs-12">{{$staff->designation}}</td>
                                            <td class="fs-14 sm-fs-12">{{$staff->blocked?'Blocked':'Active'}}</td>
                                            <td class="fs-14 sm-fs-12"><a href="{{url('/operator/edit-staff/'.$staff->id)}}" class="edit-staff">Edit <i class="fal fa-pen"></i></a></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @else 
                        <div class="login-form add-campaign-div mt-30 wow fadeInUp hide-print" data-wow-duration="2.5s">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="welcome-text fs-15 create-campaign-text">No Staff Records Found.</h4>
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