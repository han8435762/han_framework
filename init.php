<?php
/**
 * init.php
 *
 *   author:  hanyang (han2008yang@163.com)
 *   create time: 2014-12-30 13:40:23
 *
 */
if (!defined('IN_HOMEWORK')) {
    die('Hacking attempt');
}

error_reporting(E_ALL & ~E_NOTICE);

// 自定义常量
define('ROOT_DOMAIN', 'http://www.homework.com');

define('STATIC_PATH', ROOT_PATH . '/statics');
define('STATIC_DOMAIN', ROOT_DOMAIN . '/statics');

define('IMG_PATH', STATIC_PATH . '/img');
define('IMG_DOMAIN', STATIC_DOMAIN . '/img');

define('JS_PATH', STATIC_PATH . '/js');
define('JS_DOMAIN', STATIC_DOMAIN . '/js');

define('CSS_PATH', STATIC_PATH . '/css');
define('CSS_DOMAIN', STATIC_DOMAIN . '/css');


include_once ROOT_PATH . 'includes' . DIRECTORY_SEPARATOR . 'config.php';
include_once ROOT_PATH . 'includes' . DIRECTORY_SEPARATOR . 'function.inc.php';
// smarty
include_once ROOT_PATH . 'Plugin' . DIRECTORY_SEPARATOR . 'Smarty' . DIRECTORY_SEPARATOR . 'Smarty.class.php';

/**
 * __autoload
 * 类的自动加载机制
 *
 * @param  string $class_name
 * @return bool
 */
function __autoload($class_name) {
    $model_file_path = ROOT_PATH . 'Model' . DIRECTORY_SEPARATOR . $class_name . '.class.php';
    $controller_file_path = ROOT_PATH . 'Controller' . DIRECTORY_SEPARATOR . $class_name . '.class.php';
    $lib_file_path = ROOT_PATH . 'Lib' . DIRECTORY_SEPARATOR . $class_name . '.class.php';
    if (file_exists($model_file_path)) {
        include_once $model_file_path;
    } elseif (file_exists($controller_file_path)) {
        include_once $controller_file_path;
    } elseif (file_exists($lib_file_path)) {
        include_once $lib_file_path;
    }

    if (class_exists($class_name)) {
        return true;
    }

    return false;
}
