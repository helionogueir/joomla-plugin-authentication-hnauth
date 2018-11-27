<?php

class HnauthControllerUnpublishUser implements HnauthController
{

    private $plugin = null;

    public function __construct(JPlugin $plugin)
    {
        $this->plugin = $plugin;
    }

    public function unpublish($username)
    {
        try {
            $unpublished = false;
            if (!empty($username) && $this->plugin->params->get('unpublish_enable', false)) {
                $uri = (new HnauthBusinessPrepareUri())->prepare($this->plugin->params->get('unpublish_uri', ''), array("cpf" => $username));
                $token = (new HnauthBusinessPrepareToken())->prepare($this->plugin->params->get('unpublish_publickey', ''), $this->plugin->params->get('unpublish_secretkey', ''));
                if ($data = (new HnauthBusinessRequest())->get($token, $uri)) {
                    $unpublished = (new HnauthBusinessRegisterUser())->register($data);
                }
            }
            return $unpublished;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
