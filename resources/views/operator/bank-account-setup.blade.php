@extends('template.master')

@section('content')

<div class="site-breadcrumb login-breadcrumb dashboard-breadcrumb">
    <div class="container">
        <h2 class="breadcrumb-title dashboard-breadcrumb-menu-left">Disbursement Bank Setup</h2>
        <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
            <li><a href="{{url('/operator/dashboard')}}">Dashboard</a></li>
            <li>My Account</li>
            <li class="active-nav-dashboard">Disbursement Bank Setup</li>
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
                            <h4 class="user-profile-card-title">Setup Disbursement Bank Account</h4>
                            <div class="col-lg-12">
                                <div class="login-form login-form-two">
                                    <form action="{{ url('/operator/update-bank-account-setup') }}" method="post" role="form">
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

                                        @if (session()->has('flash_message'))
                                            <div class="alert alert-success">
                                                <span>{{session()->get('flash_message')}}</span>
                                            </div>
                                        @endif

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="account_name">Account Name </label>
                                                    <input type="text" class="form-control" name="account_name" id="account_name" value="{{ $operator->corporate_name ? $operator->corporate_name : $operator->account_name }}">
                                                    <i class="far fa-input-text"></i> 
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="account_number">Account Name </label>
                                                    <input type="text" class="form-control" name="account_number" id="account_number" value="{{ $operator->account_number }}">
                                                    <i class="fal fa-tally"></i> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 add-property-form">
                                                <div class="form-group">
                                                    <label for="bank_code">Select Bank <i class="fal fa-building-columns"></i></label>
                                                    <select name="bank_code" id="bank_code" data-placeholder="Select your bank" class="select">
                                                        <option value="057" @if($operator->bank_code === "057" || old("bank_code") === "057") selected @endif>Zenith Bank</option>
                                                        {{-- <option value="044" @if($operator->bank_code === "044" || old("bank_code") === "044") selected @endif>Access Bank</option>
                                                        <option value="014" @if($operator->bank_code === "014" || old("bank_code") === "014") selected @endif>Afribank</option>
                                                        <option value="023" @if($operator->bank_code === "023" || old("bank_code") === "023") selected @endif>Citibank</option>
                                                        <option value="063" @if($operator->bank_code === "063" || old("bank_code") === "063") selected @endif>Diamond Bank</option>
                                                        <option value="050" @if($operator->bank_code === "050" || old("bank_code") === "050") selected @endif>Ecobank</option>
                                                        <option value="040" @if($operator->bank_code === "040" || old("bank_code") === "040") selected @endif>Equitorial Trust Bank</option>
                                                        <option value="011" @if($operator->bank_code === "011" || old("bank_code") === "011") selected @endif>First Bank</option>
                                                        <option value="214" @if($operator->bank_code === "214" || old("bank_code") === "214") selected @endif>FCMB</option>
                                                        <option value="070" @if($operator->bank_code === "070" || old("bank_code") === "070") selected @endif>Fidelity Bank</option>
                                                        <option value="085" @if($operator->bank_code === "085" || old("bank_code") === "085") selected @endif>Finbank</option>
                                                        <option value="058" @if($operator->bank_code === "058" || old("bank_code") === "058") selected @endif>Guaranty Trust Bank</option>
                                                        <option value="069" @if($operator->bank_code === "069" || old("bank_code") === "069") selected @endif>Intercontinental Bank</option>
                                                        <option value="056" @if($operator->bank_code === "056" || old("bank_code") === "056") selected @endif>Oceanic Bank</option>
                                                        <option value="082" @if($operator->bank_code === "082" || old("bank_code") === "082") selected @endif>BankPhb</option>
                                                        <option value="076" @if($operator->bank_code === "076" || old("bank_code") === "076") selected @endif>Skye Bank</option>
                                                        <option value="084" @if($operator->bank_code === "084" || old("bank_code") === "084") selected @endif>SpringBank</option>
                                                        <option value="221" @if($operator->bank_code === "221" || old("bank_code") === "221") selected @endif>StanbicIBTC</option>
                                                        <option value="068" @if($operator->bank_code === "068" || old("bank_code") === "068") selected @endif>Standard Chartered Bank</option>
                                                        <option value="232" @if($operator->bank_code === "232" || old("bank_code") === "232") selected @endif>Sterling Bank</option>
                                                        <option value="033" @if($operator->bank_code === "033" || old("bank_code") === "033") selected @endif>United Bank for Africa (UBA)</option>
                                                        <option value="032" @if($operator->bank_code === "032" || old("bank_code") === "032") selected @endif>Union Bank</option>
                                                        <option value="035" @if($operator->bank_code === "035" || old("bank_code") === "035") selected @endif>Wema bank</option>
                                                        <option value="057" @if($operator->bank_code === "057" || old("bank_code") === "057") selected @endif>Zenith Bank</option>
                                                        <option value="215" @if($operator->bank_code === "215" || old("bank_code") === "215") selected @endif>Unity bank</option> --}}
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="address">Address </label>
                                                    <input type="text" class="form-control" name="address" id="address" value="{{ $operator->address ? $operator->address : old("address") }}">
                                                    <i class="fal fa-address-card"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="phone">Account Phone Number </label>
                                                    <input type="text" class="form-control" name="phone" id="phone" value="{{ $operator->phone ? $operator->phone : old("phone") }}">
                                                    <i class="fal fa-mobile"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email">Account Email</label>
                                                    <input type="text" class="form-control" name="email" id="email" value="{{ $operator->email ? $operator->email : old("email") }}">
                                                    <i class="fal fa-at"></i>
                                                </div>
                                            </div>
                                        </div>
                                         <p>You can change your bank information anytime.</p>
                                        <div class="d-flex justify-content-end justify-content-xm-center">
                                            <button type="submit" class="theme-btn update-profile-btn"><i class="far fa-sign-in"></i>Save Changes</button>
                                        </div>
                                    </form>
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