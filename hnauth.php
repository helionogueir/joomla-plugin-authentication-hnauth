<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgAuthenticationHnauth extends JPlugin
{

    public function __construct(&$subject, $config = array())
    {
        parent::__construct($subject, $config);
        JLoader::register('HnauthLibJWT', dirname(__FILE__) . "/src/lib/JWT.php");
        JLoader::register('HnauthLibCurl', dirname(__FILE__) . "/src/lib/Curl.php");
        JLoader::register('HnauthBusiness', dirname(__FILE__) . "/src/business/Business.php");
        JLoader::register('HnauthController', dirname(__FILE__) . "/src/controller/Controller.php");
        JLoader::register('HnauthBusinessRequest', dirname(__FILE__) . "/src/business/Request.php");
        JLoader::register('HnauthBusinessPrepareUri', dirname(__FILE__) . "/src/business/PrepareUri.php");
        JLoader::register('HnauthBusinessPrepareToken', dirname(__FILE__) . "/src/business/PrepareToken.php");
        JLoader::register('HnauthBusinessAdminSession', dirname(__FILE__) . "/src/business/AdminSession.php");
        JLoader::register('HnauthBusinessRegisterFields', dirname(__FILE__) . "/src/business/RegisterFields.php");
        JLoader::register('HnauthBusinessRegisterGroups', dirname(__FILE__) . "/src/business/RegisterGroups.php");
        JLoader::register('HnauthBusinessRegisterUser', dirname(__FILE__) . "/src/business/RegisterUser.php");
        JLoader::register('HnauthControllerPublishUser', dirname(__FILE__) . "/src/controller/PublishUser.php");
        JLoader::register('HnauthControllerUnpublishUser', dirname(__FILE__) . "/src/controller/UnpublishUser.php");
    }

    public function onUserAuthenticate($credentials, $options, &$response)
    {
        try {
            $session = new HnauthBusinessAdminSession($this);
            $session->start();
            if (!empty($options['action']) && !empty($credentials['username']) && ('admin' != $credentials['username'])) {
                if (preg_match('/(login\.site)/', $options['action'])) {
                    if (!(new HnauthControllerPublishUser($this))->publish($credentials['username'])) {
                        (new HnauthControllerUnpublishUser($this))->unpublish($credentials['username']);
                    }
                }
            }
            $session->destroy();
        } catch (Exception $ex) {
            $session->destroy();
            throw $ex;
        }
    }


}
