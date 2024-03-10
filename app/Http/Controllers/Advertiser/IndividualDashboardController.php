<?php

namespace App\Http\Controllers\Advertiser;

use Illuminate\Http\Request;
use \App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use \App\Http\Traits\TransactionTrait;
use \App\Http\Traits\UserProfileTrait;
use \App\Http\Traits\AssetTrait;
use \App\Operator;
use \App\State;
use \App\LGA;
use \App\AssetBooking;
use \App\Individual;
use \App\Corporate;
use \App\AssetType;
use \App\Transaction;
use \App\MaterialUpload;
use \App\TransactionToken;
use \App\FastTrack;
use \App\Campaign;
use \App\CampaignDetail;
use \App\PlatformSettings;
use Carbon\Carbon;
use \App\Asset;
use Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use \App\Http\Traits\LoggerTrait;
use \App\Http\Traits\GenericThirdPartyRequestTrait;
use \DateTime;

class IndividualDashboardController extends Controller
{
    //
    use TransactionTrait, UserProfileTrait, LoggerTrait, AssetTrait, GenericThirdPartyRequestTrait;

    public function dashboard()
    {
        $title = "Dashboard";

        $user = \Request::get('user');

        $pending_tranx_count = $this->get_pending_payment_transaction_count();

        $paid_tranx_count = $this->get_paid_payment_transaction_count();

        $booked_asset_count = $this->get_booked_asset_count();

        $tokens = $this->get_transaction_tokens($user->corp_id);

        return view('advertiser.dashboard', compact('pending_tranx_count', 'paid_tranx_count', 'booked_asset_count', 'user', 'tokens', 'title'));
    }

    public function logout()
    {
        Auth::logout();
        \session()->flush();
        return redirect('/advertiser/login');
    }

    public function pending_transactions(string $type = 'single')
    {
        $user = \Request::get('user');
        $fields = ['type' => $type];
        $pending_tranx_recs = [];
        $campaigns = [];
        if ($type === env('CAMPAIGN_BOOKING_TYPE')) {
            $campaigns = $this->get_campaign_transactions($user);
        } else {
            $pending_tranx_recs = $this->get_transaction_receipt(0, 0, $fields);
        }
        $title = "Pending Transactions";
        return view('advertiser.pendingtransaction', \compact('campaigns', 'pending_tranx_recs', 'user', 'title', 'type'));
    }


    public function delete_pending_transactions(string $booking_id, string $booking_type = '', string $booking_type_id = '')
    {
        if (!$booking_id) {
            return redirect()->back()->with(["flash_error" => "Booking reference was not provided."]);
        }
        $assetBooking = AssetBooking::find($booking_id);
        $trnx_id = $assetBooking->trnx_id;
        $asset_id = $assetBooking->asset_id;
        $assetBooking->delete();
        Transaction::where("asset_booking_ref", $trnx_id)->delete();
        if ($booking_type === env('CAMPAIGN_BOOKING_TYPE') && $booking_type_id) {
            CampaignDetail::where(['campaign_id' => $booking_type_id, 'asset_id' => $asset_id])->delete();
        }
        return redirect()->back()->with(["flash_message" => "Successfully deleted the booking."]);
    }


    public function pending_transaction_payments_detail(string $type = 'single', int $booking_id = 0)
    {
        $user = \Request::get('user');
        $fields = ['type' => $type];

        $asset_pending_recs = [];
        $campaign = null;
        if ($type === env('CAMPAIGN_BOOKING_TYPE')) {
            $campaign = $this->get_campaign_by_id($booking_id);
        } else {
            $asset_pending_recs = $this->get_transaction_receipt(0, $booking_id, $fields);
        }

        $userInfo = new \stdClass();
        $userInfo->designation = "Manager";
        if ($user->user_type_id === intval(env('INDIVIDUAL_USER_TYPE'))) {

            if ($user->corp_id) {
                $corporate = Corporate::find($user->corp_id);
                $userInfo->name = $corporate->name;
                $userInfo->address = $corporate->address;
                $userInfo->phone = $corporate->phone;
                $userInfo->email = $corporate->email;
            } else {
                $individual = Individual::find($user->id);
                $userInfo->name = $individual->lastname . ' ' . $individual->firstname;
                $userInfo->address = $individual->address;
                $userInfo->phone = $individual->phone;
                $userInfo->email = $individual->email;
                $userInfo->designation = $individual->designation;
            }
        }
        $operator = new \stdClass();
        if (count($asset_pending_recs)) {
            $operatorId = $asset_pending_recs[0]->asset->uploaded_by;
            $operatorRec = $this->get_operator_by_id($operatorId);
            $operator->corporateName = $operatorRec->corporate_name;
            $operator->address = $operatorRec->address ? $operatorRec->address : $operatorRec->oaan_number . ', ' . $operatorRec->email . ', ' . $operatorRec->phone;
            $operator->designation = "CEO";
        }

        // dd($user, $userInfo, $operator, env('INDIVIDUAL_USER_TYPE'));
        $title = "Transaction Payment Details";
        return view('advertiser.transactionpaymentsdetail', \compact('campaign', 'asset_pending_recs', 'user', 'title', 'userInfo', 'operator', 'type'));
    }


    public function paid_transaction_payments_detail(int $booking_id = 0)
    {
        $user = \Request::get('user');
        $paid_tranx_recs = $this->get_transaction_receipt(1, $booking_id);
        $title = "Paid Transaction Details";
        return view('advertiser.paidtransactionpaymentsdetail', \compact('paid_tranx_recs', 'user', 'title'));
    }

    public function payment_history()
    {
        $user = \Request::get('user');
        $asset_booking_recs = AssetBooking::where([
            'booked_by_user_id' => $user->user_id,
            'user_type_id' => $user->user_type_id
        ])->get();

        $title = "Payment History";

        foreach ($asset_booking_recs as $key => &$asset_booking_rec) {
            $asset_booking_rec->transaction = Transaction::where(['asset_booking_id' => $asset_booking_rec->id])->get();
        }
        return view('advertiser.payment-history', \compact('asset_booking_recs', 'user', 'title'));
    }

    public function transaction_history()
    {
        $user = \Request::get('user');
        $title = "Historical Transactions";
        $paid_tranx_recs = $this->get_transaction_receipt(1, 0);
        return view('advertiser.transactionhistory', \compact('paid_tranx_recs', 'user', 'title'));
    }

    public function myassets()
    {
        $user = \Request::get('user');
        $asset_recs = AssetBooking::where([
            ['booked_by_user_id', '=', $user->id],
            ['user_type_id', '=', $user->user_type_id]
        ])->paginate(1);
        $title = "My Booked Assets";
        return view('advertiser.myassets', \compact('asset_recs', 'user', 'title'));
    }

    public function edit_profile_view()
    {
        $user = \Request::get('user');
        $edit_user = Individual::where('id', '=', $user->id)->first();
        $edit_user->user_type_id = $user->user_type_id;
        $title = "Edit Profile";

        $pending_tranx_count = $this->get_pending_payment_transaction_count();

        $paid_tranx_count = $this->get_paid_payment_transaction_count();

        $booked_asset_count = $this->get_booked_asset_count();

        return view('advertiser.editprofile', \compact('user', 'edit_user', 'pending_tranx_count', 'paid_tranx_count', 'booked_asset_count', 'title'));
    }

    public function edit_corporate_profile_view()
    {
        $user = \Request::get('user');
        $edit_corp = Corporate::where('id', '=', $user->corp_id)->first();
        $edit_corp->corp_id = $user->corp_id;

        $pending_tranx_count = $this->get_pending_payment_transaction_count();

        $paid_tranx_count = $this->get_paid_payment_transaction_count();

        $booked_asset_count = $this->get_booked_asset_count();

        $title = "Edit Corporate Profile";

        return view('advertiser.editcompanyprofile', \compact('user', 'edit_corp', 'pending_tranx_count', 'paid_tranx_count', 'booked_asset_count', 'title'));
    }

    public function edit_profile(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'firstname' => 'bail|required|min:5',
            'lastname' => 'bail|required|min:5',
            'email' => 'bail|required|email',
            'phone' => 'bail|required',
            'address' => 'bail|required|filled',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user_id = $request->user_id;
        $user_type_id = $request->user_type_id;

        $user_cred = \App\UserCredential::where([['user_id', '=', $user_id], ['user_type_id', '=', $user_type_id]])->first();
        if ($user_cred) {

            if ($user_cred->email !== $request->email) {
                $user_cred->email = $request->email;
                $user_cred->save();
            }

            $individual = Individual::where('id', '=', $user_id)->first();

            if ($individual) {

                $individual->firstname = $request->firstname;
                $individual->lastname = $request->lastname;
                $individual->email = $request->email;
                $individual->phone = $request->phone;
                $individual->address = $request->address;
                try {
                    if ($individual->save()) {
                        // $this->set_user_profile();
                        return redirect()->back()->with('edit-success', 'Successfully modified your profile record.');
                    } else {
                        return redirect()->back()->withErrors(['errors' => ['Apologies, unable to modify your profile at the moment.']]);
                    }
                } catch (\Illuminate\Database\QueryException $qex) {
                    $this->log('Critical', $qex->getMessage()); // Log the exception here.
                }
            }
        }
    }

    public function edit_corporate_profile(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|min:5',
            'website' => 'bail|required|regex:/^((http(s)?)\:\/\/)?(www\.)?[A-za-z\-\_]+(.)?[A-Za-z]+$/i',
            'email' => 'bail|required|email',
            'phone' => 'bail|required',
            'address' => 'bail|required',
            'rc_number' => 'bail|required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $corporate_user = Corporate::where('id', '=', $request->corp_id)->first();

        if ($corporate_user) {
            $corporate_user->name  = $request->name;
            $corporate_user->website = $request->website;
            $corporate_user->rc_number = $request->rc_number;
            $corporate_user->address = $request->address;
            $corporate_user->phone = $request->phone;
            $corporate_user->email = $request->email;

            try {
                if ($corporate_user->save()) {
                    return redirect()->back()->with('corporate-edit-success', 'Successfully modified your profile record.');
                } else {
                    return redirect()->back()->withErrors(['errors' => ['Apologies, unable to modify your profile at the moment.']]);
                }
            } catch (\Illuminate\Database\QueryException $qex) {
                $this->log('Critical', $qex->getMessage()); // Log the exception here.
            }
        }
    }

    public function change_password_view()
    {
        $user = \Request::get('user');

        $title = "Change Password";

        $pending_tranx_count = $this->get_pending_payment_transaction_count();

        $paid_tranx_count = $this->get_paid_payment_transaction_count();

        $booked_asset_count = $this->get_booked_asset_count();

        return view('advertiser.changepassword', \compact('user', 'pending_tranx_count', 'paid_tranx_count', 'booked_asset_count', 'title'));
    }

    public function change_password(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'previous_password' => 'bail|required',
            'new_password' => 'bail|required|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user_id = $request->user_id;
        $user_type_id = $request->user_type_id;
        $previous_password = $request->previous_password;
        $new_password = $request->new_password;

        $user_cred = \App\UserCredential::where([
            ['user_id', '=', $user_id],
            ['user_type_id', '=', $user_type_id],
        ])->first();

        if (Hash::check($previous_password, $user_cred->password)) {

            $user_cred->password = bcrypt($new_password);
            try {
                if ($user_cred->save()) {
                    return redirect()->back()->with('edit-success', 'Successfully modified your password.');
                } else {
                    return redirect()->back()->withErrors(['errors' => ['Apologies, unable to modify your password at the moment.']]);
                }
            } catch (\Illuminate\Database\QueryException $qex) {
                $this->log('Critical', $qex->getMessage()); // Log the exception here.
            }
        }

        return redirect()->back()->withInput()->withErrors(['errors' => ['Apologies, the supplied previous password does not match our database record.']]);
    }


    public function material_view()
    {
        $title = "Upload Material";
        $user = \Request::get('user');
        $asset_bookings = AssetBooking::where([
            ['booked_by_user_id', '=', $user->id],
            ['user_type_id', '=', $user->user_type_id]
        ])->get();

        $check10perc = true;
        foreach ($asset_bookings as $key => $asset_booking) {
            $trans_with_10_perc = $this->get_paid_receipts($asset_booking, $check10perc)->first();
            if (!$trans_with_10_perc) {
                unset($asset_bookings[$key]);
            }
        }

        // dd($asset_bookings);
        $pending_tranx_count = $this->get_pending_payment_transaction_count();

        $paid_tranx_count = $this->get_paid_payment_transaction_count();

        $booked_asset_count = $this->get_booked_asset_count();

        $materials = MaterialUpload::where([
            'uploaded_by_user_id' => $user->id,
            'user_type_id' => $user->user_type_id
        ])->get();

        return view('advertiser.material', \compact('user', 'asset_bookings', 'title', 'pending_tranx_count', 'paid_tranx_count', 'booked_asset_count', 'materials'));
    }

    public function material(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'asset_bookings' => 'bail|required',
            'upload_name' => 'bail|required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $asset_bookings = explode('=>', $request->asset_bookings);

            $booking_id = $asset_bookings[0];
            $booking_ref = $asset_bookings[1];

            $user = \Request::get('user');

            if ($request->hasFile('asset_medias')) {
                $uids = [];
                $asset_medias = $request->file('asset_medias');
                foreach ($asset_medias as $key => $asset_media) {
                    $uid = Storage::putFile('public/materials', $asset_media);
                    array_push($uids, $uid);
                }

                if (count($uids)) {

                    $material = MaterialUpload::where([
                        'asset_booking_id' => $booking_id,
                        'booking_ref' => $booking_ref,
                        'uploaded_by_user_id' => $user->id,
                        'user_type_id' => $user->user_type_id
                    ])->first();

                    if ($material) {
                        $material->upload_name = $request->upload_name;

                        if ($material->media) {
                            $material->media = $material->media . ',' . implode(',', $uids);
                        } else {
                            $material->media = implode(',', $uids);
                        }

                        $material->save();
                    } else {

                        $material = new MaterialUpload;
                        $material->asset_booking_id = $booking_id;
                        $material->booking_ref = $booking_ref;
                        $material->uploaded_by_user_id = $user->id;
                        $material->user_type_id = $user->user_type_id;
                        $material->upload_name = $request->upload_name;
                        $material->media = implode(',', $uids);
                        $material->save();
                    }

                    return redirect()->back()->with('upload-material-success', 'Successfully uploaded advert materials to the asset owner.');
                } else {
                    return redirect()->back()->withErrors(['errors' => ["Apologies, we couldn't find the file(s) to be uploaded."]]);
                }
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['errors' => ["Apologies, we unable to upload your file(s)."]]);
        }
    }

    public function advanced_search_view()
    {
        $user = \Request::get('user');
        $asset_types = AssetType::all();
        $operators = Operator::orderBy('corporate_name', 'ASC')->get();
        $title = "Search";
        return view('advertiser.advancesearch', \compact('user', 'asset_types', "title", "operators"));
    }

    public function advanced_search_result_view(Request $request)
    {
        $user = \Request::get('user');
        $asset_types = AssetType::all();


        $search = $this->search_asset($request, null);
        $title = "Search Result";
        return view('advertiser.advancesearchresult', \compact('user', 'asset_types', 'title'))->with($search);
    }

    public function search_site_campaign(Request $request)
    {
        // sleep(50);
        $asset_result = $this->search_asset($request);
        $available_asset = $asset_result['available_asset'];
        if (count($available_asset)) return response()->json(['status' => true, 'errors' => null, 'success' => $available_asset]);

        return response()->json(['status' => false, 'errors' => 'No result found.', 'success' => null]);
    }

    private function search_asset(Request $request, string $name = null)
    {
        $assets = null;
        $asset_category_count = [];
        // $assets = \App\Asset::all()->sortByDesc('asset_type');
        $max_price = \App\Asset::avg('max_price');


        $asset_location_state = $request->asset_location_state;
        $asset_location_lga = $request->asset_location_lga;
        // $asset_location_lcda = $request->asset_location_lcda;
        $search_criteria = $request->search_criteria;
        $proximities = $request->proximity;
        $site_board_type = $request->site_board_type;
        $site_catgory = $request->site_catgory;
        $substrate = $request->substrate;
        $orientation = $request->orientation;
        $face_count = $request->face_count;
        $dimension_width = $request->asset_dimension_width;
        $dimension_height = $request->asset_dimension_height;
        // $dimension = $dimension_width . 'm x ' . $dimension_height . 'm';
        $price_range = $request->price_range;
        $payment_freq = $request->payment_freq;
        $keywords = $request->keywords;

        $filterByState = false;
        $distance_km = PlatformSettings::find(1)->distance_km;
        $latitude = $longitude = 0;
        $searchTerm = "";
        $apiKey = env("GOOGLE_MAP_API_KEY");

        \DB::enableQueryLog();

        $where_builder = [];

        // if ($asset_location_lcda) array_push($where_builder, ['location_lcda', '=', $asset_location_lcda]);
        if ($substrate) array_push($where_builder, ['substrate', '=', ucwords(strtolower($substrate))]);
        if ($orientation) array_push($where_builder, ['orientation', '=', $orientation]);
        if ($face_count) array_push($where_builder, ['face_count', '=', $face_count]);
        if ($payment_freq) array_push($where_builder, ['payment_freq', '=', $payment_freq]);

        $raw_assets = \App\Asset::query();


        if (isset($search_criteria[1]) && $request->asset_owner) {
            $asset_owner = $request->asset_owner;
            $raw_assets->where(['uploaded_by' => $asset_owner]);
        }

        if ($site_catgory && count($site_catgory)) $raw_assets->whereIn('asset_type', $site_catgory);

        if (isset($search_criteria[3])) {
            if ($asset_location_state)  $raw_assets->where([['location_state', '=', $asset_location_state]]);
            if ($asset_location_lga) $raw_assets->where([['location_lga', '=', $asset_location_lga]]);

            if ($asset_location_state || $asset_location_lga) {
                $filterByState = true;
            }
        }


        $filterByLocation = false;

        if (isset($search_criteria[4]) && isset($request->asset_location_search)) {
            $filterByLocation = true;
            $searchTerm = $request->asset_location_search;
        }

        // if ($dimension_width && $dimension_height) {
        //     $raw_assets->where([['asset_dimension_width', '>=', $dimension_width]]);
        //     $raw_assets->orWhere([['asset_dimension_width', '<=', $dimension_width]]);
        //     $raw_assets->where([['asset_dimension_height', '>=', $dimension_height]]);
        //     $raw_assets->orWhere([['asset_dimension_height', '<=', $dimension_height]]);
        // }

        if ($site_board_type && count($site_board_type)) {
            $raw_assets->whereIn('asset_category', $site_board_type);
        }


        if (count($where_builder)) {
            $raw_assets->where(function ($query) use ($where_builder) {
                foreach ($where_builder as $where) {
                    $query->where([$where]);
                }
            });
        }

        if ($keywords) {
            $start = 10000;
            if ($start) $raw_assets->whereBetween('max_price', [$start, $max_price]);
        }

        $assets = $raw_assets->orderBy('asset_type', 'DESC')->get();

        // dd($assets);

        if ($proximities) {
            $collect = new Collection;

            foreach ($assets as $key => $asset) {
                $found = $asset->assetProximityRecords()->whereIn('proximity_type', $proximities)->get();
                if (count($found)) {
                    $asset->proximities = $found;
                    $collect->add($asset);
                }
            }
            $assets = $collect;
        }

        // dd(\DB::getQueryLog());

        $currentUrl = \url()->current();

        $extract = substr($currentUrl, 0, strpos($currentUrl, '/search'));

        $asset_types_categories = [];

        if ($filterByState) {
            $state_name = State::find($asset_location_state)->state_name ?? "";
            $lga_name = LGA::where(['id' => $asset_location_lga, 'state_id' => $asset_location_state])->first()->lga_name ?? "";

            if ($state_name) {
                $searchTerm = $state_name;
            }

            if ($lga_name) {
                $searchTerm .= ',' . $lga_name;
            }
        }

        if ($filterByState || $filterByLocation) {

            $endpoint = env("GOOGLE_MAP_API") . "/geocode/json?address=" . urlencode($searchTerm) . "&key=" . urlencode($apiKey);

            $response = $this->doCurlRequest($endpoint);
            if ($response['status'] && ($data = json_decode($response['data'], true))) {
                if (count($data['results'])) {
                    $latitude = floatval(trim($data['results'][0]['geometry']['location']['lng']));
                    $longitude = floatval(trim($data['results'][0]['geometry']['location']['lat']));
                    // $latitude = floatval(trim($data['results'][0]['geometry']['location']['lat']));
                    // $longitude = floatval(trim($data['results'][0]['geometry']['location']['lng']));
                } else {
                    $filterByState = $filterByLocation = false;
                }
            } else {
                $filterByState = $filterByLocation = false;
            }
        }

        // combining the asset image to the resultset obtained from the asset query.
        foreach ($assets as $assetKey => $asset) {

            $asset_location = explode(",", $asset->location);
            $asset_longitude = floatval(trim($asset_location[0]));
            $asset_latitude = floatval(trim($asset_location[1]));

            $distance = floatval($this->calculateDistance($asset_latitude, $asset_longitude, $latitude, $longitude));

            if ($filterByState || $filterByLocation) {
                if ($distance_km && $distance <= floatval($distance_km)) {
                    $asset->type = $asset->assetTypeRecord->type;
                    $asset->images = $asset->assetImagesRecord;

                    $asset->ajax_images = array_map(function ($imagesObj) use ($extract) {
                        return $extract . \Storage::url($imagesObj['image_path']);
                    }, $asset->assetImagesRecord->toArray());

                    $asset->lcda = $asset->assetLCDARecords;

                    $asset->owner = $asset->assetOwner->corporate_name;

                    $key = strtolower($asset->asset_category) . "-" . strtolower($asset->type);
                    $asset_types_categories[$key] = $asset->type;

                    if (!isset($asset_category_count[$key])) $asset_category_count[$key] = 1;
                    else $asset_category_count[$key] += 1;
                } else {
                    unset($assets[$assetKey]);
                }
            } else {
                $asset->type = $asset->assetTypeRecord->type;
                $asset->images = $asset->assetImagesRecord;

                $asset->ajax_images = array_map(function ($imagesObj) use ($extract) {
                    return $extract . \Storage::url($imagesObj['image_path']);
                }, $asset->assetImagesRecord->toArray());

                $asset->lcda = $asset->assetLCDARecords;

                $asset->owner = $asset->assetOwner->corporate_name;

                $key = strtolower($asset->asset_category) . "-" . strtolower($asset->type);
                $asset_types_categories[$key] = $asset->type;

                if (!isset($asset_category_count[$key])) $asset_category_count[$key] = 1;
                else $asset_category_count[$key] += 1;
            }
        }


        $asset_types = array_unique(array_column($assets->toArray(), 'type'));

        // dd($assets->toArray(), $asset_category_count, $asset_types_categories);

        return [
            'available_asset' => $assets,
            'asset_category_count' => $asset_category_count,
            'asset_types_categories' => $asset_types_categories,
        ];
    }

    public function generate_nibbs_transaction_code(Request $request)
    {
        $user =  $user = \Request::get('user');
        if ($user) {
            if (($code = $this->generate_otp($user, $request->txref, $request->tid)) && $code != null) {
                return redirect()->back()->with(['otp-success' => 'Successfully Generated One Time Token.', 'code' => $code])->withInput();
            }
            return redirect()->back()->with(['otp-failed' => 'Apologies, unable to generate  One Time Token at the moment.'])->withInput();
        } else {
            return redirect()->back()->with(['otp-failed' => 'Apologies, unable to generate  One Time Token at the moment.'])->withInput();
        }
    }

    private function get_transaction_tokens(int $corp_id)
    {
        $tokens = TransactionToken::where('corp_id', '=', $corp_id)->orderBy('status', 'desc')->get();
        if (count($tokens)) {
            foreach ($tokens as $key => $token) {

                $user = $this->get_user_details($token->used_by, $token->corp_id);
                $logged_user = $this->get_user_details($token->admin_user_id, $token->corp_id);
                $corporate = $this->get_corporate_details($token->corp_id);

                $usedby_name = 'None Yet';
                $corporate_name = 'None Yet';
                $token_created_by = 'Unknown';

                if ($user) $usedby_name = $user->lastname . ' ' . $user->firstname;
                if ($corporate) $corporate_name = $corporate->name;
                if (!$token->trnx_id) $token->trnx_id = 'None Yet';
                if ($logged_user) $token_created_by = $logged_user->lastname . ' ' . $logged_user->firstname;

                $token->usedby_name = $usedby_name;
                $token->auth_by = $token_created_by;
                $token->corporate_name = $corporate_name;
            }
        }

        return $tokens;
    }


    public function create_campaign_view(int $campaign_id = 0)
    {
        $user = \Request::get('user');
        $assets = $this->getAvailableAsset();
        $asset_types = AssetType::all();

        $campaigns_found = null;

        if ($campaign_id) {
            $campaigns_found = Campaign::where('id', $campaign_id)->get();
            if (count($campaigns_found)) {
                foreach ($campaigns_found as $key => $campaign) {
                    $campaign->campaign_count = $campaign->campaignDetails()->count();
                    $campaign->start_date = date('F jS Y', strtotime($campaign->start_date));
                }
            }
        } else {
            $campaigns_found = $this->getCampaign('', $user->user_id, $user->user_type_id, $user->corp_id)->get();
            if (count($campaigns_found)) {
                foreach ($campaigns_found as $key => $campaign) {
                    $campaign->campaign_count = $campaign->campaignDetails()->count();
                    $campaign->start_date = date('F jS Y', strtotime($campaign->start_date));
                }
            }
        }

        return view('advertiser.create_campaign', compact('user', 'assets', 'asset_types', 'campaigns_found', 'campaign_id'));
    }


    private function check_campaign_site_not_available($campaign_details_found)
    {
        if ($campaign_details_found) {

            // Convert the STDClass Object to Array Data-Structure for easy access of its properties. 
            $campaign_details_found_arry = $campaign_details_found->toArray();

            /**
             * 
             * Extract the necessary features needed to 
             * determine which advert has been booked by another user thus in these campaign.
             * 
             */
            $campaign_details_found_asset_id_arry = array_column($campaign_details_found_arry, 'asset_id');
            $campaign_details_found_id_arry = array_column($campaign_details_found_arry, 'id');
            $campaign_asset_exist = AssetBooking::whereIn('asset_id', $campaign_details_found_asset_id_arry)->get();
            $campaign_asset_exist_arry = $campaign_asset_exist->toArray();
            $campaign_asset_exist_asset_id_arry = array_column($campaign_asset_exist_arry, 'asset_id');


            // Filter by the asset_id so we could know which asset is booked and which is not.
            $asset_id_intercept = array_filter($campaign_details_found_asset_id_arry, function ($value) use ($campaign_asset_exist_asset_id_arry) {
                return in_array($value, $campaign_asset_exist_asset_id_arry);
            });

            return $asset_id_intercept;
        }
    }


    private function suggest_similar_asset($booked_asset_id_arry)
    {
        $suggession_array = [];

        if (count($booked_asset_id_arry)) {

            // Retrieve Asset records in form of STDClass. 
            $asset_records = Asset::whereIn('id', $booked_asset_id_arry)->get();

            // Transform STDClass Object into Array Data-Structure for easy access of its properties.
            $asset_records_arry = $asset_records->toArray();

            // First let's extract the features we need in order to make the suggestion.
            $extract_features = [];
            $extract_features['loc_states'] = array_column($asset_records_arry, 'location_state');
            $extract_features['loc_lgas'] = array_column($asset_records_arry, 'location_lga');
            $extract_features['asset_type'] = array_column($asset_records_arry, 'asset_type');
            $extract_features['asset_id'] = array_column($asset_records_arry, 'id');

            // Let's make the suggestion to this client.
            foreach ($extract_features['loc_states'] as $key => $props) {
                $asset_records = Asset::where([
                    ['location_state', '=', $extract_features['loc_states'][$key]],
                    ['location_lga', '=', $extract_features['loc_lgas'][$key]],
                    ['asset_type', '=', $extract_features['asset_type'][$key]]
                ])->whereNotIn('id', $booked_asset_id_arry)->get();

                if (count($asset_records)) $suggession_array[$extract_features['asset_id'][$key]] = $asset_records;
            }
        }
        return $suggession_array;
    }


    public function campaign_view()
    {
        $user = \Request::get('user');
        $campaigns_found = $this->getCampaign('', $user->user_id, $user->user_type_id, $user->corp_id)->get();
        if (count($campaigns_found)) {
            foreach ($campaigns_found as $key => $campaign) {
                $campaign->campaign_count = $campaign->campaignDetails->count();
                $campaign->start_date = date('F jS Y', strtotime($campaign->start_date));
                $campaign->campaignDetails = $campaign->campaignDetails;
                $campaign_det = $campaign->campaignDetails;
                foreach ($campaign->campaignDetails as $key => $campaign_detail) {
                    $campaign_detail->assetDetails = Asset::where('id', $campaign_detail->asset_id)->first();
                    $campaign_detail->assetDetails->assetImages = $campaign_detail->assetDetails->assetImagesRecord;
                }
                $campaign->booked_asset_id_arry = $this->check_campaign_site_not_available($campaign->campaignDetails);
                $campaign->asset_suggestions = $this->suggest_similar_asset($campaign->booked_asset_id_arry);
            }
        }
        // dd($campaigns_found);
        // $campaigns_found = [];
        $title = "Campaigns";
        return view('advertiser.view_create_campaign', compact('user', 'campaigns_found', 'title'));
    }

    public function replace_campaign_view($old_asset_id = 0, $new_asset_id = 0)
    {
        $user = \Request::get('user');
        if ($old_asset_id > 0 && $new_asset_id > 0) {
            $campaign_det = CampaignDetail::where('asset_id', $old_asset_id)->first();
            $campaign_det->asset_id = $new_asset_id;

            try {
                if ($campaign_det->save()) {
                    return redirect()->back();
                }
            } catch (\Illuminate\Database\QueryException $qex) {
                $this->log('Critical', $qex->getMessage()); // Log the exception here.
            }
        }
    }


    public function remove_campaign_view($campaign_id = 0)
    {
        $user = \Request::get('user');
        if ($campaign_id > 0) {
            $campaign_det = CampaignDetail::where('id', $campaign_id)->first();
            try {
                if ($campaign_det->delete()) {
                    return redirect()->back();
                }
            } catch (\Illuminate\Database\QueryException $qex) {
                $this->log('Critical', $qex->getMessage()); // Log the exception here.
            }
        }
    }

    public function remove_campaign($campaign_id = 0)
    {
        $user = \Request::get('user');
        if ($campaign_id > 0) {
            if (Campaign::find($campaign_id)->delete()) {
                try {
                    $campaign_details = CampaignDetail::where('campaign_id', $campaign_id);
                    if ($campaign_details->count()) {
                        $campaign_details->delete();
                    }
                    return redirect()->back();
                } catch (\Illuminate\Database\QueryException $qex) {
                    $this->log('Critical', $qex->getMessage()); // Log the exception here.
                }
            }
        }
    }


    public function create_campaign(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'campaign_name' => 'bail|required',
            'campaign_date' => 'bail|required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->getMessageBag()->toArray(), 'success' => null]);
        }

        $found = $this->getCampaign($request->campaign_name, $request->user_id, $request->user_type_id, $request->corp_id)->count();
        if ($found) {
            return response()->json(['status' => false, 'errors' => 'Apologies, The provided campaign name has been taken', 'success' => null]);
        }
        $campaigns = new Campaign;
        $campaigns->user_id = $request->user_id;
        $campaigns->user_type_id = $request->user_type_id;
        $campaigns->name = $request->campaign_name;
        $campaigns->corp_id = $request->corp_id;
        $campaign_dates = explode(' -to- ', $request->campaign_date);
        $startDate = DateTime::createFromFormat('d/m/Y', $campaign_dates[0]);
        $endDate = DateTime::createFromFormat('d/m/Y', $campaign_dates[1]);
        $campaigns->start_date = $startDate->format('Y-m-d');
        $campaigns->end_date = $endDate->format('Y-m-d');
        try {
            if ($campaigns->save()) {
                $campaigns_found = $this->getCampaign('', $request->user_id, $request->user_type_id, $request->corp_id)->get();
                if (count($campaigns_found)) {
                    foreach ($campaigns_found as $key => $campaign) {
                        $campaign->campaign_count = $campaign->campaignDetails()->count();
                        $campaign->start_date = date('F jS Y', strtotime($campaign->start_date));
                        $campaign->end_date = date('F jS Y', strtotime($campaign->end_date));
                    }
                }
                return response()->json([
                    'status' => true, 'errors' => null, 'success' => [
                        'msg' => 'Congratulation! You have just created a campaign.',
                        'result' => $campaigns_found,
                    ],
                ]);
            }
        } catch (\Illuminate\Database\QueryException $qex) {
            $this->log('Critical', $qex->getMessage()); // Log the exception here.
        }

        // ALTER TABLE `campaigns` ADD `end_date` DATE NOT NULL AFTER `start_date`;
    }

    public function save_campaign_cart_item(Request $request)
    {
        $campaigns = $request->campaign;
        $counter = 0;
        $exist_cnt = 0;
        $success_msg = '';
        $success_push = [];
        foreach ($campaigns as $key => $campaign) {
            $found = CampaignDetail::where([['campaign_id', '=', $campaign['campaign_id']], ['asset_id', '=', $campaign['item_id']]])->count();
            if (!$found) {
                CampaignDetail::create([
                    'campaign_id' => $campaign['campaign_id'],
                    'asset_id' => $campaign['item_id'],
                    'qty' => 1,
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time())
                ]);
                array_push($success_push, [
                    'campaign_id' => $campaign['campaign_id'],
                    'item_id' => $campaign['item_id'],
                    'qty' => 1,
                    'price' => $campaign['price'],
                    'name' => $campaign['name'],
                ]);
                $counter++;
            } else {
                $exist_cnt++;
            }
        }

        if ($counter) {
            $msg_ext = $counter > 1 ? 'sites have been' : 'site has been';
            $success_msg = '(' . $counter . ') ' . $msg_ext . ' added to your campaign cart';
            $smt = $exist_cnt > 1 ? 'they' : 'it';
            $success_msg .=  $exist_cnt ? ', while (' . $exist_cnt . ') failed to add because ' . $smt . ' already exist.' : '.';
            return response()->json(['status' => true, 'errors' => null, 'success' => [
                'msg' => $success_msg,
                'result' => $success_push
            ]]);
        } else {
            return response()->json(['status' => false, 'errors' => 'Apologies, unable to add site, seems site already exist.', 'success' => null]);
        }
    }

    private function getCampaign($campaign_name, $user_id, $user_type_id, $corp_id)
    {
        $query = [];
        if ($campaign_name) array_push($query, ['name', '=', $campaign_name]);
        if ($corp_id) array_push($query, ['corp_id', '=', $corp_id]);
        else array_push($query, ['user_id', '=', $user_id], ['user_type_id', '=', $user_type_id]);

        return Campaign::where($query);
    }


    public function fast_track()
    {
        $user = \Request::get('user');
        $fast_track = new FastTrack();
        $fast_track->user_id = $user->user_id;
        $fast_track->user_type_id = $user->user_type_id;

        try {
            if ($fast_track->save()) {
                session()->put('fast-track-mode', true);
                return redirect('advertiser/individual/dashboard');
            }
        } catch (\Illuminate\Database\QueryException $qex) {
            $this->log('Critical', $qex->getMessage()); // Log the exception here.
        }
    }

    public function exit_fast_track()
    {
        $user = \Request::get('user');
        $fast_track = FastTrack::where([['user_id', '=', $user->user_id], ['user_type_id', '=', $user->user_type_id]]);
        if (count($fast_track->get())) {
            $fast_track->delete();
            session()->forget('fast-track-mode');
            return redirect()->back();
        }
    }


    public function corporate_dashboard()
    {
        $user = \Request::get('user');
        $bookings = $this->get_corporate_bookings($user->corp_id);
        $booked_sites = 0;
        $pending_trans = 0;
        $paid_trans = 0;

        if (count($bookings['corporate_bookings'])) {
            $booked_sites = count($bookings['corporate_bookings']);
            foreach ($bookings['corporate_bookings'] as $key => $booking) {
                $pending_trans += count($booking->pending_payment_records);
                $paid_trans += count($booking->payment_records);
            }
        }

        $title = "Corporate Dashboard";
        return view('advertiser.corporate_dashboard', compact('user', 'paid_trans', 'pending_trans', 'booked_sites', 'title'));
    }

    public function corporate_bookings()
    {
        $user = \Request::get('user');
        $bookings = $this->get_corporate_bookings($user->corp_id);
        $title = "Corporate Bookings";
        return view('advertiser.corporate_bookings', compact('user', 'bookings'));
    }

    public function corporate_dealslip(int $booking_id = 0, int $user_id = 0)
    {
        // dd($booking_id, $user_id);
        $user = \Request::get('user');
        $bookings = $this->get_corporate_bookings($user->corp_id);
        $pending_tranx_recs = [];
        $booked_by_user = [];

        if (count($bookings['corporate_bookings'])) {
            $pending_tranx_recs = $bookings['corporate_bookings'];
            $staff_details = $bookings['staff_details'];
            $booked_by_user = array_filter($staff_details, function ($el) use ($user_id) {
                return $user_id === $el[0];
            });
            $booked_by_user = array_shift($booked_by_user);
        }
        return view('advertiser.corporate_dealslip', compact('user', 'pending_tranx_recs', 'booked_by_user'));
    }


    public function corporate_staffs()
    {
        $user = \Request::get('user');
        $staffs = $this->get_corporate_staffs($user->corp_id);
        return view('advertiser.corporate_staffs', compact('user', 'staffs'));
    }


    public function corporate_staff_details(int $staff_id)
    {
        $user = \Request::get('user');
        $staff = $this->get_corporate_staff_booking_records($user->corp_id, $staff_id);
        $staff_details = $staff['staff_details'];
        $booking_details = $staff['booking_details'];
        return view('advertiser.corporate_single_staff', compact('user', 'staff_details', 'booking_details'));
    }


    public function regenerateTransactionReference(string $booking_id, string $tranx_id)
    {
        if ($tranx_ref = $this->regenerate_transaction_id($booking_id)) {
            if ($this->update_transaction(['id' => $tranx_id], ['tranx_id' => $tranx_ref])) {
                return redirect()->back()->with(["flash_message" => "Regenerated merchant transaction reference."]);
            }
        }
    }



    private function get_user_details($user_id = 0, $corp_id = 0)
    {
        return Individual::where([['id', '=', $user_id], ['corp_id', '=', $corp_id]])->first();
    }

    private function get_corporate_details($corp_id = 0)
    {
        return Corporate::where('id', '=', $corp_id)->first();
    }
}
