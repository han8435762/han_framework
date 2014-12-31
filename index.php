<?php
/**
 * index.php
 *
 *   author:  hanyang (han2008yang@163.com)
 *   create time: 2014-12-30 13:40:23
 *
 */
define('IN_HOMEWORK', true);
define('ROOT_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);

include_once ROOT_PATH . 'init.php';

$controller_name = isset($_GET['c']) ? trim($_GET['c']) : '';
$action_name = isset($_GET['a']) ? trim($_GET['a']) : '';

$controller_name = format_controller_name($controller_name);

if (!$controller_name) {
    $controller_name = 'UserController';
}

if (!$action_name)  {
    $action_name = 'index';
}

if (!class_exists($controller_name)) {
    die('controller not exists');
}

if (!method_exists($controller_name, $action_name)) {
    die('action not exists');
}

define('CONTROLLER_NAME', str_replace('Controller', '', $controller_name));
define('ACTION_NAME', $action_name);

$controller = new $controller_name();
echo $controller->$action_name();