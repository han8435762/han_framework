han_framework
=============
详见：!doc/readme.txt
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
1、将!doc/homework.sql导入到数据库
2、修改includes/config.php 为正确的数据库地址以及用户名
3、修改init.php中的ROOT_DOMAIN为自己的域名。
4、修改Template_c文件夹访问权限为777

三、项目访问地址：
http://www.xxx.com/?c=user&a=login
c对应的是controller的类名
a对应的是类的方法名
