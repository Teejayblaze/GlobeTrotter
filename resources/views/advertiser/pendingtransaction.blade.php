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
                    <h4 class="user-profile-card-title">Pending Transactions</h4>
                    @if ( count($pending_tranx_recs) )
                    <div class="col-lg-12 pending-transactions-table-div pt-10 pb-10">
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
                                    <a href="{{url('/advertiser/individual/pending/transaction/payments/detail/'.$pending_tranx_rec->id)}}">
                                        <span class="wow flash" data-wow-duration="2.5s" data-wow-iteration="infinite">Payment Required</span>
                                        {{-- <span class="wow flash" data-wow-duration="2.5s" data-wow-iteration="infinite">{{$pending_tranx_rec->payment_remaining_perc . '% Payment Required'}}</span> --}}
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table>
                    </div>
                    @else
                    <div class="col-lg-12 pending-transactions-table-div pt-10 pb-10">
                        <p>No Transaction Records Found.</p>
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