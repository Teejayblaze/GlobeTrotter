@extends('template.master')

@section('content')
<style>

    .edit-video {
        width: 50%;
        height: 24px;
    }

    .edit-video i.fal.fa-video {
        translate: 6px 5px;
        color: #fff;
    }

    .video-holder h2 {
        padding: 20px 0 0 0;
        text-align: left;
    }

    .video-holder p {
        margin-bottom: 10px;
    }

    .video-holder {
        padding: 20px;
    }

    video {
        height: 464px; 
        width: 730px;
        background: #000;
        position: relative;
    }
    .video-form-holder {
        margin-top: 100px;
        padding: 10px 10px 10px 10px;
        display: flex;
        border: 1px dashed #beb3b3;
        flex-flow: column nowrap;
        border-radius: 7px;
        justify-content: center;
        align-items: center;
    }
    .video-file-holder {
        height: 200px;
        width: 80%;
        border: 1px dashed #c2b9b9;
        margin: 26px 0px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 12px;
        font-weight: 100;
        cursor: pointer;
        flex-flow: column nowrap;
    }

    .action-button {
        border: none;
    }
</style>

<div class="site-breadcrumb login-breadcrumb dashboard-breadcrumb">
    <div class="container">
        <h2 class="breadcrumb-title dashboard-breadcrumb-menu-left">Vacant Assets</h2>
        <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
            <li><a href="{{url('/operator/dashboard')}}">Dashboard</a></li>
            <li><a href="{{url('/operator/vacantasset')}}">Owned Asset</a></li>
            <li><a href="{{url('/operator/vacantasset')}}">Vacant Asset</a></li>
            <li class="active-nav-dashboard">Video Edit</li>
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
                        <h4 class="user-profile-card-title">Asset [ {{$operator_assets->name}} ] Video Edit</h4>
                        <div class="col-lg-12 pending-transactions-table-div vacant-asset-table-div pt-10 pb-10">
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
                            <div class="alert alert-success" style="text-align: left;">
                                <strong>{{session()->get('flash_message')}}</strong>
                            </div>
                            @endif

                            <div class="row">
                                <div class="col-md-9">
                                    <div class="video-holder">
                                        <h2 class="fs-15">Your video advert will start playing on this player</h2>
                                        <p class="fs-13">Video might preload, you will still need to submit the form to make it permanent</p>
                                        <video autoplay controls src="{{$operator_assets->video_path ? Storage::url($operator_assets->video_path) : ''}}" id="video-player">
                                            Your browser does not support HTML video.
                                          </video>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <form class="video-form-holder" action="{{url("operator/edit-asset-video/upload/".$operator_assets->id)}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <label class="video-file-holder" for="asset_video">
                                            <span>Click here to Upload Video</span>
                                            <p id="file-name"></p>
                                        </label>
                                        <input type="file" name="asset_video" id="asset_video" style="display:none;" accept="video/mp4,video/x-m4v,video/*">
                                        <button type="submit" class="action-button theme-btn">Upload Video<i class="fal fa-video"></i></button>
                                    </form>
                                </div>
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