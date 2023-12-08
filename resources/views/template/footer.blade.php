  <!--Footer-->
  <footer class="footer-area">
    <div class="footer-widget">
        <div class="container">
          <div class="row footer-widget-wrapper pt-40 pb-10 text-center">
              <div class="col-md-12 col-lg-12">
                <div class="footer-widget-box about-us">
                    <a href="#" class="footer-logo">
                      <img src="{{ asset("img/logo/footer-logo.png") }}" alt="Company logo">
                    </a>
                    <p class="mb-3">
                    {{ config('app.name') }}<sup style="font-size: 9px;">TM</sup> is a digital platform by {{env('APP_OWNER_NAME')}} that aggregates over 25,000 billboards.
                    
                    {{-- and out of home advertising
                      assets of the
                      165 Outdoor Advertising Association of Nigeria members, Advertisers, media buyers and the
                      General public into
                      a very interactive digital marketplace. --}}
                    </p>
                    <ul class="footer-contact text-center">
                      <li><a href="tel:{{env('APP_CUSTOMER_CARE_PHONE', '+234-813-2614337')}}"><i class="far fa-phone"></i>{{env('APP_CUSTOMER_CARE_PHONE', '+234-813-2614337')}}</a></li>
                      <li><i class="far fa-map-marker-alt"></i>12, Ribadu Road, Off Awolowo way, Ikoyi</li>
                      <li><a href=""><i class="far fa-envelope"></i><span class="__cf_email__">
                        {{env('APP_CUSTOMER_CARE_EMAIL', 'info@globetrotter.com.ng')}}</span></a></li>
                    </ul>
                </div>
              </div>
          </div>
        </div>
    </div>
    <div class="copyright">
        <div class="container">
          <div class="row">
              <div class="col-md-12 align-self-center">
                <p class="copyright-text text-center">
                    &copy; Copyright <span id="date"></span> <a href="#"> {{ strtoupper(config('app.name')) }} </a>. All Rights Reserved.
                </p>
              </div>
          </div>
        </div>
    </div>
  </footer>
  <!--Footer Ends-->
  <a href="#" id="scroll-top"><i class="far fa-angle-up"></i></a>
  {{-- <script data-cfasync="false" src="../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script> --}}
  <script src="{{ asset("vendor/js/jquery-3.6.0.min.js") }}"></script>
  {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> --}}
  <script src="{{ asset("vendor/js/modernizr.min.js") }}"></script>
  <script src="{{ asset("vendor/js/bootstrap.bundle.min.js") }}"></script>
  <script src="{{ asset("vendor/js/imagesloaded.pkgd.min.js") }}"></script>
  <script src="{{ asset("vendor/js/jquery.magnific-popup.min.js") }}"></script>
  <script src="{{ asset("vendor/js/isotope.pkgd.min.js") }}"></script>
  <script src="{{ asset("vendor/js/jquery.appear.min.js") }}"></script>
  <script src="{{ asset("vendor/js/jquery.easing.min.js") }}"></script>
  <script src="{{ asset("vendor/js/owl.carousel.min.js") }}"></script>
  <script src="{{ asset("vendor/js/counter-up.js") }}"></script>
  <script src="{{ asset("vendor/js/masonry.pkgd.min.js") }}"></script>
  <script src="{{ asset("vendor/js/jquery.nice-select.min.js") }}"></script>
  <script src="{{ asset("vendor/js/jquery-ui.min.js") }}"></script>
  <script src="{{ asset("vendor/js/wow.min.js") }}"></script>
  <script src="{{ asset("vendor/js/main.js") }}"></script>
  <script src="{{ asset('vendor/js/printjs/print.min.js') }}"></script>
  <script src="{{ asset('/js/app.js') }}"></script>
  <script src="{{ asset('vendor/js/ion-range-slider.min.js') }}"></script>
  <script src="{{ asset('vendor/js/chartjs.js') }}"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <script src="{{ asset("vendor/js/jquery-ui.min.js") }}"></script>
  <script src="{{ asset('/js/pending-trans-script.js') }}"></script>
  <script src="{{ asset('/js/advanced-search.js') }}"></script>
  <?php 
    $segment = Request::segment(1);
    if ($segment && $segment === 'operator' || $segment === 'advertiser'){
  ?>
  <script src="{{ asset('/js/operator-asset-upload.js') }}"></script>
  <?php
    } 
   $segment = Request::segment(1);
   if ($segment && $segment === 'admin'){
  ?>
  <script src="{{ asset('/js/admin-backend.js') }}"></script>
  <?php
    } 
  ?>
  @yield('asset-details-js')
  <style>

    .notice-top {
        position: fixed;
        z-index: 9;
        width: 360px;
        top: 70px;
        right: 30px;
        visibility: hidden;
        transform: scale(0);
        transition: transform .5s ease-in-out 0s, visibility .5s linear 0s;
    }
    
    .show {
        visibility: visible;
        transform: scale(1);
    }
    
    .alert-content p {
        margin: 0;
        padding: 0;
    }
    
    .alert-content div p {
        font-size: 13px;
    }
    
  </style>

  @if ( session()->has('otp-failed') || session()->has('otp-success'))
  <script type="text/javascript">
      $(document).ready(function(){
          (function(){
            new bootstrap.Modal(".main-payment-token", {
              backdrop: 'static',
              keyboard: false
            }).show();
          })();
      })
  </script>
  @endif


  @if( Request::get('user') )
  <div class="alert alert-info notice-top" role="alert">
      <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <div class="alert-content"></div>
  </div>

  <script>
    window.Echo.channel('asset').listen('.asset-payment', function(response){
      prepare_successful_payment_notification(response);
    });

    function prepare_successful_payment_notification(parse) {
        var html = `
            <p class="text-msg">${ parse.message.msg }</p>
            <div>
                <p class="text-info"><strong>Asset Name</strong></p>
                <p class="text-info">${ parse.message.asset_name }</p>    
            </div>
            <div>
                <p class="text-info"><strong>Asset Booking Ref</strong></p>
                <p class="text-info">${ parse.message.asset_booking_ref }</p>    
            </div>
            <div>
                <p class="text-info"><strong>Amount Paid</strong></p>
                <p class="text-info">&#8358;${ parse.message.amount_paid }</p>    
            </div>
            <div>
                <p class="text-info"><strong>Bank Reference</strong></p>
                <p class="text-info">${ parse.message.bank_ref }</p>    
            </div>`

        $('.notice-top').addClass('show').find('.alert-content').html(html);

        new buzz.sound("{{asset('sounds/pling.mp3')}}").play();

        var time = setTimeout(function(){
            $('.notice-top').removeClass('show');
            clearTimeout(time);
            window.location.reload();
        }, 10000);

        $('.close').click(function(){
            clearTimeout(time);
            $(this).parent().removeClass('show');
        });
    }
  </script>
  @endif 
</body>

</html>