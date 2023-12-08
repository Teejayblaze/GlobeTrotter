@extends('template.master')

@section('content')
<div class="site-breadcrumb login-breadcrumb dashboard-breadcrumb">
    <div class="container">
        <h2 class="breadcrumb-title dashboard-breadcrumb-menu-left">Subscription</h2>
        <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
            <li><a href="{{url('/operator/dashboard')}}">Dashboard</a></li>
            <li class="active-nav-dashboard">Subscription</li>
        </ul>
    </div>
</div>
<section id="advertiser-dashboard">
    <div class="user-profile pt-40 pb-40 dashboard-bg-color">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="user-profile-sidebar operator-profile-sidebar wow slideInLeft" data-wow-duration="2.5s" data-wow-delay=".25s">
                        <div class="user-profile-sidebar-top mt-20">
                            {{-- <img class="operator-image" src="{{ asset('/img/operators/e-motion.png') }}" alt="Operator Image"> --}}
                            <p class="welcome-text fs-18 mt-10">Welcome</p>
                            <h3 class="profile-name operator-profile-name">{{ $user->firstname?$user->lastname:$user->corporate_name }}</h3>
                        </div>
                            @if ($user->corp_id)
                            <div class="user-profile-sidebar-top profile-detail mt-40">
                                <h6 class="profile-position">{{$user->designation}} <br><br> <span>@</span></h6>
                                <h3 class="profile-company mt-20">{{$user->work_with->corporate_name}}</h3>
                            </div>
                            @endif
                        <div class="edit-profile-div text-center mt-20 mb-30">
                            <a href=""><button class="theme-btn edit-profile-btn">SUBSCRIPTION STATUS: {{$user->subscription == 1 ? 'POSITIVE':'NEGATIVE'}}</button></a>
                        </div>
                        <div class="profile-stats text-center">
                            <div class="row">
                               <div class="col-lg-6 profile-stats-divider">
                                <a href="{{ url('/operator/totalasset') }}" style="width: 100%">
                                 <div class="profile-stats-border-right mb-10">
                                <div class="profile-stats-title">Total Asset</div>
                                 <div class="profile-stats-value">{{ $uploaded_asset_count }}</div>
                                 </div>
                                </a>
                               </div>
                               <div class="col-lg-6 profile-stats-divider">
                                 <a href="{{ url('/operator/vacantasset') }}">
                                     <div class="mb-10">
                                     <div class="profile-stats-title">Vacant Asset</div>
                                      <div class="profile-stats-value">{{ $vacant_asset_count }}</div>
                                     </div>
                                 </a>
                               </div>
                               <div class="col-lg-12">
                                 <a href="{{ url('/operator/bookedasset') }}">
                                     <div class="mt-10">
                                         <div class="profile-stats-title">Booked Asset</div>
                                         <div class="profile-stats-value">{{ $ordered_asset_count }}</div>
                                     </div>
                                 </a>
                               </div>
                            </div>
     
                           </div>
                        <ul class="user-profile-sidebar-list text-center">
                            <li><a class="profile-logout-btn" href="{{ url('/operator/logout') }}"><i class="far fa-sign-out"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="user-profile-wrapper wow slideInRight" data-wow-duration="2.5s" data-wow-delay=".25s">
                        <div class="user-profile-card add-property">
                            <h4 class="user-profile-card-title">Annual Subscription Payment</h4>
                            <div class="col-lg-12">
                                <div class="login-form login-form-two">
                                    @if (session()->has('transaction'))
                                        @if (session()->has('flash_message'))
                                            <div class="alert alert-success">
                                                <span>{{session()->get('flash_message')}}</span>
                                            </div>
                                        @endif
                                        @if (session()->has('error_message'))
                                            <div class="alert alert-success">
                                                <span>{{session()->get('error_message')}}</span>
                                            </div>
                                        @endif
                                    <div class="alert alert-warning">
                                        <?php $transaction = session()->get('transaction'); ?>
                                        <div class="row">
                                            @if ($transaction->paid)
                                            <div class="col-md-12">You have already made payment for this year {{date('Y')}}.</div>
                                            @else
                                            <div class="col-md-12">
                                                {{$transaction->description}}
                                            </div>
                                            <div class="col-md-12">&nbsp;</div>
                                            <div class="col-md-8">
                                                Your transaction ID: <strong>{{$transaction->tranx_id}}</strong>
                                                <a href="{{url('operator/transaction/regenerate-reference/' . $transaction->asset_booking_id . '/' . $transaction->id)}}" style="font-size: 12px;display: block;">Regenerate Transaction?</a>
                                            </div>
                                            <div class="col-md-4">
                                            {{-- Process Global Pay Integration. --}}
                                            <form id="global-pay" target="_blank" action="{{env("APP_GLOBAL_PAY_FORM_PAYMENT_URL")}}" method="post">
                                                <input type="hidden" id="names" name="names" value="{{ $user->corporate_name }}">
                                                <input type="hidden" id="amount" name="amount" value="{{$transaction->amount}}">
                                                <input type="hidden" id="email_address" name="email_address" value="{{$user->email}}">
                                                <input type="hidden" id="phone_number" name="phone_number" value="{{$user->phone}}">
                                                <!-- Please change the currency as suited to your merchant-->
                                                <input type="hidden" id="currency" name="currency" value="{{env("APP_GLOBAL_PAY_CURRENCY")}}">
                                                <input type="hidden" id="merch_txnref" name="merch_txnref" value="{{$transaction->tranx_id}}"><!-- Merchant reference number should be unique per transaction-->
                                                <!-- Merchant Id value should be changed to your merchant id-->
                                                {{--  --}}
                                                <input type="hidden" id="merchantid" name="merchantid" value="{{env("APP_GLOBAL_PAY_MERCHANT_ID")}}">
                                                <button type="submit" class="theme-btn theme-btn-custom"><i class="far fa-credit-card"></i>Confirm Payment</button>
                                            </form>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @else
                                    <form action="{{ url('/operator/subscription') }}" method="post" role="form">
                                        @csrf
                                        @if ( $errors->any() )
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach( $errors->all() as $error )
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        @if (session()->has('success'))
                                            <div class="alert alert-success">
                                                <span>{{session()->get('success')}}</span>
                                            </div>
                                        @endif

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="oaan_number">{{env("APP_OWNER_NAME")}} Number <i class="fal fa-address-card"></i> </label>
                                                        <input type="text" class="form-control" name="oaan_number" id="oaan_number" readonly value="{{ $user->oaan_number }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="subscription_amount">{{env("APP_OWNER_NAME")}} Subscription Amount<i class="fal fa-credit-card"></i> </label>
                                                        <input type="text" class="form-control" name="subscription_amount" id="subscription_amount" readonly value="{{ $subscription_amount }}">
                                                </div>
                                            </div>
                                            <p class="mb-20">Kindly make payment for your annual subscription</p>
                                            <div class="d-flex justify-content-end justify-content-xm-center">
                                                <button type="submit" class="theme-btn update-profile-btn"><i class="far fa-sign-in"></i>Make Payment</button>
                                            </div>
                                        </div>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection