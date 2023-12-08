@extends('template.dashboard.master')

@section('content')

    <style>
        .notice, .show_print, .show_print_btn {
            display: none;
        }
        .grid-block {
            display: block;
            position: relative;
        }
        .grid-block-left {
            float: left;
        }
        .grid-block-right {
            float: right;
        }

        .panel-body {
            position: relative;
        }

        .panel-body:hover .overlay {
            transform: scale(1);
        }

        .overlay {
            position: absolute;
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
            background-color: rgba(0,0,0,.59);
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9;
            transform: scale(0);
            transition: transform .8s ease-in-out 0s;
        }
        .overlay-content {
            width: 50%;
            position: relative;
            color: #fff;
            text-align: center;
        }
        .blink {
            border-radius: 0;
            margin-bottom: 0;
            text-align: center;
        }
        .payment-sched-printable-btn, .payment-sched-printable-content, .hide_dismiss {
            display: none;
        }
    </style>

    <div id="page-wrapper">
        <div id="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <h2>PENDING TRANSACTIONS</h2>
                    <h5>Administer your pending transactions. </h5>
                </div>
            </div>
            <hr>

            <div class="row">
                <div class="col-md-12">
                
                    <div class="col-md-12">
                        <div class="alert alert-warning alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            To make payment, kindly generate transaction on each Asset and use the generated invoice for payment in the bank.
                        </div>
                    </div>

                    @if ( count($pending_tranx_recs) )
                        @foreach ($pending_tranx_recs as $key => $pending_tranx_rec)

                            {{-- @if (count($pending_tranx_rec->pending_trans_recs)) --}}

                                <?php 
                                    $actualprice = floatval(str_replace(',', '', $pending_tranx_rec->asset->price));
                                    $paidamt = floatval(str_replace(',', '', $sum_paid_tranx->tranx_amt_accumulator[$pending_tranx_rec->trnx_id]));
                                    $actualbal = ($actualprice - $paidamt);
                                ?>
                                
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            {{ $pending_tranx_rec->asset->name }}
                                            @if ($pending_tranx_rec->paid_trans_recs)
                                                <a href="#" 
                                                    data-asset-name="{{ $pending_tranx_rec->asset->name }}"
                                                    data-booked-ref="{{ $pending_tranx_rec->trnx_id }}" 
                                                    data-booking-id="{{ $pending_tranx_rec->id }}"
                                                    data-actualbal="{{ number_format($actualbal, 2, '.', ',') }}" 
                                                    class="btn btn-primary gen-tranx" 
                                                    style="float:right;margin-top: -7px;">Generate Payment Schedule</a>
                                            @endif
                                        </div>
                                        <div class="panel-body">
                                            <div id="dealslip-{{$key}}" class="dealslip">
                                                <table class="table table-condensed table-noborder">
                                                    <tr>
                                                        <td style="width: 31%; text-align:left; border: none">Asset Name: </td>
                                                        <td style="text-align:right; border: none">{{ $pending_tranx_rec->asset->name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 31%; text-align:left; border: none">Reserved ID: </td>
                                                        <td style="text-align:right; border: none">{{ $pending_tranx_rec->trnx_id }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 31%; text-align:left; border: none">Reserved? </td>
                                                        <td style="text-align:right; border: none"><span class="label label-primary" style="float: right;">{{ intval($pending_tranx_rec->locked) === 1 ? 'YES' : 'NO' }}</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 31%; text-align:left; border: none">Next Availability: </td>
                                                        <td style="text-align:right; border: none">{{ date('j F Y', strtotime($pending_tranx_rec->next_availability_date)) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 31%; text-align:left; border: none">Asset Started: </td>
                                                        <td style="text-align:right; border: none">{{ date('j F Y', strtotime($pending_tranx_rec->start_date)) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 31%; text-align:left; border: none">Asset End: </td>
                                                        <td style="text-align:right; border: none">{{ date('j F Y', strtotime($pending_tranx_rec->end_date)) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 31%; text-align:left; border: none">Tranx Count: </td>
                                                        <td style="text-align:right; border: none">{{ count($pending_tranx_rec->pending_trans_recs) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 31%; text-align:left; border: none">Asset Price: </td>
                                                        <td style="text-align:right; border: none">&#8358;{{ number_format(str_replace(',', '', $pending_tranx_rec->asset->price), 2, '.', ',') }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            {{-- <a href="#"class="print-dealslip"style="float:left;">PRINT DEALSLIP</a> --}}
                                            <div class="overlay">
                                                <div class="overlay-content">
                                                    @if (count($pending_tranx_rec->pending_trans_recs) === 0 && $pending_tranx_rec->paid_trans_recs == 0)
                                                        <p>Kindly make your payment by clicking the "Next" button.</p>
                                                    @endif
                                                    <div class="btn-group">
                                                        <a href="javascript://" class="btn btn-danger">Cancel</a>
                                                        <a href="javascript://" class="btn btn-success print-dealslip">Print DealSlip</a>
                                                        @if (count($pending_tranx_rec->pending_trans_recs) === 0 && $pending_tranx_rec->paid_trans_recs == 0)
                                                            <a href="javascript://" class="btn btn-info next"
                                                                data-booking-id="{{ $pending_tranx_rec->id }}"
                                                                data-asset-name="{{ $pending_tranx_rec->asset->name }}"
                                                                data-booked-ref="{{ $pending_tranx_rec->trnx_id }}"
                                                                data-actualbal="{{ number_format($actualbal, 2, '.', ',') }}">Next &RightArrow;</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if (count($pending_tranx_rec->pending_trans_recs) === 0 && $pending_tranx_rec->paid_trans_recs == 0)
                                            <div class="alert alert-danger" style="margin-bottom: 0px; border-radius: 0;">
                                                <div class="row">
                                                    <div class="col-sm-10">
                                                        <div class="blink">
                                                            <span>{{$pending_tranx_rec->grace_prd_message}}</span>
                                                        </div>
                                                    </div>
                                                    {{-- @if ($pending_tranx_rec->paid_trans_recs === 0) --}}
                                                        <div class="col-sm-2">
                                                            <a href="javascript://" class="next btn btn-danger"
                                                                data-booking-id="{{ $pending_tranx_rec->id }}"
                                                                data-asset-name="{{ $pending_tranx_rec->asset->name }}"
                                                                data-booked-ref="{{ $pending_tranx_rec->trnx_id }}"
                                                                data-actualbal="{{ number_format($actualbal, 2, '.', ',') }}">Next</a>
                                                        </div>
                                                    {{-- @endif --}}
                                                </div>
                                            </div>
                                        @endif

                                        @if (
                                            (count($pending_tranx_rec->pending_trans_recs) && $pending_tranx_rec->paid_trans_recs == 1) || 
                                            (count($pending_tranx_rec->pending_trans_recs) && $pending_tranx_rec->paid_trans_recs == 0))
                                            <table class="table" style="margin-bottom: 0;">
                                                @foreach ($pending_tranx_rec->pending_trans_recs as $key => $pending_trans_rec)
                                            
                                                    <tr class="danger">
                                                        <td>
                                                            <a href="#" class="print_recipt" title="Click to Print Invoice"
                                                                data-trnx-id="{{ $pending_trans_rec->tranx_id }}"
                                                                data-asset-name="{{ $pending_tranx_rec->asset->name }}"
                                                                data-booked-ref="{{ $pending_tranx_rec->trnx_id }}"
                                                                data-amount="{{ number_format(str_replace(',', '', $pending_trans_rec->amount), 2, '.', ',') }}"
                                                                data-description="{{ $pending_trans_rec->description }}"
                                                                data-actualbal="{{ number_format($actualbal, 2, '.', ',') }}">{{ $pending_trans_rec->tranx_id }} </a>
                                                        </td>
                                                        <td>{{ $pending_trans_rec->description }}</td>
                                                        <td>{{ date('j F Y', strtotime($pending_trans_rec->created_at)) }}</td>
                                                        <td><span class="label label-danger">{{ intval($pending_trans_rec->paid) === 1 ? 'PAID' : 'PENDING' }}</span></td>
                                                        <td>&#8358;{{ number_format(str_replace(',', '', $pending_trans_rec->amount), 2, '.', ',') }}</td>
                                                    </tr>

                                                @endforeach
                                            </table>
                                        @endif

                                        <div class="panel-footer">
                                            <strong>Balance:</strong> <span style="float: right;">&#8358;{{ number_format($actualbal, 2, '.', ',') }}</span>
                                        </div>
                                    </div>
                                </div>

                            {{-- @endif --}}

                        @endforeach

                    @else
                        <div class="alert alert-info">
                            <strong class="text-info">No Record Found.</strong>
                        </div>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="app_modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="javascript://" method="post">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Payment Schedule Generator</h4>
                        <small>Generate payment schedule and print the slip for making payment in the bank</small>
                    </div>
                    <div class="modal-body">
                        <div class="content">
                                <div class="alert notice alert-danger">
                                    <ul></ul>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="printable">
                                        <div style="padding: 8px;" class="show_print row">
                                            <div class="col-md-6 grid-block grid-block-left"><strong>Transaction ID</strong></div>
                                            <div class="col-md-6 grid-block  grid-block-right"><span style="float:right;" id="tranx_id"></span></div>
                                        </div>
                                        <div style="padding: 8px;" class="row">
                                            <div class="col-md-6 grid-block grid-block-left"><strong>Asset Name</strong></div>
                                            <div class="col-md-6 grid-block  grid-block-right"><span style="float:right;" id="asset_name_read"></span></div>
                                        </div>
                                        <div style="padding: 8px;" class="row">
                                            <div class="col-md-6 grid-block grid-block-left"><strong>Reserve ID</strong></div>
                                            <div class="col-md-6 grid-block  grid-block-right"><span style="float:right;" id="reserve_id_read"></span></div>
                                            <input type="hidden" name="reserve_id" id="reserve_id">
                                            <input type="hidden" name="booking_id" id="booking_id">
                                            <input type="hidden" name="actual_bal" id="actual_bal">
                                        </div>
                                        <div style="padding: 8px;" class="row show_print">
                                            <div class="col-md-6 grid-block grid-block-left"><strong>Description</strong></div> 
                                            <div class="col-md-6 grid-block  grid-block-right"><span style="float:right;direction: rtl;" id="desc_read"></span></div>
                                        </div>
                                        <div style="padding: 8px;" class="row hide_print">
                                            <div class="col-md-6 grid-block grid-block-left"><strong>Balance Amount</strong></div> 
                                            <div class="col-md-6 grid-block  grid-block-right"><span style="float:right;" id="bal_amt_read"></span></div>
                                        </div>
                                        <div style="padding: 8px;" class="row show_print">
                                            <div class="col-md-6 grid-block grid-block-left"><strong>Amount</strong></div> 
                                            <div class="col-md-6 grid-block  grid-block-right"><span style="float:right;" id="amt_read"></span></div>
                                        </div>
                                        <div style="padding: 8px;" class="row hide_print">
                                            <div class="col-md-6 grid-block grid-block-left"><strong>Amount Payable (&#8358;)</strong></div> 
                                            <div class="col-md-6 grid-block  grid-block-right">
                                                <span contenteditable="true"
                                                style="float:right;border-top:1px solid #d3d3d3;border-bottom:1px solid #d3d3d3;padding:2px 0;min-width:125px;max-width:200px;direction: rtl;" 
                                                id="amt_payable">0</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Dismiss</button>
                        <input type="submit" value="Generate Payment Schedule" class="btn btn-primary generate_tranx hide_print">
                        <input type="button" value="Print Payment Schedule" class="btn btn-info printbtn show_print_btn">
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" tabindex="-1" role="dialog" id="app_next_modal">
        <div class="modal-dialog" role="document">
            <form action="javascript://">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Payment Schedule Generator</h4>
                        <small>Generate payment schedule and print the slip for making payment in the bank</small>
                    </div>

                    <div class="modal-body">
                        <div class="content">
                            <div class="law-context hide_print" style="padding: 5px; max-height:450px; min-height:200px; overflow-y:scroll;">
                                What is Lorem Ipsum?
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                                
                                Why do we use it?
                                It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
                                
                                
                                Where does it come from?
                                Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.
                                
                                The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.
                                
                                Where can I get some?
                                There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.
                                                        
                            </div>
                            <div class="payment-sched-printable-content" id="payment-sched-printable-content" style="padding: 5px;">
                                                        
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <input type="submit" value="I AGREE" class="btn btn-primary generate_tranx hide_print">
                        <button type="button" class="btn btn-default hide_dismiss" data-dismiss="modal">Dismiss</button>
                        <input type="button" value="Print Transaction Invoice" class="btn btn-info printbtn payment-sched-printable-btn">
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection