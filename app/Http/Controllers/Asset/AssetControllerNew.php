<?php

namespace App\Http\Controllers\Asset;

use Illuminate\Http\Request;
use App\Asset;
use App\AssetType;
use \App\AssetBooking;
use \App\AssetGracePeriod;
use \App\TransactionToken;
use \App\PlatformSettings;
use \App\LCDA;
use \App\AssetProximity;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use App\Http\Traits\LoggerTrait;
use App\Http\Traits\TransactionTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use \App\Http\Traits\UserProfileTrait;
use \App\Http\Traits\GenericThirdPartyRequestTrait;

class AssetController extends Controller
{
    //
    use LoggerTrait, TransactionTrait, UserProfileTrait, GenericThirdPartyRequestTrait;

    public function available_asset(Request $request, string $name = null)
    {
        $user = \Request::get('user');
        return view('assets.assetresults', compact("user"))->with($this->search_asset($request, $name));
    }


    public function details()
    {
        $user = \Request::get('user');
        return view('assets.assetdetails', compact("user"))->with($this->search_asset($request));
    }

    public function asset_area_auto_complete_search(Request $request)
    {
        $predictions = [];
        $searchTerm = $request->term;
        $apiKey = env("GOOGLE_MAP_API_KEY");
        $endpoint = env("GOOGLE_MAP_API") . "/place/autocomplete/json?input=" . urlencode($searchTerm) . 
                    "&components=country:NG&key=" . urlencode($apiKey);

        $response = $this->doCurlRequest($endpoint);

        if($response['status'] && ($data = json_decode($response['data'], true))) {
            $predictions = array_column($data["predictions"], "description");
        }

        return response()->json($predictions, 200);
    }


    private function search_asset(Request $request, string $name = null)
    {
        $assets = [];
        $asset_category_count = [];
        $asset_category = [];
        $distance_km = 500;
        $isDistanceResultFound = false;
        
        if ($request->asset_location_search) {
            $searchTerm = $request->asset_location_search;
            $apiKey = env("GOOGLE_MAP_API_KEY");
            $endpoint = env("GOOGLE_MAP_API") . "/geocode/json?address=" . urlencode($searchTerm) . "&key=" . urlencode($apiKey);
            
            $response = $this->doCurlRequest($endpoint);
            
            $distance_km = PlatformSettings::find(1)->distance_km;
            
            $latitude = $longitude = 0;
            $assets = Asset::all()->sortByDesc('asset_type');
            
            if($response['status'] && ($data = json_decode($response['data'], true))) {
                if (count($data['results'])) {
                    $latitude = floatval(trim($data['results'][0]['geometry']['location']['lng']));
                    $longitude = floatval(trim($data['results'][0]['geometry']['location']['lat']));
                    // $latitude = floatval(trim($data['results'][0]['geometry']['location']['lat']));
                    // $longitude = floatval(trim($data['results'][0]['geometry']['location']['lng']));
                    $isDistanceResultFound = true;
                }
                else {
                    $assetIds = [];
                    $lcdas = LCDA::where('lcda_name', 'LIKE', '%'.$searchTerm.'%')->get();
                    if(count($lcdas)) {
                        $ids = array_column($lcdas->toArray(), "id");
                        $assetIds = array_merge($assetIds, Asset::whereIn("location_lcda", $ids)->pluck("id")->toArray());
                    }

                    $proxis = AssetProximity::where("proximity_type", "LIKE", "%".$searchTerm."%")
                                ->orWhere("proximity_name", "LIKE", "%".$searchTerm."%")->get();
                    if(count($proxis)) {
                        $assetIds = array_merge($assetIds, array_column($proxis->toArray(), "asset_id"));
                    }

                    if(count($assetIds)) {
                        $assets = Asset::whereIn("id", $assetIds)->get();
                    }

                    $isDistanceResultFound = false;
                }
            }
        }
        else {
            $assets = Asset::all()->sortByDesc('asset_type');
        }

        $assetRecord = collect([]);

        if (count($assets)) {
            foreach ($assets as $key => $asset) {
                $asset_location = explode(",", $asset->location);
                $asset_longitude = floatval(trim($asset_location[0]));
                $asset_latitude = floatval(trim($asset_location[1]));

                $distance = floatval($this->calculateDistance($asset_latitude, $asset_longitude, $latitude, $longitude));
                
                if($isDistanceResultFound) {
                    if ($distance_km && $distance <= floatval($distance_km)) {
                        $asset->distance_in_km = ceil($distance) ."km";
                        $asset->type = AssetType::find($asset->asset_type)->type;
                        $asset->images = $asset->assetImagesRecord;
                        $asset->lcda = $asset->assetLCDARecords;
                        $asset->owner = $asset->assetOwner;
    
                        if (!in_array($asset->type, array_keys($asset_category_count))) $asset_category_count[$asset->type] = 1;
                        else $asset_category_count[$asset->type] += 1;
    
                        $assetRecord->push($asset);
                    }
                }
                else {
                    $asset->distance_in_km = "N/A";
                    $asset->type = AssetType::find($asset->asset_type)->type;
                    $asset->images = $asset->assetImagesRecord;
                    $asset->lcda = $asset->assetLCDARecords;
                    $asset->owner = $asset->assetOwner;
    
                    if (!in_array($asset->type, array_keys($asset_category_count))) $asset_category_count[$asset->type] = 1;
                    else $asset_category_count[$asset->type] += 1;
    
                    $assetRecord->push($asset);
                }
                
            }
            $assets = [];
            $asset_category = array_unique(array_column($assetRecord->toArray(), 'type'));
        }

        $assets = $assetRecord;

        // dd("longitude = ", $longitude, "latitude = ", $latitude,  "assets = ", $assets);
        if ($assets && $assets->count()) {
            $assets = $assets->sortBy("type");
        }

        uasort($asset_category, function($a, $b) {
            return strcmp($a, $b);
        });
        ksort($asset_category_count);

        return [
            'available_asset' => $assets,
            'asset_category' => $asset_category,
            'asset_category_count' => $asset_category_count,
            'search_criteria' => $request->dest_name,
        ];
    }


    public function asset_detail(int $asset_id = null)
    {
        if ($asset_id) {
            $user = \Request::get('user');
            $available_asset = Asset::where('id', '=', $asset_id)->first();
            $available_asset->asset_type_record = $available_asset->assetTypeRecord;
            $available_asset->asset_images_record = $available_asset->assetImagesRecord;
            $available_asset->asset_keyword_record = $available_asset->assetKeywordRecord;
            $available_asset->lcda = $available_asset->assetLCDARecords;
            $available_asset->owner = $available_asset->assetOwner;
            $search_criteria = $available_asset->name;
            $title = "Asset Details";
            return view('assets.assetdetails', compact('available_asset', 'search_criteria', 'user', 'title'));
        } else {
            return abort(404);
        }
    }

    public function book_asset(Request $request, AssetBooking $assetBooking)
    {
        $start_date                 = new Carbon($request->start_date);
        $end_date                   = new Carbon($request->end_date);
        $d                          = new Carbon($request->end_date);
        $next_availability_date     = $d->addDay();
        $aid                        = $request->aid;
        $price                      = floatval(str_replace(',', '', $request->price));

        if (!$this->check_asset_first_transaction_payment()) {
            return response()->json([
                'status' => false,
                'errors' => '<li class="fs-13">Apologies, You still have a pending payment schedule, kindly make payment on the schedule before booking another asset.</li>',
                'success' => null
            ]);
        }

        $existing = $assetBooking->where(function ($query) use ($aid, $start_date, $end_date) {
            $query->where([['locked', '=', 1], ['asset_id', '=', $aid], ['start_date', '<=', $start_date], ['end_date', '>=', $start_date]])
                ->orWhere([['locked', '=', 1], ['asset_id', '=', $aid], ['start_date', '<=', $end_date], ['end_date', '>=', $end_date]]);
        })->first();

        if ($existing) {

            $who = 'another advertiser';
            if ($request->booked_by_user_id == $existing->booked_by_user_id) $who = 'You';
            return response()->json([
                'status' => false,
                'errors' => '<li class="fs-13">Apologies, This Asset has been booked by <strong>' . $who . '</strong> and your specified period is within its booked period.</li>',
                'success' => null
            ]);
        } else {
            $is_existing = $this->check_asset_existence($assetBooking, $aid, $start_date, $end_date);

            if ($is_existing === false) {

                $grace_period_started = Carbon::now();

                $booking_id                             = 'Ast/bk/' . date('Y') . '/' . $this->generate_transaction_id(5) . '/' . str_pad($request->booked_by_user_id, 4, '0', STR_PAD_LEFT);
                $assetBooking->start_date               = $start_date;
                $assetBooking->end_date                 = $end_date;
                $assetBooking->next_availability_date   = $next_availability_date;
                $assetBooking->grace_period_started     = $grace_period_started;
                $assetBooking->asset_id                 = $aid;
                $assetBooking->locked                   = 1;
                $assetBooking->booked_by_user_id        = $request->booked_by_user_id;
                $assetBooking->user_type_id             = $request->user_type_id;
                $assetBooking->trnx_id                  = $booking_id;
                $assetBooking->price                    = \number_format($price, 2, '.', ',');

                try {

                    if ($assetBooking->save()) {

                        // $desc = 'Generating transaction of 10% for the asset with the reserved ref number: '. $booking_id;
                        // $perc = 0.1;
                        $x = new Carbon($grace_period_started);
                        $grace_period_ends = $x->addHours(48);

                        $sec_grace_prd_start_date = new Carbon($request->start_date);
                        $sec_grace_prd_start = $sec_grace_prd_start_date->subDays(3);

                        $sec_grace_prd_end_date = new Carbon($sec_grace_prd_start);
                        $sec_grace_prd_end = $sec_grace_prd_end_date->addHours(48);

                        $bulk_grace_records = [
                            [
                                'asset_booking_id' => $assetBooking->id,
                                'booked_id' => $booking_id,
                                'percentage' => '10%',
                                'grace_period_started' => $grace_period_started,
                                'grace_period_ends' => $grace_period_ends,
                                'completed' => 0,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ],
                            [
                                'asset_booking_id' => $assetBooking->id,
                                'booked_id' => $booking_id,
                                'percentage' => '90%',
                                'grace_period_started' => $sec_grace_prd_start,
                                'grace_period_ends' => $sec_grace_prd_end,
                                'completed' => 0,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ],

                        ];

                        $created = false;

                        foreach ($bulk_grace_records as $key => $bulk_grace_record) {
                            $created = AssetGracePeriod::create($bulk_grace_record);
                        }

                        // check if we are a corporate body and then save the token against the transaction used to authorize.
                        // if ( $request->otp ) {
                        //     $token = TransactionToken::where('token', '=', $request->otp)->first();
                        //     if ($token) {
                        //         $token->trnx_id = $booking_id;
                        //         $token->save();
                        //     }
                        // }

                        if ($created) {

                            $statement = '<li class="fs-13">
                                You have successfully booked this Asset.<br />
                                However, you are required to make 10% payment deposit within 48hrs to validate your interest<br />
                            </li>';

                            $request->session()->flash('booking', $statement);
                            $user = \Request::get('user');
                            $route = null;

                            if ($user->user_type_id === 2 && $user->operator === 0) $route = route('individaulPendingTransaction');
                            else if ($user->user_type_id === 2 && $user->operator === 0) $route = route('corporatePendingTransaction');
                            else $route = route('home');


                            return response()->json(['status' => true, 'errors' => null, 'success' => ['url' => $route, 'msg' => $statement]]);
                        }
                    }

                    return response()->json(['status' => false, 'errors' => '<li class="fs-13">Apologies, we are unable to complete the asset booking at the moment.<br />' .
                        '<br>Please try again later</li>', 'success' => null]);
                } catch (QueryException $qe) {
                    $this->log('Critical', $qe->getMessage());
                }
            } else return $is_existing;
        }
    }

    private function check_asset_existence(AssetBooking $assetBooking, $aid, $start_date, $end_date)
    {
        $existing = $assetBooking->where(function ($query) use ($aid, $start_date, $end_date) {
            $query->where([
                ['locked', '=', 1],
                ['asset_id', '=', $aid],
                ['start_date', '<=', $start_date],
                ['end_date', '>=', $start_date],
                // This simply means that your selected start_date is within our known period therefore we will not allow such asset request to save.
            ])
                ->orWhere([
                    ['locked', '=', 1],
                    ['asset_id', '=', $aid],
                    ['start_date', '<=', $end_date],
                    ['end_date', '>=', $end_date],
                    // This simply means that your selected end_date is within our known period therefore we will not allow such asset request to save.
                ])
                ->orWhere([
                    ['locked', '=', 1],
                    ['asset_id', '=', $aid],
                    ['start_date', '>=', $start_date],
                    ['end_date', '<=', $end_date],
                    // This simply mean that our saved period actually falls within another user chosen duration therefore we will not allow such asset request to save. 
                ])
                ->orWhere([
                    ['locked', '=', 1],
                    ['asset_id', '=', $aid],
                    ['start_date', '<=', $start_date],
                    ['end_date', '>=', $end_date],
                    // This simply mean that the selected duration of the user falls within our known period therefore we will not allow such asset request to save. 
                ]);
        })->first();

        if ($existing) {

            if (
                $start_date >= $this->convert_str_to_date($existing->start_date) &&
                $start_date <= $this->convert_str_to_date($existing->end_date)
            ) {
                return response()->json([
                    'status' => false,
                    'errors' => '<li>Apologies, Your specified <strong>StartDate</strong> period of asset booking is within the <strong>StartDate [' . $this->convert_str_to_date($existing->start_date)->format('d-m-Y') . ']</strong> and ' .
                        '<strong>EndDate [' . $this->convert_str_to_date($existing->end_date)->format('d-m-Y') . ']</strong> of a currently booked asset.<br />Kindly choose another <strong>StartDate</strong> period.</li>',
                    'success' => null
                ]);
            } else if (
                $end_date >= $this->convert_str_to_date($existing->start_date) &&
                $end_date <= $this->convert_str_to_date($existing->end_date)
            ) {
                return response()->json([
                    'status' => false,
                    'errors' => '<li>Apologies, Your specified <strong>EndDate</strong> period of asset booking is within the <strong>StartDate [' . $this->convert_str_to_date($existing->start_date)->format('d-m-Y') . ']</strong> and ' .
                        '<strong>EndDate [' . $this->convert_str_to_date($existing->end_date)->format('d-m-Y') . ']</strong> of a currently booked asset.<br />Kindly choose another <strong>EndDate</strong> period.</li>',
                    'success' => null
                ]);
            } else if (
                $start_date <= $this->convert_str_to_date($existing->start_date) &&
                $end_date >= $this->convert_str_to_date($existing->end_date)
            ) {
                return response()->json([
                    'status' => false,
                    'errors' => '<li>Apologies, We already have a booked asset within your specified period.<br /> <strong>Booked Asset StartDate [' . $this->convert_str_to_date($existing->start_date)->format('d-m-Y') . ']</strong> and ' .
                        '<strong>Booked Asset EndDate [' . $this->convert_str_to_date($existing->end_date)->format('d-m-Y') . ']</strong>.<br />Kindly choose another period.</li>',
                    'success' => null
                ]);
            } else if (
                $start_date >= $this->convert_str_to_date($existing->start_date) &&
                $end_date <= $this->convert_str_to_date($existing->end_date)
            ) {
                return response()->json([
                    'status' => false,
                    'errors' => '<li>Apologies, We already have a booked asset covering the period you selected.<br /> <strong>Booked Asset StartDate [' . $this->convert_str_to_date($existing->start_date)->format('d-m-Y') . ']</strong> and ' .
                        '<strong>Booked Asset EndDate [' . $this->convert_str_to_date($existing->end_date)->format('d-m-Y') . ']</strong>.<br />Kindly choose another period.</li>',
                    'success' => null
                ]);
            }
        } else {
            return false;
        }
    }

    private function convert_str_to_date(string $date)
    {
        return Carbon::parse($date);
    }

    public function get_lga($state_id = null)
    {
        $lgas = \App\LGA::where('state_id', '=', $state_id)->get();
        return response()->json(['status' => true, 'errors' => null, 'success' => $lgas]);
    }

    public function get_lcda($state_id = null)
    {
        $lcdas = \App\LCDA::where('state_id', '=', $state_id)->get();
        return response()->json(['status' => true, 'errors' => null, 'success' => $lcdas]);
    }

    public function get_states()
    {
        return response()->json(['status' => true, 'errors' => null, 'success' => \App\State::all()]);
    }
}
