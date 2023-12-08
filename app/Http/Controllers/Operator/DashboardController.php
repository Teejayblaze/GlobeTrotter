<?php

namespace App\Http\Controllers\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AssetUploadValidator;
use App\Asset;
use App\AssetBooking;
use App\AssetImage;
use App\Keywords;
use App\AssetType;
use App\Individual;
use App\Corporate;
use App\UserCredential;
use App\AssetProximity;
use App\Transaction;
use App\AssetPromo;
use App\MaterialUpload;
use App\Operator;
use Carbon\Carbon;
use \App\PlatformSettings;
use Illuminate\Support\Facades\Storage;
use App\Providers\ActivityEventNotifier;
use App\Http\Traits\LoggerTrait;
use \App\Http\Traits\UserProfileTrait;
use \App\Http\Traits\TransactionTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use File;

class DashboardController extends Controller
{
    //
    use LoggerTrait, UserProfileTrait, TransactionTrait;

    public function dashboard()
    {
        $user = \Request::get('user');
        return view('operator.dashboard')->with([
            'uploaded_asset_count' => $this->countUploadedAsset(),
            'ordered_asset_count' => $this->countOrderedAsset(),
            'vacant_asset_count' => ($this->countUploadedAsset() - $this->countOrderedAsset()),
            'user' => $user,
        ]);
    }



    public function assetupload_form(AssetType $assetType, Keywords $keywords)
    {
        // dd(\Hash::make("myglobe"));
        $user = \Request::get('user');
        $id = Asset::max('id');
        $oaan_number = $user->oaan_number ? $user->oaan_number : $user->work_with->oaan_number;
        return view('operator.assetupload')->with([
            'asset_types' => $assetType->all(),
            'keywords' => $keywords->all(),
            'user' => $user,
            'asset_name' => $oaan_number . '/' .  str_pad(($id + 1), 4, "0", STR_PAD_LEFT),
        ]);
    }


    // AssetUploadValidator $validator, Asset $asset
    public function create_assetupload(Request $request, Asset $asset)
    {
        // create asset validation.
        $user = \Request::get('user');
        $validator = Validator::make($request->all(), [
            'asset_name' => 'required|unique:assets,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->getMessageBag()->toArray(), 'success' => null]);
        }

        $pixel_resolution_width = $request->pixel_resolution_width ? $request->pixel_resolution_width : null;
        $pixel_resolution_height = $request->pixel_resolution_height ? $request->pixel_resolution_height : null;

        $pixel_resolution = null;
        if ($pixel_resolution_width && $pixel_resolution_height)
            $pixel_resolution = $pixel_resolution_width . 'm x ' . $pixel_resolution_height . 'm';

        list($min, $max) = explode(';', $request->price_range);
        $asset->name = $request->asset_name;
        $asset->location_state = $request->asset_location_state;
        $asset->location_lga = $request->asset_location_lga;
        $asset->location_lcda = $request->asset_location_lcda;
        $asset->location = $request->asset_location_latitude . ',' . $request->asset_location_longitude;
        $asset->address = $request->asset_location_address;
        $asset->min_price = $min;
        $asset->max_price = $max;
        $asset->asset_type = $request->asset_type;
        $asset->asset_dimension_width = $request->asset_dimension_width;
        $asset->asset_dimension_height = $request->asset_dimension_height;
        $asset->print_dimension = $request->print_dimension ? $request->print_dimension : null;
        $asset->pixel_resolution = $pixel_resolution;

        $asset->file_format = $request->file_format ? $request->file_format : null;
        if($request->file_format === 'others') {
            $asset->file_format = $request->other_file_format;
        }

        $asset->file_size = $request->file_size ? $request->file_size : null;
        $asset->apply_promo = $request->apply_promo ? $request->apply_promo : 'NO';
        $asset->substrate = $request->substrate ? $request->substrate : null;

        $asset->num_slides = $request->number_of_slots ? $request->number_of_slots : null;
        if($request->number_of_slots === 'others') {
            $asset->num_slides = $request->number_of_slots_others;
        }

        $asset->num_slides_per_secs = $request->number_of_seconds_per_slot ? $request->number_of_seconds_per_slot : null;
        if($request->number_of_seconds_per_slot === 'others') {
            $asset->num_slides_per_secs = $request->number_of_seconds_per_slot_others;
        }

        $asset->advert_type = $request->advert_type ? $request->advert_type : null;
        $asset->orientation = $request->orientation ? $request->orientation : null;
        if($request->orientation === 'others') {
            $asset->orientation = $request->asset_orientation_others;
        }
        $asset->asset_category = $request->asset_category;
        $asset->face_count = $request->face_count ? $request->face_count : 1;
        $asset->payment_freq = $request->payment_freq;

        $asset->uploaded_by = $user->user_id;


        try {

            if ($request->hasFile('asset_video')) {
                $asset_video = $request->file('asset_video');
                $uid = Storage::putFile('public/operator/video', $asset_video);
                $asset->video_path = $uid;
            }

            if ($asset->save()) {

                $aid = $asset->id;

                $proximities = json_decode($request->proximity, true);
                foreach ($proximities as $proximity_key => $proximity_value) {
                    AssetProximity::create([
                        'proximity_type' => ucwords(str_replace('_', ' ', $proximity_key)),
                        'proximity_name' => $proximity_value,
                        'asset_id' => $aid,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                }

                $promo_festive_periods = json_decode($request->promo_festive_periods, true);
                $promo_price_range = json_decode($request->promo_price_range, true);
                if (count($promo_festive_periods)) {
                    $i = 0;
                    foreach ($promo_festive_periods as $key => $value) {
                        AssetPromo::create([
                            'festive_name' => $value,
                            'price_range' => $promo_price_range['promo_price_range_' . $i],
                            'festive_date' => '',
                            'asset_id' => $aid,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ]);
                        $i++;
                    }
                }

                if ($request->hasFile('asset_images')) {

                    $asset_images = $request->file('asset_images');

                    foreach ($asset_images as $key => $asset_image) {

                        $uid = Storage::putFile('public/operator', $asset_image);

                        $assetImage = new AssetImage();
                        $assetImage->asset_id = $aid;
                        $assetImage->image_path = $uid;
                        $assetImage->save();
                    }

                    broadcast(new ActivityEventNotifier('asset', 'upload', [
                        'asset_name' => $request->asset_name,
                        'asset_price' => 'Price Ranges (Min: ' . $min . ',  Max: ' . $max . ')',
                        'msg' => 'Asset <strong>' . $request->asset_name . '</strong> created successfully.'
                    ]));
                }
                return response()->json(['status' => true, 'errors' => null, 'success' => 'Asset Created Successfully.']);
            } else {
                return response()->json(['status' => false, 'errors' => 'Apologies, we are currently unable to create your asset at the moment.', 'success' => null]);
            }
        } catch (\Illuminate\Database\QueryException $qe) {
            $this->log('Critical', $qe->getMessage());
        }
    }

    public function logout()
    {
        Auth::logout();
        \session()->flush();
        return redirect('/operator/login');
    }

    private function countUploadedAsset()
    {
        $user = \Request::get('user');
        return Asset::where('uploaded_by', $user->user_id)->count();
    }

    private function countOrderedAsset()
    {
        $user = \Request::get('user');
        $operator_assets = Asset::where('uploaded_by', '=', $user->user_id)->get();
        $count = 0;
        $now = Carbon::now()->format('Y-m-d');
        foreach ($operator_assets as $key => $operator_asset) {
            $asset_bookings = $operator_asset->assetBookingsRecords()->where([
                ['asset_bookings.asset_id', '=', $operator_asset->id],
                ['asset_bookings.end_date', '>=', $now]
            ])->first();

            if ($asset_bookings) $count++;
        }
        return $count;
    }

    public function vacant_asset()
    {
        $user = \Request::get('user');
        $vacant_assets = [];
        $assets = Asset::where('uploaded_by', $user->user_id)->get();
        foreach ($assets as $key => $asset) {
            $assetBooking = AssetBooking::where('asset_id', $asset->id)->first();
            if ($assetBooking) continue;
            $asset->asset_typex = $asset->assetTypeRecord->type;
            $asset->proximities = $asset->assetProximityRecords;
            $asset->date_added = Carbon::parse($asset->created_at)->format('jS \o\f F Y');
            array_push($vacant_assets, $asset);
        }
        return view('operator.vacantasset', compact('vacant_assets', 'user'));
    }


    public function booked_asset()
    {
        $user = \Request::get('user');
        $booked_assets = [];
        $now = Carbon::now()->format('Y-m-d');
        $assets = Asset::where('uploaded_by', $user->user_id)->get();
        foreach ($assets as $key => $asset) {
            $assetBookings =  $asset->assetBookingsRecords()->where([
                ['asset_bookings.asset_id', '=', $asset->id],
                ['asset_bookings.end_date', '>=', $now]
            ])->get();
            if (count($assetBookings)) {
                foreach ($assetBookings as $key => $assetBooking) {
                    $assetBooking->payments = $this->get_paid_receipts($assetBooking)->get();
                    $assetBooking->pending_payments = $this->get_pending_receipts($assetBooking)->get();
                    $assetBooking->paid_count = $this->get_paid_receipts($assetBooking)->count();
                    $assetBooking->pending_count = $this->get_pending_receipts($assetBooking)->count();
                    $assetBooking->asset_typex = $asset->assetTypeRecord->type;
                    $assetBooking->name = $asset->name;
                    array_push($booked_assets, $assetBooking);
                }
            }
        }

        // dd($booked_assets);
        return view('operator.bookedasset', compact('booked_assets', 'user'));
    }


    public function total_asset()
    {
        $user = \Request::get('user');
        $total_assets = [];
        $assets = Asset::where('uploaded_by', $user->user_id)->get();
        $now = Carbon::now()->format('Y-m-d');
        foreach ($assets as $key => $asset) {
            $assetBooking = AssetBooking::where('asset_id', $asset->id)->where([
                ['asset_id', '=', $asset->id],
                ['end_date', '>=', $now]
            ])->first();
            $asset->status = "vacant";
            if ($assetBooking) {
                $asset->status = "booked";
            }
            $asset->asset_typex = $asset->assetTypeRecord->type;
            $asset->proximities = $asset->assetProximityRecords;
            $asset->date_added = Carbon::parse($asset->created_at)->format('jS \o\f F Y');
            array_push($total_assets, $asset);
        }
        return view('operator.totalasset', compact('total_assets', 'user'));
    }


    public function subscription_view()
    {
        $title = "Operator Subscriptions";
        $user = \Request::get('user');
        $settings = PlatformSettings::find(1);
        $transaction = Transaction::where(['subscription' => 1, 'subscribed_by' => $user->id, 'asset_booking_id' => date('Y')])->first();

        \session()->put('transaction', $transaction);
        return view('operator.subscription', compact('user', 'title'))->with([
            'uploaded_asset_count' => $this->countUploadedAsset(),
            'ordered_asset_count' => $this->countOrderedAsset(),
            'subscription_amount' => number_format($settings->subscription, 2, '.', ','),
            'vacant_asset_count' => ($this->countUploadedAsset() - $this->countOrderedAsset()),
        ]);
    }

    public function subscription(Request $request)
    {
        $user = \Request::get('user');

        $validator = Validator::make($request->all(), [
            'oaan_number' => 'required',
            'subscription_amount' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $booking_id = date('Y');
        $payable = $request->subscription_amount;
        $oaan_number = $user->oaan_number;

        if($payable <= 0) {
            return redirect()->back()->withErrors(['errors' => ['Subscription amount should be greater than zero.']])->withInput();
        }

        $desc = "Generated 100% (". $payable . ") annual subscription payment for ". $user->corporate_name ." with a member id of ". $oaan_number;
        $payperc = '100%';
        $first_installment = 1;

        $transaction = $this->generate_transaction($booking_id, $payable, $desc, 'subscription', $payperc, $first_installment);
        if ( $transaction ) {
            $transaction->subscription = 1;
            $transaction->subscribed_by = $user->id;
            $transaction->save();
            \session()->put('transaction', $transaction);
            return redirect()->back();
        }
        // return redirect()->back()->withInput()->with('success', 'Successfully made your subscriptions.');
    }

    public function regenerateTransactionReference(string $booking_id, string $id)
    {
        if($tranx_ref = $this->regenerate_transaction_id($booking_id)) {
            if($this->update_transaction(['id' => $id], ['tranx_id' => $tranx_ref])) {
                return redirect()->back()->with(["flash_message" => "Regenerated merchant transaction reference."]);
            }
        }
    }

    public function staffs_view()
    {
        $title = "Operator Staff";
        $user = \Request::get('user');
        $staffs = Individual::where([['operator', '=', $user->operator], ['corp_id', '=', $user->user_id]])->get();
        return view('operator.staffs', compact('staffs', 'user', 'title'));
    }

    public function edit_staffs_view(string $id)
    {
        $title = "Editing Operator Staff";
        $user = \Request::get('user');
        $edit_user = Individual::where([['id', '=', $id], ['operator', '=', $user->operator], ['corp_id', '=', $user->user_id]])->first();
        return view('operator.editstaff', compact('edit_user', 'user', 'title'))->with([
            'uploaded_asset_count' => $this->countUploadedAsset(),
            'ordered_asset_count' => $this->countOrderedAsset(),
            'vacant_asset_count' => ($this->countUploadedAsset() - $this->countOrderedAsset()),
        ]);
    }

    public function create_staff_view()
    {
        $user = \Request::get('user');
        return view('operator.createstaff')->with([
            'uploaded_asset_count' => $this->countUploadedAsset(),
            'ordered_asset_count' => $this->countOrderedAsset(),
            'vacant_asset_count' => ($this->countUploadedAsset() - $this->countOrderedAsset()),
            'user' => $user,
        ]);
    }

    public function create_staff(Request $request)
    {
        $user = \Request::get('user');
        $validator = Validator::make($request->all(), [
            'firstname' => 'bail|required',
            'lastname' => 'bail|required',
            'password' => 'bail|required',
            'email' => 'bail|required|email|unique:operators',
            'phone' => 'bail|required|unique:operators',
            'address' => 'bail|required',
            'designation' => 'bail|required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        list($email_name, $email_domain) = explode('@', $request->email);
        list($admin_email_name, $admin_email_domain) = explode('@', $user->email);

        if ($email_domain !== $admin_email_domain) {
            return redirect()
                ->back()
                ->withErrors([
                    'errors' => [
                        'Email Address domain provided along with the email does not match the corporate email address domain ' .
                            'kindly check the provided email address.'
                    ]
                ])
                ->withInput();
        }

        $individual = new Individual;
        $individual->firstname = $request->firstname;
        $individual->lastname = $request->lastname;
        $individual->address = $request->address;
        $individual->phone = $request->phone;
        $individual->email = $request->email;
        $individual->designation = $request->designation;
        $individual->token = base64_encode($request->email);
        $individual->corp_id = $user->id;
        $individual->operator = $user->operator;
        $individual->blocked = $user->blocked;
        $individual->email_verified = $user->email_verified;
        $individual->tandc = 1;
        $individual->email_verified_at = Carbon::now();
        $individual->active = $user->active;

        try {
            if ($individual->save()) {

                $credential = new UserCredential;
                $credential->email = $request->email;
                $credential->password = \bcrypt($request->password);
                $credential->user_id = $individual->id;
                $credential->user_type_id = 2;
                $credential->operator = $user->operator;
                $credential->admin = 0;

                try {
                    if ($credential->save()) {
                        return redirect()->back()->with('create-success', 'Successfully created ' . $request->lastname . ' ' . $request->firstname . ' as a staff.');
                    }
                } catch (QueryException $qe) {
                    $this->log('Critical', $qe->getMessage());
                }
            }
        } catch (QueryException $qe) {
            $this->log('Critical', $qe->getMessage());
        }
    }
    

    public function edit_staff(Request $request)
    {
        $user = \Request::get('user');
        $validator = Validator::make($request->all(), [
            'firstname' => 'bail|required',
            'lastname' => 'bail|required',
            'email' => 'bail|required|email|unique:operators',
            'phone' => 'bail|required|unique:operators',
            'address' => 'bail|required',
            'designation' => 'bail|required',
            'indv_id' => 'bail|required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        list($email_name, $email_domain) = explode('@', $request->email);
        list($admin_email_name, $admin_email_domain) = explode('@', $user->email);

        if ($email_domain !== $admin_email_domain) {
            return redirect()
                ->back()
                ->withErrors([
                    'errors' => [
                        'Email Address domain provided along with the email does not match the corporate email address domain ' .
                            'kindly check the provided email address.'
                    ]
                ])
                ->withInput();
        }

        $individual = Individual::find($request->indv_id);
        $individual->firstname = $request->firstname;
        $individual->lastname = $request->lastname;
        $individual->address = $request->address;
        $individual->phone = $request->phone;
        $individual->email = $request->email;
        $individual->designation = $request->designation;
        $individual->token = base64_encode($request->email);
        $individual->corp_id = $user->id;
        $individual->operator = $user->operator;
        $individual->blocked = $user->blocked;
        $individual->email_verified = $user->email_verified;
        $individual->active = $user->active;

        try {
            if ($individual->save()) {

                $credential = UserCredential::where(['user_id' => $individual->id])->first();
                $credential->email = $request->email;
                $credential->operator = $user->operator;

                try {
                    if ($credential->save()) {
                        return redirect()->back()->with('flash_message', 'Successfully updated ' . $request->lastname . ' ' . $request->firstname . ' Details.');
                    }
                } catch (QueryException $qe) {
                    $this->log('Critical', $qe->getMessage());
                }

            }
        } catch (QueryException $qe) {
            $this->log('Critical', $qe->getMessage());
        }
    }

    public function view_edit_asset_video(Request $request)
    {
        $id = $request->id;
        $user = \Request::get('user');
        $operator_assets = Asset::where(['id' => $id, 'uploaded_by' => $user->user_id])->first();
        return view('operator.asset-edit-video', compact('operator_assets', 'user'));
    }

    public function edit_asset_video(Request $request)
    {
        $id = $request->id;
        $user = \Request::get('user');
        $operator_assets = Asset::where(['id' => $id, 'uploaded_by' => $user->user_id])->first();
        if($operator_assets) {
            try {
                if ($request->hasFile('asset_video')) {
                    $asset_video = $request->file('asset_video');
                    $uid = Storage::putFile('public/operator/video', $asset_video);
                    $operator_assets->video_path = $uid;
                    $operator_assets->save();
                    return redirect()->back()->with('flash_message', 'Successfully added video to the editted asset.');
                }
                else {
                    return redirect()->back()->withErrors(['errors' => ['No video file selected for upload or Format not supported!.']]);
                }
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['errors' => ['Video Format not supported!.']]);
            }
        }
        return redirect()->back()->withErrors(['errors' => ['Unable to add video to the editted asset.']]);
    }


    public function payment_history()
    {
        $user = \Request::get('user');

        $assetIds = array_column(Asset::select('id')->where(['uploaded_by' => $user->user_id])->get()->toArray(),'id');
        $asset_booking_recs = AssetBooking::whereIn('asset_id', $assetIds)->orderBy('updated_at', 'desc')->get();

        foreach($asset_booking_recs as $key => &$asset_booking_rec) {
            $asset_booking_rec->transaction = Transaction::where([
                'asset_booking_id' => $asset_booking_rec->id, 
                // 'disbursed' => 1
            ])->orderBy('updated_at', 'desc')->get();
        }

        // dd($assetIds, $asset_booking_recs);
        return view('operator.payment-history', compact('asset_booking_recs', 'user'));

    }


    public function bank_account_setup()
    {
        $user = \Request::get('user');
        $operator = Operator::find($user->id);
        return view('operator.bank-account-setup')->with([
            'uploaded_asset_count' => $this->countUploadedAsset(),
            'ordered_asset_count' => $this->countOrderedAsset(),
            'vacant_asset_count' => ($this->countUploadedAsset() - $this->countOrderedAsset()),
            'user' => $user,
            'operator' => $operator,
        ]);
    }
   
   
    public function update_bank_account_setup(Request $request)
    {
        $user = \Request::get('user');
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'account_name' => 'required',
            'account_number' => 'required|numeric',
            'bank_code' => 'required|numeric',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        try{

            $operator = Operator::find($user->id);
            $operator->account_name = $request->account_name;
            $operator->account_number = $request->account_number;
            $operator->bank_code = $request->bank_code;
            $operator->address = $request->address;
            $operator->phone = $request->phone;
            $operator->email = $request->email;

            if($operator->save()) {
                return redirect()->back()->with(['flash_message' => 'Successfully modified your bank account setup']);
            }
        } catch(\Exception $e){
            return redirect()->back()->withErrors(['errors' => [$e->getMessage()]])->withInput();
        }
    }

    public function materials()
    {
        $title = "Uploaded Materials";
        $user = \Request::get('user');

        $materials = [];
        $ids = Asset::where(['uploaded_by' => $user->id])->select("id")->get()->toArray();
        // 
        if(count($ids)) {
            $ids = array_column($ids, 'id');

            $asset_bookings = AssetBooking::whereIn('asset_id', $ids)->select('id', 'trnx_id')->get()->toArray();
            if(count($asset_bookings)) {
                $ids = array_column($asset_bookings, 'id');
                $ref = array_column($asset_bookings, 'trnx_id');
                $materials = MaterialUpload::whereIn('asset_booking_id', $ids)->whereIn('booking_ref', $ref)->get();
                foreach ($materials as $key => $material) {
                    if($material->user_type_id === 2) {
                        $indv = Individual::find($material->uploaded_by_user_id);
                        $material->name = $indv->lastname . ' ' . $indv->firstname;
                    }
                    else if($material->user_type_id === 1) {
                        $corps = Corporate::find($material->uploaded_by_user_id);
                        $material->name = $corps->name;
                    }
                }
            }
        }

        return view('operator.materials', compact('materials', 'user', 'title'));
    }

    public function material_download($id = null)
    {
        try {
            $material = MaterialUpload::find($id);
            $medias = explode(',', $material->media);
            if(!count($medias)) {
                return redirect()->back()->withErrors(['errors' => ['Uploaded material has no media content, hence you can not download.']]);
            }
    
            array_walk($medias, function(&$v, $k){ $v = File::files(public_path($v)); });

            $zip = new \ZipArchive();
            $zip_name = "media-files-".time().".zip";
            if($zip->open($zip_name, \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
                foreach ($medias as $key => $media) {
                    $zip->addFile($media);
                }
                $zip->close();
            }
            // Storage::putFile("public/materials/download/", $zip_name);
            // $zip_name = Storage::url($zip_name);
            dd($zip_name);
            // return response()->download($zip_name);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->withErrors(['errors' => ['Apologies, there is a glitch while trying to download uploaded files by the advertiser.']]);
        }
    }
}
