@extends('template.master')

@section('content')

<div class="site-breadcrumb login-breadcrumb dashboard-breadcrumb">
    <div class="container">
        <h2 class="breadcrumb-title dashboard-breadcrumb-menu-left">Booked Assets</h2>
        <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
            <li><a href="{{url('/advertiser/individual/dashboard')}}">Dashboard</a></li>
            <li>Advert Manager</li>
            <li class="active-nav-dashboard">Booked Assets</li>
        </ul>
    </div>
</div>
<section id="advertiser-dashboard">
    <div class="user-profile pt-40 pb-40 dashboard-bg-color">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="user-profile-wrapper wow slideInRight" data-wow-duration="2.5s" data-wow-delay=".25s">
                        <div class="user-profile-card add-property">
                            <h4 class="user-profile-card-title">My Booked Assets</h4>
                            <div class="col-lg-12">
                                <div class="row listing-list">
                                    @forelse ($asset_recs as $asset_rec)
                                    <div class="col-md-6 col-lg-12">
                                        <div class="listing-item">
                                            <div class="listing-img">
                                                <img src="{{Storage::url($asset_rec->asset->assetImagesRecord[array_rand($asset_rec->asset->assetImagesRecord->toArray())]->image_path)}}" alt="Booked Asset">
                                            </div>
                                            <div class="listing-content booked-asset-details">
                                                <h4 class="listing-title">{{ $asset_rec->asset->name }}</h4>
                                                <h6 class="fs-14 mb-10"><i class="far fa-books"></i> Reserved?  &ldrdhar;  <span>{{ intval($asset_rec->locked) === 1 ? 'YES' : 'NO' }}</span></h6>
                                                <h6 class="fs-14 mb-10"><i class="far fa-layer-group"></i> Category  &ldrdhar; <span>{{ $asset_rec->asset->assetTypeRecord->type }}</span></h6>
                                                <h6 class="fs-14 mb-10"><i class="far fa-chalkboard"></i> Board Type  &ldrdhar;  <span>{{ ucwords($asset_rec->asset->asset_category) }}</span></h6>
                                                <h6 class="fs-14 mb-10"><i class="far fa-brain"></i> Orientation  &ldrdhar;  <span>{{ ucwords($asset_rec->asset->orientation) }}</span></h6>
                                                <h6 class="fs-14 mb-10"><i class="far fa-hand-holding-dollar"></i> Price  &ldrdhar;  <span>&#8358;{{ number_format(str_replace(',', '', $asset_rec->asset->max_price), 2, '.', ',') }}</span></h6>
                                                <h6 class="fs-14 mb-10"><i class="far fa-barcode"></i> Transaction ID  &ldrdhar;  <span>{{ $asset_rec->trnx_id }}</span></h6>
                                                <h6 class="fs-14 mb-10"><i class="far fa-calendar-days"></i> Site Start Date  &ldrdhar;  <span>{{ \Carbon\Carbon::parse($asset_rec->start_date)->format('jS \o\f F Y') }}</span></h6>
                                                <h6 class="fs-14 mb-10"><i class="far fa-calendar-days"></i> Site End Date  &ldrdhar;  <span>{{ \Carbon\Carbon::parse($asset_rec->end_date)->format('jS \o\f F Y') }}</span></h6>
                                                <h6 class="fs-14 mb-10"><i class="far fa-gears"></i> Status  &ldrdhar;  <span>
                                                    <?php 
                                                        $start_date = \Carbon\Carbon::parse($asset_rec->start_date);
                                                        $end_date = \Carbon\Carbon::parse($asset_rec->end_date);
                                                        $now = \Carbon\Carbon::now();
                                                        $ad_started = '';
                                                        if ($now->gt($start_date)) {
                                                            if (($now->gt($start_date) || $now->eq($start_date)) &&  ($now->lt($end_date) || $now->eq($end_date))) $ad_started = '<span class="text-success">Advert Currently Running</span>';
                                                            else $ad_started = '<span class="text-danger">Advert has Finished Running</span>';
                                                        } else $ad_started = '<span class="text-warning">Advert has not Started Running</span>';

                                                        echo $ad_started;
                                                    ?> 
                                                </span></h6>
                                                <p>{{$asset_rec->asset->description}}</p>
                                                <div class="listing-bottom">
                                                    <ul>
                                                        <li><i class="fal fa-cubes"></i>  {{$asset_rec->asset->face_count}} Faces<span>{{$asset_rec->asset->face_count}} Faces</span></li>
                                                      </ul>
                                                    <a href="{{url('/asset/'.$asset_rec->asset->id.'/detail')}}" class="listing-btn">View Details</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <p>No Sites Record.</p>
                                    @endforelse
                                    {{-- @if (count($asset_recs)) --}}
                                    <div class="pagination-area">
                                        {!! $asset_recs->links() !!}
                                        {{-- <div aria-label="Page navigation example">
                                            <ul class="pagination">
                                                <li class="page-item">
                                                    <a class="page-link" href="#" aria-label="Previous">
                                                        <span aria-hidden="true"><i class="far fa-angle-double-left"></i></span>
                                                    </a>
                                                </li>
                                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                                <li class="page-item">
                                                    <a class="page-link" href="#" aria-label="Next">
                                                        <span aria-hidden="true"><i class="far fa-angle-double-right"></i></span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div> --}}
                                    </div>
                                    {{-- @endif --}}
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