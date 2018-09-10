<?php
//公共函数存放的文件

//从session中获取当前对象信息
//需要操作session所以需要下发session
//session_start();
function bx_get_current_user(){
    if(empty($_SESSION['current_login_user'] )){
        header('Location: /admin/login.php');
        //在跳转后，就不要让代码向下执行了
        exit();
    }
    return $_SESSION['current_login_user'];
}