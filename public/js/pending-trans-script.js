$(document).ready(function(){
    var payable = 0;

    $('#amt_payable').focusin(function(){
        var self = $(this); 
        $(this).addClass('is-focus');
    }).focusout(function(){
        var self = $(this);
        const value = parseFloat(self.text().trim());
        if(!isNaN(value) && typeof(value) !== undefined && value > 0) {
            payable = parseFloat(value);
        }
        $(this).removeClass('is-focus');
    });

    $('#app_next_modal, #app_modal').on('hide.bs.modal', function(){
        window.location.reload();
    });

    $('body').on('click', '.hide_dismiss, .close-reg', function(evt){  
        evt.preventDefault();  
        window.location.reload();
    });

    $('body').on('click', '.modal-open-payment-schedule-wrap', function(evt){  
        evt.preventDefault();
        var self = $(this);
        fill_params(self);
        payable = self.data('actualbal');
        if(payable) {
            payable = parseFloat(payable.trim().replace(/,/g, ''));
        }
        open_modal('.main-payment-schedule-wrap');
    });

    $('body').on('click', '.modal-open-pending-payment-schedule-wrap', function(evt){  
        evt.preventDefault();
        fill_params($(this));
        open_modal('.pending-payment-schedule-wrap');
    });
    
    $('body').on('click', '.view-img', function(evt){  
        evt.preventDefault();
        let paths = $(this).data('path');
        let columns = ``;
        if(paths) {
            paths = paths.split(",");

            for (let index = 0; index < paths.length; index++) {
                const path = paths[index];
                const extension = path.substr(path.indexOf('.')+1);
                if(['jpg', 'jpeg', 'bmp'].indexOf(extension) !== -1) {
                    columns += `<div class="col-md-3">
                                    <img src="${path}" alt="Uploaded Image" width="100%" />
                                </div>`;
                }
                else if (['mp4'].indexOf(extension) !== -1) {
                    columns += `<div class="col-md-3">
                                    <video autoplay controls src="${path}" width="100%">
                                        Your browser does not support HTML video.
                                    </video>
                                </div>`;
                }
                
            }
        }

        $('.image-preview').find('.content').html(columns);
        open_modal('.image-preview');
    });


    
    $('body').on('click', '#generate-nibbs-code', function(evt){  
        evt.preventDefault();
        $('#otp-generator-form').submit();
    });   

    $('body').on('click', '.generate_tranx', function(evt) {
        evt.preventDefault();
        $self = $(this);
        serve_axios($self, function(status, data) {
            if ( status ) {

                $self.css("display", "none");

                window.swal({
                    title: 'Successful',
                    type: 'success',
                    text: data.msg,
                });

                $table = create_transaction_table(data.trnx);
                $('.payment-sched-printable-content').html($table).css('display', 'block');
                $('.payment-sched-printable-btn, .hide-defualt').css('display', 'inline-block');
                $('.hide_print').css('display', 'none');
            } else {
                console.log(data);
                window.swal({
                    title: 'Server Error',
                    type: 'error',
                    text: data,
                });
            }
        });
    });

    $('body').on('click', '.bank_ul li a', function(evt){
        var self = $(this);
        if(['global-pay', 'flutterwave'].includes(self.data('formid'))) {
            $("#" + self.data('formid')).submit();
            self.closest(".bank_ul").removeClass("bank-open");
        }
        else if(self.data('formid') === 'open-payment-token-modal') {
            $('#txref').val(self.data('txref'));
            $('#tid').val(self.data('tid'));
            open_modal('.main-payment-token');
        }
    });


    $('body').on('click', '.payment-sched-printable-btn', function(){
        print_area('payment-sched-printable-content', 'PAYMENT SCHEDULE SLIP');
    });

    $('body').on('click', '.print-dealslip', function(evt){
        evt.preventDefault();
        window.print();
    });


    /**
     * 
     * Util functions. 
     */

    function print_area(area_id, header) {
        printJS({
            printable: area_id, 
            type: 'html', 
            header: header,
        });
    }

    function open_modal(modal) {
        new bootstrap.Modal(modal, {
            backdrop: 'static',
            keyboard: false
        }).show();
    }

    function fill_params(self) {
        $('#asset_name_read').text(self.data('asset-name'));
        $('#reserve_id_read').text(self.data('booked-ref'));
        $('#bal_amt_read').html('&#8358;'+ self.data('actualbal'));

        $('#reserve_id').val(self.data('booked-ref'));
        $('#booking_id').val(self.data('booking-id'));
        $('#actual_bal').val(self.data('actualbal'));             
    }

    function serve_axios(self, cb) {

        var amt_payable = parseFloat($('#amt_payable').text().trim());


        if(payable <= 0) 
        {
            if(isNaN(amt_payable))
            {
                alert("Please enter an amount you wish to pay");
                return;
            }
    
            if (amt_payable <= 0 ) {
                alert("Please enter an amount greater than zero");
                return ;
            }

            payable = amt_payable;
        }




        self.attr('disabled', 'disabled');

        window.axios.post('/api/v1/generate/transaction', {
            reserve_ref: $('#reserve_id').val().trim(),
            booking_id: $('#booking_id').val().trim(),
            payable: payable,
            actualbal: $('#actual_bal').val().trim(),
        }).then(
        function(response) {
            console.log('response = ', response);
            if ( response.data.status ) {
                self.removeAttr('disabled');

                if(cb) {
                    cb(response.data.status, response.data.success);
                }
            }
            else {
                if(cb) {
                    cb(response.data.status, response.data.errors);
                } 
                self.removeAttr('disabled');
            }
        }, 
        function(err) {
            if(cb) {
                cb(response.data.status,response.data.errors);
            }
            self.removeAttr('disabled');
        });
    }

    function create_transaction_table(params) {
        return `
            <div>
                <table class="table">
                    <tr>
                        <th class="fs-14">Schedule ID</th>
                        <td class="fs-14 text-right">${params.tranx_id}</td>
                    </tr>
                    <tr>
                        <th class="fs-14">Payment Description</th>
                        <td class="fs-14 text-right">${params.description}</td>
                    </tr>
                    <tr>
                        <th class="fs-14">Amount</th>
                        <td class="fs-14 text-right">&#8358;${params.amount}</td>
                    </tr>
                    <tr>
                        <th class="fs-14">Percentage</th>
                        <td class="fs-14 text-right">${params.percentage}</td>
                    </tr>
                </table>  
            </div> 
        `
    }



    function doAxios(url, method, param, headers, callback) {
        const axios = method == "get" ? window.axios.get : window.axios.post;
        axios(url, param, headers).then(r => {
            if(callback) {
                callback(r);
            }
        })
    }

});