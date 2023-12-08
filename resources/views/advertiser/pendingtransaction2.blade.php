@extends('template.dashboard.master')

@section('content')
    <div id="page-wrapper">
        <div id="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <h2>PENDING TRANSACTIONS</h2>
                    <h5>Administer your pending transactions. </h5>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <tr>
                                <th>S/N</th>
                                <th>Asset Name</th>
                                <th>Reserved ID</th>
                                <th>Asset Price (&#8358;)</th>
                                <th>Reserved?</th>
                                <th>Next Availability</th>
                                <th>Asset Started</th>
                                <th>Asset End</th>
                                <th>Tranx Count</th>
                            </tr>

                            @if ( count($pending_tranx_recs) )
                                
                                <?php $sn = 0 ?>
                                <?php $snx = 0 ?>
                                
                                @foreach ($pending_tranx_recs as $pending_tranx_rec)
                                    
                                    @if (count($pending_tranx_rec->trans_recs))

                                        <?php $sn++ ?>

                                        <tr class="info">
                                            <td>{{ $sn }}</td>
                                            <td>{{ $pending_tranx_rec->asset->name }}</td>
                                            <td>{{ $pending_tranx_rec->trnx_id }}</td>
                                            <td>{{ number_format(str_replace(',', '', $pending_tranx_rec->asset->price), 2, '.', ',') }}</td>
                                            <td><span class="label label-primary">{{ intval($pending_tranx_rec->locked) === 1 ? 'YES' : 'NO' }}</span></td>
                                            <td>{{ date('j F Y', strtotime($pending_tranx_rec->next_availability_date)) }}</td>
                                            <td>{{ date('j F Y', strtotime($pending_tranx_rec->start_date)) }}</td>
                                            <td>{{ date('j F Y', strtotime($pending_tranx_rec->end_date)) }}</td>
                                            <td style="text-align: center;">{{ count($pending_tranx_rec->trans_recs) }}</td>
                                        </tr>
                                        
                                        <?php $snx++ ?>
                                        <tr>
                                            <td colspan="9" style="padding: 0;">
                                                <table class="table" style="margin-bottom: 0;">
                                                    @foreach ($pending_tranx_rec->trans_recs as $key => $trans_rec)
                                                
                                                        <tr class="danger">
                                                            <td>{{ $trans_rec->tranx_id }}</td>
                                                            <td>{{ $trans_rec->description }}</td>
                                                            <td>&#8358;{{ number_format(str_replace(',', '', $trans_rec->amount), 2, '.', ',') }}</td>
                                                            <td><span class="label label-danger">{{ intval($trans_rec->paid) === 1 ? 'PAID' : 'PENDING' }}</span></td>
                                                            <td>{{ date('j F Y', strtotime($trans_rec->created_at)) }}</td>
                                                        </tr>

                                                    @endforeach
                                                </table>
                                            </td>
                                        </tr>
                                    
                                    @endif

                                @endforeach

                            @else

                                <tr class="danger">
                                    <td colspan="9" style="text-align:center" class="text-danger"><strong>No Record Found.</strong></td>
                                </tr>
                                
                            @endif

                            
                        </table>
                    </div>



                    @if ( count($pending_tranx_recs) )

                        @foreach ($pending_tranx_recs as $pending_tranx_rec)
                            
                            @if (count($pending_tranx_rec->trans_recs))

                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">{{ $pending_tranx_rec->asset->name }}</div>
                                        <div class="panel-body">
                                            <h5>Reserved ID: <span style="float: right;">{{ $pending_tranx_rec->trnx_id }}</span></h5>
                                            <h5>Reserved?: <span class="label label-primary" style="float: right;">{{ intval($pending_tranx_rec->locked) === 1 ? 'YES' : 'NO' }}</span></h5>
                                            <h5>Next Availability: <span style="float: right;">{{ date('j F Y', strtotime($pending_tranx_rec->next_availability_date)) }}</span></h5>
                                            <h5>Asset Started: <span style="float: right;">{{ date('j F Y', strtotime($pending_tranx_rec->start_date)) }}</span></h5>
                                            <h5>Asset End: <span style="float: right;">{{ date('j F Y', strtotime($pending_tranx_rec->end_date)) }}</span></h5>
                                            <h5>Tranx Count: <span style="float: right;">{{ count($pending_tranx_rec->trans_recs) }}</span></h5>
                                            <h5>Asset Price: <span style="float: right;">&#8358;{{ number_format(str_replace(',', '', $pending_tranx_rec->asset->price), 2, '.', ',') }}</span></h5>
                                        </div>
                                        <table class="table" style="margin-bottom: 0;">
                                            @foreach ($pending_tranx_rec->trans_recs as $key => $trans_rec)
                                        
                                                <tr class="danger">
                                                    <td>{{ $trans_rec->tranx_id }}</td>
                                                    <td>{{ $trans_rec->description }}</td>
                                                    <td>{{ date('j F Y', strtotime($trans_rec->created_at)) }}</td>
                                                    <td><span class="label label-danger">{{ intval($trans_rec->paid) === 1 ? 'PAID' : 'PENDING' }}</span></td>
                                                    <td>&#8358;{{ number_format(str_replace(',', '', $trans_rec->amount), 2, '.', ',') }}</td>
                                                </tr>

                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            
                            @endif

                        @endforeach

                    @else
                        <div class="alert alert-info">
                            <h3 class="text-info">No Record Found.</h3>
                        </div>

                    @endif
                    
                </div>
            </div>
        </div>
    </div>
@endsection