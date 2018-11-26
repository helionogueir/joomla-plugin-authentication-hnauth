<?php

class HnauthBusinessRegisterGroups implements HnauthBusiness
{
    private $model = null;

    public function __construct()
    {
        JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_users/models', 'UsersModel');
        $this->model = JModelLegacy::getInstance('Group', 'UsersModel', array('ignore_request' => true));
        return $this;
    }

    public function prepare($data)
    {
        try {
            $groups = array();
            if (!empty($data->users) && !empty($data->users->username)) {
                if (!empty($data->groups)) {
                    if ($parentid = $this->getParentIdGroup()) {
                        $groups[$parentid] = $parentid;
                        foreach ($data->groups as $row) {
                            if (!empty($row->title)) {
                                if ($groupid = $this->getGroupIdByTtile($row->title)) {
                                    $groups[$groupid] = $groupid;
                                } else {
                                    $this->model->save(array(
                                        "id" => 0,
                                        "title" => $row->title,
                                        "parent_id" => $parentid
                                    ));
                                    if ($groupid = $this->getGroupIdByTtile($papel->ds_papel)) {
                                        $groups[$groupid] = $groupid;
                                    }
                                }
                            }
                        }
                    }
                }
                $this->matchGroups($groups, $data->users->username);
            }
            return $groups;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    private function matchGroups(&$groups, $username)
    {
        try {
            if ($user = JUser::getInstance(JUserHelper::getUserId($username))) {
                if ($data = $user->getProperties()) {
                    if (!empty($data['groups']) && is_array($data['groups'])) {
                        $groups = array_merge($groups, $data['groups']);
                        $groups = array_unique($groups);
                    }
                }
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    private function getParentIdGroup()
    {
        try {
            $title = 'Registered';
            return $this->getGroupIdByTtile($title);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    private function getGroupIdByTtile($title)
    {
        try {
            $id = null;
            if (!empty($title)) {
                $db = $this->model->getDbo();
                $query = $db->getQuery(true)
                    ->select('id')
                    ->from($db->quoteName($this->model->getTable()->getTableName()))
                    ->where("title = '{$title}'");
                if ($row = $db->setQuery($query)->loadObject()) {
                    $id = $row->id;
                }
            }
            return $id;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}
