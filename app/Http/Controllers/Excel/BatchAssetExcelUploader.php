<?php

namespace App\Http\Controllers\Excel;

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use App\Operator;
use App\Asset;
use App\AssetType;
use App\State;
use App\LGA;
use App\AssetProximity;


class BatchAssetExcelUploader implements ToCollection, WithHeadingRow
{
    private $assetOwner;
    public function __construct(int $assetOwner)
    {
        $this->assetOwner = $assetOwner;
    }



    public function collection(Collection $rows)
    {
        $cache = [];
        $operator = $this->getAssetOwnersDetails($this->assetOwner);
        // dd($rows);
        foreach ($rows as $row) {
            if($row->filter()->isNotEmpty()){
                if($operator && $row['asset_category']) {
                    $assetName = $this->generateAssetName($operator->oaan_number);
                    $assetCategory = strtolower($row['asset_category']);
                    $assetCategoryCode = $this->getAssetAttribute('code', $assetCategory);
                    $assetTypeID = $this->getAssetTypeID($assetCategoryCode, $row['asset_type']);
                    $stateID = $this->getStateID($row['location_state']);
                    $stateLGAID = $this->getStateLGAID($stateID, $row['location_lga']);
        
                    $asset = new Asset;
                    $asset->name = $assetName;
                    $asset->asset_category = $assetCategory;
                    $asset->asset_type = $assetTypeID;
                    $asset->advert_type = $row['advert_type'];
                    $asset->face_count = intval($row['face_count']);
                    $asset->location = $row['location_lat_lon'];
                    $asset->location_state = $stateID;
                    $asset->location_lga = $stateLGAID;
                    $asset->location_lcda = $stateLGAID;
                    $asset->address = $row['address'];
                    $asset->min_price = floatval($row['price_min']);
                    $asset->max_price = floatval($row['price_max']);
                    $asset->asset_dimension_width = floatval($row['dimension_width']);
                    $asset->asset_dimension_height = floatval($row['dimension_height']);
                    $asset->pixel_resolution = $row['pixel_resolution'];
                    $asset->substrate = $row['substrate_material_type'];
                    $asset->num_slides = intval($row['number_of_slides']);
                    $asset->num_slides_per_secs = intval($row['number_of_slides_per_seconds']);
                    $asset->file_format = $row['file_format'];
                    $asset->file_size = $row['file_size'];
                    $asset->orientation = $row['orientation'];
                    $asset->print_dimension = $row['print_dimension'];
                    $asset->payment_freq = $row['payment_frequency'];
                    $asset->advert_type = $row['advert_type'];
                    $asset->apply_promo = "NO";
                    $asset->uploaded_by = $this->assetOwner;
                    if($asset->save()) {
                        $aid = $asset->id;
    
                        if($row['proximities']) {
                            $details = explode(',', $row['proximities']);
                            foreach ($details as $one => $detail) {
                                $proximity = explode('=', $detail);
                                $key = $proximity[0];
                                $value = $proximity[1];
                                AssetProximity::create([
                                    'proximity_type' => ucwords(str_replace('_', ' ', $key)),
                                    'proximity_name' => $value,
                                    'asset_id' => $aid,
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now()
                                ]);
                            }
                        }
                        array_push($cache, $aid);
                    }
                }
                else {
                    break;
                }
            }
        }
        \session()->put('cache', $cache);
    }


    private function getAssetOwnersDetails(int $id = null)
    {
        return Operator::find($id);
    }

    private function getAssetTypeID($assetCategoryCode = '', $assetType = '')
    {
        return AssetType::where(['type' => $assetType, 'board_type' => $assetCategoryCode])->first()->id ?? 0;
    }

    private function getStateID($state = '')
    {
        return State::where(['state_name' => $state])->first()->id;
    }
    
    private function getStateLGAID($stateID, $lga_name)
    {
        $record = LGA::where(['state_id' => $stateID])->where('lga_name', 'like', "%$lga_name%")->first();
        if($record) return $record->id;
        else return 0;
    }

    private function generateAssetName($oaan_number)
    {
        $id = Asset::max('id');
        return $oaan_number . '/' .  str_pad(($id + 1), 4, "0", STR_PAD_LEFT);
    }


    private function getAssetAttribute($filter = 'code', $key = 'static')
    {
        $assetCategories = ['static' => 1, 'dynamic' => 2, 'digital' => 3, 'mobile' => 4];
        if($filter === 'code') {
            return $assetCategories[$key];
        }
        else if($filter === 'keys') {
            return array_keys($assetCategories);
        }
    }

}