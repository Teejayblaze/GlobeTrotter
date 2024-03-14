$(document).ready(function(){

    $tab_ind = $('.overlay-tab-indi');
    $asset_type_ph = $('#asset_types_cat');
    $temp_board = ''; $old_board = '', $current_board = '';

    if(typeof asset_types_arry !== "undefined")
      $asset_type_ph.html(format_asset_types(asset_types_arry).join(''));

    $('input[name*="search_criteria"]').on('click', function(){
      const self = $(this);
      if(self.is(":checked")) {
        $("." + self.data('search_criteria')).removeClass("hide");
      }
      else {
        $("." + self.data('search_criteria')).addClass("hide");
      }
    });

    $('.board_types').on('click', function(){
        $self = $(this);
        $board_type_id = parseInt($self.data('id'));
        $asset_types = asset_types_arry.filter((el, id) => el.board_type === $board_type_id);
        $asset_type_ph.html(format_asset_types(asset_types_arry).join(''));
    });

    if ($('input[name*="search_criteria"]').length) {
      $('input[name*="search_criteria"]').eq(0).trigger('click');
    }

    if($('.map-proxi').length) {
      $('.map-proxi').on('click', function(){
          $self = $(this);
          if ($self.is(':checked')) $('.' + $self.data('maps')).removeClass('hide');
          else $('.' + $self.data('maps')).addClass('hide');
      });
    }


    function format_asset_types($asset_types) {
        $asset_type_chk = [];
        $asset_type_len = $asset_types.length;
        $asset_type_ph = $('#asset_types_cat');
        $divisor = isNaN(parseInt($asset_type_ph.data('layout'))) ? 4 : parseInt($asset_type_ph.data('layout'));
        $layoutColumn = isNaN(parseInt($asset_type_ph.data('layout-column'))) ? 3 : parseInt($asset_type_ph.data('layout-column'));
        $n_times = $asset_type_len / $divisor;
        $iter = 0;

        for($i = 0; $i < $divisor; $i++) {
            $ul = `<ul class="col-${$layoutColumn} col-md-${$layoutColumn}">`;
            for($j = 0; $j < $n_times; $j++) {
              if ($asset_types[$iter]) {
                  $ul += `<li>
                      <div class="form-check">
                        <input class="form-check-input" id="check-asset_type_${$iter}" type="checkbox" name="site_catgory[]" value="${$asset_types[$iter].id}">
                        <label class="form-check-label" for="check-asset_type_${$iter}" title="${$asset_types[$iter].type}">${$asset_types[$iter].type}</label>
                      </div>
                    </li>`;
                  $iter++;
              }
            }
            $ul += `</ul>`;
            $asset_type_chk.push($ul);
        }
        return $asset_type_chk;
    }

    try {
      
      if(typeof Chart !== undefined){
        Chart.defaults.global.elements.line.fill = false;
    
        new Chart($('#trans-hist-chart'), {
            type: 'line',
            data: {
              labels: ['January','February','March','April','May','June','July','August','September','October', 'November', 'December'],
              datasets: [{ 
                  data: [86,114,106,106,107,111,133,221,783,2478,3456,6372],
                  label: "Paid",
                  borderColor: "#3e95cd",
                  fill: false
                }, 
                // { 
                //   data: [282,350,411,502,635,809,947,1402,3700,5267],
                //   label: "Paid",
                //   borderColor: "#8e5ea2",
                //   fill: false
                // }, { 
                //   data: [168,170,178,190,203,276,408,547,675,734],
                //   label: "Europe",
                //   borderColor: "#3cba9f",
                //   fill: false
                // }, { 
                //   data: [40,20,10,16,24,38,74,167,508,784],
                //   label: "Latin America",
                //   borderColor: "#e8c3b9",
                //   fill: false
                // }, 
                { 
                  data: [6,3,2,2,7,26,82,172,312,433,563,218],
                  label: "Pending",
                  borderColor: "#c45850",
                  fill: false
                }
              ]
            },
            options: {
              title: {
                display: true,
                text: 'Corporate Transaction History (in millions)'
              }
            }
        });
      }
    } catch (error) {
      console.log(error);
    }


    function searchAssetAutocomplete() {
      const textField = $("input[name='asset_location_search']");
      const delay = 500; // Debounce delay in milliseconds
      let timeoutId;

      textField.autocomplete({
        source: function(request, response) {
          // Simulating an AJAX request to fetch autocomplete data
          clearTimeout(timeoutId);
          timeoutId = setTimeout(function() {
            $.ajax({
              url: '/asset/search-asset-by-area', // Replace with your API endpoint
              dataType: 'json',
              data: {
                term: request.term
              },
              success: function(data) {
                response(data);
              }
            });
          }, delay);
        },
        minLength: 2, // Minimum characters required to trigger autocomplete
        create: function () {
          $(this).data('ui-autocomplete')._renderItem = function (ul, item) {
            ul.addClass("search-results");
              return $('<li>')
                  .append(item.label)
                  .appendTo(ul);
          };
      }
      });
    }

    searchAssetAutocomplete();


    function cycle_image_slider() {
      $('.img-holder').each(function(idx, el) {
        $active = $('.img-holder:eq('+idx+')').find('.faded-img.active');  //$('.img-holder .faded-img.active');
        $next = ($active.next().length > 0) ? $active.next() : $('.img-holder:eq('+idx+')').find('.faded-img:first');
        $next.css('z-index', 2);
        $active.fadeOut(1500, function(){
          $active.css('z-index', 1).show().removeClass('active');
          $next.css('z-index', 3).addClass('active');
        });
      });
    }

    setInterval(cycle_image_slider, 7000);


  // create a class for adding campaign to cart (OOP) style.
  function CampaignCart() {
    this.user_carts = [];
  }

  CampaignCart.prototype.addCart = function (cart) {
    if (this.isExist(cart)) return ;
    this.user_carts.push(cart);
  }

  CampaignCart.prototype.isExist = function (param_cart) {
    var found = this.user_carts.filter((cart, index) => cart.item_id === param_cart.item_id);
    return found.length;
  }

  CampaignCart.prototype.getCart = function () {
    var template = ``, gtotal = 0;
    if (this.user_carts.length) {
      template = this.createTemplateHeader();
        this.user_carts.map( (cart, idx) => {
          gtotal += parseFloat(cart.price);
          template += this.createCartDetailsTemplate(cart);
        });
      template += this.createTemplateFooter(gtotal);
    }
    else template = `<p>Your cart is currently empty.</p>`;

    return template;
  }

  CampaignCart.prototype.createCartDetailsTemplate = function (cart) {
    return `<tr>
              <td>${cart.name}</td>
              <td>${cart.qty}</td>
              <td>&#8358;${this.thousandSeparator(cart.price)}</td>
            </tr>`;
  }

  CampaignCart.prototype.createTemplateHeader = function () {
    return `<div class="cart-items">
              <table style="border-collapse:collapse; width:100%" border="1">
                <thead>
                    <tr>
                        <th>Site Name</th>
                        <th>Qty</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>`;
  }

  CampaignCart.prototype.createTemplateFooter = function (grand_total) {
    return `<tr>
                <td colspan="2" style="text-align:right;font-weight:700">Grand Total</td>
                <td style="font-weight:700">&#8358;${this.thousandSeparator(grand_total)}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <a href="/advertiser/individual/view/campaign" class="more-proximity">view cart details &rarr;</a>`;
  }

  CampaignCart.prototype.thousandSeparator = function (num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }


  CampaignCart.prototype.initializeCampaign = function () {
    selectedCampaign = $('input[name="campaign_id"]').val();
    if (selectedCampaign <= 0) {
      var height = $(document).height();
      $('.campaign-overlay').css('height', height).addClass('show-campaign-overlay');
      $('.campaign-content.campaign-form').css('display', 'block');
    }
  }

  CampaignCart.prototype.getUserCart = function () {
    return this.user_carts;
  }

  CampaignCart.prototype.emptyUserCart = function(){
    return this.user_carts.splice(0, this.user_carts.length);
  }

  CampaignCart.prototype.permanentlySaveCart = function () {
    var self = this;
    return $.ajax({
      url: '/save/cart/campaign',
      method: 'POST',
      data: {
        'campaign': self.getUserCart(),
      },
      beforeSend: function() {
        $('.campaign-loader').css('display', 'block');
      },
    }).done(res => {
      $('.campaign-loader').css('display', 'none');
      if(res.status) {
         window.swal({
          title: 'Site Added!',
          text: res.success.msg,
          type: 'success',
        }).then(result => {
          $('.campaign-overlay').removeClass('show-campaign-overlay');
        });
      } else {
        window.swal({
          title: 'Site Error',
          text: res.errors,
          type: 'error',
        }).then(result => {
          $('.campaign-overlay').removeClass('show-campaign-overlay');
        });
      }
    }).fail(err => {
        $('.campaign-loader').css('display', 'none');
        $('.campaign-overlay').removeClass('show-campaign-overlay');
    });
  }

  // !-- Class End --!

  var selectedCampaign = 0;
  var campaign_count = 0;
  var ccart = new CampaignCart();
  $('.cart-placement').html(ccart.getCart());
  ccart.initializeCampaign();


  $.fn.openCampaignDialog = function() {
    this.on('click', function(evt){
      evt.preventDefault();
      if ($('input[name="assets-check"]:checked').length == 0){
        alert('Kindly select a site to add to Campaign Cart.');
        return ;
      }
      
      if (campaign_count > 0) {
        $('.campaign-lists').css('display', 'block'); 
        $('.campaign-content.campaign-form').css('display', 'none');
      }
      console.log('campaign_count = ', campaign_count);
      var height = $(document).height();
      $('.campaign-overlay').css('height', height).addClass('show-campaign-overlay');     
    });
  }

  $('.hover-board').on('click', function(){
    $('input[name="assets-check"]').each(function(id, el){ 
      $el = $(el);
        if ($el.is(':checked')) {
          campaign_count++;
        }
    });
  });

  $.fn.addCampaignToCart = function() {
    this.on('click', function(){
      selectedCampaign = $(this).data('id');
      ccart.emptyUserCart();
      $('input[name="assets-check"]').each(function(id, el){
        $el = $(el);
        if ($el.is(':checked')) {
          ccart.addCart({
            name: $el.data('name'),
            qty: 1,
            price: $el.data('price'),
            item_id: $el.data('id'),
            campaign_id: selectedCampaign
          });
        }
      });
      
      ccart.permanentlySaveCart().then(function(res) {
        if (res.status) {
          ccart.emptyUserCart();
          Object.assign(ccart.user_carts, res.success.result);
          $('.cart-placement').html(ccart.getCart());
        } 
      });
    });
  }

  $('.campaign-content .cbody ul li').addCampaignToCart();

  $('.closex-overlay').on('click', function(){
    $('.campaign-overlay').removeClass('show-campaign-overlay');
  });


  $('.add-to-campaign').openCampaignDialog();

  if ($('#campaign_date').length) {
    let today = new Date();
    $('#campaign_date').daterangepicker({
      autoUpdateInput: false,
      startDate: new Date(today.getFullYear(), today.getMonth()+1, today.getDate(), 0, 0, 0, 0),
      minDate: new Date(today.getFullYear(), today.getMonth()+1, today.getDate(), 0, 0, 0, 0),
      locale: {
          format: 'DD/MM/YYYY'
      } 
    });

    $('#campaign_date').on('apply.daterangepicker', function(e, picker){
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' -to- ' + picker.endDate.format('DD/MM/YYYY'));
    });     
  }

  if ($('.open-sug').length) {
    $('.open-sug').on('click', function() {

      $el = $(this);
      $sug = $el.next('.suggestions');

      if (! $sug.hasClass('scale-suggestion') ) {
        $sug.addClass('scale-suggestion');
        $el.text('Close Suggestion');
      } 
      
      else {
        $sug.removeClass('scale-suggestion');
        $el.text('Open Suggestion');
      }
      
    });
  }

  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        'X-Requested-With': 'XMLHttpRequest'
    }
  });

  if ($('.save-campaign').length) {

    $('.save-campaign').on('click', function(){
      
      $campaign_name = $('#campaign_name').val().trim();
      $campaign_date = $('#campaign_date').val().trim();
      $user_id = $('input[name="user_id"]').val().trim();
      $user_type_id = $('input[name="user_type_id"]').val().trim();
      $corp_id = $('input[name="corp_id"]').val().trim();

      if ($campaign_name == '') {
        alert('Campaign name should not be empty.');
        return ;
      }

      if ($campaign_date == '') {
        alert('Campaign date should not be empty.');
        return ;
      }

      $.ajax({
        url: '/create/campaign',
        method: 'POST',
        data: {
          'campaign_name': $campaign_name, 
          'campaign_date': $campaign_date, 
          'user_id': $user_id, 
          'user_type_id': $user_type_id, 
          'corp_id': $corp_id
        },
        beforeSend: function() {
          $('.campaign-loader').css('display', 'block');
        },
      }).done(res => {
        $('.campaign-loader').css('display', 'none');
        if (!res.status) {
          if (typeof res.errors == 'object') {
            for(var i in res.errors) {
              window.swal({
                title: 'Campaign Failed',
                text: res.errors[i].join(''),
                type: 'error',
              });
            }
          } else {
            window.swal({
              title: 'Campaign Failed',
              text: res.errors,
              type: 'error',
            });
          }
          return ;
        }
        window.swal({
          title: 'Campaign Created',
          text: res.success.msg,
          type: 'success',
        }).then(_ => {
          const result = res.success.result || [];
          // update the campaign list
          $('.campaign-lists .cbody').html(display_campaign_lists(result));
          $('.campaign-form').css('display', 'none');
          $('.campaign-overlay').removeClass('show-campaign-overlay');
          $('.campaign-content .cbody ul li').addCampaignToCart();

          const lastResult = result.length > 0 ? result[result.length - 1] : 0;
          window.location.href = '/advertiser/individual/create/campaign/' + lastResult.id;
        });
      }).fail(err => {
        $('.campaign-loader').css('display', 'none');
      });
    });
  }

  function display_campaign_lists(campaigns) {
    $template = `<ul>`;
    for (let index = 0; index < campaigns.length; index++) {
      const element = campaigns[index];
      $template += `<li data-id="${element.id}"><p>${element.name} (${element.start_date})</p><p class="label">${element.campaign_count}</p></li>`
    }
    $template += `</ul>`;
    return $template;
  }


  $('.search-campaign').on('click', function(){
    var server_data = {};
    server_data.proximity = [];
    $('input[type="radio"]').each(function(id, el){
      $el = $(el);
      if ($el.is(':checked')) {
        server_data[$el.attr('name')] = $el.val();
      }
    });
    $('input[type="checkbox"]').each(function(id, el){
      $el = $(el);
      if ($el.is(':checked')) {
        server_data.proximity.push($el.val());
      } else {
        $item_index = server_data.proximity.indexOf($el.val());
        if ( $item_index != -1 ) server_data.proximity.splice($item_index, 1);
      }
    });
    $('select').each(function(id, el){
      $el = $(el, 'option:selected');
      if ($el.val()) {
        server_data[$el.attr('name')] = $el.val();
      }
    });
    console.log(server_data);
    $.ajax({
      url: '/search/campaign/sites',
      method: 'POST',
      data: server_data,
      beforeSend: function() {
        $('.campaign-content').css('display', 'none');
        $('.campaign-result-loader').css('display', 'block');
        $('.campaign-overlay').addClass('show-campaign-overlay');
      },
    }).done(res => {
      console.log(res);
      $('.campaign-result-loader').css('display', 'none');
      $('.campaign-overlay').removeClass('show-campaign-overlay')
      if (!res.status) {
        window.swal({
          title: 'Empty Record',
          text: '(0) Record found for the search criteria.',
          type: 'warning',
        });
        return ;
      } 

      //Load asset.
      $('.board-result').html(create_campaign_asset_layout(res.success));
    }).fail(err => {
      $('.campaign-loader').css('display', 'none');
    });
  });

  function create_campaign_asset_layout(records) {
    return records.map(layout);
  }

  // setThousandSeparator function 
  function setThousandSeparator (num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }
});