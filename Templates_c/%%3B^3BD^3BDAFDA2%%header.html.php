<?php /* Smarty version 2.6.17, created on 2014-12-31 16:05:36
         compiled from Public/header.html */ ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <link type="text/css" rel="stylesheet" href="<?php echo @CSS_DOMAIN; ?>
/bootstrap.css" />
        <script src="<?php echo @JS_DOMAIN; ?>
/jquery.min.js"></script>
        <script src="<?php echo @JS_DOMAIN; ?>
/bootstrap.js"></script>
    </head>
    <body>
        <div class="container">
            <div style="margin-top:5px;">
                <span style="float:right;">您好, <?php echo $this->_tpl_vars['logged_user']['user_name']; ?>
 | <a href="<?php echo @ROOT_DOMAIN; ?>
/?c=user&a=logout">退出</a></span>
            </div>

            <ul class="nav nav-pills">
              <li <?php if (@CONTROLLER_NAME == 'User'): ?>class="active"<?php endif; ?>>
                <a href="<?php echo @ROOT_DOMAIN; ?>
/?c=user&a=index">用户列表</a>
              </li>
              <li <?php if (@CONTROLLER_NAME == 'UserGroup'): ?>class="active"<?php endif; ?>><a href="<?php echo @ROOT_DOMAIN; ?>
/?c=user_group&a=index">用户组列表</a></li>
            </ul>