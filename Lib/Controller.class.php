<?php
/**
 * Controller.class.php
 *
 *   author:  hanyang (han2008yang@163.com)
 *   create time: 2014-12-30 13:40:23
 *
 */
class Controller {

    /**
     * $tpl
     * 模板引擎
     *
     * @var obj
     */
    public $tpl;

    /**
     * __construct
     *
     * @return void
     */
    function __construct () {
        $this->tpl = new Smarty();
        $this->tpl->template_dir = ROOT_PATH . '/Templates/';
        $this->tpl->compile_dir = ROOT_PATH . '/Templates_c/'; // 置为可写
        $this->tpl->caching = false; //这里是调试时设为false,发布时请使用true
        $this->tpl->left_delimiter = '<{';
        $this->tpl->right_delimiter = '}>';
    }

    /**
     * assign
     *
     * @param  string $param
     * @param  mixed $value
     * @return void
     */
    public function assign($param, $value) {
        $this->tpl->assign($param, $value);
    }

    /**
     * display
     *
     * @param  string $tpl_name
     * @return void
     */
    public function display($tpl_name = '') {
        if (!$tpl_name) {
            $tpl_name = CONTROLLER_NAME . DIRECTORY_SEPARATOR . ACTION_NAME;
        }

        $tpl_name .= '.html';

        $this->tpl->display($tpl_name);
        exit;
    }
}