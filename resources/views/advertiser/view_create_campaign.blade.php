@extends('template.master')

@section('content')
    <style>

        .search-criteria-sidebar {
            position: relative;
            width: 355px;
        }
        
        .bigger {
            padding: 0 45px 0;
            height: 54px;
            line-height: 54px;
            width: 100%;
            text-align: center;
        }

        .bigger i {
            height: 54px;
            line-height: 54px;
        }

        .smaller {
            padding: 0 0px 0 7px;
            height: 30px;
            text-align: center;
            float: right;
            font-size: .8rem;
        }

        .smaller i {
            height: 27px;
            line-height: 27px;
            width: 33px;
        }

        .smaller:after, .bigger:after {
            position: unset;
        }

        .smaller.red, .bigger.red {
            background: #d72828;
        }


        .col-md-12 .campaign-title {
            padding: 20px 0 8px 0;
        }
        
        .col-md-12 .campaign-title  h3 {
            text-align: left;
            font-size: 15px;
            float:left;
        }

        .col-md-12 .campaign-title a {
            float: right;
        }

        .col-md-12 .campaign-title .clearfix {
            clear:both;
            padding-bottom: 5px;
        }

        .campaign-items {
            background-color: #fff;
            margin-bottom: 15px;
            margin-bottom: 15px;
            display: table-row;
            height: 46px;
        }

        .campaign-items div.cimages, .campaign-items p {
            float: left;
            margin-right: 15px;
            padding-bottom: 0;
        }

        .campaign-items p label {
            display: block;
            font-weight: 600 !important;
            margin-bottom: 0 !important;
            padding: 0 !important
        }

        .campaign-items p span {
            display: block;
            text-align: left;
        }

        .cimages {
            float: left;
            width: 253px;
        }

        .cimages div{
            width: 40px;
            height: 40px;
            background-color: #eee;
            border: 1px dashed #33333344
        }

        .cimages div.left, .cimages div.right {
            float: left;
            margin-bottom: 0;
            margin-right: 10px;
            cursor:pointer;
        }

        .create-btn {
            background-color: #330065;
            color: #fff;
        }

        .empty {
            color: #9b9292;
            padding: 32px 0;
            font-size: .8rem;
            text-align: center;
            display: flex;
            flex-flow: column;
            width: auto;
            justify-content: center;
            align-items: center;
        }

        .empty i.fal.fa-box-open {
            font-size: 40px;
            line-height: 46px;
        }

        .empty h4 {
            margin-top: 20px;
        }

        .empty .smaller {
            height: 31px;
            padding: 0px 10px !important;
        }

        .campaign-items.existed {
            background-color: #ff929c59;
        }

        .campaign-items.existed p, .campaign-items.existed p label {
            color: #e92727;
        }

        .suggestions {
            display: block;
            position: relative;
            height: 0;
            transition: height .4s ease-in 0s;
            overflow: hidden;
        }

        .suggestions .suggest-img {
            width: 230px;
            height: 130px;
            background-color: #fff;
            margin-right: 20px;
            margin-bottom: 10px;
            cursor: pointer;
        }

        .suggestions.scale-suggestion {
            height: 140px;
            display: flex;
            flex-flow: row nowrap;
            justify-content: center;
            margin: 21px 0px 0px 10px;
        }

        .open-sug {
            padding: 10px;
            display: block;
            width: 150px;
            background: linear-gradient(#E71755 20%, #f9958b 80%);
            transform: translate(-50%);
            margin-left: 50%;
            position: relative;
            margin-top: 8px;
            cursor: pointer;
            border-radius: 200px;
            margin-bottom: 12px;
            text-align: center;
        }

        .open-sug:hover {
            background: linear-gradient(#f9958b 20%, #e71717 80%);
        }

        .suggestions .suggest-img .sug-informatics {
            position: absolute;
            width: 100%;
            height: 100%;
            padding: 8px;
            background-color: #ffffffd1;
            opacity: 1;
            transition: opacity .4s linear 0s; 
        }

        .suggestions .suggest-img .sug-informatics p {
            color: #333;
            font-weight: 600;
            margin-right: 0;
        }

        .suggestions .suggest-img:hover .sug-informatics {
            opacity: 1;
        }

        .suggestions .suggest-img .sug-informatics a {
            padding: 2px 8px;
            margin-top: 5px;
            display: block;
            float: left;
            font-weight: 100;
        }

        .suggestions .suggest-img .sug-informatics a:nth-child(1) {
            background-color: #3300659e;
            color: #fff;
            border-radius: 35px;
        }

        .suggestions .suggest-img .sug-informatics a:nth-child(2) {
            background-color: #3aacedb8;
            color: #fff;
            border-radius: 35px;
        }
        

        .justify-content-space-between {
            justify-content: space-between;
            border-bottom: 1px solid rgba(0, 0, 0, .1);
        }

        .no-border {
            border: none;
            margin-bottom: 8px;
        }

        .pending-transactions-table tbody tr td {
            vertical-align: middle;
        }

        button, .campaign-delete {
            color: #fff;
            background-color: #c40b0b;
            border: none;
            border-radius: 10px;
        }

        .campaign-delete {
            padding: 2px 18px;
            margin-right: 8px;
        }

        .campaign-delete:hover {
            color: #fff;
        }

    </style>
    <div class="site-breadcrumb login-breadcrumb dashboard-breadcrumb hide-print">
        <div class="container">
            <h2 class="breadcrumb-title dashboard-breadcrumb-menu-left">Campaigns</h2>
            <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
                <li><a href="{{url('/advertiser/individual/dashboard')}}">Dashboard</a></li>
                <li>Campaigns</li>
                <li class="active-nav-dashboard">View Campaigns</li>
            </ul>
        </div>
    </div>
    <section id="advertiser-dashboard">
        <div class="user-profile pt-40 pb-40 dashboard-bg-color">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="user-profile-wrapper wow fadeIn" data-wow-duration="2.5s" data-wow-delay=".25s">
                            <div class="user-profile-card add-property mt-30 hide-print">
                                <div class="d-flex justify-content-space-between">
                                    <h4 class="user-profile-card-title no-border">View Campaigns</h4>
                                    <a href="{{url('/advertiser/individual/create/campaign')}}" class="btn create-btn smaller">Create Campaign<i class="fal fa-plus"></i></a>
                                </div>
                                <div class="col-lg-12 pending-transactions-table-div pt-10 pb-10">
                                    @if (session()->has('flash_error'))
                                        <div class="alert alert-danger">
                                            <span>{{session()->get('flash_error')}}</span>
                                        </div>
                                    @endif
                                    <table class="pending-transactions-table">
                                        <tbody>
                                            <tr>
                                                <th colspan="9">
                                                    @if (count($campaigns_found) === 0)
                                                        <div class="empty">
                                                            <i class="fal fa-box-open"></i>
                                                            <h4>No Campaign Record</h4>
                                                            <p style="text-align: center;float: none;margin-bottom:5px;">Kindly create a new campaign by clicking on the right button.</p>
                                                            <a href="{{url('/advertiser/individual/create/campaign')}}" class="btn create-btn smaller">Create Campaign<i class="fal fa-plus"></i></a>
                                                        </div>
                                                    @else
                                                        <?php $is_asset_booked_class = ""; ?>
                                                        @foreach($campaigns_found as $key => $campaigns)
                                                            <div class="col-md-12">
                                                                <div class="campaign-title">
                                                                    <h3>{{$campaigns->name}} ({{$campaigns->start_date}})</h3>
                                                                    @if ($campaigns->id)
                                                                        <a href="{{url('/advertiser/individual/create/campaign/'.$campaigns->id)}}" target="_blank" class="btn create-btn smaller">Add items to campaign cart<i class="fal fa-cart-plus"></i></a>
                                                                        <a href="{{url('/advertiser/individual/remove/campaign/'.$campaigns->id)}}" class="campaign-delete"><i class="fal fa-times"></i></a>
                                                                    @endif
                                                                    <p class="clearfix"></p>
                                                                </div>
                                                                <div class="campaign-list">
                                                                    <?php $grndtot = 0; ?>
                                                                    @if (count($campaigns->campaignDetails))
                                                                        <form method="post" action="{{url('/asset/campaign/create/transaction')}}" enctype="application/x-www-form-urlencoded">
                                                                            @csrf
                                                                            <input type="hidden" name="campaign_id" value="{{ $campaigns->id }}" class="required">
                                                                            <input type="hidden" name="booked_by_user_id" value="{{ $user->user_id }}" class="required">
                                                                            <input type="hidden" name="user_type_id" value="{{ $user->user_type_id }}" class="required">
                                                                            <div class="col-lg-12 pending-transactions-table-div pt-10 pb-10">
                                                                                <table class="pending-transactions-table">
                                                                                    <tbody>
                                                                                        @foreach($campaigns->campaignDetails as $idx => $campaignDetail)
                                                                                            @if ($campaignDetail->assetDetails)
                                                                                                <?php $grndtot+= $campaignDetail->assetDetails->max_price ?>
                                                                                                <tr class="campaign-items">
                                                                                                    <td class="fs-14 sm-fs-12 p-0">
                                                                                                        <div class="cimages">
                                                                                                            <!-- disk('local')-> -->
                                                                                                            @foreach($campaignDetail->assetDetails->assetImages as $imgdx => $assetImage)
                                                                                                                @if ( ($imgdx+1) % 2 === 0 )
                                                                                                                    <div class="right" style="background: url('{{Storage::url($assetImage->image_path)}}') no-repeat center center/cover #eee;"></div>
                                                                                                                @else
                                                                                                                    <div class="left" style="background: url('{{Storage::url($assetImage->image_path)}}') no-repeat center center/cover;"></div>
                                                                                                                @endif
                                                                                                                <?php if (($imgdx+1) === 4) break; ?>
                                                                                                            @endforeach
                                                                                                        </div>    
                                                                                                    </td>
                                                                                                    <td class="fs-14 sm-fs-12 p-0">{{$campaignDetail->assetDetails->name}}</td>
                                                                                                    <input type="hidden" name="aid[]" value="{{ $campaignDetail->assetDetails->id }}" class="required">
                                                                                                    
                                                                                                    <td class="fs-14 sm-fs-12 p-0">{{$campaignDetail->assetDetails->address}}</td>
                                                                                                    <td class="fs-14 sm-fs-12 p-0">{{$campaignDetail->assetDetails->asset_dimension_width.'m x '.$campaignDetail->assetDetails->asset_dimension_height.'m'}}</td>
                                                                                                    <td class="fs-14 sm-fs-12 p-0">
                                                                                                        <span>&#8358;{{ number_format(str_replace(',', '', $campaignDetail->assetDetails->max_price), 2, '.', ',') }}</span>
                                                                                                        <input type="hidden" name="price[]" value="{{ $campaignDetail->assetDetails->max_price }}" class="required">
                                                                                                    </td>
                                                                                                    <td class="fs-14 sm-fs-12 p-0">
                                                                                                        @if ($campaignDetail->assetDetails->asset_category == 'mobile')
                                                                                                            {{ ucwords($campaignDetail->assetDetails->advert_type .' '. $campaignDetail->assetDetails->asset_category) }}
                                                                                                        @else
                                                                                                            {{ ucwords($campaignDetail->assetDetails->asset_category) }}
                                                                                                        @endif
                                                                                                    </td>
                                                                                                    <td class="fs-14 sm-fs-12 p-0">{{$campaignDetail->assetDetails->payment_freq}}</td>
                                                                                                    <td class="fs-14 sm-fs-12 p-0">
                                                                                                        <a href="{{url('/advertiser/individual/remove/campaign/booking/'.$campaignDetail->id)}}" class="">Remove <i class="fal fa-times"></i></a>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            @else 
                                                                                                <tr>
                                                                                                    <td colspan="9" class="fs-14 sm-fs-12 p-0">
                                                                                                        <p>There are no item(s) in this campaign cart.</p>
                                                                                                        <a href="{{url('/advertiser/individual/create/campaign/'.$campaigns->id)}}" class="btn create-btn smaller">Add items to campaign cart<i class="fal fa-cart-plus"></i></a>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            @endif
                                                                                        @endforeach
                                                                                        <tr>
                                                                                            <td colspan="9" class="fs-14 sm-fs-12 p-0">
                                                                                                <p style="display: inline-block;"><button type="submit">Generate Dealslip and Make Payment &raquo;</button></p>
                                                                                                <p style="font-weight:bolder; float:right; margin-right:40px;">Grand Total&nbsp;&nbsp;&nbsp;&#8358;{{ number_format(str_replace(',', '', $grndtot), 2, '.', ',') }}</p>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </form>
                                                                    @else 
                                                                        <div class="campaign-items">
                                                                            <p>No active cart record.</p>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection