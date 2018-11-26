<?php

class HnauthBusinessPrepareUri implements HnauthBusiness
{
    public function __construct()
    {
        return $this;
    }

    public function prepare($uri, $params)
    {
        if (!empty($uri)) {
            foreach ($params as $key => $value) {
                $uri = preg_replace("/(\:{$key})/s", $value, $uri);
            }
        }
        return $uri;
    }

}
