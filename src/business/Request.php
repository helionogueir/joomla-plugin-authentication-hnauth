<?php

class HnauthBusinessRequest implements HnauthBusiness
{
    public function __construct()
    {
        return $this;
    }

    public function get($token, $uri)
    {
        try {
            $data = null;
            if (!empty($token) && !empty($uri)) {
                $result = (new HnauthLibCurl())->get($uri, array(
                    'Content-Type: application/json',
                    "Authorization: {$token}"
                ));
                $resultObject = json_decode($result);
                if (!empty($result) && (JSON_ERROR_NONE == json_last_error()) && !empty($resultObject->data)) {
                    $data = $resultObject->data;
                }
            }
            return $data;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}
