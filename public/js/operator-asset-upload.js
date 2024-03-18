$(document).ready(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    });

    $upload_cont = $('#asset-upload-container');
    $upload_cont_holder = $('.upload-faces-holder');
    
    var current_fs, next_fs, previous_fs;
    var asset_data = {};
    $proximity = {};
    $festive_promo = {};
    $price_promo = {};
    var asset_cat_id = '';
    var state_sel = $('select[name="asset_location_state"]');
    var lga_sel = $('select[name="asset_location_lga"]');
    var lcda_sel = $('select[name="asset_location_lcda"]');
    var sec3 = $('#sec3');
    const wrapper = $('.wrapper');
    $dsc = $('.apply-promo');
    $dc = $('.promo-copy');
    $elpromo = $('input[name="apply_promo"]');
    $payment_freq = $('select[name="payment_freq"]');
    $asset_cat_name_arry = ['static', 'dynamic', 'mobile'];

    if($('.payment_freq_price_range-slider').length) {
        const freq_price_range = $('.payment_freq_price_range-slider');
        addRangeSlider(freq_price_range, function(){
            $to = freq_price_range.data('to');
        });
    }

    addRangeSlider($('.range-slider'), function(){}).addClass('irs-hidden-input');

    if ($('.asset_dimension').length) {
        addRangeSlider($('.asset_dimension'), function(el){
            console.log(el.from + ";" + el.to, el.input)
            el.input.value = el.from + ";" + el.to
        });
    }

    $('.add-promo').on('click', function(e) {
        e.preventDefault();
        $len = $('.apply-promo').children('.promo-copy').length;
        $clone = $dc.clone();
        $festive_prd = $clone.find('.festive_periods');
        $festive_price = $clone.find('.range-slider');
        $festive_prd.attr('name', 'festive_periods_' + $len);
        $festive_price.attr('name', 'promo_price_range_' + $len);
        $festive_prd.val("");
        $clone.find('.irs-with-grid').remove();
        addRangeSlider($festive_price, function(){}).addClass('irs-hidden-input');
        $dsc.append($clone);
    });

    function addRangeSlider(element, callback) {
        return element.ionRangeSlider({
            type: "double",
            keyboard: true,
            grid: true,
            prettify: thousand_separator,
            prefix: "₦",
            postfix: '.00',
            force_edges: true,
            onFinish: callback,
        });
    }

    $('body').on('click', '.listing-item', function() {
        $self = $(this);

        const asset_cat_name = $self.data('name');
        asset_cat_id = parseInt($self.data('id'));

        $('.listing-item').not($self).parent().css('display', 'none');
        wrapper.addClass('fadeIn').removeClass('hide');
        wrapper.find('.' + asset_cat_name).removeClass('hide');


        $('.upload-title').css('display', 'block').html($self.data('title'));

        asset_data.asset_category = asset_cat_name;
        asset_data.asset_category_id = asset_cat_id;

        $('.board-plh').text(asset_cat_name.toUpperCase());

        if ( $asset_cat_name_arry.indexOf(asset_cat_name) != -1 ) {
            $basic_cat = $('.'+asset_cat_name);
            $basic_cat.removeClass('hide').removeClass('initial').addClass('current');
        } else {
            $digital = $('.digital');
            $digital.removeClass('hide').removeClass('initial').addClass('current');
        }

        show_price_frequency_variation();
        $("." + asset_cat_name).find("input:not([type='radio'])").addClass('required');

        // $('.initial').remove();

        $cont = $('#showboard');

        if ($cont.hasClass('show-asset-upload')) $cont.removeClass('show-asset-upload');
        else $cont.addClass('show-asset-upload');

        $('html, body').animate({scrollTop: $upload_cont.offset().top }, 700);  
        
        $asset_types = asst_types.filter((el,id) => el.board_type === asset_cat_id && el.enabled === 1);

        $options = [];
        $options.push(new Option('-- Select --', ""));
        $asset_types.map((el, id) => {
            $options.push(new Option(el.type, el.id));
        });
        $('select[name="asset_type"]').html($options);
    });

    // $('.listing-item:first').get(0).click();

    $old_sub_board = ''; $current_sub_board = ''; $temp_sub_board = '';
    $('input[name="advert_type"]').on('click', function() {
        $self = $(this);
        // $('#mobile-sub').removeClass('hide');
        $current_sub_board = $self.val();
        if ( $temp_sub_board !== $current_sub_board ) {
            $old_sub_board = $temp_sub_board;
            $temp_sub_board = $current_sub_board;
        }
        if ($old_sub_board) $('.' + $old_sub_board).addClass('hide');
        $('.' + $current_sub_board).removeClass('hide');
        show_price_frequency_variation();

        console.log(asset_cat_id, $current_sub_board);

        if (asset_cat_id === 4 && $current_sub_board === 'static') {
            $('input[name="pixel_resolution_width"]').addClass('required');
            $('input[name="pixel_resolution_height"]').addClass('required');
        } 
        else {
            $('input[name="pixel_resolution_width"]').removeClass('required');
            $('input[name="pixel_resolution_height"]').removeClass('required');
        } 
    });

    $(".next-form").on("click", function (e) {
        e.preventDefault();
        navigate_form('next', $(this));
    });

    $(".back-form").on("click", function (e) {
        e.preventDefault();
        navigate_form('prev', $(this));
    });

    $(".submit-asset").on("click", function (e) {
        e.preventDefault();
    });

    $('input[name="apply_promo"]').on('click', function(){
        $self = $(this);
        $dcs = $('.promo-settings');
        if ($self.is(':checked') && $self.val() === 'YES') {
            if ($dcs.hasClass('hide')) $dcs.removeClass('hide');
            $('.festive_periods').addClass('required');
            $('.promo_price_range').addClass('required');
        } else {
            $dcs.addClass('hide');
            $('.festive_periods').removeClass('required');
            $('.promo_price_range').removeClass('required');
        }
    });

    $('body').on('click', 'input[name="face_count"]', function() {
        $faces = $(this).val();
        $upload_cont_holder.html(present_upload_faces(parseInt($faces)));
    });



    if ($(".show_paid_det").length) {
        $(".show_paid_det").on("click", function (e) {
            e.preventDefault();
            $self = $(this);
            $paid_el = $self.data('paid_el');
            $('.modal-title').text('Paid Schedule');
            $('.asset-content').html(sec3.find('#'+$paid_el).clone().css('display','block'));
        });
    }
    if ($(".show_pending_det").length) {
        $(".show_pending_det").on("click", function (e) {
            e.preventDefault();
            $self = $(this);
            $pending_el = $self.data('pending_el');
            $('.modal-title').text('Pending Payment Schedule');
            $('.asset-content').html(sec3.find('#'+$pending_el).clone().css('display','block'));
        });
    }

    $('body').on('click', '.add-more-asset-images', function() {
       $self = $(this);
       $(upload_image_clickable_template()).insertBefore($self);
    });

    function upload_image_clickable_template() {
        return `
            <div class="col-sm-3">
                <div class="add-list-media-wrap">
                    <div class="fuzone" style="background:url(${origin + '/img/assets/more-advert.jpg'}) no-repeat center/cover">
                        <div class="fu-text">
                            <span>Kindly upload more asset pictures to attract advertisers.</span>
                        </div>
                        <input type="file" class="upload" accept="image/jpeg, image/jpg, image/png, image/bmp">
                        <div class="listing-counter" style="left: 15px; top: 10px;">MoreShot</div>
                    </div>
                </div>
            </div>
        `;
    }

    function show_clickable_modal(el) {
        new bootstrap.Modal(el, {
            backdrop: 'static',
            keyboard: false
        }).show();
    }

    function show_price_frequency_variation() {

        $payment_freq_opts = `
            <option value="">-- Select --</option>
            <option value="Annually">Annually</option>
            <option value="Quarterly">Quarterly</option>
            <option value="Monthly">Monthly</option>
            <option value="Annually">Annually</option>
            <option value="Quarterly">Quarterly</option>
            <option value="Monthly">Monthly</option>
            <option value="Weekly">Weekly</option>
            <option value="Daily">Daily</option>
            <option value="Hourly">Hourly</option>
            <option value="Minute">Minute</option>
            <option value="Second">Second</option>
            `; 

        $payment_freq.html($payment_freq_opts);
    }
    

    preview_before_upload();


    state_sel.on('change', function(){
        $state_id = $(this).children(':selected').val();
        $state_text = $(this).children(':selected').text();
        get_operation('/asset/states/'+$state_id+'/lga', function(lgas){
            lga_arr = ['<option value="">-- Select LGA --</option>'];
            if (lgas.status) {
                $selected = "";
                lgas.success.forEach(element => {
                    if (element.default_value) $selected = "selected = 'selected'";
                    lga_arr.push('<option value="'+element.id+'" '+$selected+'>'+ element.lga_name +'</option>');
                    $selected = "";
                });
                try {
                    lga_sel.html(lga_arr.join(''));
                } catch (error) {
                }
            }
        }); 

        get_operation('/asset/states/'+$state_id+'/lcda', function(lcdas){
            lcda_arr = ['<option value="">-- Select LCDA --</option>'];
            if (lcdas.status) {
                lcdas.success.forEach(element => {
                    lcda_arr.push('<option value="'+element.id+'">'+ element.lcda_name +'</option>');
                });
                try {
                    lcda_sel.html(lcda_arr.join(''));
                } catch (error) {
                }
            }
        }); 
    });
    // $.get(api + '/states/'+ $state_text +'/lgas').done(lgas =>{ 
        //     console.log(lgas);
        //     // $.post('/operator/states/lga', {lgas: lgas, state_id: $state_id}).done(res => {
        //     //    if (res.status) alert(res.success)
        //     // });
        // }).fail(err => console.log(err));

    get_operation('/asset/states', function(states){
        if (states.status) {
            states.success.forEach(element => {
                $option = new Option(element.state_name, element.id);
                state_sel.append($($option))
            });
            try {
                state_sel;
            } catch (error) {
                console.error(error);
            }
        }
    });


    function get_operation(url, cb) {
        $.get(url).done(states =>{ 
            cb(states);
        }).fail(err => console.log(err));;
    }


    function navigate_form(moves, el) {

        current_fs = el.closest('.tab-pane');

        if (moves === 'prev') {
            previous_fs = el.closest('.tab-pane').removeClass('active show').prev();
            $("#progressbar li").eq($(".tab-pane").index(current_fs)).removeClass("active").prev("li").addClass('active');
            previous_fs.addClass('active show');
            $('html, body').animate({scrollTop: $upload_cont.offset().top }, 700);  
        }
        else {
            validate_asset_form(current_fs, el, function(valid){
                // valid = true;
                if (valid) {
                    next_fs = el.closest('.tab-pane').removeClass('active show').next();
                    $("#progressbar li").removeClass('active').eq($(".tab-pane").index(next_fs)).addClass("active");
                    next_fs.addClass('active show');
                    $('html, body').animate({scrollTop: $upload_cont.offset().top }, 700);  
                }
            });
        }

    }


    function present_upload_faces(faces) {
        var sides = 3;
        var total_sides = ( sides * faces );
        var composite = '';
        var faces = 0;
        var origin = location.origin;
        for (let index = 0; index < total_sides; index+=sides) {
            faces++;
            composite += `
                <div class="row clearfix">
                    <div class="col-sm-12">
                        <h2 style="font-size: 15px; padding: 18px 0;">Face ${faces}</h2>
                        <div class="row clearfix">
                            <div class="col-sm-3">
                                <div class="add-list-media-wrap">
                                    <div class="fuzone" style="background:url(${origin + '/img/assets/billboard-front.jpg'}) no-repeat center/cover">
                                        <div class="fu-text">
                                            <span>Upload longshot of this asset facing traffic.</span>
                                        </div>
                                        <input type="file" class="upload" accept="image/jpeg, image/jpg, image/png, image/bmp">
                                        <div class="listing-counter" style="left: 15px; top: 10px;">LongShot</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="add-list-media-wrap">
                                    <div class="fuzone" style="background:url(${origin + '/img/assets/billboard-sides.jpg'}) no-repeat center/cover">
                                        <div class="fu-text">
                                            <span>Upload closeshot of this asset for quality purpose.</span>
                                        </div>
                                        <input type="file" class="upload" accept="image/jpeg, image/jpg, image/png, image/bmp">
                                        <div class="listing-counter" style="left: 15px; top: 10px;">CloseShot</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="add-list-media-wrap">
                                    <div class="fuzone" style="background:url(${origin + '/img/assets/night-face.jpg'}) no-repeat center/cover">
                                        <div class="fu-text">
                                            <span>Upload nightshow of this asset for attraction.</span>
                                        </div>
                                        <input type="file" class="upload" accept="image/jpeg, image/jpg, image/png, image/bmp">
                                        <div class="listing-counter" style="left: 15px; top: 10px;">NightShot</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 add-more-asset-images">
                                <div class="add-list-media-wrap">
                                    <div class="fuzone" style="background:url(${origin + '/img/assets/add-more-billboards.jpg'}) no-repeat center/cover"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
        return composite;
    }

    function preview_before_upload() {
        $('body').on('change', '.upload', function(evt) { // ensure a preview of the selected file from user (system HD)

            evt.preventDefault();

            $self = $(this); 
            $parent = $self.parent();

            $files = $self.get(0).files[0];
            $fileReader = new FileReader();

            $fileReader.onload = function(evt){
                $parent.css({
                    background: 'url('+evt.target.result+') no-repeat center/cover',
                });
            }
            $fileReader.readAsDataURL($files);
        });

        $('body').on('change', '.upload-video', function(evt) { // ensure a preview of the selected file from user (system HD)

            evt.preventDefault();

            $self = $(this);
            $parent = $self.parent();
            $videoPlayer = $parent.find("#video-player").get(0);
            $videoPlayerSrc = $parent.find("#video-source").get(0);

            $files = $self.get(0).files[0];
            if($files.size > (3 * 1024 * 1204)) {
                alert("You can not upload a file larger than 2MB");
                $self.val("");
                return ;
            }

            // $videoPlayerSrc.src = URL.createObjectURL($files);
            // $videoPlayer.load();

            $fileReader = new FileReader();

            $fileReader.onload = function(evt){
                $videoPlayerSrc.src = evt.target.result;
                $videoPlayer.load();
            }
            $fileReader.readAsDataURL($files);
        });


        $('body').on('change', '#asset_video', function(evt) { 
            evt.preventDefault();

            $self = $(this);
            $videoPlayer = $("#video-player").get(0);
            
            $files = $self.get(0).files[0];
            console.log("$files = ", $files);
            $('#file-name').html($files.name);
            if($files.size > (3 * 1024 * 1204)) {
                alert("You can not upload a file larger than 3MB");
                $self.val("");
                return ;
            }

            $fileReader = new FileReader();

            $fileReader.onload = function(evt){
                $videoPlayer.src = evt.target.result;
                $videoPlayer.load();
            }
            $fileReader.readAsDataURL($files);
        });
    }

    function validate_asset_form($fieldset, $el, cb) {
        $errors = [];

        if ( $el.hasClass('submit-asset') ) {

            $former = `Confirm &amp; Submit<i class="fal fa-save"></i>`;
            $new = `Processing`;

            $el.removeAttr('href').html($new);

            var fd = new FormData();
            $err = [];
            if(!$('.upload').length) {
                $err.push(false);
            }

            $('.upload').each((index, element) => { // prepare  each file for upload to the server
                $files = $(element).get(0).files[0];
                if (typeof($files) !== "undefined") {
                    fd.append('asset_images[]', $files);
                    $(element).parent().addClass('preview-suc');
                } else {
                    $err.push(false);
                    $(element).parent().addClass('preview-err');
                }
            });

            if($('.upload-video').length) {
                console.log('asset_video = ', $('.upload-video').get(0).files[0]);
                fd.append('asset_video', $('.upload-video').get(0).files[0]);
            }
            
            if ($err.length) {
                $errors.push('<li class="fs-13">Kindly ensure you select the appropriate photo, also ensure that all boxes displayed your chosen photo.</li>');
                cb(determine_errors($errors, $fieldset));
            }
            else {

                fd.append('_token', $('meta[name="csrf-token"]').attr('content'));

                for($index in asset_data) {
                    fd.append($index, asset_data[$index]);
                }

                console.log("key, value");
                for(let v of fd.entries()) {
                    console.log(`${v[0]}, ${v[1]}`);
                }
                console.log('asset_data = ', asset_data);
                
                $.ajax({
                    url: '/operator/asset/upload',
                    method: 'POST',
                    processData: false,
                    contentType: false,
                    data: fd,
                    mimeType: 'multipart/form-data',
                }).done(res => { 
                    res = JSON.parse(res);
                    if (!res.status) {
                        window.swal({
                            title: 'Asset Declined',
                            type: 'error',
                            text: res.errors,
                        });
                        $el.attr('href', '#').html($former);
                        $errors = ['<li>'+ res.errors +'</li>', ...$errors];
                        cb(determine_errors($errors, $fieldset));
                    } else {
                        cb(determine_errors($errors, $fieldset));
                    }
                }).fail(err => { 
                    window.swal({
                        title: 'Server Error',
                        type: 'error',
                        text: 'Apologies, server has encounter an error.',
                    });
                    $el.attr('href', '#').html($former);
                    $errors = ['<li class="fs-13">Apologies, server has encounter an error.</li>', ...$errors];
                    cb(determine_errors($errors, $fieldset));
                });                
            }

        } else {

            if ($fieldset.find('.check-proximity').length) {
                $checked_len = $fieldset.find('.check-proximity input[type="checkbox"]:checked').length;
                if (!$checked_len) 
                    $errors = [...$errors, '<li class="fs-13">Kindly ensure to select more than 1 proximity or landmark close to your asset to enhance better search result for this asset.</li>'];
            }

            // if(!$fieldset.find('.upload').length) {
            //     $errors.push('<li>Kindly ensure each upload boxes appears to be displaying your uploaded asset pictures.</li>');
            // }

            if(!$fieldset.find('.upload').length && $fieldset.data('attrib') === "image") {
                $errors = [...$errors, '<li class="fs-13">Kindly select number of faces your board have in order to upload pictures related to each face.</li>'];
            }

            $fieldset.find('.upload').each((index, element) => { // prepare  each file for upload to the server
                $files = $(element).get(0).files[0];
                if (typeof($files) === "undefined") { 
                    $errors = [...$errors, '<li class="fs-13">Kindly ensure to upload asset picture for the No '+ (index+1) +' box.</li>'];
                }
            });

            // Check validation for input
            $fieldset.find('input.required').each((index, element) => {
                $el = $(element);
                if (!$el.val().trim()) {
                    $errors = [...$errors, '<li class="fs-13">Kindly ensure the '+ $el.attr('name').toUpperCase().replace(/_/g, ' ') +' field is not empty.</li>'];
                }
    
                if ($el.val().trim()) {

                    if ($el.data('proximity')) {
                        $proximity[$el.attr('name')] = $el.val().trim();
                        return ;
                    }

                    
                    // Price range data capture for promo
                    if ($el.data('price_promo')) {
                        $price_promo[$el.attr('name')] = $el.val().trim();
                        return ;
                    }

                    asset_data[$el.attr('name')] = $el.val().trim();
                }
            });
    
            // Check validation for select input
            $fieldset.find('select.required').each((index, element) => {
                $el = $(element);
                if (!$el.val().trim()) {
                    $errors = [...$errors, '<li class="fs-13">Kindly ensure the '+ $el.attr('name').toUpperCase().replace(/_/g, ' ') +' field is not empty.</li>'];
                }
    
                if ($el.val().trim()) {


                    if ($el.data('proximity')) {
                        $proximity[$el.attr('name')] = $el.val().trim();
                        return ;
                    }

                    
                    // Festive period data capture for promo
                    if ($el.data('festive_promo')) {
                        $festive_promo[$el.attr('name')] = $el.val().trim();
                        return ;
                    } 

                    asset_data[$el.attr('name')] = $el.val().trim();
                }
            });

            if ($proximity) asset_data['proximity'] = JSON.stringify($proximity);

            // Promo (Festive Period & Price Range) data capture.
            if ($festive_promo) asset_data['promo_festive_periods'] = JSON.stringify($festive_promo);
            if ($price_promo) asset_data['promo_price_range'] = JSON.stringify($price_promo);


            $error_radio_arr = [];
            $input_radio_arr = [];
            // Check validation for radio button input
            $fieldset.find('input[type="radio"]').each((index, element) => {
                $el = $(element);
                if ($el.is(':checked')) { 

                    $elem = $('input[name="'+$el.attr('name')+'"]:checked');

                    asset_data[$el.attr('name')] = $elem.val().trim();

                    if ($elem.val().trim() === 'others') {
                        asset_data[$el.attr('name')] = $elem.closest('.col-sm-12').next('div').find('input').val().trim();
                    }
    
                    // if this checkbox has been checked now but it still appear in the error array, kindly remove 
                    // it from the array.
                    if ( $error_radio_arr.indexOf($el.attr('name')) !== -1 ) {
                        $index = $error_radio_arr.indexOf($el.attr('name'));
                        $error_radio_arr.splice($index, 1);
                    }
    
                    $input_radio_arr = [...$input_radio_arr, $el.attr('name')];
    
                } else {
                    // Makes all visible field compulsory for entry.
                    if ( $('input[name="'+$el.attr('name')+'"]').is(':visible') ) {
                        if ( $error_radio_arr.indexOf($el.attr('name')) === -1 && $input_radio_arr.indexOf($el.attr('name')) === -1 ) 
                            $error_radio_arr = [...$error_radio_arr, $el.attr('name')];
                    }
                }
            });
    
            if ($error_radio_arr.length) {
                $error_radio_arr.forEach((el, idx) => { $errors = [...$errors, '<li class="fs-13">Kindly ensure one of the '+ el.toUpperCase().replace(/_/g, ' ') +' field is checked.</li>']; });
            }

            cb(determine_errors($errors, $fieldset));
        }
    }

    function determine_errors($errors, $fieldset) {
        if ( $errors.length ) {
            $fieldset.find('.alert').removeClass('hide');
            $fieldset.find('.alert ul').html($errors.join(''));
            $('html, body').animate({scrollTop: $upload_cont.offset().top }, 700);
            return false;
        }
        else {
            $fieldset.find('.alert').addClass('hide');
            $fieldset.find('.alert ul').html('');
            return true;
        }
    }

    check_others();
    function check_others() {
        $('input[type="radio"]').on('click', function(){
            $self = $(this);
            if ($self.is(':checked') && $self.data('others')) {
                $self.closest('.col-sm-12').next().removeClass('hide-others').find('input').addClass('required');
            } else {
                $self.closest('.col-sm-12').next().addClass('hide-others').find('input').removeClass('required');
            }
        });
    }

    check_proximity();
    function check_proximity() {
        $event_center_row = $('.event_center_row');
        $('.check-proximity').find('input[type="checkbox"]').on('click', function(){
            $self = $(this);
            more_proximity_options($self, $event_center_row);
        });
    }

    function more_proximity_options($self, $event_center_row) {
        if ($self.is(':checked')) {
            if ($self.data('maps')) {
                $more_prox = $('#' + $self.data('maps'));
                $more_prox.find('select').addClass('required');
                if ($more_prox.hasClass('hide-more-proximity')) $more_prox.removeClass('hide');
            } else {
                $more_description = `<div class="col-6 col-md-4" id="${$self.val().toLowerCase().replace(/ /g, '_').replace(/\//g, '_')}">
                                        <div class="form-group">
                                            <label class="fs-13">Enter the <span class="text-cyan">${$self.val()}</span> name close to your asset</label>
                                            <input type="text" placeholder="${$self.val()} name" name="${$self.val().toLowerCase().replace(/ /g, '_').replace(/\//g, '_')}" class="form-control form-control-sm required" data-proximity="proximity">
                                            <i class="far fa-location-dot"></i>
                                        </div>
                                    </div>`;
                $event_center_row.prepend($more_description);
            }
        } else {
            if ($self.data('maps')) {
                $more_prox = $('#' + $self.data('maps'));
                $more_prox.find('select').removeClass('required');
                $more_prox.addClass('hide-more-proximity hide');
            } 
            else $event_center_row.find('#' + $self.val().toLowerCase().replace(/ /g, '_').replace(/\//g, '_')).remove();
        }
    }

    function thousand_separator(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
});