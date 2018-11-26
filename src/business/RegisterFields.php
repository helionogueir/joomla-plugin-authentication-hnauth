<?php

class HnauthBusinessRegisterFields implements HnauthBusiness
{
    public function __construct()
    {
        return $this;
    }

    public function prepare($data)
    {
        try {
            $fields = array();
            if (!empty($data->fields) && !empty($data->users) && !empty($data->users->username)) {
                $values = (array)$data->fields;
                $keys = array_keys($values);
                $user = JUser::getInstance(JUserHelper::getUserId($data->users->username));
                if ($rowSet = FieldsHelper::getFields('com_users.user', $user, true)) {
                    foreach ($rowSet as $row) {
                        if (in_array($row->name, $keys)) {
                            $fields[$row->name] = $values[$row->name];
                        }
                    }
                }
            }
            return $fields;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
