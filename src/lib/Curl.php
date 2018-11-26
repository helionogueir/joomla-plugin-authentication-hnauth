<?php

class HnauthLibCurl
{

    public function get($uri, array $httpHeader = array())
    {
        $data = null;
        try {
            if (!empty($uri)) {
                $ch = curl_init($uri);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                if (!empty($httpHeader)) {
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);
                }
                $result = curl_exec($ch);
                $info = curl_getinfo($ch);
                curl_close($ch);
                if (!empty($result) && !empty($info['http_code']) && preg_match('/^(2|3)(\d{2})$/', $info['http_code'])) {
                    $data = $result;
                }
            }
        } catch (Exception $ex) {
            //continue
        }
        return $data;
    }

}
