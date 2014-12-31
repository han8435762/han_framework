<?php
/**
 * UserController.class.php
 *
 *   author:  hanyang (han2008yang@163.com)
 *   create time: 2014-12-30 13:40:23
 *
 */
class UserController extends BaseController{

    /**
     * login
     *
     * @return void
     */
    public function login() {
        $this->need_unlogged();

        $this->display();
    }

    /**
     * do_login
     *
     * @return void
     */
    public function do_login() {
        $this->need_unlogged();

        $user_name = $_POST['user_name'] ? trim($_POST['user_name']) : '';
        $password = $_POST['password'] ? trim($_POST['password']) : '';

        if (!$user_name) {
            $this->json_error('用户名不能为空');
        }

        if (!Validate::is_username($user_name)) {
            $this->json_error('用户名格式不正确');
        }

        if (!$password) {
            $this->json_error('密码不能为空');
        }

        if (!Validate::is_password($password)) {
            $this->json_error('密码格式不正确');
        }

        $user_obj = new UserModel();
        if (!$user_obj->do_login($user_name, $password)) {
            $this->json_error('账号不存在或密码错误');
        }

        $this->json_success();
    }

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

        $is_ajax = $_GET['is_ajax'] ? $_GET['is_ajax'] : '';
        $order = $_GET['order'] ? $_GET['order'] : 'add_time_desc';
        $page = $_GET['page'] ? $_GET['page'] : 1;
        $per_page = 10;

        // order
        if ($order == 'add_time_desc') {
            $user_order = 'add_time DESC';
            $order = 'add_time_asc';
        } elseif ($order == 'add_time_asc') {
            $user_order = 'add_time ASC';
            $order = 'add_time_desc';
        }

        // where
        $where = '';
        // 获取用户能看到的组以及组ID 拼接条件
        $user_groups = $permission_obj->get_user_allowed_groups($this->logged_user['user_id']);
        $user_group_ids = array_unique(UtilArray::get_col($user_groups, 'group_id'));
        if ($user_group_ids) {
            $where = 'group_id IN (' . implode(',', $user_group_ids) . ')';
        }

        // 获取用户列表
        $users = $user_obj->fetch_all($where, $user_order, $per_page * ($page - 1) . ', ' . $per_page);

        if ($users) {
            foreach ($users as $key => $user) {
                $users[$key]['user_group'] = $user_group_obj->fetch_one('group_id=' . $user['group_id']);
            }
        }

        $total_rows = $user_obj->count($where);
        $pages = pages($total_rows, $page, $per_page);

        $this->assign('user_order', $user_order);
        $this->assign('order', $order);
        $this->assign('pages', $pages);
        $this->assign('users', $users);
        $this->assign('user_groups', $user_groups);

        if ($is_ajax) {
            $this->display('User/list');
        } else {
            $this->display();
        }
    }

    /**
     * add
     * 添加
     *
     * @return void
     */
    public function add() {
        $this->need_logged();

        $user_group_obj = new UserGroupModel();
        $user_obj = new UserModel();
        $permission_obj = new PermissionModel();

        $user_name = $_POST['user_name'] ? $_POST['user_name'] : '';
        $password = isset($_POST['password']) && trim($_POST['password']) ? trim($_POST['password']) : '';
        $user_group_id = $_POST['user_group'] ? $_POST['user_group'] : $_POST['user_group'];

        if (!$user_name || !Validate::is_username($user_name)) {
            $this->json_error('用户名格式不正确');
        }

        if (!$password || !Validate::is_password($password)) {
            $this->json_error('密码格式不正确');
        }

        if (!$user_group_id) {
            $this->json_error('所属组不能为空');
        }

        $user_group = $user_group_obj->fetch_one(array('group_id' => $user_group_id));

        if (!$user_group) {
            $this->json_error('用户组不存在');
        }

        // 权限判断
        if (!$permission_obj->check_user_add($this->logged_user['user_id'], $user_group_id)) {
            $this->error('你没有添加这个组成员的权限');
        }

        $salt = rand_string(4);
        $data = array(
            'user_name' => $user_name,
            'password' => $user_obj->get_md5_password($password, $salt),
            'salt' => $salt,
            'group_id' => $user_group_id,
            'add_time' => time(),
        );

        if ($user_obj->insert($data)) {
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

        $user_id = $_POST['user_id'] ? $_POST['user_id'] : '';
        $user_name = $_POST['user_name'] ? $_POST['user_name'] : '';
        $password = isset($_POST['password']) && trim($_POST['password']) ? trim($_POST['password']) : '';
        $user_group_id = $_POST['user_group'] ? $_POST['user_group'] : $_POST['user_group'];

        $data = array();

        if (!$user_id) {
            $this->json_error('用户ID不能为空');
        }

        $user = $user_obj->fetch_one('user_id=' . $user_id);
        if (!$user) {
            $this->json_error('用户不存在');
        }

        // 判断是否有编辑该成员的权限
        if (!$permission_obj->check_user_edit($this->logged_user['user_id'], $user['group_id'])) {
            $this->error('你没有编辑这个成员的权限');
        }

        if ($user_name) {
            if (!Validate::is_username($user_name)) {
                $this->json_error('用户名格式不正确');
            }

            $data['user_name'] = $user_name;
        }

        if ($password) {
            if (!Validate::is_password($password)) {
                $this->json_error('密码格式不正确');
            }

            $salt = rand_string(4);
            $data['salt'] = $salt;
            $data['password'] = $user_obj->get_md5_password($password, $salt);
        }

        if (!$user_group_id) {
            $this->json_error('所属组不能为空');
        }

        $user_group = $user_group_obj->fetch_one(array('group_id' => $user_group_id));

        if (!$user_group) {
            $this->json_error('用户组不存在');
        }

        // 判断是否有编辑新组的权限
        if (!$permission_obj->check_user_edit($this->logged_user['user_id'], $user_group_id)) {
            $this->error('你没有编辑新的组成员的权限');
        }

        $data['group_id'] = $user_group_id;

        if ($user_obj->update($data, 'user_id = ' . $user_id)) {
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
        $permission_obj = new PermissionModel();

        $user_id = $_POST['user_id'] ? $_POST['user_id'] : 0;
        if (!$user_id) {
            $this->json_error('用户ID不能为空');
        }

        $user = $user_obj->fetch_one(array('user_id' => $user_id));

        if (!$user) {
            $this->json_error('用户不存在');
        }

        // 判断是否有删除用户的权限
        if (!$permission_obj->check_user_delete($this->logged_user['user_id'], $user['group_id'])) {
            $this->error('你没有编辑新的组成员的权限');
        }

        if ($user_obj->delete('user_id=' . $user_id)) {
            $this->json_success();
        } else {
            $this->json_error('删除失败');
        }
    }

    /**
     * logout
     * 退出
     *
     * @return void
     */
    public function logout() {
        $this->need_logged();

        $user_obj = new UserModel();
        $user_obj->logout();
        redirect(ROOT_DOMAIN . '/?c=user&a=login');
    }
}