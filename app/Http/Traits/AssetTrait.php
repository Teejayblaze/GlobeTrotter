<?php

namespace App\Http\Traits;

use \App\Asset;
use \App\AssetBooking;

trait AssetTrait
{

    public function getAvailableAsset()
    {
        $assetBookings = AssetBooking::select('asset_id')->where(['locked' => 1])->get()->toArray();
        $assetIds = array_column($assetBookings, 'asset_id');
        $availableAssets = Asset::whereNotIn("id", $assetIds)->get();
        return $availableAssets;
    }


    public function getAvailableAsset2()
    {
        $assetBookings = AssetBooking::select('asset_id')->where(['locked' => 1])->get()->toArray();
        $assetIds = array_column($assetBookings, 'asset_id');
        $availableAssets = Asset::whereNotIn("id", $assetIds)->limit(40)->get();
        return $availableAssets;
    }


    // public function getBookedAsset()
}
