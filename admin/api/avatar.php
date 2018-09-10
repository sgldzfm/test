<?php
//接收ajax请求

function postback(){
    //载入配置文件
    require_once '../../config.php';
    //获取用户提交的数据
    $email = $_POST['email'];
    //连接数据库
    $conn = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    if(!$conn){
        exit('<h1>数据库连接失败</h1>');
    }
    //查询用户提交过来的email值在数据库是否存在,当查询到一个时就停止往后查了，提高效率
    $query = mysqli_query($conn,"select * from users where email = '{$email}' limit 1");
    if(!$query){
        $GLOBALS['message'] = '登陆失败，请重试';
        return;
    }
    //从查询出来的结果中取第一条数据
    $user = mysqli_fetch_assoc($query);
    if(!$user){
        $GLOBALS['message'] = '邮箱与密码不匹配';
        return;
    }
    $imgpath = $user['avatar'];
    if($imgpath){
        echo $imgpath;
    }
}

if($_SERVER['REQUEST_METHOD']==='POST'){
    postback();
}