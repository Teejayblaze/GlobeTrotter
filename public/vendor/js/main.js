/* =====================================================
Template Name   : Homfind
Description     : Real Estate HTML5 Template
Author          : Themesland
Version         : 1.0
=======================================================*/


(function ($) {
    "use strict";


    // multi level dropdown menu
    $('.dropdown-menu a.dropdown-toggle').on('click', function (e) {
        if (!$(this).next().hasClass('show')) {
            $(this).parents('.dropdown-menu').first().find('.show').removeClass('show');
        }
        var $subMenu = $(this).next('.dropdown-menu');
        $subMenu.toggleClass('show');

        $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function (e) {
            $('.dropdown-submenu .show').removeClass('show');
        });
        return false;
    });



    // data-background    
    $(document).on('ready', function () {
        $("[data-background]").each(function () {
            $(this).css("background-image", "url(" + $(this).attr("data-background") + ")");
        });
    });


    // wow init
    new WOW().init();


    // hero slider
    $('.hero-slider').owlCarousel({
        loop: true,
        nav: true,
        dots: false,
        margin: 0,
        autoplay: true,
        autoplayHoverPause: true,
        autoplayTimeout: 5000,
        items: 1,
        navText: [
            "<i class='far fa-long-arrow-left'></i>",
            "<i class='far fa-long-arrow-right'></i>"
        ],

        onInitialized: function(event) {
        var $firstAnimatingElements = $('.owl-item').eq(event.item.index).find("[data-animation]");
        doAnimations($firstAnimatingElements);
        },

        onChanged: function(event){
        var $firstAnimatingElements = $('.owl-item').eq(event.item.index).find("[data-animation]");
        doAnimations($firstAnimatingElements);
        }
    });

    //hero slider do animations
    function doAnimations(elements) {
		var animationEndEvents = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
		elements.each(function () {
			var $this = $(this);
			var $animationDelay = $this.data('delay');
			var $animationDuration = $this.data('duration');
			var $animationType = 'animated ' + $this.data('animation');
			$this.css({
				'animation-delay': $animationDelay,
				'-webkit-animation-delay': $animationDelay,
                'animation-duration': $animationDuration,
                '-webkit-animation-duration': $animationDuration,
			});
			$this.addClass($animationType).one(animationEndEvents, function () {
				$this.removeClass($animationType);
			});
		});
	}



    // category-slider
    $('.category-slider').owlCarousel({
        loop: true,
        margin: 20,
        nav: false,
        dots: true,
        navText: [
            "<i class='fal fa-long-arrow-left'></i>",
            "<i class='fal fa-long-arrow-right'></i>"
        ],
        autoplay: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 4
            }
        }
    });



    // property-listing-slider
    $('.property-listing-slider').owlCarousel({
        loop: true,
        margin: 0,
        nav: false,
        dots: true,
        navText: [
            "<i class='fal fa-long-arrow-left'></i>",
            "<i class='fal fa-long-arrow-right'></i>"
        ],
        autoplay: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 3
            }
        }
    });


    // testimonial-slider
    $('.testimonial-slider').owlCarousel({
        loop: true,
        margin: 30,
        nav: false,
        dots: true,
        autoplay: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 4
            }
        }
    });


    // partner-slider
    $('.partner-slider').owlCarousel({
        loop: true,
        margin: 20,
        nav: false,
        dots: false,
        autoplay: true,
        responsive: {
            0: {
                items: 2
            },
            600: {
                items: 3
            },
            1000: {
                items: 6
            }
        }
    });



    // property-single-slider
    $('.property-single-slider').owlCarousel({
        loop: true,
        margin: 0,
        nav: true,
        dots: false,
        navText: [
            "<i class='fal fa-long-arrow-left'></i>",
            "<i class='fal fa-long-arrow-right'></i>"
        ],
        autoplay: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    });


    // property-similar-slider
    $('.property-similar-slider').owlCarousel({
        loop: true,
        margin: 0,
        nav: false,
        dots: true,
        navText: [
            "<i class='fal fa-long-arrow-left'></i>",
            "<i class='fal fa-long-arrow-right'></i>"
        ],
        autoplay: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    });



    // preloader
    $(window).on('load', function () {
        $(".preloader").delay(1000).fadeOut("slow");
    });


    // fun fact counter
    $('.counter').countTo();
    $('.counter-box').appear(function () {
        $('.counter').countTo();
    }, {
        accY: -100
    });



    // progress bar
    $(document).ready(function(){
        var progressBar = $('.progress');
        if(progressBar.length) {
        progressBar.each(function () {
            var Self = $(this);
            Self.appear(function () {
            var progressValue = Self.data('value');
            Self.find('.progress-bar').animate({
                width:progressValue+'%'           
            }, 1000);
            });
        })
        }
    });


    // magnific popup init
    $(".popup-gallery").magnificPopup({
        delegate: '.popup-img',
        type: 'image',
        gallery: {
            enabled: true
        },
    });

    $(".popup-youtube, .popup-vimeo, .popup-gmaps").magnificPopup({
        type: "iframe",
        mainClass: "mfp-fade",
        removalDelay: 160,
        preloader: false,
        fixedContentPos: false
    });



    // scroll to top
    $(window).scroll(function () {

        if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
            $("#scroll-top").fadeIn('slow');
        } else {
            $("#scroll-top").fadeOut('slow');
        }
    });

    $("#scroll-top").click(function () {
        $("html, body").animate({ scrollTop: 0 }, 1500);
        return false;
    });


    $(window).scroll(function () {
        if ($(this).scrollTop() > 50) {
            $('.navbar').addClass("fixed-top");
        } else {
            $('.navbar').removeClass("fixed-top");
        }
    });


    // countdown
    if ($('#countdown').length) {
        $('#countdown').countdown('2028/01/30', function (event) {
            $(this).html(event.strftime('' + '<div class="row">' + '<div class="col countdown-single">' + '<h2 class="mb-0">%-D</h2>' + '<h5 class="mb-0">Day%!d</h5>' + '</div>' + '<div class="col countdown-single">' + '<h2 class="mb-0">%H</h2>' + '<h5 class="mb-0">Hours</h5>' + '</div>' + '<div class="col countdown-single">' + '<h2 class="mb-0">%M</h2>' + '<h5 class="mb-0">Minutes</h5>' + '</div>' + '<div class="col countdown-single">' + '<h2 class="mb-0">%S</h2>' + '<h5 class="mb-0">Seconds</h5>' + '</div>' + '</div>'));
        });
    }


    // project filter
    $(window).on('load', function () {
        if ($(".filter-box").children().length > 0) {
            $(".filter-box").isotope({
                itemSelector: '.filter-item',
                masonry: {
                    columnWidth: 1
                },
            });

            $('.filter-btns').on('click', 'li', function () {
                var filterValue = $(this).attr('data-filter');
                $(".filter-box").isotope({ filter: filterValue });
            });

            $(".filter-btns li").each(function () {
                $(this).on("click", function () {
                    $(this).siblings("li.active").removeClass("active");
                    $(this).addClass("active");
                });
            });
        }
    });


    // copywrite date
    let date = new Date().getFullYear();
    $("#date").html(date);


    // nice select
    $(document).ready(function() {
        $('.select').niceSelect();
    });


    // advanced search
    $('.advanced-search').click(function(){
        $('.advanced-search-menu').toggle();
    });


    // price slider
    $(function() {
        $(".price-range").slider({
          step: 500,
          range: true, 
          min: 0, 
          max: 90000, 
          values: [10000, 50000], 
          slide: function(event, ui)
          {$(".priceRange").val("$" + ui.values[0].toLocaleString() + " - $" + ui.values[1].toLocaleString());}
        });
        $(".priceRange").val("$" + $(".price-range").slider("values", 0).toLocaleString() + " - $" + $(".price-range").slider("values", 1).toLocaleString());
    });


    // profile image btn
    $(".profile-img-btn").click(function () {
        $(".profile-img-file").click(); 
   });


    // property images upload
    $(".property-img-upload").click(function () {
        $(".property-img-file").click(); 
   });


    // message bottom scroll
    if($('.message-content-info').length){
        $(function () {
            var chatbox = $('.message-content-info');
            var chatheight = chatbox[0].scrollHeight;
            chatbox.scrollTop(chatheight);
        });
    }

    //Add New Staff
    $("#add-staff-btn").click(function () {
      $(".add-staff-form").css("display", "block"); 
    });

    //Show Site Location
    $("#dynamic-btn").click(function () {
        $("#dynamic-board").removeClass("hide-site-location").addClass("show-site-location");
        $("#static-board, #digital-board, #mobile-board").removeClass("show-site-location").addClass("hide-site-location");
    });
    $("#digital-btn").click(function () {
        $("#digital-board").removeClass("hide-site-location").addClass("show-site-location");
        $("#static-board, #dynamic-board, #mobile-board").removeClass("show-site-location").addClass("hide-site-location");
    });
    $("#mobile-btn").click(function () {
        $("#mobile-board").removeClass("hide-site-location").addClass("show-site-location");
        $("#static-board, #dynamic-board, #digital-board").removeClass("show-site-location").addClass("hide-site-location");
    });
    $("#static-btn").click(function () {
        $("#static-board").removeClass("hide-site-location").addClass("show-site-location");
        $("#mobile-board, #dynamic-board, #digital-board").removeClass("show-site-location").addClass("hide-site-location");
    });

    //Show Site Category
    $("#dynamic-category-btn").click(function () {
        $("#dynamic-board").removeClass("hide-site-category").addClass("show-site-category");
        $("#static-board, #digital-board, #mobile-board").removeClass("show-site-category").addClass("hide-site-category");
    });
    $("#digital-category-btn").click(function () {
        $("#digital-board").removeClass("hide-site-category").addClass("show-site-category");
        $("#static-board, #dynamic-board, #mobile-board").removeClass("show-site-category").addClass("hide-site-category");
    });
    $("#mobile-category-btn").click(function () {
        $("#mobile-board").removeClass("hide-site-category").addClass("show-site-category");
        $("#static-board, #dynamic-board, #digital-board").removeClass("show-site-category").addClass("hide-site-category");
    });
    $("#static-category-btn").click(function () {
        $("#static-board").removeClass("hide-site-category").addClass("show-site-category");
        $("#mobile-board, #dynamic-board, #digital-board").removeClass("show-site-category").addClass("hide-site-category");
    });

    //Campaign-form-submit-button
    $("#campaign-form-submit-button").click(function () {
        $("#create-campaign-modal").toggleClass("hide-campaign-modal");
    })

    //Show asset single info
    $("#neighbour-btn").click(function () {
     $("#eye-slash-visibility-one").toggleClass("visibility-off");
     $("#eye-visibility-one").toggleClass("visibility-on");
     $("#asset-neighbour-info").toggleClass("show-single-info");
    });
    $("#dimension-btn").click(function () {
        $("#eye-slash-visibility-two").toggleClass("visibility-off");
        $("#eye-visibility-two").toggleClass("visibility-on");
        $("#asset-dimension-info").toggleClass("show-single-info");
       });
       $("#description-btn").click(function () {
        $("#eye-slash-visibility-three").toggleClass("visibility-off");
        $("#eye-visibility-three").toggleClass("visibility-on");
        $("#asset-description-info").toggleClass("show-single-info");
       });
    
    //Add items to campaign
    $("#asset-campaign-1").click(function() {
       $("#check-badge-1").toggleClass("show-check-badge");
    });
    $("#asset-campaign-2").click(function() {
        $("#check-badge-2").toggleClass("show-check-badge");
     });
     $("#asset-campaign-3").click(function() {
        $("#check-badge-3").toggleClass("show-check-badge");
     });
     $("#asset-campaign-4").click(function() {
        $("#check-badge-4").toggleClass("show-check-badge");
     });
     $("#asset-campaign-5").click(function() {
        $("#check-badge-5").toggleClass("show-check-badge");
     });
     $("#asset-campaign-6").click(function() {
        $("#check-badge-6").toggleClass("show-check-badge");
     });
     $("#asset-campaign-7").click(function() {
        $("#check-badge-7").toggleClass("show-check-badge");
     });
     $("#asset-campaign-8").click(function() {
        $("#check-badge-8").toggleClass("show-check-badge");
     });
     $("#asset-campaign-9").click(function() {
        $("#check-badge-9").toggleClass("show-check-badge");
     });
     $("#asset-campaign-10").click(function() {
        $("#check-badge-10").toggleClass("show-check-badge");
     });
     $("#asset-campaign-11").click(function() {
        $("#check-badge-11").toggleClass("show-check-badge");
     });

     //Campaign cart btn
     $("#campaign-cart-btn").click(function() {
        $("#add-to-existing-campaign-modal").css("display", "block");
     });

     //Close campaign list btn
    $("#close-campaign-list-btn").click(function() {
      $("#add-to-existing-campaign-modal").css("display", "none");
    });

    //Toggle between show and hide map on asset-details page
    $("#show-and-hide-map-btn").click(function() {
          $("#property-single-map").toggleClass("show-property-single-map");
    })
})(jQuery);










