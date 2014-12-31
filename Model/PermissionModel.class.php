<?php
/**
 * PermissionModel.class.php
 * 权限控制类
 *
 *   author:  hanyang (han2008yang@163.com)
 *   create time: 2014-12-30 13:40:23
 *
 */
class PermissionModel {

    /**
     * is_super_admin
     * 是否为超级管理员
     *
     * @param  integer  $user_id
     * @return boolean
     */
    public function is_super_admin($user_id) {
        $user_obj = new UserModel();
        $user_group_obj = new UserGroupModel();

        $user = $user_obj->fetch_one('user_id=' . $user_id);
        $user_group = $user_group_obj->fetch_one('group_id=' . $user['group_id']);
        if ($user_group['permission_sum'] == 4) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * check_user_add
     * 检查用户没有有添加某个组成员的权限
     *
     * @param  integer $user_id
     * @param  integer $group_id
     * @return bool
     */
    public function check_user_add($user_id, $group_id) {
        $user_allowed_group_ids = $this->get_user_allowed_group_ids($user_id);
        if (in_array($group_id, $user_allowed_group_ids)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * check_user_edit
     * 检查用户没有有编辑某个组成员的权限
     *
     * @param  integer $user_id
     * @param  integer $group_id
     * @return bool
     */
    public function check_user_edit($user_id, $group_id) {
        $user_allowed_group_ids = $this->get_user_allowed_group_ids($user_id);
        if (in_array($group_id, $user_allowed_group_ids)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * check_user_delete
     * 检查用户有没有删除某个组成员的权限
     *
     * @param  integer $user_id
     * @param  integer $group_id
     * @return bool
     */
    public function check_user_delete($user_id, $group_id) {
        $user_allowed_group_ids = $this->get_user_allowed_group_ids($user_id);
        if (in_array($group_id, $user_allowed_group_ids)) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * get_user_allowed_groups
     * 用户能够获取到的所有的组信息
     *
     * @param  integer $user_id
     * @return array
     */
    public function get_user_allowed_groups($user_id) {
        $user_obj = new UserModel();
        $user_group_obj = new UserGroupModel();

        $user = $user_obj->fetch_one('user_id=' . $user_id);
        $user_group = $user_group_obj->fetch_one('group_id=' . $user['group_id']);
        $user_allowed_groups = $user_group_obj->fetch_all('permission_sum < ' . $user_group['permission_sum']);
        if (!$user_allowed_groups) {
            $user_allowed_groups = array();
        }

        return $user_allowed_groups;
    }

    /**
     * get_user_allowed_group_ids
     * 用户能够看到的所有组ID
     *
     * @param  integer $user_id
     * @return array
     */
    public function get_user_allowed_group_ids($user_id) {

        $user_allowed_groups = $this->get_user_allowed_groups($user_id);
        $user_allowed_group_ids = array_unique(UtilArray::get_col($user_allowed_groups, 'group_id'));

        return $user_allowed_group_ids;
    }
}