<?php

namespace App\Http\Controllers\Asset;

use Illuminate\Http\Request;
use App\Transaction;
use App\TransactionToken;
use App\PlatformSettings;
use App\AssetBooking;
use App\Individual;
use App\Corporate;
use App\AssetGracePeriod;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\LoggerTrait;
use App\Http\Traits\TransactionTrait;
use App\Providers\ActivityEventNotifier;
use Mail;

class TransactionController extends Controller
{
    //
    use LoggerTrait, TransactionTrait;

    public function transactions()
    {
        $user = \Request::get('user');
        $trans_records = AssetBooking::where([
            ['booked_by_user_id', '=', $user->user_id], 
            ['user_type_id', '=', $user->user_type_id]
        ])->get();

        if ( count($trans_records) ) {
            $trans_recs = [];
            $asset_recs = [];
            foreach ($trans_records as $key => $tranx) {
                $trans_recs[$key] = $tranx->transaction()->where([['paid', '=', 1], ['bank_ref_number', '<>', null], ['subscription', '=', 0]])->get();
                $asset_recs[$key] = $tranx->asset;
            }
            $tranx->trans_recs = $trans_recs;
            $tranx->asset_recs = $asset_recs;
        }

        return view('assets.assettransactions', compact('trans_records'));
    }


    public function create_transaction(Request $request)
    {
        $payable = floatval(str_replace(',', '', $request->payable));
        $balance = floatval(str_replace(',', '', $request->actualbal));
        $booking_type = $request->booking_type;
        $reserve_ref = $request->reserve_ref;
        $first_installment = 0;
        $booking_id = -1;
        $count_trans = 0;
        $where = [['asset_booking_ref', '=', $reserve_ref]];

        if ($booking_type === 'single') {
            $fields = ['type' => $booking_type];
            $booking_id = $request->booking_id;
            $pending_recs = $this->get_transaction_receipt(0, $booking_id, $fields);
            if (count($pending_recs) && $balance < $pending_recs[0]->payment_remaining) {
                $balance = $pending_recs[0]->payment_remaining;
            }

            $where[] = ['asset_booking_id', '=', $booking_id];
        }
        else {
            $campaign_id = $request->campaign_id;
            $campaign = $this->get_campaign_by_id($campaign_id);
            $payment_remaining = ($campaign->total_price - $campaign->total_payment);
            if ($balance < $payment_remaining) {
                $balance = $payment_remaining;
            }

            $where[] = ['campaign_id', '=', $campaign_id];

            $booking_id = $campaign_id;
        }

        $count_trans = Transaction::where($where)->count();
        

        if ( $count_trans ) {
            $payperc = round(($payable / ($balance/100)),2);
            if ($payperc > 90) {
                return response()->json([
                    'status' => false,
                    'errors' => 'Apologies, You are not allowed to pay more than NGN'.\number_format($balance, 2, '.', ','),
                    'success' => null,
                ]);
            }
            $payperc .= '%';
            $payable = \number_format($payable, 2, '.', ',');

            $desc = "Generated payment schedule of ".$payperc." (".$payable.") from the asset balance of (". 
            \number_format($balance, 2, '.', ',') .") for the asset with the reserved reference number: ". $reserve_ref;

            if ($booking_type === env('CAMPAIGN_BOOKING_TYPE')) {
                $desc = "Generated payment schedule of ".$payperc." (".$payable.") from the campaign balance of (". 
                \number_format($balance, 2, '.', ',') .") with the reserved reference number: ". $reserve_ref;
            }
        }
        else {
            $first_installment = 1;
            $payperc = 10;
            $payable = (($payperc/100) * $balance);
            $payperc .= '%';
            $payable = \number_format($payable, 2, '.', ',');
            $desc = "Generated payment schedule of 10% (". $payable . ") from the asset price of (".
            \number_format($balance, 2, '.', ',').") for the asset with the reserved reference number: ". $reserve_ref;

            if ($booking_type === env('CAMPAIGN_BOOKING_TYPE')) {
                $desc = "Generated payment schedule of 10% (". $payable . ") from the campaign total price of (".
                \number_format($balance, 2, '.', ',').") with the reserved reference number: ". $reserve_ref;
            }
        }



        $transaction = $this->generate_transaction($booking_id, $payable, $desc, $reserve_ref, $payperc, $first_installment, $booking_type);
        if ( $transaction ) {
            return response()->json([
                'status' => true,
                'errors' => null,
                'success' => [
                    'msg' => 'Successfully generated payment schedule slip.',
                    'trnx' => $transaction
                ]
            ]);
        }
        else {
            return response()->json([
                'status' => false,
                'errors' => 'Apologies, unable to generate payment schedule slip at the moment.',
                'success' => null,
            ]);
        }
    }


    /**
     * 
     * STEP 1: This module retrive the 10% pending transaction without payment and forward it to the banking
     * application for payment approval by the advertiser.
     * 
     * STEP 2: This module also assumes that advertiser may not pay a lumpsum of the remaining 90% therefore
     * it forwards the balance of the current remaining payment to the banking application.
     * 
     * @param string $tranx_id
     * @return json
     * 
     */

    public function transaction(string $tranx_id = null)
    {
        
        if ( $tranx_id ) {

            $asset_name = null;
            $asset_desc = null;
            $deposit_amount = null;
            $account_number = env('APP_DEBIT_ACCOUNT_NUMBER'); // Datashare account number. not really advicable to hardcode.

            $where = [['tranx_id', '=', $tranx_id], ['paid', '=', 1], ['bank_ref_number', '<>', null], ['subscription', '=', 0]];
            $trans_records = Transaction::where($where)->get();

            if ( count($trans_records) ) {
                return response()->json([
                    'status' => false, 
                    'error' => 'Apologies, You have already made payment for the supplied payment schedule, Kindly create another payment schedule and make payment on it.', 
                    'success' => null,
                ]);
            }
            else {

                $where = [['tranx_id', '=', $tranx_id], ['paid', '=', 0], ['bank_ref_number', '=', null], ['subscription', '=', 0]];
                $trans_records = Transaction::where($where)->first();

                if ( $trans_records ) {

                    $assetObj = $trans_records->asset_booking->asset;
                    $asset_name = $assetObj->name;
                    $asset_desc = $trans_records->description;
                    $deposit_amount = $trans_records->amount;
                    
                    return response()->json([
                        'status' => true, 
                        'error' => null, 
                        'success' => [
                            'asset_name' => $asset_name, 
                            'asset_desc' => $asset_desc, 
                            'deposit_amt' => $deposit_amount, 
                            'account_num' => $account_number,
                        ],
                    ]);
                }
                else {
                    return response()->json([
                        'status' => false, 
                        'error' => 'Apologies, we are currently unable to find the supplied transaction number', 
                        'success' => null,
                    ]);
                }
            }
        }
    }

    public function create_bank_transaction($request, array $result = [])
    {
        if($request){
            $extra_info = json_encode($request->all());
            $txnid = $request->TxnId;
            $txref = $request->BnkRef;
            $desc = $request->Desc;
            $amount = $request->Amt;
            $payment_type = $request->payment_type;
        }
        else {
            $extra_info = json_encode($result);
            $txnid = $result['txid'];
            $txref = $result['txnref'];
            $desc = $result['desc'];
            $amount = $result['amount'];
            $payment_type = $result['payment_type'];
        }

        $trans = Transaction::where([['tranx_id', '=', $txnid], ['paid', '=', 0], ['bank_ref_number', '=', null], ['subscription', '=', 0]])->first();

        if ( $trans ) {

            $trans->paid = 1;
            $trans->extra_info = $extra_info;
            $trans->bank_ref_number = $txref;
            $trans->payment_type = $payment_type;
            
            try {

                if ( $trans->save() ) {
                    
                    $this->log('Info', 'Successfully Logged Payment for Asset with the Description: '. $desc .' and Transaction Number: '.$txnid);
                   
                    $assetBooking = AssetBooking::where([
                        ['id', '=', $trans->asset_booking_id],
                        ['trnx_id', '=', $trans->asset_booking_ref],
                    ])->first();
                   
                    $assetBooking->bank_ref = 1;

                    try {
                        if ($assetBooking->save()) {

                            $gracePeriods = AssetGracePeriod::where([['asset_booking_id', '=', $trans->asset_booking_id],['booked_id', '=', $trans->asset_booking_ref]]);

                            if ( $trans->first_pay ) { // Flag first 10% as a completed transaction.
                                $gracePeriods = $gracePeriods->first();
                                $gracePeriods->completed = 1;
                                $gracePeriods->save();
                            } else {

                                $paid_trans = Transaction::where([
                                    ['paid', '=', 1], 
                                    ['bank_ref_number', '<>', null],
                                    ['subscription', '=', 0],
                                    ['asset_booking_ref', '=', $trans->asset_booking_ref],
                                    ['asset_booking_id', '=', $trans->asset_booking_id],
                                ])->get();

                                $perc_paid = 0;
                                
                                if ( count($paid_trans) ) {
                                    foreach ($paid_trans as $key => $paid_tran) { 
                                        // Stripe out the % sign on the percent number an convert to integer
                                        $perc_paid += intval( substr($paid_tran->percentage, 0, (strlen($paid_tran->percentage)-1)) );
                                    }

                                    $gracePeriods = $gracePeriods->orderBy('id', 'desc')->first();
                                    $grace_perc = intval( substr($gracePeriods->percentage, 0, (strlen($gracePeriods->percentage)-1)) );

                                    if ( $perc_paid === $grace_perc ) { 
                                        // accumulate the payment history on this asset and check against the 90% balance 
                                        // payment and flag as completed transaction if they are same.
                                        $gracePeriods->completed = 1;
                                        $gracePeriods->save();
                                    }
                                }
                            }

                            // \broadcast(new ActivityEventNotifier('asset', 'asset-payment', [
                            //     'asset_name' => $assetBooking->asset->name,
                            //     'amount_paid' => \number_format(str_replace(',', '', $amount), 2, '.', ','),
                            //     'bank_ref' => $txref,
                            //     'asset_booking_ref' => $trans->asset_booking_ref,
                            //     'msg' => 'Asset Payment was successful'
                            // ]));
                            return response()->json(['status' => true, 'errors' => null, 'success' => 'Payment Logged Successfully.']);
                        }
                    } catch (\QueryException $qe) {
                        $this->log('Critical', $qe->getMessage());
                        return response()->json(['status' => false, 'errors' => 'Apologies, Could not log Payment at this Time.', 'success' => null]);
                    }
                }
                else {
                    $this->log('Warning', 'Could not log Payment for Asset with Description: '. $desc .' and Transaction Number: '.$txnid);
                    return response()->json(['status' => false, 'errors' => 'Apologies, Could not log Payment at this Time.', 'success' => null]);
                }

            } catch (QueryException $qe) {
               $this->log('Critical', $qe->getMessage());
               return response()->json(['status' => false, 'errors' => 'Apologies, Could not log Payment at this Time.', 'success' => null]);
            }
        }
    }


    public function ebillsValidate(Request $request)
    {
       try {
            $response = [
                "Message" => "Unable to retrieve transaction details, kindly see errors.",
                "Amount" => 0.0,
                "HasError" => true,
            ];

            $settings = PlatformSettings::find(1);

            $iv = $settings->iv;
            $key = $settings->sec;
            $cipher = env('APP_NIBSS_CIPHER');

            if(!$request->hasHeader("HASH")) {

                $response['ErrorMessages'] = ["Header `HASH` parameter not supplied"];

                $encoded = $this->encrypt($response, $iv, $key, $cipher);

                return response()->json($encoded, 200, ['Content-Type: text/plain', 'Accept: application/json']);
            }

            $payload = trim($request->header("HASH"));
            // $payload = trim($request->getContent());

            $decoded = $this->decrypt($payload, $iv, $key, $cipher);
            $errmsg = [];

            if($decoded && $decoded->params) {
                $token = $decoded->params->TransactionID;
                $email = $decoded->params->Email;
                $clientID = 0;
                $clientName = "";
                if($token) {
                    $txtoken = TransactionToken::where(['token' => $token, 'locked' => "1", "status" => "unused"])->first();
                    if($txtoken) {
                        $tx1 = Transaction::where([
                            'id' => $txtoken->trnx_id,
                            'tranx_id' => $txtoken->payment_id,
                            'paid' => 0,
                            'bank_ref_number' => null,
                            'subscription' => 0
                        ])->first();

                        if($tx1) {
                            $response = [
                                "Message" => "Successfully retrieve transaction details",
                                "Amount" => floatval(str_replace(',', '', $tx1->amount)),
                                "HasError" => false,
                            ];
                        }
                        else {
                            $tx1 = Transaction::where([
                                'id' => $txtoken->trnx_id, 
                                'tranx_id' => $txtoken->payment_id,
                                'paid' => 1,
                                'subscription' => 0
                            ])->where(function($query){
                                return $query->where(['bank_ref_number', '<>', null]);
                            })->first();

                            if($tx1) {
                                $errmsg[] = "Apologies, it seems you have already made payment for this transaction.";
                            }
                            else {
                                $errmsg[] = "Apologies, we are unable to find the transaction attached to your token.";
                            }
                        }

                        $assetBooking = $tx1->asset_booking;
                        $clientID = $assetBooking->booked_by_user_id;
                        if ($assetBooking->user_type_id === 2) {
                            $indv = Individual::find($clientID);
                            $clientName = $indv->lastname . " " . $indv->firstname;
                        }
                        else {
                            $corp = Corporate::find($clientID);
                            $clientName = $corp->name;
                        }

                    }
                    else {
                        $errmsg[] = "Apologies, we are currently unable to find the supplied TransactionID.";
                    }
                }
                else {
                    $errmsg[] = "Apologies, Transaction ID not found. Kindly supply a valid TransactionID.";
                }

                $response["params"] = [
                    "TransactionID" => $token,
                    "Email" => $email,
                    "ClientID" => $clientID,
                    "ClientName" => $clientName
                ];
                $response["ErrorMessages"] = $errmsg;
            }
            else {
                $response["ErrorMessages"] = ["Apologies, we are unable to decode and find [params] field."];
            }

            $encoded = $this->encrypt($response, $iv, $key, $cipher);
            return response()->json($encoded, 200, ['Content-Type: text/plain', 'Accept: application/json']);
       } catch (\Exception $e) {
            return response()->json($e->getMessage(), 200, ['Content-Type: text/plain', 'Accept: application/json']);
       }
    }


    public function ebillsNotify(Request $request)
    {
        try {
            $settings = PlatformSettings::find(1);

            $iv = $settings->iv;
            $key = $settings->sec;
            $cipher = env('APP_NIBSS_CIPHER');

            $response = [
                "Message" => "Unable save transaction details.",
                "Amount" => 0.0,
                "HasError" => true,
            ];

            if(!$request->hasHeader("HASH")) {

                $response['ErrorMessages'] = ["Header `HASH` parameter not supplied"];

                $encoded = $this->encrypt($response, $iv, $key, $cipher);

                return response()->json($encoded, 200, ['Content-Type: text/plain', 'Accept: application/json']);
            }

            $payload = trim($request->header("HASH"));
            // $payload = trim($request->getContent());

            $decoded = $this->decrypt($payload, $iv, $key, $cipher);
            // dd($decoded);
            $errmsg = [];
            if($decoded && $decoded->params) {
                $token = $decoded->params->TransactionID;
                $email = $decoded->params->Email;
                $clientID = 0;
                $clientName = "";
                if($token) {
                    $txtoken = TransactionToken::where(['token' => $token, 'locked' => "1", "status" => "unused"])->first();
                    if($txtoken) {
                        $tx1 = Transaction::where([
                            'id' => $txtoken->trnx_id,
                            'tranx_id' => $txtoken->payment_id,
                            'paid' => 0,
                            'bank_ref_number' => null,
                            'subscription' => 0
                        ])->first();

                        if($tx1) {
                            $amount = floatval($decoded->Amount);
                            $db_amount = floatval(str_replace(',', '', $tx1->amount));
                            if($amount === $db_amount) {
                                $payload = [
                                    "txid" => $tx1->tranx_id,
                                    "txnref" => $decoded->SessionId,
                                    "desc" => "Payment made via Nibbs through " . $decoded->ChannelCode . " channel on the " . $decoded->TransactionDate . " " . $decoded->TransactionTime,
                                    "amount" => $decoded->Amount,
                                    "payment_type" => $decoded->ChannelCode,
                                    "extra_object" => json_encode($decoded),
                                ];

                                $result = $this->create_bank_transaction(null, $payload);
                                $resultContent = $result->getData();
                                if($result && $resultContent->status) {
                                    $response = [
                                        "Message" => "Successfully logged transaction",
                                        "Amount" => $db_amount,
                                        "HasError" => false,
                                    ];
                                }
                            }
                            else {
                                $errmsg[] = "Apologies, payment made does not match schedule payment.";
                            }

                            $assetBooking = $tx1->asset_booking;
                            $clientID = $assetBooking->booked_by_user_id;
                            if ($assetBooking->user_type_id === 2) {
                                $indv = Individual::find($clientID);
                                $clientName = $indv->lastname . " " . $indv->firstname;
                            }
                            else {
                                $corp = Corporate::find($clientID);
                                $clientName = $corp->name;
                            }
                        }
                        else {
                            $errmsg[] = "Apologies, we are currently unable to find the supplied Reference Number.";
                        }
                    }
                    else {
                        $errmsg[] = "Kindly supply a valid Reference Number.";
                    }
                }
                else {
                    $errmsg[] = "Apologies, we are unable to find the transaction attached to your token.";
                }

                $response["params"] = [
                    "TransactionID" => $token,
                    "Email" => $email,
                    "ClientID" => $clientID,
                    "ClientName" => $clientName
                ];

                $response["ErrorMessages"] = $errmsg;
            }
            else {
                $response["ErrorMessages"] = ["Apologies, we are unable to decode and find [params] fieeld."];
            }

            $encoded = $this->encrypt($response, $iv, $key, $cipher);
            return response()->json($encoded, 200, ['Content-Type: text/plain', 'Accept: application/json']);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 200, ['Content-Type: text/plain', 'Accept: application/json']);
        }
    }


    public function ebillsReset(Request $request)
    {
        $cipher = env('APP_NIBSS_CIPHER');
        $iv = bin2hex(random_bytes(openssl_cipher_iv_length($cipher)/2));
        $key = bin2hex(random_bytes(openssl_cipher_iv_length($cipher)/2));
        
        // Save keys to DB.
        PlatformSettings::where(['id' => 1])->update(['iv' => $iv, 'sec' => $key]);
        $data = compact('iv', 'key');

        //TODO: Send as a mail.
        try {
            $to = env('APP_NIBSS_EMAIL');
            $toName = substr($to, 0, (strpos($to, '@') + 1));
            $data['toName'] = $toName;
            Mail::send('operator.mails.nibss', $data, function ($message) use($to, $toName) {
                $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                $message->sender(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                $message->to($to, $toName);
                $message->cc(env('MAIL_CC'), $toName);
                $message->subject('Cryptograhic Credentials');
            });
        } catch (\Exception $e) {
            $this->log('Critical', $e->getTraceAsString());
        }

        return response()->json($data, 200);
    }


    public function globalPayValidate(Request $request)
    {
        if(strtolower($request->status) === 'successful') {
            if(($transaction = $this->get_transaction_by_ref($request->txnref)) && $transaction != null) {
                // We need to reQuery another endpoint for verification of payment before giving the user it's value.
                $queryResult = $this->reQueryGlobalPay($request->txnref, $transaction->amount);
                if($queryResult['status']) {
                    if($transaction->subscription) {
                        $transaction->paid = 1;
                        $transaction->extra_info = $queryResult['message']['extra_object'];
                        $transaction->payment_type = $queryResult['message']['payment_type'];
                        $transaction->bank_ref_number = $queryResult['message']['txnref'];
                        if($transaction->save()) {
                            \session()->put('transaction', $transaction);
                            return redirect('/operator/subscription')->with('flash_message', 'Successfully made payment for annual subscription.');
                        }
                    }
                    $queryResult['message']['desc'] = $transaction->asset_booking_ref;
                    $result = $this->create_bank_transaction(null, $queryResult['message']);
                    $resultContent = $result->getData();
                    if($result && $resultContent->status) {
                       return redirect('/advertiser/individual/pending/transaction/payments/detail/'.$transaction->asset_booking_id)->with(['flash_message' => $resultContent->success]);
                    }
                    else {
                        return redirect('/advertiser/individual/pending/transaction/payments/detail/'.$transaction->asset_booking_id)->withErrors(['errors' => [$resultContent->errors]]);
                    }
                }else{
                    if($transaction->subscription) {
                        \session()->put('transaction', $transaction);
                        return redirect('/operator/subscription')->with('error_message', 'Unable to make payment for annual subscription.');
                    }
                    return redirect('/advertiser/individual/pending/transaction/payments/detail/'.$transaction->asset_booking_id)->withErrors(['errors' => [$queryResult['message']]]);
                }
            }
            else {
                return redirect('/advertiser/individual/transactions/pending')->withErrors(['errors' => ["Apologies, transaction supplied was invalid."]]);
            }
        }
        else {
            return redirect('/advertiser/individual/transactions/pending')->withErrors(['errors' => ["Apologies, your transaction was not successful."]]);
        }
    }

    public function validate_otp(string $otp = null, string $user_id = null)
    {
        $token = TransactionToken::where([['token', '=', $otp],['status', '=', 'unused'], ['locked', '=', "1"]])->first();
        if ($token) {
            $token->status = 'used';
            $token->used_by = $user_id;
            if ($token->save()) return response()->json(['status' => true, 'errors' => null, 'success' => ['msg' => 'OTP valid', 'otp' => $otp]]);
        }
        else {
            return response()->json(['status' => false, 'errors' => 'Kindly ensure OTP has not been used or check if correct.', 'success' => null]);
        }
    }


    public function reQueryFlutterwaveTransaction(Request $request)
    {
        $trnx_id = $request->tx_ref;
        if(($transaction = $this->get_transaction_by_ref($trnx_id)) && $transaction != null) {
            $url = env('FLUTTERWAVE_API_ENDPOINT')."/transactions/".$trnx_id."/verify";
            $headers = ['Content-Type: application/json', 'Authorization: Bearer '.env("FLUTTERWAVE_TEST_PAYMENT_SEC_KEY")];
            $response = $this->doCurlRequest($url, null, $headers);
            if($response['status'] && (($data = json_decode($response['data'])) && $data->status === 'success')) {
                if($data->data->status === 'successful' && floatval($data->data->amount) === floatval($transaction->amount)) {

                    $payload = [
                        'txid'   => $trnx_id,
                        'txnref' => $data->data->flw_ref,
                        'desc' => $data->data->narration,
                        'amount' => $data->data->amount,
                        'payment_type' => 'flutterwave'
                    ];

                    $result = $this->create_bank_transaction(null, $payload);
                    $resultContent = $result->getData();
                    if($result && $resultContent->status) {
                        return redirect('/advertiser/individual/pending/transaction/payments/detail/'.$transaction->asset_booking_id)->with(['flash_message' => $resultContent->success]);
                    } else {
                        return redirect('/advertiser/individual/pending/transaction/payments/detail/'.$transaction->asset_booking_id)->withErrors(['errors' => [$resultContent->errors]]);
                    }
                } else {
                    return redirect('/advertiser/individual/pending/transaction/payments/detail/'.$transaction->asset_booking_id)->withErrors(['errors' => ["Apologies, your payment was not successful"]]);
                }
            }
        } else {
            return redirect('/advertiser/individual/transactions/pending')->withErrors(['errors' => ["Apologies, transaction supplied was invalid."]]);
        }
    }
}