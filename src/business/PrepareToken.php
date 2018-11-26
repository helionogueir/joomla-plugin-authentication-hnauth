<?php

class HnauthBusinessPrepareToken implements HnauthBusiness
{
    public function __construct()
    {
        return $this;
    }

    public function prepare($publicKey, $secretKey)
    {
        try {
            $token = null;
            if (!empty($publicKey) && !empty($secretKey)) {
                $payload = array(
                    "exp" => time() + 10,
                    "publicOrAccessKey" => $publicKey
                );
                $token = HnauthLibJWT::encode($payload, $secretKey, 'HS256');
            }
            return $token;
        } catch (Exception $ex) {
            return null;
            //throw $ex;
        }
    }

}
