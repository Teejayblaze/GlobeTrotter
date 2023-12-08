@extends('template.master')

@section('content')
<div class="site-breadcrumb login-breadcrumb dashboard-breadcrumb">
    <div class="container">
        <h2 class="breadcrumb-title dashboard-breadcrumb-menu-left">Payment History</h2>
        <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
            <li><a href="{{ url("/advertiser/individual/dashboard") }}">Dashboard</a></li>
            <li>Transactions</li>
            <li class="active-nav-dashboard">Payment History</li>
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
                    <h4 class="user-profile-card-title">Payment History</h4>
                    @if ( count($asset_booking_recs) )
                    <div class="col-lg-12 pending-transactions-table-div pt-10 pb-10">
                        <table class="pending-transactions-table">
                            <thead class="">
                                <tr>
                                <th class="fs-13">Site Name</th>
                                <th class="fs-13">Reservation ID</th>
                                <th class="fs-13">Transaction Reference</th>
                                <th class="fs-13">Paid?</th>
                                <th class="fs-13">Bank Reference</th>
                                <th class="fs-13">Amount</th>
                                <th class="fs-13">Type</th>
                                <th class="fs-13">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($asset_booking_recs as $key => $asset_booking_rec)
                                    @foreach ($asset_booking_rec->transaction as $transaction)
                                    <tr>
                                        <td class="fs-14 sm-fs-12">{{$asset_booking_rec->asset->name}}</td>
                                        <td class="fs-14 sm-fs-12">{{$transaction->asset_booking_ref}}</td>
                                        <td class="fs-14 sm-fs-12">{{$transaction->tranx_id}}</td>
                                        <td class="fs-14 sm-fs-12"><span>{{$transaction->paid ? 'Yes':'No'}}</span></td>
                                        <td class="fs-14 sm-fs-12">{{ $transaction->bank_ref_number??"Check status..." }}</td>
                                        <td class="fs-14 sm-fs-12">&#8358;{{ number_format(floatval(str_replace(',', '', $transaction->amount)), 2, '.', ',') }}</td>
                                        <td class="fs-14 sm-fs-12">
                                            @if ($transaction->cancel && $transaction->bank_ref_number)
                                            <span style="color: #205ad5;font-weight: 400;">Credit (CR)</span>
                                            @elseif($transaction->cancel == 0 && $transaction->bank_ref_number)
                                            <span style="color: #ec2424;font-weight: 400;">Debit (DR)</span>
                                            @else
                                            <span style="color: #d58020;font-weight: 400;">Awaiting (00)</span>
                                            @endif
                                        </td>
                                        <td class="fs-14 sm-fs-12">
                                            @if ($asset_booking_rec->paid)
                                            {{ \Carbon\Carbon::parse($transaction->updated_at)->format('jS F Y') }}
                                            @else
                                            {{ \Carbon\Carbon::parse($transaction->created_at)->format('jS F Y') }}
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="col-lg-12 pending-transactions-table-div pt-10 pb-10">
                        <p>No Payment History Found.</p>
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