<?php
/**
 * BaseController.class.php
 *
 *   author:  hanyang (han2008yang@163.com)
 *   create time: 2014-12-30 13:40:23
 *
 */
class BaseController extends Controller {

    public $logged_user;

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $user = new UserModel();
        $this->logged_user = $user->get_logged_user();
        $this->assign('logged_user', $this->logged_user);
    }

    /**
     * error
     *
     * @param  string $message
     * @return void
     */
    public function error($message) {
        $this->assign('message', $message);
        $this->display('Public/error');
    }

    /**
     * success
     *
     * @param  stromg $message
     * @return void
     */
    public function success($message) {
        $this->assign('message', $message);
        $this->display('Public/success');
    }

    /**
     * json_error
     * ajax访问错误信息返回
     *
     * @param  string $message
     * @return void
     */
    public function json_error($message) {
        $result = array(
            'result' => false,
            'message' => $message,
            'data' => array(),
        );

        if ($_REQUEST['iframe_callback']) {
            echo '<script>parent.' . $_REQUEST['iframe_callback'] . '(\'' . json_encode($result) . '\');' . '</script>';
        } else {
            echo json_encode($result);
        }
        exit;
    }

    /**
     * json_success
     * ajax访问成功信息返回
     *
     * @param  array  $data
     * @return void
     */
    public function json_success($data = array()) {
        $result = array(
            'result' => true,
            'message' => '',
            'data' => $data,
        );

        if ($_REQUEST['iframe_callback']) {
            echo '<script>parent.' . $_REQUEST['iframe_callback'] . '(\'' . json_encode($result) . '\');' . '</script>';
        } else {
            echo json_encode($result);
        }
        exit;
    }

    /**
     * need_logged
     * 需要登陆的操作
     *
     * @return void
     */
    public function need_logged() {
        if (!$this->logged_user) {
            redirect(ROOT_DOMAIN . '/?c=user&a=login');
        }
    }

    /**
     * need_unlogged
     * 需要不登陆的操作
     *
     * @return void
     */
    public function need_unlogged() {
        if ($this->logged_user) {
            redirect(ROOT_DOMAIN . '/?c=user&a=index');
        }
    }
}
