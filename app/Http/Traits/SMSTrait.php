<?php

namespace App\Http\Traits;


trait SMSTrait
{
    //
    private $fromSender;
    private $username;
    private $hash;
    private $url;
    private $ch;

    private function getSender()
    {
        return $this->fromSender = config('app.sms_sender');
    }

    private function getUsername()
    {
        return $this->username = config('app.sms_auth_username');
    }

    private function getHash()
    {
        return $this->hash = config('app.sms_auth_hash');
    }

    private function getSMSGateWayURL()
    {
        return $this->url = config('app.sms_url');
    }

    public function sendSMSNow(string $toNumbers, string $content)
    {
        $this->ch = curl_init($this->getSMSGateWayURL());

        $data = [
            
            'username' => $this->getUsername(),

            'hash' => $this->getHash(),
            
            'message' => rawurlencode($content),
            
            'sender' => urlencode($this->getSender()),
            
            'numbers' => $toNumbers,

            'test' => '0',

        ];

        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);

        try {
            $result = curl_exec($this->ch);
            if ( $result ) {
                curl_close($this->ch);
                return response()->json(['status' => true, 'errors' => null, 'success' => 'SMS sent to the number '. $toNumbers]);
            }
        } catch( \Exception $ex ) {
            curl_close($this->ch);
            $err = curl_error($this->ch);
            return response()->json(['status' => false, 'errors' => $err->getMessage(), 'success' => null]);
        }

        
    }
}
