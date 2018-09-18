<?php
//使用数据库之前导入需要的常量
//载入配置文件
require_once '../../functions.php';
require_once '../../config.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
    if($_POST['id']){
        //带id的是修改信息
        post_edit($_POST['id']);
    }
}

function post_edit($id){

    //获取对应id的信息
    $current_edit_data = bx_fetch_one('select * from categories where id = '.$id);
    if(!empty($current_edit_data)){
        echo json_encode($current_edit_data);
    }
}