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
        <h2 class="breadcrumb-title dashboard-breadcrumb-menu-left">Assets</h2>
        <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
            <li><a href="{{ url("/admin/dashboard") }}">Dashboard</a></li>
            <li class="active-nav-dashboard">Assets</li>
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
                        <div class="user-profile-card-title d-flex" style="justify-content: space-between">
                            <h4>Assets</h4>
                            <div class="filter">
                                <select name="asset_owner" id="asset_owner" class="form-control">
                                    <option value="">Filter By Asset Owner</option>
                                    @foreach ($asset_owners as $asset_owner)
                                        <option value="{{$asset_owner->id}}">{{ $asset_owner->corporate_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if ($assets && $assets->count())
                        <div class="col-lg-12 pending-transactions-table-div pt-10 pb-10">
                            <table class="pending-transactions-table">
                            <thead class="">
                                <tr>
                                    <th class="fs-13" width="4%">S/N</th>
                                    <th class="fs-13" width="15%">Asset Owner</th>
                                    <th class="fs-13" width="15%">Asset Name</th>
                                    <th class="fs-13" width="8%">Location</th>
                                    <th class="fs-13" width="10%">Dimension</th>
                                    <th class="fs-13" width="26%">Address</th>
                                    <th class="fs-13" width="8%">Orientation</th>
                                    <th class="fs-13" width="4%">Faces</th>
                                    <th class="fs-13" width="12%">Medias</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($assets as $key => $asset)
                                <tr>
                                    <td class="fs-14 sm-fs-12">{{($key+1)}}</td>
                                    <td class="fs-14 sm-fs-12">{{ $asset->assetOwner->corporate_name }}</td>
                                    <td class="fs-14 sm-fs-12"><a href="{{url('/asset/'.$asset->id.'/detail')}}" target="_blank">{{$asset->name}}</a></td>
                                    <td class="fs-14 sm-fs-12">{{ $asset->location }}</td>
                                    <td class="fs-14 sm-fs-12">{{ $asset->asset_dimension_width."m" }} &times; {{ $asset->asset_dimension_height."m" }}</td>
                                    <td class="fs-14 sm-fs-12">{{ $asset->address }}</td>
                                    <td class="fs-14 sm-fs-12">{{ $asset->orientation }}</td>
                                    <td class="fs-14 sm-fs-12">{{ $asset->face_count }}</td>
                                    <td class="fs-14 sm-fs-12">
                                        @if ($asset->assetImagesRecord && !$asset->assetImagesRecord->count())
                                        <a href="{{url('admin/platform/asset-upload-media/images/'.$asset->id)}}" style="color:#fff" title="Upload Pictures" target="_blank">
                                            <i class="fal fa-camera"></i>
                                        </a> | 
                                        @endif

                                        @if (!$asset->video_path)
                                        <a href="{{url('admin/platform/asset-upload-media/video/'.$asset->id)}}" style="color:#fff" title="Upload Video" target="_blank">
                                            <i class="fal fa-video-camera"></i>
                                        </a> | 
                                        @endif
                                        <a href="{{url('asset/'.$asset->id.'/detail')}}" style="color:#fff" title="View Asset" target="_blank">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            </table>
                        </div>
                        {{-- <div class="paginate">
                            {!! $assets->links() !!}
                        </div> --}}
                        @else
                        <div class="col-lg-12 pending-transactions-table-div pt-10 pb-10">
                            <p>No Asset records found!</p>
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


@section('asset-details-js')
    <script>
        $(document).ready(function(){
            $("#asset_owner").on("change", function() {
                let id = $(this).val();
                let cache = window.location.href;
                let lastLetter = cache.substr(cache.lastIndexOf("/") + 1);
                if (lastLetter !== "assets") {
                    cache = cache.substr(0, cache.lastIndexOf(lastLetter) - 1);
                }
                cache += "/" + id
                window.location.href = cache;
            });
        });
    </script>
@endsection