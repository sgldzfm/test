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

//通过数据库查询获取数据
//参数为sql语句
//这个是查询的结果有多行的
function bx_fetch_all($sql){
    //创建连接
    $conn = mysqli_connect(BX_DB_HOST,BX_DB_USER,BX_DB_PASS,BX_DB_NAME);
    //返回的连接为空说明创建连接失败
    if(!$conn){
        exit('连接失败');
    }

    $query = mysqli_query($conn,$sql);
    //判断查询结果，如果为空说明查询失败
    if(!$query){
        return '查询失败';
    }
    //查询成功，就把值取出来。并放到一个数据里
    //每次循环都会把一行数据放到变量$row里面。每一行都会是一个关联数组，是键值对的形式
    while($row = mysqli_fetch_assoc($query)){
        //然后把每次循环后的$row放到数组里，放到result里是一个线性数组，（就是线性数组里放关联数组的数据结构）
        $result[] = $row;
    }
    //当循环完毕后，把数据作为返回值
    return $result;
}
//通过数据库查询获取数据
//参数为sql语句
//这个是查询的结果有一行的
function bx_fetch_one($sql){
    //创建连接
    $conn = mysqli_connect(BX_DB_HOST,BX_DB_USER,BX_DB_PASS,BX_DB_NAME);
    //返回的连接为空说明创建连接失败
    if(!$conn){
        exit('连接失败');
    }

    $query = mysqli_query($conn,$sql);
    //判断查询结果，如果为空说明查询失败
    if(!$query){
        return '查询失败';
    }

    //因为查询结果只有一行，所以也就不用去循环取值了
    return mysqli_fetch_assoc($query);
}