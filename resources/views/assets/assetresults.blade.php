@extends('template.master')

@section('content')

<style>
    .hover-board {
        position: relative;
        padding: 15px;
        background-color: #fff;
        border-radius: 10px;
        margin-bottom: 25px;
        box-shadow: 0 0 40px 5px rgb(0 0 0 / 5%);
        transition: all .5s ease-in-out;
    }
    .listing-item {
        background: #fff;
        border-radius: 0;
        margin-bottom: 0;
        box-shadow: none;
        padding: 0;
    }
    .dynamic-content {
        position: absolute;
        width: 100%;
        left: 0;
        display: none;
        padding: 15px;
        background-color: #fff;
        z-index: 99999999;
        margin-top: -23px;
    }

    .hover-board:hover .dynamic-content {
        display: block;
    }
</style>
<div class="site-breadcrumb login-breadcrumb dashboard-breadcrumb">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-12">
                <div>
                <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
                    <li><a href="{{url('/')}}">Home</a></li>
                    <li>Book a Site</li>
                    <li>Search</li>
                    <li class="active-nav-dashboard">Results</li>
                </ul>
                </div>
            </div>
            <div class="col-lg-6 col-md-4 col-12"></div>
            <div class="col-lg-2 col-md-2 col-12">
                <div class="dashboard-breadcrumb-menu-right text-align-left breadcrumb-logout-btn-div">&nbsp;</div>
            </div>
        </div>
    </div>
</div>
<section id="site-location-search">
    <div class="user-profile pt-40 pb-40 dashboard-bg-color">
        @forelse ($asset_category as $cat_idx => $asset_cat)
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="login-form search-page wow fadeIn" data-wow-duration="2.5s" data-wow-delay=".75s">
                        <div class="user-profile-sidebar-top">
                            <h3 class="profile-name dashboard-search-title">{{ $asset_cat }}</h3>
                        </div>
                        <div class="user-profile-sidebar-top text-left borderless-bottom padding-margin-bottom-none"></div>
                    </div>
                    <div class="property-sort">
                        <p class="welcome-text search-welcome-text">({{ number_format($asset_category_count[$asset_cat], 0, '', ',') }}) Result found, Kindly scroll down to see more results</p>
                    </div>
                </div>
            </div>
            <div class="row mb-40">
                @foreach ($available_asset as $key => $asset)
                    @if ( $asset->type === $asset_cat)
                    <?php 
                        $rand = 0;
                        if (count($asset->images->toArray())) {
                            $rand = array_rand($asset->images->toArray());
                        }
                    ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="hover-board">
                            <div class="listing-item wow fadeIn" data-wow-duration="2.5s" data-wow-delay=".25s">
                                <span class="listing-badge">Available</span>
                                <div class="listing-img">
                                    @if(count($asset->images))
                                    <img src="{{ Storage::url($asset->images[$rand]->image_path) }}" alt="Featured image">
                                    @endif
                                </div>
                                <div class="listing-content">
                                    <h4 class="listing-title result-listing-title"><a href="{{ url('asset/'. $asset->id .'/detail') }}">{{ $asset->name }}</a></h4>
                                    <p class="listing-sub-title mb-4 result-listing-address"><i class="far fa-location-dot"></i>{{ $asset->address }}</p>
                                </div>
                            </div>
                            <div class="dynamic-content">
                                <div class="listing-bottom">
                                    <div class="listing-price-info mb-10">&nbsp;</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-6">
                                        <p>
                                            <i class="asset-info-icon fal fa-text-width"></i><span class="asset-info-title fb fs-14">Dimension</span>
                                        </p>
                                        <p class="fs-13">{{$asset->asset_dimension_width .'m x '. $asset->asset_dimension_width .'m'}}</p>
                                    </div>
                                    @if ($asset->asset_category == 'digital' || ($asset->asset_category == 'mobile' && $asset->advert_type == 'digital'))
                                        <div class="col-md-6 col-6">
                                            <p>
                                                <i class="asset-info-icon fal fa-hdd"></i><span
                                                class="asset-info-title fb fs-14">Maximum Slots</span>
                                            </p>
                                            <p class="fs-13">{{$asset->num_slides}}</p>
                                        </div>
                                    @else
                                        <div class="col-md-6 col-6">
                                            <p>
                                                <i class="asset-info-icon fal fa-print"></i><span
                                                class="asset-info-title fb fs-14">Print Dimension</span>
                                            </p>
                                            <p class="fs-13">{{$asset->print_dimension?$asset->print_dimension:'N/A'}}</p>
                                        </div>
                                    @endif
                                    <div class="col-md-6 col-6">
                                        <p><i class="asset-info-icon fal fa-money-bill"></i><span
                                            class="asset-info-title fb fs-14">Price</span></p>
                                        <p class="fs-13">&#8358;{{ number_format(str_replace(',', '', $asset->max_price), 2, '.', ',') }}</p>
                                    </div>
                                    <div class="col-md-6 col-6">
                                        <p><i class="asset-info-icon fal fa-donate"></i><span
                                            class="asset-info-title fb fs-14">Pay Frequency</span></p>
                                        <p class="fs-13">{{$asset->payment_freq}}</p>
                                    </div>
                                    <div class="col-md-6 col-6">
                                        <p><i class="asset-info-icon fas fa-chalkboard"></i><span
                                            class="asset-info-title fb fs-14"> Board Type</span></p>
                                            @if ($asset->asset_category == 'mobile') 
                                                <p class="fs-13">{{ ucwords($asset->advert_type .' '. $asset->asset_category) }}</p>
                                            @else 
                                            <p class="fs-13">{{ ucwords($asset->asset_category) }}</p>
                                            @endif
                                    </div>
                                    <div class="col-md-6 col-6">
                                        <p><i class="asset-info-icon fal fa-cubes"></i><span class="asset-info-title fb fs-14">
                                            Asset Type</span></p>
                                        <p class="fs-13">{{$asset->assetTypeRecord->type}}</p>
                                    </div>
                                    <div class="col-md-6 col-6">
                                        <p><i class="asset-info-icon fal fa-compass"></i><span class="asset-info-title fb fs-14">
                                            Orientation</span></p>
                                        <p class="fs-13">{{ $asset->orientation }}</p>
                                    </div>
                                    @if ($asset->asset_category == 'digital' || ($asset->asset_category == 'mobile' && $asset->advert_type == 'digital'))
                                        <div class="col-md-6 col-6">
                                            <p><i class="asset-info-icon fal fa-clock"></i><span class="asset-info-title fb fs-14">Seconds Per Slot</span></p>
                                            <p class="fs-13">{{$asset->num_slides_per_secs}}</p>
                                        </div>
                                    @else
                                        <div class="col-md-6 col-6">
                                            <p><i class="asset-info-icon fal fa-cubes"></i><span class="asset-info-title fb fs-14">Material Type</span></p>
                                            <p class="fs-13">{{$asset->substrate?$asset->substrate:'N/A'}}</p>
                                        </div>
                                    @endif
                                    <div class="col-md-6 col-6">
                                        <p><i class="asset-info-icon fal fa-compress"></i><span class="asset-info-title fb fs-14">
                                            Number of Faces</span></p>
                                        <p class="fs-13">{{ $asset->face_count }}</p>
                                    </div>
                                    <div class="col-md-6 col-6">
                                        <p><i class="asset-info-icon fal fa-file-pdf"></i><span class="asset-info-title fb fs-14">
                                            File Format</span></p>
                                        <p class="fs-13">{{$asset->file_format}}</p>
                                    </div>
                                </div>
                                <div class="listing-bottom-two mt-10">
                                    <p class="fs-13">Site Location  <span class="fb">{{ $asset->lcda && $asset->lcda->lcda_name ? $asset->lcda->lcda_name : '' }}</span></p>
                                    <p class="fs-13 fb mt-10">Population Density: {{ $asset->lcda && $asset->lcda->lcda_population ? $asset->lcda->lcda_population : '12345'}}</p>
                                </div>
                                <div class="listing-bottom-two mt-10">
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <p class="asset-info-title fb fs-13">Site Proximities</p>
                                        </div>
                                        <div class="col-md-12 col-12">
                                        @foreach($asset->assetProximityRecords as $proxid => $proximity) 
                                        <p class="fs-13">{{ $proximity->proximity_type .': '. $proximity->proximity_name }}</p>
                                        @endforeach
                                        </div>
                                    </div>
                                </div>
                                <hr class="divider">
                                <div class="listing-details-btn">
                                    <a href="{{ url('asset/'. $asset->id .'/detail') }}" class="theme-btn dashboard-search-btn fs-13 asset-detail-btn">Site Details<span class="asset-detail-icon fal fa-paper-plane"></span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
        @empty
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="user-profile-wrapper wow fadeIn" data-wow-duration="2.5s" data-wow-delay=".25s">
                    <div class="user-profile-card add-property">
                        <div class="col-lg-12 pending-transactions-table-div pt-10 pb-10">
                            <h4>Apologies</h4>
                            <p>We are currently unable to find the resource you are looking for at the moment.</p>
                            <p>Kindly try again some other time.</p>
                            <br>
                            <a href="{{ url()->previous() }}" class="btn color2-bg flat-btn">Search Again?<i class="fal fa-home"></i></a>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        @endforelse
    </div>
</section>
@endsection