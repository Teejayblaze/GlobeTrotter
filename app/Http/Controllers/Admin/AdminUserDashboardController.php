<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use \App\Transaction;
use \App\AssetBooking;
use App\AssetImage;
use \Carbon\Carbon;
use \App\UserCredential;
use \App\Individual;
use \App\Corporate;
use \App\Asset;
use \App\Operator;
use \App\PlatformSettings;
use App\Http\Traits\TransactionTrait;
use App\Http\Traits\LoggerTrait;
use Illuminate\Database\QueryException;
use CodeDredd\Soap\Facades\Soap;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Excel\BatchAssetExcelUploader;

class AdminUserDashboardController extends Controller
{
    use TransactionTrait, LoggerTrait;

    public function dashboard()
    {
        $title = "Dashboard";
        $userinfo = \Request::get('user');
        $user = $userinfo->userdetail;
        $department = $userinfo->admingroups->group_name;
        return view('admin.dashboard', compact('user', 'department', 'title'));
    }

    private function payment_schedule_bookings(int $disbursed = 0)
    {
        if ($disbursed >= 0) $where = [['paid', '=', 1], ['bank_ref_number', '<>', null],['disbursed', '=', $disbursed]];
        else $where = [['paid', '=', 0], ['bank_ref_number', '=', null],['disbursed', '=', 0]];

        $transactions = Transaction::where($where)->get();

        if (count(@$transactions)) {
            foreach (@$transactions as $key => $transaction) {
                @$transaction->bookings = $transaction->asset_booking;
                @$transaction->asset = $transaction->asset_booking->asset;
                @$transaction->asset->owner = $transaction->asset_booking->asset->assetOwner;
                @$transaction->asset_type = $transaction->asset_booking->asset->assetTypeRecord;
            }
        }
        return $transactions;
    }

    private function asset_bookings()
    {
        return AssetBooking::where('cancel', 0)->orderBy('created_at', 'desc')->get();
    }

    public function payment_disbursement_view()
    {
        $title = "Payment Disbursement";
        $userinfo = \Request::get('user');
        $user = $userinfo->userdetail;
        $department = $userinfo->admingroups->group_name;
        $transactions = $this->payment_schedule_bookings();
        return view('admin.payment_disburse', compact('transactions', 'user', 'department', 'title'));
    }

    public function payment_disbursement(Request $request)
    {
        $now = Carbon::now();
        $disbursed_date = Carbon::now();
        // $dates = $this->get_working_dates(5, $now);

        try {

            $transaction = Transaction::find($request->payment_id);
            $operatorId = $transaction->asset_booking->asset->uploaded_by;

            if($transaction) {

                $commission_perc = 0;
                $MAX_PERC = 100;

                $settings = PlatformSettings::find(1);
                if($settings->commission) {
                    $commission_perc = floatval(str_replace('%', '', $settings->commission));
                }

                if($operatorId) {
                    $operator = Operator::find($operatorId);

                    if(empty($operator->account_number) || empty($operator->bank_code)) {
                        return redirect()->back()->withErrors(['errors' => 'Apologies, disbursement bank hasn\'t been setup by the asset owner.']);
                    }

                    $disbursedBnkRef = "DISBURSE/" . $this->generate_transaction_id(5) . "/" . $now->year;
                    $invoiceNumber = "INV-" . $this->generate_transaction_id(7);
                    $amount = floatval(str_replace(',', '', $transaction->amount));


                    $payload[] = [
                        "Amount" => $amount,
                        "BeneficiaryAccount" => $operator->account_number,
                        "BeneficiaryAddress" => $operator->address,
                        "BeneficiaryBankCode" => $operator->bank_code,
                        "BeneficiaryBankSortCode" => "",
                        "BeneficiaryCategory" => "",
                        "BeneficiaryCode" => $transaction->tranx_id,
                        "BeneficiaryEmail" => $operator->email,
                        "BeneficiaryMobile" => $operator->phone,
                        "BeneficiaryName" => $operator->corporate_name,
                        "BeneficiaryPhone" => $operator->phone,
                        "ContractDate" => "",
                        "ContractNo" => "",
                        "DebitAccount" => env('APP_DEBIT_ACCOUNT_NUMBER'),
                        "DebitAccountName" => env('APP_DEBIT_ACCOUNT_NAME'),
                        "DebitCurrency" => env('APP_DEBIT_CURRENCY'),
                        "InvoiceNumber" => $invoiceNumber,
                        "PaymentCurrency" => env('APP_DEBIT_CURRENCY'),
                        "PaymentMethod" => "",
                        "PaymentType" => "",
                        "Payment_Due_Date" =>  Carbon::now()->format('d/m/Y'),
                        "TransactionRef" => $disbursedBnkRef
                    ];
                    $isBulkDebit = false;
                    $bulkDebitRef = null;
                    $response = $this->executeCIBankingDisbursement($isBulkDebit, $bulkDebitRef, $payload);
                    if($response['status']) {

                        $return = [];
                        $data = json_decode($response['message']['data']);

                        if(in_array(strtolower($data->Transactions[0]->PaymentStatus), ['processed', 'successful'])){
                            $return['key'] = 'disbursed-success';
                            $return['label'] = 'Successfully Disbursed Payment to Asset Owner.';
                        }
                        elseif($data->Transactions[0]->PaymentStatus === 'pending') {
                            $return['key'] = 'disbursed-pending';
                            $return['label'] = 'Payment disbursement has been lodge. Kindly Requery the final status of the disbursement.';
                        }

                        $transaction->disbursed = 1;
                        $transaction->date_disbursed = $disbursed_date;
                        $transaction->disbursed_bank_ref_number = $data->Transactions[0]->TransactionRef;
                        $transaction->disbursed_amount = ($amount - ($amount * ($commission_perc/$MAX_PERC)));
                        $transaction->disburse_invoice = $invoiceNumber;
                        $transaction->disburse_status = $data->Transactions[0]->PaymentStatus;
                        $transaction->disburse_extra = json_encode($data);
                        $transaction->save();

                        return redirect()->back()->with($return['key'], $return['label']);

                    }
                    return redirect()->back()->withErrors(['errors' => $response['message']]);
                }
            }
    
        } catch (\Exception $e) {
            $this->log('Admin::Critical', $e->getMessage());
            return redirect()->back()->withErrors(['errors' => 'Transaction not found!']);
        }

        // $post_fields = [];
        // $transfers = $request->transfers;
        // $title = $request->title;

        // if($request->isBulk) {
        //     $post_fields['title'] = $title;
        //     $post_fields['bulk_data'] = [];
        //     foreach($transfers as $key => $transfer) {
        //         $post_fields['bulk_data'][] = [
        //             "bank_code" => $transfer->bank_code,
        //             "account_number" => $transfer->account_number,
        //             "amount" => floatval($transfer->account_number),
        //             "currency" => "NGN",
        //             "narration" => $title
        //         ];
        //     }
        // }
        // else {

        // }


        // $url = env('FLUTTERWAVE_API_ENDPOINT') . "/bulk-transfers";
        // $this->doCurlRequest($url, $post_fields = null, $headers = null);
    }

    public function disbursed_payment_view()
    {
        $title = "Disbursed Payment";
        $userinfo = \Request::get('user');
        $user = $userinfo->userdetail;
        $department = $userinfo->admingroups->group_name;
        $transactions = $this->payment_schedule_bookings(1);
        return view('admin.disbursed_payment', compact('transactions', 'user', 'department', 'title'));
    }

    public function reverse_disbursed_payment(int $txid)
    {
        $transaction = Transaction::where('id', $txid)->first();
        if ($transaction) {
            $transaction->disbursed = 0;
            $transaction->date_disbursed = null;
            $transaction->expect_pay_date = null;
            $transaction->disbursed_bank_ref_number = null;
            $transaction->disbursed_amount = null;
            try {
                if ($transaction->save()) return redirect()->back()->with('reverse-disburse-success', 'Successfully Reverse Disbursed Payment.');
            } catch (\QueryException $qe) {
                $this->log('Admin::Critical', $qe->getMessage());
            }
        }
    }

    public function payment_disbursement_requery(int $txid)
    {
        try {
            $transaction = Transaction::where('id', $txid)->first();
            if ($transaction) {
                $txRef = $transaction->disbursed_bank_ref_number;
                $headers = ["Content-Type: application/json", "Accept: application/json", "Cache-Control: no-cache"];

                $response = $this->reQueryCIBankingDisbursement($txRef, $headers);
                if($response['status']) {
                    $data = json_decode($response['message']['data']);

                    if($data->ResponseCode === '00' && (count($data->Transactions) && $data->Transactions[0]->ResponseCode === '00')) {
                        $transaction->disburse_status = $data->Transactions[0]->PaymentStatus;
                        $transaction->disburse_extra = json_encode($data);
                        $transaction->save();

                        if($data->Transactions[0]->PaymentStatus === 'pending') {
                            return redirect()->back()->with('disbursed-pending', 'Payment status has not changed. Kindly Requery the disbursement again at a later time.');
                        }
                        else {
                            return redirect()->back()->with('reverse-disburse-success', 'Payment has been deposited into the rightful account.');
                        }
                    }
                    return redirect()->back()->with('disbursed-pending', 'Apologies, we are currently unable to reach reconciliation server.');
                }
                return redirect()->back()->with('disbursed-pending', 'Apologies, our reconciliation server is down at the moment.');
            }
            return redirect()->back()->with('disbursed-pending', 'Apologies, we are unable to find the transaction on our server.');
        } catch (\QueryException $qe) {
            $this->log('Admin::Critical', $qe->getMessage());
        }
    }

    public function pending_payment_schedule_view()
    {
        $title = "Pending Payment Schedule";
        $userinfo = \Request::get('user');
        $user = $userinfo->userdetail;
        $department = $userinfo->admingroups->group_name;
        $transactions = $this->payment_schedule_bookings(-1);
        return view('admin.pending_payment_schedule', compact('transactions', 'user', 'department', 'title'));
    }

    public function asset_booking_view()
    {
        $title = "Asset Bookings";
        $userinfo = \Request::get('user');
        $user = $userinfo->userdetail;
        $department = $userinfo->admingroups->group_name;
        return view('admin.asset_bookings', compact('user', 'department', 'title'))->with('bookings', $this->asset_bookings());
    }
    
    public function assets_view(string $operatorId = null)
    {
        $title = "Assets";
        $userinfo = \Request::get('user');
        $user = $userinfo->userdetail;
        $department = $userinfo->admingroups->group_name;
        $asset_owners = Operator::select("id", "corporate_name")->orderBy("corporate_name", "ASC")->get();
        $assetsQueryBuilder = Asset::where([["created_at", "<>", null]]);

        if($operatorId) {
            $assetsQueryBuilder->where("uploaded_by", $operatorId);
        }

        $assets = $assetsQueryBuilder->orderBy("uploaded_by", "ASC")->get();
        return view('admin.assets', compact('user', 'department', 'title', 'assets', 'asset_owners'));
    }

    public function users_view()
    {
        
        $userinfo = \Request::get('user');
        $user = $userinfo->userdetail;
        $department = $userinfo->admingroups->group_name;
        $platform_users = [];
        $users = [];

        $all_user_creds = UserCredential::orderBy('user_type_id', 'asc')->get(); 
        if (count($all_user_creds)) {
            foreach ($all_user_creds as $key => $all_user_cred) {

                if ($all_user_cred->operator === 1 && $all_user_cred->user_type_id === 1) {
                    
                    $operator = Operator::where('id', $all_user_cred->user_id)->first();

                    $staffs = Individual::where([['corp_id', '=', $all_user_cred->user_id], ['operator', '=', $all_user_cred->operator]])->get();

                    $platform_users['operator'][$all_user_cred->user_id] = $operator;
                    
                    $platform_users['operator'][$all_user_cred->user_id]['staffs'] = $staffs;

                } else if ($all_user_cred->operator === 0 && $all_user_cred->user_type_id === 1) {

                    $corporate = Corporate::where('id', $all_user_cred->user_id)->first();

                    $staffs = Individual::where([['corp_id', '=', $all_user_cred->user_id],['operator', '=', $all_user_cred->operator]])->get();

                    $platform_users['corporate'][$all_user_cred->user_id] = $corporate;
                    
                    $platform_users['corporate'][$all_user_cred->user_id]['staffs'] = $staffs;

                } else if ($all_user_cred->operator === 0 && $all_user_cred->user_type_id === 2) {
                    $individual = Individual::where([['corp_id', '=', 0],['operator', '=', $all_user_cred->operator]])->first();
                    $platform_users['individual'][$individual->id] = $individual;
                }
            }
        }
        // dd($platform_users);
        
        return view('admin.platform_users', compact('user', 'department', 'platform_users'));
    }


    public function settings_view()
    {
        $title = "Platform Settings";
        $userinfo = \Request::get('user');
        $user = $userinfo->userdetail;
        $department = $userinfo->admingroups->group_name;
        $settings = PlatformSettings::find(1);
        return view('admin.settings', compact('user', 'settings', 'department', 'title'));
    }
    
    public function settings(Request $request)
    {
        try {
            $settings = PlatformSettings::find(1);
            $settings->commission = $request->commission;
            $settings->subscription = $request->subscription;
            $settings->distance_km = $request->distance_km;
            $settings->population_randomness = $request->population_randomness;
            if($settings->save()) {
                return redirect()->back()->with('success', 'Successfully modify platform settings.');
            }
        } catch (\QueryException $qe) {
            $this->log('Admin::Critical', $qe->getMessage());
            return redirect()->back()->withErrors(['errors' => ['Unable to save platform settings at the moment.']]);
        }
    }


    public function run_utility(string $utility)
    {
        switch ($utility) {
            case 'population-randomness':
                $settings = PlatformSettings::find(1);
                if (!$settings->population_randomness){
                    return redirect()->back()->withErrors(['errors' => ['Kindly set your Population Randomness to continue.']]);
                }
                $population_randomness = $settings->population_randomness / 100;
                $affectedRows = \DB::statement("update l_c_d_as set lcda_population = CEIL(lcda_population + (lcda_population *  $population_randomness))");
                if ($affectedRows) {
                    return redirect()->back()->with('success', 'Population Randomness has been increased by ' . $settings->population_randomness . '%');
                }
                break;
            
            default:
                break;
        }
    }


    public function batch_asset_upload_view()
    {
        $title = "Batch Asset Upload";
        $operators = Operator::orderBy('corporate_name', 'ASC')->get();
        return view('admin.batch_asset_upload', compact('title', 'operators'));
    }

    public function batch_asset_upload(Request $request)
    {
        if($request->asset_owner) {
            if ($request->hasFile('asset_file')) {
                $asset_file = $request->file('asset_file');
                $uid = Storage::putFile('public/operator/excel', $asset_file);
                Excel::import(new BatchAssetExcelUploader($request->asset_owner), $uid);
                if(\session()->has('cache')) {
                    $cache = \session()->get('cache');
                    $uploadedAssets = Asset::whereIn('id', $cache)->get();
                    \session()->put('uploadedAssets', $uploadedAssets);
                    if(file_exists($uid)) {
                        unlink($uid);
                    }
                    return redirect()->back()->with(['success' => 'Excel data imported successfully.']);
                }
            }
        }
        else {
            return redirect()->back()->withErrors(['errors' => ['Apologies, Please select the asset owner to whom you wish to upload asset.']]);
        }
    }

    public function batch_asset_media_upload_view(Request $request)
    {
        $title = "Batch Asset Media Upload";
        if(!$request->id)
        {
            return redirect()->back()->withErrors(['errors' => ['Apologies, Please ensure you are at the right url.']]);
        }
        $asset = Asset::find($request->id);
        return view('admin.asset-upload-media', compact('title', 'asset'));
    }
    
    
    public function batch_asset_media_upload(Request $request)
    {
        $title = "Batch Asset Media Upload";

        if(!$request->id)
        {
            return redirect()->back()->withErrors(['errors' => ['Apologies, Please ensure you are at the right url.']]);
        }
        try {
            $asset = Asset::find($request->id);

            if ($request->hasFile('asset_video')) {
                
                $asset_video = $request->file('asset_video');
                $uid = Storage::putFile('public/operator/video', $asset_video);
                $asset->video_path = $uid;
                $asset->save();
                return redirect()->back()->with(['flash_message' => 'Successfully uploaded your video.']);
                
            }
            else if ($request->hasFile('asset_image')) {

                $asset_images = $request->file('asset_image');

                foreach ($asset_images as $key => $asset_image) {

                    $uid = Storage::putFile('public/operator', $asset_image);

                    $assetImage = new AssetImage();
                    $assetImage->asset_id = $asset->id;
                    $assetImage->image_path = $uid;
                    $assetImage->save();
                }

                return redirect()->back()->with(['flash_message' => 'Successfully uploaded your images.']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['errors' => ['Apologies, we are unable to upload your media files at the moment.' . $ex->getMessage()]]);
        }

    }

    public function logout(Request $request)
    {
        \session()->flush();
        $request->attributes->add(['user' => null]);
        return redirect('/admin/login');
    }
}
