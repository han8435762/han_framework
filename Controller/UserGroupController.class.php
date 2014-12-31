<?php
/**
 * UserGroupController.class.php
 *
 *   author:  hanyang (han2008yang@163.com)
 *   create time: 2014-12-30 13:40:23
 *
 */
class UserGroupController extends BaseController{

    /**
     * index
     * 列表页
     *
     * @return void
     */
    public function index() {
        $this->need_logged();

        $user_obj = new UserModel();
        $user_group_obj = new UserGroupModel();
        $permission_obj = new PermissionModel();

        $user = $user_obj->fetch_one('user_id = ' . $this->logged_user['user_id']);

        $groups = $user_group_obj->fetch_all();

        $this->assign('is_super_admin', $permission_obj->is_super_admin($this->logged_user['user_id']));
        $this->assign('groups', $groups);
        $this->display();
    }

    /**
     * add
     * 添加
     *
     * @return void
     */
    public function add() {

        $user_group_obj = new UserGroupModel();
        $permission_obj = new PermissionModel();

        // 只有超级管理员才能进行此操作
        if (!$permission_obj->is_super_admin($this->logged_user['user_id'])) {
            $this->json_error('只有超级管理员才能进行此操作');
        }

        $group_name = $_POST['group_name'] ? $_POST['group_name'] : '';
        $description = $_POST['description'] ? $_POST['description'] : '';
        $allow_add = isset($_POST['allow_add']) ? 1 : 0;
        $allow_delete = isset($_POST['allow_delete']) ? 1 : 0;
        $allow_edit = isset($_POST['allow_edit']) ? 1 : 0;
        $allow_select = isset($_POST['allow_select']) ? 1 : 0;

        if (!$group_name) {
            $this->json_error('组名称不能为空');
        }

        $user_group = $user_group_obj->fetch_one(array('group_name' => $group_name));

        if ($user_group) {
            $this->json_error('组名称已经存在');
        }

        $permission_sum = $allow_add + $allow_delete + $allow_edit + $allow_select;

        $data = array(
            'group_name' => $group_name,
            'description' => $description,
            'allow_add' => $allow_add,
            'allow_delete' => $allow_delete,
            'allow_edit' => $allow_edit,
            'allow_select' => $allow_select,
            'permission_sum' => $permission_sum,
            'add_time' => time(),
        );

        if ($user_group_obj->insert($data)) {
            $this->json_success();
        } else {
            $this->json_error('添加失败');
        }

    }

    /**
     * edit
     * 编辑
     *
     * @return void
     */
    public function edit() {

        $user_obj = new UserModel();
        $user_group_obj = new UserGroupModel();
        $permission_obj = new PermissionModel();

        // 只有超级管理员才能进行此操作
        if (!$permission_obj->is_super_admin($this->logged_user['user_id'])) {
            $this->json_error('只有超级管理员才能进行此操作');
        }

        $group_id =  $_POST['group_id'] ? $_POST['group_id'] : 0;
        $group_name = $_POST['group_name'] ? $_POST['group_name'] : '';
        $description = $_POST['description'] ? $_POST['description'] : '';
        $allow_add = isset($_POST['allow_add']) ? 1 : 0;
        $allow_delete = isset($_POST['allow_delete']) ? 1 : 0;
        $allow_edit = isset($_POST['allow_edit']) ? 1 : 0;
        $allow_select = isset($_POST['allow_select']) ? 1 : 0;

        if (!$group_id) {
            $this->json_error('组ID不能为空');
        }

        if (!$group_name) {
            $this->json_error('组名称不能为空');
        }

        $user_group = $user_group_obj->fetch_one(array('group_id' => $group_id));

        if (!$user_group) {
            $this->json_error('组不存在');
        }

        $permission_sum = $allow_add + $allow_delete + $allow_edit + $allow_select;

        // 不能将自己所在的组变为非超级管理员组
        $user = $user_obj->fetch_one('user_id=' . $this->logged_user['user_id']);
        if ($user['group_id'] == $group_id ) {
            if ($permission_sum != 4) {
                $this->json_error('不能将自己所在的组变为非超级管理员组');
            }
        }

        $data = array(
            'group_name' => $group_name,
            'description' => $description,
            'allow_add' => $allow_add,
            'allow_delete' => $allow_delete,
            'allow_edit' => $allow_edit,
            'allow_select' => $allow_select,
            'permission_sum' => $permission_sum,
        );

        if ($user_group_obj->update($data, 'group_id = ' . $group_id)) {
            $this->json_success();
        } else {
            $this->json_error('编辑失败');
        }
    }

    /**
     * delete
     * 删除
     *
     * @return void
     */
    public function delete() {

        $user_obj = new UserModel();
        $user_group_obj = new UserGroupModel();
        $permission_obj = new PermissionModel();

        // 只有超级管理员才能进行此操作
        if (!$permission_obj->is_super_admin($this->logged_user['user_id'])) {
            $this->json_error('只有超级管理员才能进行此操作');
        }

        $group_id = $_POST['group_id'] ? $_POST['group_id'] : 0;
        if (!$group_id) {
            $this->json_error('组ID不能为空');
        }

        $user_group = $user_group_obj->fetch_one(array('group_id' => $group_id));

        if (!$user_group) {
            $this->json_error('组不存在');
        }

        // 不能删除自己所在的组
        $user = $user_obj->fetch_one('user_id=' . $this->logged_user['user_id']);
        if ($user['group_id'] == $group_id) {
            $this->json_error('不能删除自己所在的组');
        }

        if ($user_group_obj->delete('group_id=' . $group_id)) {
            $user_obj->delete('group_id=' . $group_id);
            $this->json_success();
        } else {
            $this->json_error('删除失败');
        }
    }
}