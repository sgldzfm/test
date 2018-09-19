<?php
//下发session
session_start();
if($_SERVER['REQUEST_METHOD']==='POST'){
    if($_POST['behavior']){
        //判断是否已经登录
        if(empty($_SESSION['current_login_user'])){
            //进入里面说明没有登陆,跳转回登陆页面
            echo "user_exit";
        }
//删除单个session
        unset($_SESSION['current_login_user']);
        if(empty($_SESSION['current_login_user'])){
            echo "user_exit";
        }
    }
}
