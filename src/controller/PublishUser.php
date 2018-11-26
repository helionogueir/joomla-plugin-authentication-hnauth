<?php

class HnauthControllerPublishUser implements HnauthController
{

    private $plugin = null;

    public function __construct($plugin)
    {
        $this->plugin = $plugin;
    }

    public function publish($username)
    {
        try {
            $published = false;
            if (!empty($username) && $this->plugin->params->get('publish_enable', false)) {
                $uri = (new HnauthBusinessPrepareUri())->prepare($this->plugin->params->get('publish_uri', ''), array("cpf" => $username));
                $token = (new HnauthBusinessPrepareToken())->prepare($this->plugin->params->get('publish_publickey', ''), $this->plugin->params->get('publish_secretkey', ''));
                if ($data = (new HnauthBusinessRequest())->get($token, $uri)) {
                    if ((new HnauthBusinessRegisterUser())->register($data, $this->plugin->params->get('publish_params', ''))) {
                        $published = true;
                    }
                }
            }
            return $published;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
