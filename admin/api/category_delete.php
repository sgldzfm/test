<?php

/*
 * 根据用户传过来的id删除相应的数据
 */
require_once '../../functions.php';
//使用数据库之前导入需要的常量
require_once '../../config.php';
if(empty($_POST['id'])){
    exit('缺少必要参数');
}

$id = $_POST['id'];
$comma_separated = null;
if( is_array( $id )) {
    $comma_separated = implode(",", $id);
} else {
    $comma_separated = $id;
}


//根据id删除数据，in里可以是根据多个id,删除多条数据
$rows = bx_execute('delete from categories where id in('.$comma_separated.');');

$result = $rows>0? true : false;
echo $result;