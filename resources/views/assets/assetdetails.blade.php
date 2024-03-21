@extends('template.master')

@section('content')
  <style>
    .owl-stage {
      height: 503px;
      overflow: hidden;
    }

    .notice {
      display: none;
    }
  </style>
    @if ($available_asset)
    <?php 
    $randImg = "";
    if (count($available_asset->asset_images_record->toArray())){
      $randImg = Storage::url($available_asset->asset_images_record[array_rand($available_asset->asset_images_record->toArray())]->image_path);
    }
    ?>
    <div class="listing-single-breadcrumb" style="background: url({{ $randImg }})">
        <div class="container">
           <h1 class="breadcrumb-title text-left asset-detail-breadcrumb-id">{{ $available_asset->name }}</h1>
           <div class="mt-10">
              <ul class="text-left asset-detail-breadcrumb-info-list">
                 <li class="fs-14 fb"><span class="far fa-phone"></span>{{"+234" . $available_asset->owner->phone}}</li>
                 <li class="fs-14 fb"><span class="far fa-map-marker-alt"></span>{{ $available_asset->address }}</li>
                 <li class="fs-14 fb"><span class="far fa-envelope"></span>{{ $available_asset->owner->email}}</li>
              </ul>
           </div>
        </div>
    </div>
    <div class="site-breadcrumb login-breadcrumb dashboard-breadcrumb listing-single-breadcrumb-two">
        <div class="container">
           <div class="row">
              <div class="col-lg-4 col-md-4 col-12">
                 <div>
                    <ul class="breadcrumb-menu dashboard-breadcrumb-menu-left">
                       <li><a href="{{url('/advertiser/individual/dashboard')}}">Dashboard</a></li>
                       <li><a href="">Asset Details</a></li>
                       <li class="active-nav-dashboard">Single Listing</li>
                    </ul>
                 </div>
              </div>
              <div class="col-lg-6 col-md-4 col-12"></div>
              <div class="col-lg-2 col-md-2 col-12">
                @if ($user)
                 <div class="dashboard-breadcrumb-menu-right text-align-left breadcrumb-logout-btn-div">
                    <a class="profile-logout-btn breadcrumb-logout-btn" href="{{ url('/advertiser/individual/logout') }}"><i class="far fa-sign-out"></i>
                       Logout</a></div>
                @endif
              </div>
           </div>
        </div>
    </div>
    <section>
        <div class="user-profile pb-40 dashboard-bg-color">
           <div class="property-single">
              <div class="container">
                <div class="row">
                  <div class="col-lg-8 mb-5 mt-40">
                    <div class="property-single-wrapper wow fadeInUp" data-wow-duration="1s" data-wow-delay="1s">
                      <div class="property-single-slider owl-carousel owl-theme">
                        @foreach ($available_asset->asset_images_record as $key => $image)
                        <img src="{{ Storage::url($image->image_path) }}" alt="Asset slider image">
                        @endforeach
                      </div>
                      <div class="property-single-content asset-single-content wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.25s">
                        <div class="property-single-meta" style="display: block;">
                          <div class="row">
                            <div class="col-md-9 col-12">
                              <div class="property-single-meta-left">
                                <h3 class="listing-single-id">{{ $available_asset->name }}</h3>
                                <p class="listing-single-address fs-14 fb"><i class="listing-single-icon far fa-location-dot"></i> {{ $available_asset->address }}</p>
                                <p class="listing-single-mail fs-14"><i class="listing-single-icon far fa-envelope"></i> {{ $available_asset->owner->email }}</p>
                                <p class="listing-single-mail fs-14">
                                  <i class="listing-single-icon far fa-rocket"></i> 
                                  Asset proximities
                                  @foreach($available_asset->assetProximityRecords as $proxid => $proximity) 
                                  ({{ $proximity->proximity_type .': '. $proximity->proximity_name }})<br/>
                                  @endforeach
                                </p>
                                @if ($user && $user->operator === 0)
                                <a href="javascript://" class="theme-btn request_book_asset" data-aid="{{$available_asset->id}}">Book Asset<i class="far fa-arrow-right"></i></a> 
                                @endif
                              </div>
                            </div>
                            <div class="col-md-3 col-12">
                              <div class="property-single-meta-right">
                                <div class="property-single-rating-box">
                                  <h6 class="property-single-price">&#8358;{{ number_format($available_asset->max_price,2, '.', ',') }}</h6>
                                  <div class="property-single-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                  </div>
                                </div>
                                <div class="property-single-meta-option">
                                  <a href="#" class="icons-tooltip">
                                    <i class="far fa-share-alt"></i>
                                    <span class="tooltip fs-12">Share</span>
                                  </a>
                                  <a href="#" class="icons-tooltip">
                                    <i class="far fa-comment-alt-check"></i>
                                    <span class="tooltip fs-12">Add Review</span>
                                  </a>
                                  <a href="#" class="icons-tooltip">
                                    <i class="far fa-heart"></i>
                                    <span class="tooltip fs-12">Save</span>
                                  </a>
                                  <a href="#" class="icons-tooltip">
                                    <i class="far fa-bookmark"></i>
                                    <span class="tooltip fs-12">Book Now</span>
                                  </a>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      @if ($available_asset->video_path)
                      <div class="property-single-content">
                        <h4>Asset Video</h4>
                        <div class="property-single-info">
                          <div class="property-single-video">
                            <div class="video-area">
                              <div class="container-fluid px-0">
                                <div class="video-content" style="background-image: url(assets/img/video/01.jpg);">
                                  <div class="row align-items-center">
                                    <div class="col-lg-12">
                                      <div class="video-wrapper">
                                        <video  class="asset-video" autoplay controls src="{{Storage::url($available_asset->video_path)}}">
                                          Your browser does not support HTML video.
                                        </video>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      @endif
                      <div class="property-single-content asset-single-content wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.25s">
                        <h4>Site Information</h4>
                        <div class="property-single-info">
                          <div class="row">
                            <div class="col-6 col-md-6 col-lg-4">
                              <div class="property-single-info-item fb fs-14">
                                <i class="fal fa-wifi"></i> Dimension  &ldrdhar;  {{$available_asset->asset_dimension_width.'m x '.$available_asset->asset_dimension_height.'m'}}
                              </div>
                            </div>
                            <div class="col-6 col-md-6 col-lg-4">
                              <div class="property-single-info-item fb fs-14">
                                <i class="fal fa-parking"></i> Orientation &ldrdhar; {{$available_asset->orientation}}
                              </div>
                            </div>
                            @if ($available_asset->substrate)
                            <div class="col-6 col-md-6 col-lg-4">
                              <div class="property-single-info-item fb fs-14">
                                <i class="fal fa-snowflake"></i> Substrate/Material Type &ldrdhar; {{$available_asset->substrate}}
                              </div>
                            </div>
                            @endif
                            <div class="col-6 col-md-6 col-lg-4">
                              <div class="property-single-info-item fb fs-14">
                                <i class="fal fa-plane"></i> Board Type &ldrdhar; {{ucwords($available_asset->asset_category)}}
                              </div>
                            </div>
                            <div class="col-6 col-md-6 col-lg-4">
                              <div class="property-single-info-item fb fs-14">
                                <i class="fal fa-paw"></i>  Face Count &ldrdhar; {{$available_asset->face_count}}
                              </div>
                            </div>
                            <div class="col-6 col-md-6 col-lg-4">
                              <div class="property-single-info-item fb fs-14" title>
                                <i class="far fa-blackboard"></i>  Site Type &ldrdhar; {{$available_asset->assetTypeRecord->type}}
                              </div>
                            </div>
                            <div class="col-6 col-md-6 col-lg-4">
                              <div class="property-single-info-item fb fs-14">
                                <i class="far fa-suitcase-medical"></i>  Payment Frequency &ldrdhar; {{$available_asset->payment_freq}}
                              </div>
                            </div>
                            <div class="col-6 col-md-6 col-lg-8">
                              <div class="property-single-info-item fb fs-14">
                                <i class="far fa-users"></i>  Asset Owner &ldrdhar;
                                <span class="listing-single-mail fs-14">{{ $available_asset->owner->corporate_name }}</span>
                              </div>
                            </div>
                            </div>
                        </div>
                     </div>
                      <div class="property-single-content wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.50s">
                        <h4> Asset Neighbourhood Details</h4> 
                        <button id="neighbour-btn" class="asset-info-btn">
                         <span id="eye-slash-visibility-one" class="far fa-eye-slash"></span>
                         <span id="eye-visibility-one" class="far fa-eye visibility-off"></span>
                       </button>

                        <div id="asset-neighbour-info" class="property-single-info hide-single-info">
                          @foreach($available_asset->assetProximityRecords as $proxid => $proximity) 
                          <p class="fb">{{ $proximity->proximity_type }} &ldrdhar; {{ $proximity->proximity_name }}</p>
                          @endforeach
                          <p class="fb">Site Location &ldrdhar; {{ $available_asset->lcda && $available_asset->lcda->lcda_name ? $available_asset->lcda->lcda_name : '' }}</p>
                          <p class="fb">Population Density &ldrdhar; {{ $available_asset->lcda && $available_asset->lcda->lcda_population ? $available_asset->lcda->lcda_population : '' }}</p>
                        </div>
                      </div>
                      <div class="property-single-content wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.75s">
                        <h4>  Dimension/Orientation Details   </h4>
                        <button id="dimension-btn" class="asset-info-btn">
                         <span id="eye-slash-visibility-two" class="far fa-eye-slash"></span>
                         <span id="eye-visibility-two" class="far fa-eye visibility-off"></span>
                       </button>
                        <div id="asset-dimension-info" class="property-single-info hide-single-info">
                          <p>This site is <span class="fb">{{ $available_asset->pixel_resolution }}</span> both in width and height and its Orientaion is <span class="fb">{{ $available_asset->orientation }}</span></p>
                        </div>
                      </div>
                      <div class="property-single-content wow fadeInUp" data-wow-duration="1s" data-wow-delay="1.50s">
                        <h4>   More Description     </h4>
                        <button id="description-btn" class="asset-info-btn">
                         <span id="eye-slash-visibility-three" class="far fa-eye-slash"></span>
                         <span id="eye-visibility-three" class="far fa-eye visibility-off"></span>
                       </button>
                        <div id="asset-description-info" class="property-single-info hide-single-info">
                          <p></p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-4 mt-40">
                    <div class="property-single-sidebar wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.25s">
                     <div class="property-single-info">
                       <div class="booking-btn">
                         <a target="_blank" href="https://www.google.com/maps/search/?api=1&query={{$available_asset->location}}" id="show-and-hide-map-btn" class="theme-btn"><span class="map-title">On The Map</span> <i class="fal fa-map-marked-alt"></i></a>
                       </div>
                     </div>
                      <div class="property-single-content sidebar-map">
                       <h4>Location View</h4>
                       <div class="property-single-info">
                         <div class="property-single-map">
                           <div class="contact-map">
                            
                            <?php 
                              $GAPIKey = env('GOOGLE_MAP_API_KEY');
                              $address = urlencode($available_asset->address);
                              $url = "https://www.google.com/maps/embed/v1/place?key=".$GAPIKey."&q=".$address
                            ?>
                             <iframe
                              frameborder="0"
                              referrerpolicy="no-referrer-when-downgrade"
                              src={{$url}}
                              style="border:0;" allowfullscreen loading="lazy"></iframe>
                           </div>
                         </div>
                       </div>
                     </div>
                      <div class="property-single-content featured-site">
                        <h4>Similar Site</h4>
                        <div class="property-single-info">
                          <div class="property-single-similar">
                            <div class="property-similar-slider owl-carousel owl-theme">
                              <div class="listing-item">
                                <span class="listing-badge">Available</span>
                                <div class="listing-img">
                                  <img src="{{asset('img/property/digital.jpg')}}" alt="">
                                </div>
                                <div class="listing-content">
                                  <h4 class="listing-title result-listing-title"><a href="#">OAAN/OPR/2022/53485/0206</a></h4>
                                  <p class="listing-sub-title"><i class="far fa-location-dot"></i> 40 JOURNAL SQUARE, LAGOS, NIG</p>
                                  <div class="listing-price">
                                    <div class="listing-price-info">
                                      <h6 class="listing-price-amount property-single-price">₦463,089.00</h6>
                                    </div>
                                    </div>
                                  </div>
                              </div>
                              <div class="listing-item">
                                <span class="listing-badge">Available</span>
                                <div class="listing-img">
                                  <img src="{{asset('img/property/digital.jpg')}}" alt="">
                                </div>
                                <div class="listing-content">
                                  <h4 class="listing-title result-listing-title"><a href="#">OAAN/OPR/2022/53485/0206</a></h4>
                                  <p class="listing-sub-title"><i class="far fa-location-dot"></i>40 JOURNAL SQUARE, LAGOS, NIG</p>
                                  <div class="listing-price">
                                    <div class="listing-price-info">
                                      <h6 class="listing-price-amount property-single-price">₦463,089.00</h6>
                                    </div>
                                   </div>
                                  </div>
                              </div>
                              <div class="listing-item">
                                <span class="listing-badge">Available</span>
                                <div class="listing-img">
                                  <img src="{{asset('img/property/digital.jpg')}}" alt="">
                                </div>
                                <div class="listing-content">
                                  <h4 class="listing-title result-listing-title"><a href="#">OAAN/OPR/2022/53485/0206</a></h4>
                                  <p class="listing-sub-title"><i class="far fa-location-dot"></i>40 JOURNAL SQUARE, LAGOS, NIG</p>
                                  <div class="listing-price">
                                    <div class="listing-price-info">
                                      <h6 class="listing-price-amount property-single-price">₦463,089.00</h6>
                                    </div>
                                    </div>
                                  </div>
                              </div>
                            </div>
                            <div class="similar-listings-btn-div">
                             <a href="#">
                               <button class="theme-btn similar-listings-btn">See All Listings <span class="fal fa-long-arrow-right"></span></button>
                             </a>
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
    @else 

        

    @endif


    @if ($user)
    {{-- Asset Booking Generator --}}
    <div class="main-book-asset-wrap modal fade" id="main-book-asset-wrap" tabindex="-1" role="dialog" aria-labelledby="main-book-asset-wrap-generator" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="main-book-asset-wrap-generator">Book An Asset</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body body-content">
                <div class="error-wrap">
                  <div class="alert notice alert-danger">
                      <ul></ul>
                  </div>

                  <form action="javascript://">
                    @csrf
                    <input type="hidden" name="booked_by_user_id" value="{{ $user->user_id }}" class="required">
                    <input type="hidden" name="price" value="{{ $available_asset->max_price }}" class="required">
                    <input type="hidden" name="user_type_id" value="{{ $user->user_type_id }}" class="required">
                    <div class="book-asset-container row">
                      <div class="col-md-8">
                        <input name="book_date" id="se" type="text" class="form-control daterangepickerx required" placeholder="Select Your Booking DateRange" autocomplete="off" value="">
                      </div>
                      <div class="col-md-4 d-flex justify-content-start">
                        <button class="search-submit theme-btn theme-btn-custom book_asset" id="submit_btn">Book Asset</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
          </div>
      </div>
  </div>
  {{-- END Payment Token Generator END --}}
    @endif
@endsection


@section('asset-details-js')
    <script>
      $(document).ready(function(){

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest',
            }
        });

        var today = new Date();
        var book_asset_container = $('.book-asset-container');
        var corporate_user = $("#corporate").val();
        var corporate_admin = $("#user_admin").val();
        $notice = $('.notice');
        var book_asset = {};
        attach_date_range_picker();

        $('body').on('click', '.request_book_asset', function(e){
          new bootstrap.Modal("#main-book-asset-wrap", {
            backdrop: 'static',
            keyboard: false
          }).show();
          $aid = $(this).data('aid');
          book_asset.aid = $aid;
        });


        $('body').on('click', '.book_asset', function(e){

          e.preventDefault();

          var self = $(this);

          self.attr('disabled', 'disabled').html('Processing...');
            
          $errors = [];
          $valid = true;

          $('.required').each(function(id, el) {
              $elm = $(el);
              if (!$elm.val().trim()) {
                  $errors.push('<li><strong>' + $elm.attr('placeholder') + '</strong> field should not be blank.</li>');
                  $valid = false;
              }
              else {
                  book_asset[$elm.attr('name')] = $elm.val().trim();
              }
          });

          if ( !$valid ) {
              $content = $errors.join('');
              show_success_error($notice, $content, true);
              self.removeAttr('disabled').html('Book Asset');
              return ;
          }
          
          $notice.css('display', 'none');

          var book_date_arr = book_asset.book_date.split('-');
          book_asset.start_date = book_date_arr[0].trim().replace(/\//g, '-');
          book_asset.end_date = book_date_arr[2].trim().replace(/\//g, '-');

          $.post('/asset/book', book_asset).done(function(res) {
              if (res.status) {
                  $msg = res.success.msg + '<li>You will be redirected soon....</li>';
                  show_success_error($notice, res.success.msg, false);
                  self.removeAttr('disabled').html('Book Asset');
                  setTimeout(function(){
                      window.location.href = res.success.url;
                  }, 3000);
              } 
              else {
                  show_success_error($notice, res.errors, true);
                  self.removeAttr('disabled').html('Book Asset');
              } 

          }).fail(function(err){ self.removeAttr('disabled').html('Book Asset'); });
        });

        function show_success_error( alert, content, error ) {
          if (error) {
              alert.removeClass('alert-success').addClass('alert-danger').css('display', 'block');
          }
          else {
              alert.removeClass('alert-danger').addClass('alert-success').css('display', 'block');
          }
          alert.find('ul').html( content );
        }


        function attach_date_range_picker() {
          $('.daterangepickerx').daterangepicker({
              autoUpdateInput: false,
              startDate: new Date(today.getFullYear(), today.getMonth(), today.getDate(), 0, 0, 0, 0),
              minDate: new Date(today.getFullYear(), today.getMonth(), today.getDate(), 0, 0, 0, 0),
              locale: {
                  format: 'DD/MM/YYYY'
              } 
          });

          $('.daterangepickerx').on('apply.daterangepicker', function(e, picker){
              $(this).val(picker.startDate.format('DD/MM/YYYY') + ' -to- ' + picker.endDate.format('DD/MM/YYYY'));
          });     
        }
      });
    </script>
@endsection