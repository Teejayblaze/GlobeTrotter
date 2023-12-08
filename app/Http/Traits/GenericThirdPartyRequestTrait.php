<?php

namespace App\Http\Traits;


trait GenericThirdPartyRequestTrait
{
    //
    private $url;
    private $ch;

    public function sendRequestNow(string $url, string $method = 'GET', $data = [])
    {
        $this->ch = curl_init($url);

        if ( $method === 'POST' ) {
            curl_setopt($this->ch, CURLOPT_POST, true);
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($data));
        } 
        
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);

        try {
            
            $result = curl_exec($this->ch);
            curl_close($this->ch);
            return $result;

        } catch( \Exception $ex ) {
            curl_close($this->ch);
            $err = curl_error($this->ch);
            return response()->json(['status' => false, 'errors' => $err->getMessage(), 'success' => null]);
        }
    }

    public function calculateDistance($latitude1, $longitude1, $latitude2, $longitude2) {
        $earthRadius = 6371; // Radius of the earth in kilometers
    
        $latDiff = deg2rad($latitude2 - $latitude1);
        $lonDiff = deg2rad($longitude2 - $longitude1);
    
        $a = sin($latDiff/2) * sin($latDiff/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($lonDiff/2) * sin($lonDiff/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    
        $distance = $earthRadius * $c; // Distance in kilometers
    
        return $distance;
    }
}
