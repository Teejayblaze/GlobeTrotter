@extends('template.master')

@section('content')

<?php
    function walk_modify(&$v, $k) {
        $v = Storage::url($v);
    }
?>

    <div class="site-breadcrumb login-breadcrumb dashboard-breadcrumb">
        <div class="container">
            <h2 class="breadcrumb-title dashboard-breadcrumb-menu-left">Uploaded Material</h2>
            <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
                <li><a href="{{ url("/operator/dashboard") }}">Dashboard</a></li>
                <li class="active-nav-dashboard">Uploaded Material</li>
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
                        <h4 class="user-profile-card-title">Uploaded Material</h4>
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
                        <div class="alert alert-success">{{ session()->get('flash_message') }}</div>
                        @endif
                        @if (count($materials))
                        <table class="pending-transactions-table mb-30">
                            <thead class="">
                                <tr>
                                    <th class="fs-13" width="5%">S/N</th>
                                    <th class="fs-13" width="13%">Booking Reference</th>
                                    <th class="fs-13" width="14%">Uploaded By</th>
                                    <th class="fs-13" width="13%">Upload Name</th>
                                    <th class="fs-13" width="10%">Media</th>
                                    <th class="fs-13" width="10%">Date</th>
                                    <th class="fs-13" width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($materials as $key => $material)
                                <?php 
                                    $files = explode(',', $material->media);
                                    array_walk($files, "walk_modify");
                                    $files = implode(',', $files);

                                    $medias = count(explode(',', $material->media));
                                ?>
                                <tr>
                                    <td class="fs-14 sm-fs-12">{{$key + 1}}.</td>
                                    <td class="fs-14 sm-fs-12">{{$material->booking_ref}}</td>
                                    <td class="fs-14 sm-fs-12">{{$material->name}}</td>
                                    <td class="fs-14 sm-fs-12">{{$material->upload_name}}</td>
                                    <td class="fs-14 sm-fs-12">
                                        @if ($medias)
                                        <a href="#" class="view-img" data-path="{{$files}}">{{$medias}} files uploaded</a></td>
                                        @else
                                        <span>No media files</span>
                                        @endif
                                    <td class="fs-14 sm-fs-12">{{ \Carbon\Carbon::parse($material->created_at)->format('jS F Y') }}</td>
                                    <td class="fs-14 sm-fs-12">
                                        @if ($medias)
                                        <a href="{{ url("/operator/material-download/" . $material->id) }}" ><i class="fal fa-download"></i> Download</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div class="col-lg-12 pending-transactions-table-div pt-10 pb-10">
                            <p>No Uploaded Materials Yet.</p>
                        </div>
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