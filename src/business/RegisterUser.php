<?php

class HnauthBusinessRegisterUser implements HnauthBusiness
{
    public function __construct()
    {
        return $this;
    }

    public function register($data, $params = '')
    {
        try {
            $isSave = false;
            if (!empty($data->users) && !empty($data->users->username)) {
                $users = (array)$data->users;
                JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_users/models', 'UsersModel');
                $model = JModelLegacy::getInstance('User', 'UsersModel', array('ignore_request' => true));
                $user = JUser::getInstance(JUserHelper::getUserId($users['username']));
                $row = array(
                    "lastvisitDate" => Date("Y-m-d H:i:s"),
                    "lastResetTime" => "",
                    "resetCount" => 0,
                    "sendEmail" => 1,
                    "block" => 0,
                    "requireReset" => 1,
                    "id" => 0,
                    "groups" => (new HnauthBusinessRegisterGroups())->prepare($data),
                    "com_fields" => (new HnauthBusinessRegisterFields())->prepare($data),
                    "params" => $this->prepareParams($params),
                    "username" => "",
                    "firstname" => "",
                    "lastname" => "",
                    "email" => "",
                    "password" => "",
                    "password2" => "",
                    "name" => ""
                );
                $this->matchValues($row, $user->getProperties());
                $this->matchValues($row, $users, array('id'));
                $this->prepareName($row);
                $this->preparePassword($row);
                if ($model->save($row)) {
                    $isSave = true;
                }
            }
            return $isSave;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    private function prepareParams($params)
    {
        $data = array(
            "admin_style" => "",
            "admin_language" => "",
            "language" => "",
            "editor" => "",
            "helpsite" => "",
            "timezone" => ""
        );
        if ($metadata = $this->parseParams($params)) {
            foreach (array_keys($data) as $key) {
                if (!empty($metadata->users->{$key})) {
                    $data[$key] = "{$metadata->users->{$key}}";
                }
            }
        }
        return $data;
    }

    private function prepareName(&$row)
    {
        if (!empty($row['name'])) {
            $name = explode(' ', $row['name']);
            $row['firstname'] = $name[0];
            unset($name[0]);
            $row['lastname'] = implode(' ', $name);
        }
    }

    private function preparePassword(&$row)
    {
        if (empty($row['id']) && !empty($row['username'])) {
            $row['password'] = $row['username'];
            $row['password2'] = $row['password'];
        }
    }

    private function parseParams($params)
    {
        $data = array();
        if (!empty($params) && !is_array($params)) {
            $metadata = json_decode($params);
            if (JSON_ERROR_NONE == json_last_error()) {
                $data = (array)$metadata;
            }
        }
        return $data;
    }

    private function matchValues(array &$row, array &$values, array $ignore = array())
    {
        $ignore = array_merge($ignore, array(
            'lastResetTime',
            'password',
            'password2',
            'groups',
            'com_fields'
        ));
        foreach ($row as $key => $value) {
            if (!in_array($key, $ignore) && isset($values[$key])) {
                if ('params' == $key) {
                    $row[$key] = $this->parseParams($values[$key]);
                } else {
                    $row[$key] = $values[$key];
                }
            }
        }
    }

}
