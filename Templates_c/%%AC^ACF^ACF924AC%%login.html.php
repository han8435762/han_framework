<?php /* Smarty version 2.6.17, created on 2014-12-31 15:47:00
         compiled from User%5Clogin.html */ ?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>login</title>
        <link type="text/css" rel="stylesheet" href="<?php echo @CSS_DOMAIN; ?>
/bootstrap.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo @CSS_DOMAIN; ?>
/login.css" />
        <script src="<?php echo @JS_DOMAIN; ?>
/jquery.min.js"></script>

    </head>
    <body>
        <div class="container">
            <form class="form-signin" id="login_form" method="POST" target="inner_iframe" action="<?php echo @ROOT_DOMAIN; ?>
/index.php?c=user&a=do_login">
                <input type="hidden" name="iframe_callback" id="iframe_callback" value="handle_result"/>
                <h2 class="form-signin-heading">Please sign in</h2>
                User Name:<input type="text" name="user_name" class="input-block-level" placeholder="test1">
                Password:<input type="password" name="password" class="input-block-level" placeholder="123qaz">
                <p><span class="label label-warning" id="notice"></span></p>
                <p><input class="btn btn-large btn-primary" type="submit" value="submit" /></p>
                <p class="label label-warning">以下账号的密码都是：123qaz</p>
                <p>超级管理员账号：admin</p>
                <p>主编账号：test2</p>
                <p>小编账号：test4</p>
                <p>游客账号：test6</p>
            </form>
            <iframe style="display:none;" name="inner_iframe"></iframe>
            <div>

                <p>项目下载地址：<a href="http://www.sjzdazhe.com/homework/homework.zip" target="_blank">http://www.sjzdazhe.com/homework/homework.zip</a></p>
                <p>项目SVN地址：<a href="https://123.57.72.235:8000/svn/homework_hanyang/" target="_blank">https://123.57.72.235:8000/svn/homework_hanyang/</a></p>
                <p>项目SVN账号：guest</p>
                <p>项目SVN密码：guest</p>
                <pre>
    一、说明：
    本项目采用MVC模式，PHP端的框架是本人现写的（引入的Smarty、Mysql.class.php、UtilArray.class.php除外）
    以下为各个文件夹的作用
    Model：数据模型
    Template：模板
    Template_c：模板编译
    Controller：控制器
    Lib：框架所需类
    Plugin：扩展类（比如smarty）
    includes：配置以及函数集合等文件
    statics：静态文件
    index.php：入口文件，分配c和a
    init.php：项目常量的定义以及自动加载的实现

    二、项目部署步骤：
    1、将homework.sql导入到数据库
    2、修改includes/config.php 为正确的数据库地址以及用户名
    3、修改init.php中的ROOT_DOMAIN为自己的域名。
    4、修改Template_c文件夹访问权限为777

    三、项目访问地址：
    http://www.xxx.com/?c=user&a=login
    c对应的是controller的类名
    a对应的是类的方法名

    四、项目用时：
    编写框架：6小时
    数据表设计：1小时
    用户组列表以及添加：2小时
    用户列表以及添加：3小时
    权限控制：1小时
    测试调整：1小时

                </pre>
            </div>
        </div>
        <script>
        function handle_result(json_string) {
            var json_obj = eval("(" + json_string + ")");
            if (json_obj.result) {
                $('#notice').attr('class', 'label label-success');
                $('#notice').html('登陆成功');
                setTimeout(function () {window.location.reload();},500);
            } else {
                $('#notice').attr('class', 'label label-warning');
                $('#notice').html(json_obj.message);
            }
        }
        </script>
    </body>
</html>