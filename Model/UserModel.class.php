<?php
/**
 * UserModel.class.php
 * 用户模型类
 *
 *   author:  hanyang (han2008yang@163.com)
 *   create time: 2014-12-30 13:40:23
 *
 */
class UserModel extends Model {

    /**
     * do_login
     * 登陆操作
     *
     * @param  string $user_name
     * @param  string $password
     * @return bool
     */
    public function do_login($user_name, $password) {

        $user = $this->fetch_one(array('user_name' => $user_name));
        if (!$user) {
            return false;
        }

        if ($this->get_md5_password($password, $user['salt']) != $user['password']) {
            return false;
        }

        // todo 判断用户是否被禁用

        $cookie_info = array(
            'user_name' => $user_name,
            'user_id' => $user['user_id'],
            'key' => $this->get_cookie_key($user['user_id']),
        );
        $cookietime = strtotime(date('Y-m-d 23:59:59')) + 86400 * 7;

        setcookie('homework_user_info', json_encode($cookie_info), $cookietime);

        // todo 更新登陆时间
        setcookie('homework_cookietime', $_cookietime, $cookietime);

        return true;
    }

    /**
     * logout
     * 退出操作
     *
     * @return void
     */
    public function logout() {
        setcookie('homework_user_info', '', time() - 86400);
        setcookie('homework_cookietime', '', time() - 86400);
    }

    /**
     * get_logged_user
     * 得到当前登录的用户
     *
     * @return array
     */
    public function get_logged_user() {
        $logged_user = array();

        if ($_COOKIE['homework_user_info']) {

            $cookie_info = json_decode($_COOKIE['homework_user_info'], true);
            if ($cookie_info['key'] == $this->get_cookie_key($cookie_info['user_id'])) {

                $logged_user = array(
                    'user_id' => $cookie_info['user_id'],
                    'user_name' => $cookie_info['user_name']
                );
            }
        }

        return $logged_user;
    }

    /**
     * get_md5_password
     * 密码加密算法
     *
     * @param  string $password
     * @param  string $salt
     * @return string
     */
    public function get_md5_password($password, $salt) {
        return md5('homework_' . md5($password . '_' . $salt));
    }

    /**
     * get_cookie_key
     * cookie中key的加密算法
     *
     * @param  integer $user_id
     * @return string
     */
    public function get_cookie_key($user_id) {
        return md5('homework_' . md5($user_id));
    }

}