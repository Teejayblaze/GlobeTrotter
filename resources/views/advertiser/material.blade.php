@extends('template.master')

@section('content')

<?php 
    function walk_modify(&$v, $k) {
        $v = Storage::url($v);
    }
?>

<div class="site-breadcrumb login-breadcrumb dashboard-breadcrumb">
    <div class="container">
        <h2 class="breadcrumb-title dashboard-breadcrumb-menu-left">Material Upload</h2>
        <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
            <li><a href="{{ url('/advertiser/individual/dashboard') }}">Dashboard</a></li>
            <li>Advert Manager</li>
            <li class="active-nav-dashboard">Material Upload</li>
        </ul>
    </div>
</div>
<section id="advertiser-dashboard">
    <div class="user-profile pt-40 pb-40 dashboard-bg-color">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="user-profile-sidebar wow slideInLeft" data-wow-duration="2.5s" data-wow-delay=".25s">
                        <div class="user-profile-sidebar-top mt-40">
                        <p class="welcome-text">Welcome</p>
                            <h3 class="profile-name">{{ $user->firstname?$user->lastname:$user->corporate_name }}</h3>
                        </div>
                        <div class="edit-profile-div text-center mt-20">
                            <a href="{{url('/advertiser/individual/profile/edit')}}"><button class="theme-btn edit-profile-btn">Edit Profile</button></a>
                        </div>
                        <div class="user-profile-sidebar-top profile-detail mt-40">
                            @if ($user->corp_id)
                            <h6 class="profile-position">{{$user->designation}} <br><br> <span>@</span></h6>
                            <h3 class="profile-company mt-20">{{$user->work_with->name}}</h3>
                            @endif
                        </div>
                        <div class="profile-stats text-center">
                        <div class="row">
                            <div class="col-lg-6 profile-stats-divider">
                            <div class="profile-stats-border-right mb-10">
                            <div class="profile-stats-title">Paid</div>
                            <div class="profile-stats-value">{{ $paid_tranx_count }}</div>
                            </div>
                            </div>
                            <div class="col-lg-6 profile-stats-divider">
                            <div class="mb-10">
                            <div class="profile-stats-title">Bookings</div>
                            <div class="profile-stats-value">{{ $booked_asset_count }}</div>
                            </div>
                            </div>
                            <div class="col-lg-12">
                            <div class="mt-10">
                                <div class="profile-stats-title">Pending</div>
                            <div class="profile-stats-value">{{ $pending_tranx_count }}</div>
                            </div>
                            </div>
                        </div>

                        </div>
                        <ul class="user-profile-sidebar-list text-center">
                            <li><a class="profile-logout-btn" href="{{ url('/advertiser/individual/logout') }}"><i class="far fa-sign-out"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="user-profile-wrapper wow slideInRight" data-wow-duration="2.5s" data-wow-delay=".25s">
                        <div class="user-profile-card add-property">
                            <h4 class="user-profile-card-title">Material Upload</h4>
                            <div class="col-lg-12">
                                <div class="add-property-form">
                                    <form  action="{{ url('advertiser/individual/material') }}" method="post" role="form" enctype="multipart/form-data">
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

                                        @if (session()->has('upload-material-success'))
                                            <div class="alert alert-success">
                                                <span>{{session()->get('upload-material-success')}}</span>
                                            </div>
                                        @endif
                                        <div class="row align-items-center">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                <label for="asset_bookings">Bookings</label>
                                                <select class="select" name="asset_bookings" id="asset_bookings">
                                                    <option value="">--Select--</option>
                                                    @foreach ($asset_bookings as $asset_booking)
                                                    <option @if( old('asset_bookings') ===  $asset_booking->id.'=>'.$asset_booking->trnx_id) selected @endif value="{{$asset_booking->id.'=>'.$asset_booking->trnx_id}}">{{$asset_booking->asset->name .' => '. $asset_booking->trnx_id }}</option>
                                                    @endforeach
                                                </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                <label for="upload_name">Upload Name</label>
                                                <input type="text" class="form-control" placeholder="Your Upload Name" name="upload_name" id="upload_name" value="{{ old('upload_name') }}" required>
                                                </div>
                                            </div>
                                                <h5 class="fw-bold my-4">Media Content</h5>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                <div class="property-upload-wrapper">
                                                    <div class="property-img-upload">
                                                    <span><i class="far fa-images"></i> Click to Upload Media Content</span>
                                                    </div>
                                                    {{-- <input type="file" class="property-img-file" multiple> --}}
                                                    <input type="file" class="property-img-file upload" multiple name="asset_medias[]" id="assetmedias" accept=".png, .jpg, .jpeg, .bmp, .mp4">
                                                    <p class="fs-13 fb">Kindly beware that allowed content extensions are [.png, .jpg, .bmp, .jpeg, .mp4].</p>
                                                </div>
                                                </div>
                                            </div>

                                            <div class="col-12 mt-4">
                                                <p>Kindly ensure the information you are changing are valid and concise.</p>
                                            </div>
                                            <div class="col-lg-12 my-4">
                                                <button type="submit" class="theme-btn"><i class="fal fa-paper-plane"></i> Send Uploaded Material</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            @if (count($materials))
                                <h4 class="user-profile-card-title mt-60">Uploaded Materials</h4>
                                <table class="pending-transactions-table mb-30">
                                    <thead class="">
                                        <tr>
                                            <th class="fs-13">S/N</th>
                                            <th class="fs-13">Booking Reference</th>
                                            <th class="fs-13">Upload Name</th>
                                            <th class="fs-13">Media</th>
                                            <th class="fs-13">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($materials as $key => $material)
                                        <?php 
                                            $files = explode(',', $material->media);
                                            array_walk($files, "walk_modify");
                                            $files = implode(',', $files);
                                        ?>
                                        <tr>
                                            <td class="fs-14 sm-fs-12">{{$key + 1}}.</td>
                                            <td class="fs-14 sm-fs-12">{{$material->booking_ref}}</td>
                                            <td class="fs-14 sm-fs-12">{{$material->upload_name}}</td>
                                            <td class="fs-14 sm-fs-12"><a href="#" class="view-img" data-path="{{$files}}">{{count(explode(',', $material->media))}} files uploaded</a></td>
                                            <td class="fs-14 sm-fs-12">{{ \Carbon\Carbon::parse($material->created_at)->format('jS F Y') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="image-preview modal fade" id="image-preview" tabindex="-1" role="dialog" aria-labelledby="image-preview" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Preview Uploaded File(s)</h5>
                </div>
                <div class="modal-body body-content">
                    <div class="content row">
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="action-button btn btn-light hide_dismiss">Close <i class="fal fa-times"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection