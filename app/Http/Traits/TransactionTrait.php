<?php
namespace App\Http\Traits;
use Illuminate\Http\Request;
use \App\Transaction;
use \App\TransactionToken;
use \App\AssetBooking;
use \App\AssetGracePeriod;
use Carbon\Carbon;
use \App\Individual;
use \App\Asset;
use \App\Campaign;

ini_set ('soap.wsdl_cache_enabled', 0);

trait TransactionTrait
{
    //    
    public function generate_transaction_id(int $len = 10)
    {
        mt_srand((double)microtime()*10000);
        $charid = md5(uniqid(rand(), true));

        $c = unpack("C*",$charid);
        $c = implode("",$c);

        return substr($c,0,$len); // generate $len random unique number.
    }


    public function get_transaction_by_id(string $id)
    {
        $id = trim($id);
        if(empty($id)) return null;

        $transaction = Transaction::where(['id' => $id])->first();
        return $transaction;
    }

    public function get_transaction_by_ref(string $tranx_ref)
    {
        $tranx_ref = trim($tranx_ref);
        if(empty($tranx_ref)) return null;

        $transaction = Transaction::where(['tranx_id' => $tranx_ref])->first();
        return $transaction;
    }


    public function get_asset_details($id)
    {
        return Asset::find($id);
    }


    public function generate_transaction( string $bookedId, string $amount, string $desc, string $booking_ref, string $perc, int $first_installment, string $booking_type = 'single')
    {
        $transaction = new Transaction();

        if ($booking_type === env('CAMPAIGN_BOOKING_TYPE')) {
            $transaction->campaign_id = $bookedId;
            $transaction->asset_booking_id = -1;
        }
        else {
            $transaction->asset_booking_id = $bookedId;
        }

        $transaction->tranx_id = $this->regenerate_transaction_id($bookedId);
        $transaction->description = $desc;
        $transaction->amount = $amount;
        $transaction->percentage = $perc;
        $transaction->asset_booking_ref = $booking_ref;
        $transaction->first_pay = $first_installment;

        try {
            if ($transaction->save()) return $transaction;
        } catch (\Exception $e) {
            $this->log('Critical', $e->getMessage());
        }
    }


    public function regenerate_transaction_id(string $bookedId)
    {
        return 'PYMT-'.str_pad($bookedId, 5, '0', STR_PAD_LEFT).'-'.$this->generate_transaction_id(6);
    }


    public function update_transaction(array $where, array $update)
    {
        $update = Transaction::where($where)->update($update);
        if($update) return true;

        return false;
    }


    public function get_campaign($user) {
        $campaigns = Campaign::where(['user_id' => $user->user_id, 'user_type_id' => $user->user_type_id])->with('campaignDetails')->get();
        return $campaigns;
    }

    public function get_campaign_by_id(int $campaign_id) 
    {
        $campaign = Campaign::find($campaign_id);

        if ($campaign->campaignDetails()->count() > 0) {
            $campaignDetails = array_column($campaign->campaignDetails()->get()->toArray(), 'asset_id');
            $campaign->assetBookings = AssetBooking::whereIn('asset_id', $campaignDetails)->get();
            $campaign->total_price = Asset::whereIn('id', $campaignDetails)->sum('max_price');
            $campaign->trnx_id = count($campaign->assetBookings) ? $campaign->assetBookings[0]->trnx_id : '';
            $payment_records = $this->get_paid_receipts($campaign)->orderBy('id', 'asc')->get();
            $campaign->paid_payment_records = $payment_records;
            $campaign->payment_remaining_perc = $this->get_payment_remaining_percentage($payment_records->toArray());
            $campaign->pending_payment_records = $this->get_pending_receipts($campaign)->orderBy('id', 'asc')->get();
            $campaign->total_payment = $this->get_total_payment_amount($payment_records->toArray());;
            $campaign->total_price_in_words = $this->amount_in_words(floatval($campaign->total_price));
        }  
        
        return $campaign;
    }


    public function get_campaign_transactions($user) 
    {
        $campaigns = $this->get_campaign($user);
        foreach($campaigns as $key => $campaign) {
            if ($campaign->campaignDetails()->count() > 0) {
                $campaignDetails = array_column($campaign->campaignDetails()->get()->toArray(), 'asset_id');
                $campaign->assetBookings = AssetBooking::whereIn('asset_id', $campaignDetails)->get();
                $campaign->total_price = Asset::whereIn('id', $campaignDetails)->sum('max_price');
                $campaign->trnx_id = count($campaign->assetBookings) ? $campaign->assetBookings[0]->trnx_id : '';
                $payment_records = $this->get_paid_receipts($campaign)->orderBy('id', 'asc')->get();
                $campaign->paid_payment_records = $payment_records;
                $campaign->payment_remaining_perc = $this->get_payment_remaining_percentage($payment_records->toArray());
                $campaign->pending_payment_records = $this->get_pending_receipts($campaign)->orderBy('id', 'asc')->get();
                $campaign->total_payment = $this->get_total_payment_amount($payment_records->toArray());
            }   
        }
        
        return $campaigns;
    }


    public function get_transaction_receipt(int $completed_payment, int $booking_id, array $fields = [])
    {

        $asset_booking_recs = $this->get_asset_booked_by_current_advertiser($completed_payment, $booking_id, $fields)->get();

        foreach ($asset_booking_recs as $key => $asset_booking_rec) {
            $payment_records = $this->get_paid_receipts($asset_booking_rec)->orderBy('id', 'asc')->get();
            $total_payment_amount = $this->get_total_payment_amount($payment_records->toArray());
            $asset_booking_rec->total_price_in_words = $this->amount_in_words(floatval($asset_booking_rec->asset->max_price));
            $asset_booking_rec->payment_records = count($payment_records->toArray()) ? $payment_records : [];
            $asset_booking_rec->payment_records_count = count($payment_records);
            $asset_booking_rec->payment_total = $total_payment_amount;
            $asset_booking_rec->payment_remaining = $this->get_payment_remaining_balance($total_payment_amount, floatval(str_replace(',', '', $asset_booking_rec->price)));
            $asset_booking_rec->payment_remaining_perc = $this->get_payment_remaining_percentage($payment_records->toArray());
            $asset_booking_rec->real_asset_details = $this->get_asset_details($asset_booking_rec->asset_id);
            $pending_payment_records = $this->get_pending_receipts($asset_booking_rec)->orderBy('id', 'asc')->get();
            $asset_booking_rec->pending_payment_records = $pending_payment_records;
        }


        return $asset_booking_recs;
    }


    public function get_paid_receipts($asset_booking_rec = null, $check10perc = false)
    {
        $where = [
            ['paid', '=', 1], 
            ['bank_ref_number', '<>', null], 
            ['asset_booking_ref', '=', $asset_booking_rec->trnx_id],
            ['subscription', '=', 0]
        ];

        if($check10perc) {
            array_push($where, ['percentage', '=', '10%']);
        }

        return $asset_booking_rec->transaction()->where($where);
    }


    public function get_pending_receipts($asset_booking_rec = null)
    {
        return $asset_booking_rec->transaction()->where([
            ['paid', '=', 0], 
            ['bank_ref_number', '=', null], 
            ['asset_booking_ref', '=', $asset_booking_rec->trnx_id],
            ['subscription', '=', 0]
        ]); 
    }


    public function get_payment_remaining_percentage($payment_records)
    {
        $remaining_perc = 0;
        $pay_perc = 100;
        if (count($payment_records)) {
            $paid_perc = 0;
            foreach ($payment_records as $key => $payment_record) {
                $paid_perc += floatval(substr($payment_record['percentage'], 0, (strlen($payment_record['percentage'])-1)));
            }
            $remaining_perc = ($pay_perc - $paid_perc);
        
        } else $remaining_perc = 10; 
        
        return $remaining_perc;
    }


    public function get_total_payment_amount($payment_records = [])
    {
        if (count($payment_records)) {
            return array_reduce(array_column($payment_records, 'amount'), function($a, $b){
                return floatval(str_replace(',', '', $a)) + floatval(str_replace(',', '', $b));
            }, 0);
        }
        return 0;
    }

    public function get_payment_remaining_balance($total_payment = 0, $price)
    {
        return $price - $total_payment;
    }


    public function get_asset_booked_by_current_advertiser( int $completed_payment, int $booking_id, array $fields = [] )
    {
        $user = \Request::get('user');
        
        $where = [['locked', '=', 1]];

        if ($user) {
            $where[] = ['booked_by_user_id', '=', $user->user_id];
            $where[] = ['user_type_id', '=', $user->user_type_id];
        }

        if ($booking_id) array_push($where, ['id', '=', $booking_id]);
        if ($completed_payment !== -1) array_push($where, ['paycompleted', '=', $completed_payment]);
        if (count($fields)) {
            foreach ($fields as $field => $value) {
                array_push($where, [$field, '=', $value]);
            }
        }

        $asset_booking_recs = AssetBooking::where($where);

        return $asset_booking_recs;
    }

    public function get_paid_transaction_receipts($asset_booking_rec)
    {
        return $asset_booking_rec->transaction()->where([
            ['paid', '=', 1], 
            ['bank_ref_number', '<>', null], 
            ['asset_booking_ref', '=', $asset_booking_rec->trnx_id],
            ['subscription', '=', 0]
        ])->get()->toArray();
    }

    public function get_pending_transaction_records($asset_booking_rec, $tranx_receipts_uniq)
    {
        return $asset_booking_rec->transaction()->where(function($query) use ($tranx_receipts_uniq) {
            $query->where([['paid', '=', 0],['bank_ref_number', '=', null],['subscription', '=', 0]]);
            if (count($tranx_receipts_uniq)) $query->whereNotIn('tranx_id', $tranx_receipts_uniq);
        })->get();
    }

    public function get_asset_grace_periods($asset_booking_rec, $percent)
    {
        $grace_periods = $asset_booking_rec->assetGracePeriod()->where('booked_id', $asset_booking_rec->trnx_id);
        if ($percent === 10) return $grace_periods->first();
        else return $grace_periods->orderBy('id', 'desc')->first();
    }

    /**
     * 
     *  Check that the advertiser has made at least 10% down payment for previously
     *  booked asset before he/she can book another asset.
     * 
     */
    public function check_asset_first_transaction_payment()
    {
        $asset_booking_recs = $this->get_asset_booked_by_current_advertiser(0,0,['type'=>env('SINGLE_BOOKING_TYPE')],env('SINGLE_BOOKING_TYPE'))->orderBy('created_at', 'desc')->get();
        
        $isMakePayment = true;
        foreach ($asset_booking_recs as $key => $asset_booking_rec) {
            $receipt = $asset_booking_rec->transaction()->where([
                ['asset_booking_ref', '=', $asset_booking_rec->trnx_id],
                ['bank_ref_number', '<>', null],
                ['paid', '=', 1],
                ['subscription', '=', 0],
            ])->count();
            
            if ($receipt <= 0) {
                $isMakePayment = false;
                break;
            }
        }

        return $isMakePayment;
            
        // $asset_booking_recs = $this->get_asset_booked_by_current_advertiser(0,0)->orderBy('created_at', 'desc')->first();

        // if ( $asset_booking_recs ) {

        //     $price = floatval(str_replace(',', '', $asset_booking_recs->price));

        //     $percent_10 = ((100/10) * $price);

        //     $receipt = $asset_booking_recs->transaction()->where([
        //         ['asset_booking_ref', '=', $asset_booking_recs->trnx_id],
        //         ['bank_ref_number', '<>', null],
        //         ['paid', '=', 1],
        //         ['first_pay', '=', 1],
        //         ['subscription', '=', 0],
        //         ['percentage', '>=', $percent_10]
        //     ])->first();

        //     if ($receipt) return true;
        //     else return false;
        // }
        // else {
        //     return true;
        // }
    }


    /**
     * 
     *  Get the pending payment transaction count based on today's transaction and display on the dashboard.
     * 
     */

    public function get_pending_payment_transaction_count()
    {
        $asset_booking_recs = $this->get_asset_booked_by_current_advertiser(0,0)->get();
        $count = 0;
        if (count($asset_booking_recs)) {
            $today = explode(' ', \Carbon\Carbon::today()->format('Y-m-d'));
            foreach ($asset_booking_recs as $key => $asset_booking_rec) {
                $trans = $this->get_pending_receipts($asset_booking_rec)->get();
                if (count($trans)) {
                    foreach ($trans as $key => $tranx) {
                        $trnx_date = explode(' ', $tranx->created_at);
                        if ($trnx_date[0] === $today[0]) $count++;
                    }
                }
            }
        }
        
        return $count;
    }

    /**
     * 
     *  Get the paid payment transaction count based on today's transaction and display on the dashboard.
     * 
     */

    public function get_paid_payment_transaction_count()
    {
        $asset_booking_recs = $this->get_asset_booked_by_current_advertiser(0,0)->get();
        $count = 0;
        if (count($asset_booking_recs)) {
            $today = explode(' ', \Carbon\Carbon::today()->format('Y-m-d'));
            foreach ($asset_booking_recs as $key => $asset_booking_rec) {
                $trans = $this->get_paid_receipts($asset_booking_rec)->get();
                if (count($trans)) {
                    foreach ($trans as $key => $tranx) {
                        $trnx_date = explode(' ', $tranx->updated_at);
                        if ($trnx_date[0] === $today[0]) $count++;
                    }
                }
            }
        }

        return $count;
    }

    public function get_booked_asset_count()
    {
        return count($this->get_asset_booked_by_current_advertiser(-1,0)->get());
    }


    public function get_working_dates(int $date_count, Carbon $start_date)
    {
        $working_dates = [];
        for ($i = 0; $i < $date_count; $i++) {
            $now = $start_date->addDay();
            if ( $now->isWeekend() ) $date_count++;
            else if ( $now->isWeekday() ) array_push($working_dates, $now->format('Y-m-d'));
        }
        return $working_dates;
    }


    public function get_corporate_bookings(int $corporate_id)
    {
        $individuals = Individual::where('corp_id', $corporate_id)->orderBy('id', 'asc')->get(); // Retrieve everyone belonging to this company records. 
        $ids = $individuals->pluck('id')->toArray(); // Extract the necessary field of those staffs and convert it to array.
        $firstnames = $individuals->pluck('firstname')->toArray();
        $lastnames = $individuals->pluck('lastname')->toArray();
        $emails = $individuals->pluck('email')->toArray();
        $phones = $individuals->pluck('phone')->toArray();

        $staff_details = array_map(function($id, $firstname, $lastname, $email, $phone){
           return [$id, $firstname, $lastname, $email, $phone];
        }, $ids, $firstnames, $lastnames, $emails, $phones);

        // Use that array to do a check if the user who booked this asset is within the staff array retrieve that booking.
        $corporate_bookings = AssetBooking::where('user_type_id', 2)->where(function($query) use ($ids) {
            $query->whereIn('booked_by_user_id', $ids)->orderBy('booked_by_user_id', 'asc');
        })->get();

        if ( count($corporate_bookings) ) { // Paradventure we have a record let's check its transaction records too.

            foreach ($corporate_bookings as $key => $asset_booking_rec) {

                $payment_records = $this->get_paid_receipts($asset_booking_rec)->orderBy('id', 'asc')->get();
                $total_payment_amount = $this->get_total_payment_amount($payment_records->toArray());
    
                $asset_booking_rec->payment_records = count($payment_records->toArray()) ? $payment_records : [];
                $asset_booking_rec->payment_records_count = count($payment_records);
                $asset_booking_rec->payment_total = $total_payment_amount;
                $asset_booking_rec->payment_remaining = $this->get_payment_remaining_balance($total_payment_amount, floatval(str_replace(',', '', $asset_booking_rec->price)));
                $asset_booking_rec->payment_remaining_perc = $this->get_payment_remaining_percentage($payment_records->toArray());
      
                $pending_payment_records = $this->get_pending_receipts($asset_booking_rec)->orderBy('id', 'asc')->get();
                $asset_booking_rec->pending_payment_records = $pending_payment_records;
            }
        }

        return ['corporate_bookings' => $corporate_bookings, 'staff_details' => $staff_details];
    }


    public function get_corporate_staffs(int $corporate_id)
    {
        return Individual::where('corp_id', $corporate_id)->orderBy('id', 'asc')->get(); 
    }

    
    public function get_corporate_staff_booking_records(int $corporate_id, int $staff_id)
    {
        $staff_details = Individual::where([['id', '=', $staff_id],['corp_id', '=', $corporate_id]])->first();
        $booking_details = AssetBooking::where([['booked_by_user_id', '=', $staff_id],['user_type_id', '=', 2]])->get();

        return ['staff_details' => $staff_details, 'booking_details' => $booking_details];
    }

    public function generate_otp($user, $txref, $tid)
    {
        $trans_token = new TransactionToken();
        $trans_token->token = $this->generate_transaction_id();
        $trans_token->user_type_id = $user->user_type_id;
        $trans_token->corp_id = $user->corp_id ? $user->corp_id : null;
        $trans_token->admin_user_id = $user->id;
        $trans_token->payment_id = $txref;
        $trans_token->trnx_id = $tid;
        $trans_token->locked = '1';
        $trans_token->status = 'unused';

        if ($trans_token->save()) {
            return $trans_token->token;
        }
        return null;
    }


    private function make_soap_call(string $url, $param, string $method = '', string $namespace = '', string $soapAction = '')
    {

        $client = new \nusoap_client($url, true);
        $client->soap_defencoding = 'UTF-8';
        $result = $client->call(
            $method,
            $param,
            $namespace,
            $soapAction,
            false,
            null
        );
        // dd($result, $url, $method, $param, $namespace, $soapAction);

        if ($client->fault) {
            return ["status" => false, "message" => $client->fault];
        }

        $err = $client->getError();
        if($err) {
            return ["status" => false, "message" => $err];
        }

        return ["status" => true, "message" => $result];
    }


    public function reQueryGlobalPay(string $txnref, string $db_amount)
    {
        $url = 'https://demo.globalpay.com.ng/GlobalpayWebService_demo/service.asmx?wsdl';
        $method = 'getTransactions';
        $params = [
            'merch_txnref' => $txnref,
            'channel' => '',
            'start_date' => '',
            'end_date' => '',
            'merchantID' => env("APP_GLOBAL_PAY_MERCHANT_ID"),
            'uid' => "da_ws_user",
            'pwd' => "da_ws_password",
            'payment_status' => ''
        ];
        $namespace = 'http://www.eazypaynigeria.com/globalpay_demo/';
        $soapAction = 'http://www.eazypaynigeria.com/globalpay_demo/getTransactions';
        $result = $this->make_soap_call($url, ['parameters' => $params], $method, $namespace, $soapAction);

        if($result['status']) {
            $WebResult = $method . "Result";
            $xml = simplexml_load_string($result["message"][$WebResult]);
            // dd($xml);
            if(is_object($xml) && $xml->record) {
                $amount = floatval(str_replace(',', '', $xml->record->amount));
                $db_amount = floatval(str_replace(',', '', $db_amount));
                if ($amount === $db_amount) {
                    $queryResult = [
                        'date'          => $xml->record->payment_date,
                        'method'        => $xml->record->channel,
                        'status'        => $xml->record->payment_status,
                        'txnref'        => $xml->record->txnref,
                        'booking_type'  => $xml->record->booking_type,
                        'amount'        => $amount,
                        'txid'          => $txnref,
                        'currency'      => $xml->record->field_values->field_values->field[2]->currency,
                        'trans_status'  => $xml->record->payment_status_description,
                        'payment_type'  => $xml->record->channel,
                        'extra_object'  => json_encode($xml),
                    ];

                    return ["status" => true, "message" => $queryResult];
                }
                else{
                    return ["status" => false, "message" => "Apologies, amount mismatch."];
                }
            }
            else{
                return ["status" => false, "message" => "Apologies, empty response from provider."];
            }
        }
        else{
           return ["status" => false, "message" => $result['message']];
        }
    }


    public function reQueryCIBankingDisbursement(string $txRef, array $headers)
    {
        $url = env('APP_ZENITH_CIBANKING_BASEURL') . "/api/FetchPayment";
        $payload = json_encode([
            "ClientInfo" =>  [
                "CompanyCode" => env('APP_ZENITH_CIBANKING_COMPANYCODE'),
                "Password" => env('APP_ZENITH_CIBANKING_PASSWORD'),
                "UserID" => env('APP_ZENITH_CIBANKING_USERID')
            ],
            "TransactionReference" => [
                [
                    "TransactionRef" => $txRef
                ]
            ]
        ]);
        $response = $this->doCurlRequest($url, $payload, $headers);
        if($response['status']) {
            $data = json_decode($response['data']);
            if($data->ResponseCode === '00' && (count($data->Transactions) && $data->Transactions[0]->ResponseCode === '00' && $data->Transactions[0]->TransactionRef)) {
                return ["status" => true, "message" => $response];
            }
        }

        return ["status" => false, "message" => $response];
    }


    public function executeCIBankingDisbursement($isBulkDebit = false, $bulkDebitRef = null, $transRequest = null)
    {
        $url = env('APP_ZENITH_CIBANKING_BASEURL') . "/api/SendPayment";
        $payload = json_encode([
            "BulkDebitReference" => $isBulkDebit ? $bulkDebitRef : '',
            "ClientInfo" =>  [
                "CompanyCode" => env('APP_ZENITH_CIBANKING_COMPANYCODE'),
                "Password" => env('APP_ZENITH_CIBANKING_PASSWORD'),
                "UserID" => env('APP_ZENITH_CIBANKING_USERID')
            ],
            "MAC" => "",
            "UseSingleDebitMultipleCredit" => $isBulkDebit,
            "TransactionRequest" => $transRequest
        ]);

        $headers = ["Content-Type: application/json", "Accept: application/json", "Cache-Control: no-cache"];
        $response = $this->doCurlRequest($url, $payload, $headers);

        if($response['status']) {
            $data = json_decode($response['data']);
            if($data->ResponseCode === "00" && (count($data->Transactions) && $data->Transactions[0]->ResponseCode === '00' && $data->Transactions[0]->TransactionRef)) {
                sleep(5); // wait after 5 seconds and make a requery request.
                // Requery and return the response.
                return $this->reQueryCIBankingDisbursement($data->Transactions[0]->TransactionRef, $headers);
            }
        }
        return ["status" => false, "message" => $response];
    }

    public function doCurlRequest($url, $post_fields = null, $headers = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        if (!empty($post_fields)) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        }

        if (!empty($headers))
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);

        // dd($url, $post_fields, $headers, $data);

        if (curl_errno($ch)) {
            return ['status' => false, 'data' => null, 'error' => 'Error:' . curl_error($ch)];
        }

        curl_close($ch);

        return ['status' => true, 'data' => $data, 'error' => null];
    }

    public function encrypt($payload, $iv = null, $key = null, $cipher = 'AES-128-CBC')
    {
        $payload = \openssl_encrypt(json_encode($payload), $cipher, $key, OPENSSL_RAW_DATA, $iv);

        if ($payload === false) {
            throw new \Exception('Could not encrypt the data.');
        }

        return \bin2hex($payload);
    }

    public function decrypt($payload, $iv = null, $key = null, $cipher = 'AES-128-CBC')
    {
        $payload = \hex2bin($payload);

        $decrypted = \openssl_decrypt($payload, $cipher, $key, OPENSSL_RAW_DATA, $iv);

        if ($decrypted === false) {
            throw new \Exception('Could not decrypt the data.');
        }

        return json_decode($decrypted);
    }

    public function amount_in_words(float $amount) {
        $words = array(
            '',
            'one',
            'two',
            'three',
            'four',
            'five',
            'six',
            'seven',
            'eight',
            'nine',
            'ten',
            'eleven',
            'twelve',
            'thirteen',
            'fourteen',
            'fifteen',
            'sixteen',
            'seventeen',
            'eighteen',
            'nineteen'
        );
        
        $tens = array(
            '',
            '',
            'twenty',
            'thirty',
            'forty',
            'fifty',
            'sixty',
            'seventy',
            'eighty',
            'ninety'
        );
        
        $groups = array(
            '',
            'thousand',
            'million',
            'billion',
            'trillion',
            'quadrillion',
            'quintillion'
        );
        $amount = (float) $amount;
        $words_string = '';
        
        if ($amount == 0) {
            $words_string .= 'zero';
        } else {
            $amount_parts = explode('.', $amount);
            $naira = (int) $amount_parts[0];
            $kobo = isset($amount_parts[1]) ? (int) $amount_parts[1] : 0;
            
            // Convert naira to words
            $naira_string = '';
            $naira_group = 0;
            
            while ($naira > 0) {
                $group = $naira % 1000;
                $naira = floor($naira / 1000);
                
                if ($group > 0) {
                    $group_string = '';
                    
                    $hundreds = floor($group / 100);
                    $tens_units = $group % 100;
                    
                    if ($hundreds > 0) {
                        $group_string .= $words[$hundreds] . ' hundred';
                        if ($tens_units > 0) {
                            $group_string .= ' and ';
                        }
                    }
                    
                    if ($tens_units < 20) {
                        $group_string .= $words[$tens_units];
                    } else {
                        $group_string .= $tens[floor($tens_units / 10)];
                        if ($tens_units % 10 > 0) {
                            $group_string .= ' ' . $words[$tens_units % 10];
                        }
                    }
                    
                    $group_string .= ' ' . $groups[$naira_group];
                    
                    if (!empty($naira_string)) {
                        $naira_string = $group_string . ', ' . $naira_string;
                    } else {
                        $naira_string = $group_string;
                    }
                }
                
                $naira_group++;
            }
            
            $words_string .= $naira_string;
            
            // Convert kobo to words
            if ($kobo > 0) {
                $kobo_string = '';
                if ($kobo < 20) {
                    $kobo_string .= $words[$kobo];
                } else {
                    $kobo_string .= $tens[floor($kobo / 10)];
                    if ($kobo % 10 > 0) {
                        $kobo_string .= '-' . $words[$kobo % 10];
                    }
                }
                $words_string .= ' and ' . $kobo_string . ' kobo only';
            }
            else {
                $words_string .= ' naira only';
            }
        }
        
        // Output: One hundred twenty-three million, four hundred fifty-six thousand, seven hundred eighty-nine and ninety-eight kobo
        return ucfirst($words_string);
    }    
}
