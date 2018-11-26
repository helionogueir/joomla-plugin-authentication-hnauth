<?php

class HnauthBusinessAdminSession implements HnauthBusiness
{

    private $session = null;

    public function __construct()
    {
        $this->destroy();
        return $this;
    }

    public function start()
    {
        try {
            $this->destroy();
            if ($instance = JUser::getInstance(JUserHelper::getUserId('admin'))) {
                $this->session = JFactory::getSession();
                $this->session->fork();
                $this->session->set('user', $instance);
            }

        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function destroy()
    {
        try {
            if ($this->session instanceof JSession) {
                $this->session->destroy();
            }
            $this->session = null;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
