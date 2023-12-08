@extends('template.master')

@section('content')
<style>
    .dashboard-content {
        padding-left: 273px;
    }

    .action-button {
        float: right;
    }
    
    .alert {
        clear: both;
        margin-bottom: 40px;
        padding: 20px;
        border-radius: 4px;
    }

    .alert ul {
        display: block;
    }

    .alert-success {
        background-color: #d6eafa;
        color: #4b93c8;
    }

    .alert-danger {
        background: #fadde6;
    }

    .alert ul li {
        font-size: 13px;
        line-height: 24px;
        font-weight: 500;
        color: #b80640;
        text-align: left;
    }
</style>

<div class="site-breadcrumb login-breadcrumb dashboard-breadcrumb">
    <div class="container">
        <h2 class="breadcrumb-title dashboard-breadcrumb-menu-left">Batch Asset Upload</h2>
        <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
            <li><a href="#">Asset Management</a></li>
            <li class="active-nav-dashboard">Batch Asset Upload</li>
        </ul>
    </div>
</div>
<section id="advertiser-dashboard">
    <div class="user-profile pt-40 pb-40 dashboard-bg-color">
        <div class="container-fluid">
            @if (session()->has('session-mismatch'))
                <div class="alert alert-warning">
                    <span>{{session()->get('session-mismatch')}}</span>
                </div>
            @endif
            <div class="row">
                <div class="col-lg-12">
                    <div class="user-profile-wrapper">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="login-form wow fadeIn" data-wow-duration="2.5s" data-wow-delay="1.75s">
                                    <div class="user-profile-sidebar-top">
                                        <h4 class="profile-name dashboard-search-title">Batch Asset Upload</h4>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <form action="{{ url('/admin/platform/batch-asset-upload') }}" method="post" role="form" enctype="multipart/form-data">
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
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label for="operator">Asset Owner</label>
                                                            <select data-placeholder="Asset Owner" class="form-control form-control-sm required" name="asset_owner">
                                                                <option value="">-- Select Asset Owners Name --</option>
                                                                @foreach ($operators as $operator)
                                                                <option value="{{$operator->id}}">{{$operator->corporate_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <i class="far fa-user-secret"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3"></div>
                                                    <div class="col-sm-3"></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label for="asset_file">Choose Asset File (Excel Only)</label>
                                                            <input type="file" class="form-control form-control-sm" name="asset_file" id="asset_file" accept=".xlsx, .xls, .csv">
                                                            <i class="fal fa-percentage"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3"></div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <i class="fal fa-file-excel" style="font-size: 51px;
                                                            position: relative;
                                                            top: 0;
                                                            vertical-align: top;
                                                            line-height: 61px;"></i>
                                                            <p class="fs-12">Pick a file by clicking on "Choose File"</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="filter-tags">
                                                    <label class="fs-13">You can always upload an excel sheet of captured assets.</label>
                                                </div>
                                                <span class="fw-separator"></span>
                                                <div class="col-md-2">
                                                    <button type="submit" class="action-button theme-btn" style="float:left;">Upload<i class="fal fa-upload"></i></button>
                                                </div>
                                            </form>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            @if (\session()->has('uploadedAssets'))
                                                <table class="asset-batch-upload-table" cellpadding="5">
                                                    <thead class="">
                                                        <tr>
                                                            <th class="fs-13">Asset Name</th>
                                                            <th class="fs-13">Asset Category</th>
                                                            <th class="fs-13">Asset Type</th>
                                                            <th class="fs-13">Orientation</th>
                                                            <th class="fs-13">Price (Min - Max)</th>
                                                            <th class="fs-13">Dimension (W x H)</th>
                                                            <th class="fs-13">Print Dimension</th>
                                                            <th class="fs-13">Pixel Resolution</th>
                                                            <th class="fs-13">Substrate</th>
                                                            <th class="fs-13">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach (\session()->get('uploadedAssets') as $uploadedAsset)
                                                            <tr>
                                                                <td class="fs-13">{{$uploadedAsset->name}}</td>
                                                                <td class="fs-13">{{ucfirst($uploadedAsset->asset_category)}}</td>
                                                                <td class="fs-13">
                                                                    @if ($uploadedAsset->assetTypeRecord)
                                                                        {{$uploadedAsset->assetTypeRecord->type}}
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>
                                                                <td class="fs-13">{{$uploadedAsset->orientation}}</td>
                                                                <td class="fs-13">&#8358;{{number_format($uploadedAsset->min_price, 2, '.', ',')}} - &#8358;{{number_format($uploadedAsset->max_price, 2, '.', ',')}}</td>
                                                                <td class="fs-13">{{$uploadedAsset->asset_dimension_width}}w x {{$uploadedAsset->asset_dimension_height}}h</td>
                                                                <td class="fs-13">{{$uploadedAsset->print_dimension}}</td>
                                                                <td class="fs-13">{{$uploadedAsset->pixel_resolution}}</td>
                                                                <td class="fs-13">{{$uploadedAsset->substrate}}</td>
                                                                <td class="fs-13">
                                                                    <a href="{{url('admin/platform/asset-upload-media/images/'.$uploadedAsset->id)}}" style="color:#b80640" title="Upload Pictures" target="_blank">
                                                                        <i class="fal fa-camera"></i>
                                                                    </a>
                                                                    |
                                                                    <a href="{{url('admin/platform/asset-upload-media/video/'.$uploadedAsset->id)}}" style="color:#b80640" title="Upload Video" target="_blank">
                                                                        <i class="fal fa-video-camera"></i>
                                                                    </a>
                                                                    |
                                                                    <a href="{{url('asset/'.$uploadedAsset->id.'/detail')}}" style="color:#b80640" title="View Asset" target="_blank">
                                                                        <i class="fal fa-eye"></i>
                                                                    </a>
                                                                </td>
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
                </div>
            </div>
        </div>
    </div>
</section>
@endsection